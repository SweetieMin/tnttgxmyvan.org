<?php

namespace App\Services;

use App\Models\RegistrationRequest;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class RegistrationService
{
    /**
     * Lưu ảnh thẻ phụ huynh gửi: cắt giữa theo khung 3x4, lưu jpg.
     */
    public static function storePicture(UploadedFile $file): string
    {
        $uploadPath = public_path(RegistrationRequest::PICTURE_PATH);

        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $fileName = now()->format('ymd') . '-' . Str::random(20) . '.jpg';

        $image = Image::make($file->path());
        try {
            $image->orientate(); // ảnh chụp điện thoại bị xoay (cần exif)
        } catch (\Throwable $e) {
        }

        $image->fit(600, 800, fn($constraint) => $constraint->upsize())
            ->save($uploadPath . $fileName, 85, 'jpg');

        return $fileName;
    }

    public static function deletePicture(?string $fileName): void
    {
        if ($fileName && File::exists(public_path(RegistrationRequest::PICTURE_PATH . $fileName))) {
            File::delete(public_path(RegistrationRequest::PICTURE_PATH . $fileName));
        }
    }

    public static function normalizePhone(?string $phone): string
    {
        return preg_replace('/\D/', '', (string) $phone);
    }

    /**
     * Tìm thiếu nhi để xin cấp lại thẻ: mã thiếu nhi (in trên thẻ) hoặc SĐT phụ huynh, kèm ngày sinh.
     */
    public static function findChildren(string $key, string $birthday): Collection
    {
        $key = trim($key);
        $phone = self::normalizePhone($key);

        return User::with('studentParent')
            ->whereHas('roles', fn($q) => $q->where('name', 'Thiếu Nhi'))
            ->whereDate('birthday', $birthday)
            ->where(function ($q) use ($key, $phone) {
                $q->where('account_code', $key);

                if (strlen($phone) >= 9) {
                    $q->orWhere('phone', $phone)
                        ->orWhereHas('studentParent', fn($p) => $p->where('phoneFather', $phone)->orWhere('phoneMother', $phone));
                }
            })
            ->get();
    }

    /** "Maria Nguyễn Thị Hương" -> "Maria N. T. Hương": đủ để phụ huynh nhận ra, không lộ tên đầy đủ. */
    public static function maskName(User $user): string
    {
        $initials = collect(preg_split('/\s+/u', trim($user->lastName)))
            ->filter()
            ->map(fn($part) => mb_substr($part, 0, 1) . '.')
            ->implode(' ');

        return trim($user->holyName . ' ' . $initials . ' ' . $user->name);
    }

    public static function formatName(?string $value): string
    {
        return mb_convert_case(trim(preg_replace('/\s+/u', ' ', (string) $value)), MB_CASE_TITLE, 'UTF-8');
    }

    /** Tách "Nguyễn Thị Hương" thành [họ + tên lót, tên]. */
    public static function splitFullName(string $fullName): array
    {
        $parts = explode(' ', self::formatName($fullName));
        $name = array_pop($parts);

        return [implode(' ', $parts), $name];
    }

    public static function findExistingChild(string $holyName, string $fullName, $birthday): ?User
    {
        [$lastName, $name] = self::splitFullName($fullName);

        return User::where('holyName', self::formatName($holyName))
            ->where('lastName', $lastName)
            ->where('name', $name)
            ->whereDate('birthday', $birthday)
            ->first();
    }

    /**
     * Duyệt đơn.
     * - Đăng ký mới: tạo tài khoản thiếu nhi (giống thêm thủ công ở trang Thiếu Nhi).
     * - Cấp lại thẻ: đưa về "chưa làm thẻ"; trưởng chọn có vô hiệu thẻ cũ (đổi token) hay không.
     */
    public static function approve(RegistrationRequest $request, ?string $adminNote = null, bool $invalidateOldCard = false): User
    {
        return DB::transaction(function () use ($request, $adminNote, $invalidateOldCard) {
            $user = $request->type === 'new'
                ? self::createChild($request)
                : self::reissueCard($request, $invalidateOldCard);

            $request->update([
                'user_id' => $user->id,
                'picture' => null, // ảnh đã chuyển sang images/users
                'status' => 'approved',
                'admin_note' => $adminNote,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            return $user;
        });
    }

    public static function reject(RegistrationRequest $request, string $adminNote): void
    {
        self::deletePicture($request->picture);

        $request->update([
            'picture' => null,
            'status' => 'rejected',
            'admin_note' => $adminNote,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);
    }

    private static function createChild(RegistrationRequest $request): User
    {
        [$lastName, $name] = self::splitFullName($request->fullName);

        $child = new User();
        $child->holyName = self::formatName($request->holyName);
        $child->lastName = $lastName;
        $child->name = $name;
        $child->birthday = $request->birthday;
        $child->account_code = self::generateAccountCode($request->birthday);
        $child->phone = $request->phone;
        $child->address = $request->address;
        $child->token = self::uniqueToken();
        $child->password = Hash::make($child->account_code);
        $child->status = 'active';
        $child->is_attendance = true;
        if (self::hasColumn('token_v2')) {
            $child->token_v2 = self::uniqueToken();
        }
        $child->save();

        $child->sectors()->sync(array_filter([$request->sector_id]));
        $child->courses()->sync(array_filter([$request->course_id]));
        $child->roles()->sync(Role::where('name', 'Thiếu Nhi')->firstOrFail()->id);

        if ($request->nameFather || $request->phoneFather || $request->nameMother || $request->phoneMother || $request->godParent) {
            $child->studentParent()->create([
                'nameFather' => $request->nameFather,
                'phoneFather' => $request->phoneFather,
                'nameMother' => $request->nameMother,
                'phoneMother' => $request->phoneMother,
                'godParent' => $request->godParent,
            ]);
        }

        $child->religiousProfile()->create([]);

        self::movePictureToUser($request, $child);

        ActivityLogService::log('Tạo tài khoản', 'Duyệt đăng ký thiếu nhi', $child->id, $child->SimpleName);

        return $child;
    }

    private static function reissueCard(RegistrationRequest $request, bool $invalidateOldCard): User
    {
        $child = User::findOrFail($request->user_id);

        // Thẻ cũ in QR /profile/{token} để điểm danh: đổi token thì thẻ bị mất không dùng được nữa
        if ($invalidateOldCard) {
            $child->token = self::uniqueToken();
            $child->save();
        }

        if (self::hasColumn('has_card')) {
            DB::table('users')->where('id', $child->id)->update(['has_card' => 0]);
        }

        self::movePictureToUser($request, $child);

        ActivityLogService::log(
            'Cấp lại thẻ',
            $invalidateOldCard ? 'Duyệt cấp lại thẻ thiếu nhi (vô hiệu thẻ cũ)' : 'Duyệt cấp lại thẻ thiếu nhi',
            $child->id,
            $child->SimpleName,
        );

        return $child;
    }

    private static function movePictureToUser(RegistrationRequest $request, User $child): void
    {
        $source = public_path(RegistrationRequest::PICTURE_PATH . $request->picture);

        if (!$request->picture || !File::exists($source)) {
            return;
        }

        $path = 'images/users/';
        $fileName = $child->account_code . '-' . uniqid() . '.jpg';
        File::move($source, public_path($path . $fileName));

        $oldPicture = $child->getRawOriginal('picture');
        if ($oldPicture && File::exists(public_path($path . $oldPicture))) {
            File::delete(public_path($path . $oldPicture));
        }

        $child->update(['picture' => $fileName]);
    }

    /** Cùng định dạng với trang Thiếu Nhi: MV + ddmmyy + 2 số. */
    private static function generateAccountCode($birthday): string
    {
        $prefix = 'MV' . Carbon::parse($birthday)->format('dmy');

        do {
            $accountCode = $prefix . rand(10, 99);
        } while (User::withTrashed()->where('account_code', $accountCode)->exists());

        return $accountCode;
    }

    private static function uniqueToken(): string
    {
        $hasTokenV2 = self::hasColumn('token_v2');

        do {
            $token = Str::random(64);
        } while (
            DB::table('users')
                ->where('token', $token)
                ->when($hasTokenV2, fn($q) => $q->orWhere('token_v2', $token))
                ->exists()
        );

        return $token;
    }

    private static function hasColumn(string $column): bool
    {
        static $columns = [];

        return $columns[$column] ??= Schema::hasColumn('users', $column);
    }
}
