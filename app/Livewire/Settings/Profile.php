<?php

namespace App\Livewire\Settings;

use App\Models\JourneyOfVocation;
use App\Models\StudentParent;
use App\Models\StudentReligious;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Profile extends Component
{
    public $tab = null;
    public $tabname = 'personal_details';
    protected $queryString = ['tab' => ['keep' => true]];

    public $holyName, $fullName, $email, $phone, $account_code, $birthday, $bio, $qrCode, $address;
    public $role;
    public $course;
    public $sector;

    public $nameFather, $phoneFather, $nameMother, $phoneMother, $godParents;
    public $ngay_rua_toi, $linh_muc_rua_toi, $ngay_xung_toi, $ngay_them_suc, $giam_muc_them_suc, $ngay_bao_dong, $trang_thai_ton_giao;
    public $ngay_doi_truong, $ngay_du_truong, $ngay_huynh_truong, $ngay_huynh_truong2, $ngay_huynh_truong3;

    public $isShowJourneyOfVocation = false;
    public $isShowReligiousProfile = false;
    public $isShowParent = false;
    public $isShowRole = false;
    public $isShowCourse = false;
    public $isShowSector = false;

    public $current_password, $new_password, $new_password_confirmation;


    protected $listeners = [
        'updateProfile' => '$refresh'
    ];

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        $this->tab = Request('tab') ? Request('tab') : $this->tabname;

        //

        $user = User::query()->findOrFail(Auth::user()->id);

        $this->holyName = $user->holyName;
        $this->fullName = $user->lastName . " " . $user->name;
        $this->phone = $user->phone;
        $this->birthday = $user->birthday;
        $this->email = $user->email;
        $this->bio = $user->bio;
        $this->address = $user->address;

        $this->isShowRole = $user->roles->pluck('name')->toArray();
        $this->isShowCourse = $user->courses->pluck('name')->toArray();
        $this->isShowSector = $user->sectors->pluck('name')->toArray();

        if ($this->isShowRole) {
            $this->role = $user->roles->pluck('name')->toArray();
        }
        if ($this->isShowCourse) {
            $this->course = $user->courses->pluck('name')->toArray();
        }
        if ($this->isShowSector) {
            $this->sector = $user->sectors->pluck('name')->toArray();
        }

        //Parent

        $this->isShowParent = StudentParent::query()
            ->where('user_id', $user->id)
            ->exists();

        if ($this->isShowParent) {
            $this->nameFather = $user->studentParent->nameFather ?? 'Chưa câp nhật';
            $this->phoneFather = $user->studentParent->phoneFather ?? 'Chưa cập nhật';
            $this->nameMother = $user->studentParent->nameMother ?? 'Chưa cập nhật';
            $this->phoneMother = $user->studentParent->phoneMother ?? 'Chưa cập nhật';
            $this->godParents = $user->studentParent->godParent ?? 'Chưa cập nhật';
        }

        $this->isShowReligiousProfile = StudentReligious::query()
            ->where('user_id', $user->id)
            ->exists();

        if ($this->isShowReligiousProfile) {
            //Thông tin công giáo
            $this->ngay_rua_toi = $user->religiousProfile->ngay_rua_toi
                ? Carbon::parse($user->religiousProfile->ngay_rua_toi)->format('d-m-Y')
                : null;
            $this->linh_muc_rua_toi = $user->religiousProfile->linh_muc_rua_toi ?? 'Chưa cập nhật';
            $this->ngay_xung_toi = $user->religiousProfile->ngay_xung_toi
                ? Carbon::parse($user->religiousProfile->ngay_xung_toi)->format('d-m-Y')
                : null;
            $this->ngay_them_suc = $user->religiousProfile->ngay_them_suc
                ? Carbon::parse($user->religiousProfile->ngay_them_suc)->format('d-m-Y')
                : null;
            $this->giam_muc_them_suc = $user->religiousProfile->giam_muc_them_suc ?? 'Chưa cập nhật';
            $this->ngay_bao_dong = $user->religiousProfile->ngay_bao_dong
                ? Carbon::parse($user->religiousProfile->ngay_bao_dong)->format('d-m-Y')
                : null;
            $this->trang_thai_ton_giao = $user->religiousProfile->trang_thai_ton_giao ?? 'Chưa cập nhật';
        }

        $this->isShowJourneyOfVocation = JourneyOfVocation::query()
            ->where('user_id', $user->id)
            ->exists();

        if ($this->isShowJourneyOfVocation) {
            $this->ngay_doi_truong = $user->journeysOfVocation->ngay_doi_truong
                ? Carbon::parse($user->journeysOfVocation->ngay_doi_truong)->format('d-m-Y')
                : null;
            $this->ngay_du_truong = $user->journeysOfVocation->ngay_du_truong
                ? Carbon::parse($user->journeysOfVocation->ngay_du_truong)->format('d-m-Y')
                : null;
            $this->ngay_huynh_truong = $user->journeysOfVocation->ngay_huynh_truong
                ? Carbon::parse($user->journeysOfVocation->ngay_huynh_truong)->format('d-m-Y')
                : null;
            $this->ngay_huynh_truong2 = $user->journeysOfVocation->ngay_huynh_truong2
                ? Carbon::parse($user->journeysOfVocation->ngay_huynh_truong2)->format('d-m-Y')
                : null;
            $this->ngay_huynh_truong3 = $user->journeysOfVocation->ngay_huynh_truong3
                ? Carbon::parse($user->journeysOfVocation->ngay_huynh_truong3)->format('d-m-Y')
                : null;
        }


        $this->account_code = $user->account_code;
    }

    public function updatePersonalDetails()
    {
        $user = User::query()->findOrFail(Auth::user()->id);

        $this->validate([
            'phone' => ['nullable', 'regex:/^(0[3|5|7|8|9])+([0-9]{8})$/'],
            'email' => 'email|unique:users,email,' . $user->id,
        ], [
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.regex' => 'Số điện thoại không hợp lệ. Số điện thoại phải thuộc nhà mạng Việt Nam và có 10 chữ số',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
        ]);

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages([
                'email' => ['Địa chỉ email không hợp lệ.']
            ]);
        }


        try {
            DB::beginTransaction();

            $user->phone = $this->phone;
            $user->bio = $this->bio;

            if ($this->email != $user->email) {
                $user->email = $this->email;
                $user->email_verified_at = null;
            }

            $updated = $user->save();

            if (!$updated) {
                throw new \Exception('Cập nhật người dùng thất bại');
            }

            DB::commit();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Cập nhật thông tin cá nhân thành công!']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé! Mã lỗi: ' . $e]);
        }
    }

    public function sendVerificationEmail()
    {
        $user = User::query()->findOrFail(Auth::user()->id);
        $this->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
        ]);

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => ['Địa chỉ email không hợp lệ.']
            ]);
        }

        $isTokenExists = DB::table('password_reset_tokens')
            ->where('email', $this->email)
            ->first();

        if ($isTokenExists) {
            $diffMins = Carbon::createFromFormat('Y-m-d H:i:s', $isTokenExists->created_at)->diffInMinutes(Carbon::now());

            if ($diffMins < 5) {
                $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Bạn hãy kiểm tra email của mình. Chúng tôi đã gửi mã xác minh đến tài khoản của bạn.']);
                return;
            }
        }

        $token = base64_encode(Str::random(64));



        $oldToken = DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->first();

        if ($oldToken) {
            DB::table('password_reset_tokens')
                ->where('email', $user->email)
                ->update([
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
        } else {
            DB::table('password_reset_tokens')
                ->insert([
                    'email' => $user->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
        }

        $actionLink = route('admin.verify_email', ['token' => $token]);

        $data = [
            'actionLink' => $actionLink,
            'user' => $user
        ];


        try {
            Mail::send('Mail.auth.verification-email', $data, function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Xác minh tài khoản ' . $user->account_code);
            });

            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Chúng tôi đã gửi mã xác minh đến tài khoản của bạn. Vui lòng kiểm tra email của bạn.']);
            
        } catch (\Exception $e) {

            $this->dispatch('showToastr', ['type' => 'fail', 'message' => 'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé!.']);
        }
    }

    public function updatePassword()
    {
        $user = User::query()->findOrFail(Auth::user()->id);

        $this->validate([
            'current_password' => [
                'required',
                'min:5',
                'max:45',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        return $fail(__('Bạn nhập mật khẩu không khớp với mật khẩu trong hệ thống'));
                    }
                }
            ],
            'new_password' => 'required|min:5|max:45|confirmed'
        ], [
            'current_password.required' => 'Mật khẩu là bắt buộc',
            'current_password.min' => 'Mật khẩu phải có trên 5 ký tự',
            'current_password.max' => 'Mật khẩu tối đa 45 ký tự',
            'new_password.required' => 'Mật khẩu mới là bắt buộc',
            'new_password.min' => 'Mật khẩu phải có trên 5 ký tự',
            'new_password.confirmed' => 'Mật khẩu không trùng với ô xác nhận',
        ]);

        DB::beginTransaction();

        try {
            $update = $user->update([
                'password' => Hash::make($this->new_password),
            ]);

            if (!$update) {
                throw new \Exception('Không thể cập nhật mật khẩu');
            }
            $data = [
                'user' => $user,
                'new_password' => $this->new_password
            ];

            if (Auth::user() && Auth::user()->email && Auth::user()->email_verified_at) {
                Mail::send('Mail.auth.notify-changed-password', $data, function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Thay đổi mật khẩu');
                });
            }
            DB::commit();
            Auth::logout();
            Session::flash('info', 'Bạn đã thay đổi mật khẩu thành công. Hãy sử dụng mật khẩu mới để đăng nhập nhé');
            $this->redirectRoute('admin.login');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé! Mã lỗi: ' . $e->getCode()]);
        }
    }

    public function render()
    {
        return view('livewire.settings.profile', [
            'user' => User::findOrFail(Auth::id()),
        ]);
    }
}
