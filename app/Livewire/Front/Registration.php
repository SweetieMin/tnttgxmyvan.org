<?php

namespace App\Livewire\Front;

use App\Models\Course;
use App\Models\RegistrationRequest;
use App\Models\Sector;
use App\Services\RegistrationService;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class Registration extends Component
{
    use WithFileUploads;

    private const PHONE_REGEX = 'regex:/^(0[35789])[0-9]{8}$/';

    public $tab = 'new';
    protected $queryString = ['tab' => ['except' => 'new']];

    // Chống spam: bot thường điền cả ô ẩn này
    public $website;

    // Đăng ký mới
    public $holyName, $fullName, $birthday, $address, $phone, $sector_id, $course_id;
    public $nameFather, $phoneFather, $nameMother, $phoneMother, $godParent;
    public $picture, $note, $agree = false;

    // Cấp lại thẻ
    public $lookup_key, $lookup_birthday;
    #[Locked]
    public $foundChildren = []; // [id => tên đã che]
    public $selected_user_id, $reason = 'lost', $contact_phone, $reissue_picture, $reissue_note;

    // Tra cứu đơn
    public $status_code, $status_phone;
    #[Locked]
    public $statusResult;

    #[Locked]
    public $submittedCode;

    public function selectTab($tab)
    {
        if (in_array($tab, ['new', 'reissue', 'status'])) {
            $this->tab = $tab;
            $this->submittedCode = null;
            $this->resetErrorBag();
        }
    }

    public function updatedPicture()
    {
        $this->validateOnly('picture', ['picture' => 'image|mimes:jpg,jpeg,png,webp|max:8192'], $this->messages());
    }

    public function updatedReissuePicture()
    {
        $this->validateOnly('reissue_picture', ['reissue_picture' => 'image|mimes:jpg,jpeg,png,webp|max:8192'], $this->messages());
    }

    public function submitNew()
    {
        if ($this->isBot()) {
            return;
        }

        $this->validate([
            'holyName' => 'required|string|max:100',
            'fullName' => 'required|string|max:255',
            'birthday' => 'required|date|before:today|after:' . now()->subYears(25)->toDateString(),
            'address' => 'required|string|max:255',
            'phone' => ['nullable', self::PHONE_REGEX],
            'sector_id' => 'nullable|exists:sectors,id',
            'course_id' => 'nullable|exists:courses,id',
            'nameFather' => 'nullable|string|max:255',
            'phoneFather' => ['nullable', self::PHONE_REGEX, 'required_without:phoneMother'],
            'nameMother' => 'nullable|string|max:255',
            'phoneMother' => ['nullable', self::PHONE_REGEX, 'required_without:phoneFather'],
            'godParent' => 'nullable|string|max:255',
            'picture' => 'required|image|mimes:jpg,jpeg,png,webp|max:8192',
            'note' => 'nullable|string|max:500',
            'agree' => 'accepted',
        ], $this->messages());

        if (!$this->hitRateLimit()) {
            return;
        }

        $existing = RegistrationService::findExistingChild($this->holyName, $this->fullName, $this->birthday);

        $request = RegistrationRequest::create([
            'code' => RegistrationRequest::generateCode(),
            'type' => 'new',
            'duplicate_user_id' => $existing?->id,
            'holyName' => RegistrationService::formatName($this->holyName),
            'fullName' => RegistrationService::formatName($this->fullName),
            'birthday' => $this->birthday,
            'address' => trim($this->address),
            'phone' => $this->phone,
            'sector_id' => $this->sector_id ?: null,
            'course_id' => $this->course_id ?: null,
            'nameFather' => RegistrationService::formatName($this->nameFather) ?: null,
            'phoneFather' => $this->phoneFather ?: null,
            'nameMother' => RegistrationService::formatName($this->nameMother) ?: null,
            'phoneMother' => $this->phoneMother ?: null,
            'godParent' => RegistrationService::formatName($this->godParent) ?: null,
            'contact_phone' => $this->phoneFather ?: $this->phoneMother,
            'picture' => RegistrationService::storePicture($this->picture),
            'note' => $this->note,
            'ip_address' => request()->ip(),
        ]);

        $this->reset(['holyName', 'fullName', 'birthday', 'address', 'phone', 'sector_id', 'course_id',
            'nameFather', 'phoneFather', 'nameMother', 'phoneMother', 'godParent', 'picture', 'note', 'agree']);
        $this->submittedCode = $request->code;
    }

    public function lookupChild()
    {
        $this->validate([
            'lookup_key' => 'required|string|max:30',
            'lookup_birthday' => 'required|date',
        ], $this->messages());

        $limiterKey = 'registration-lookup:' . request()->ip();
        if (RateLimiter::tooManyAttempts($limiterKey, 10)) {
            $this->addError('lookup_key', 'Bạn tra cứu quá nhiều lần. Vui lòng thử lại sau ' . ceil(RateLimiter::availableIn($limiterKey) / 60) . ' phút.');
            return;
        }
        RateLimiter::hit($limiterKey, 600);

        $this->selected_user_id = null;
        $this->foundChildren = RegistrationService::findChildren($this->lookup_key, $this->lookup_birthday)
            ->mapWithKeys(fn($user) => [$user->id => RegistrationService::maskName($user)])
            ->all();

        if (empty($this->foundChildren)) {
            $this->addError('lookup_key', 'Không tìm thấy thiếu nhi. Vui lòng kiểm tra lại mã thiếu nhi / số điện thoại và ngày sinh.');
            return;
        }

        if (count($this->foundChildren) === 1) {
            $this->selected_user_id = array_key_first($this->foundChildren);
        }
    }

    public function resetLookup()
    {
        $this->reset(['foundChildren', 'selected_user_id', 'reason', 'contact_phone', 'reissue_picture', 'reissue_note']);
        $this->resetErrorBag();
    }

    public function submitReissue()
    {
        if ($this->isBot()) {
            return;
        }

        $this->validate([
            'selected_user_id' => ['required', 'in:' . implode(',', array_keys($this->foundChildren))],
            'reason' => 'required|in:' . implode(',', array_keys(RegistrationRequest::REASONS)),
            'contact_phone' => ['required', self::PHONE_REGEX],
            'reissue_picture' => [$this->reason === 'photo' ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'reissue_note' => 'nullable|string|max:500',
        ], $this->messages());

        $pending = RegistrationRequest::where('type', 'reissue')
            ->where('user_id', $this->selected_user_id)
            ->where('status', 'pending')
            ->exists();

        if ($pending) {
            $this->addError('selected_user_id', 'Em này đã có một đơn cấp lại thẻ đang chờ duyệt. Vui lòng chờ hoặc tra cứu bằng mã hồ sơ đã nhận.');
            return;
        }

        if (!$this->hitRateLimit()) {
            return;
        }

        $request = RegistrationRequest::create([
            'code' => RegistrationRequest::generateCode(),
            'type' => 'reissue',
            'user_id' => $this->selected_user_id,
            'reason' => $this->reason,
            'contact_phone' => $this->contact_phone,
            'picture' => $this->reissue_picture ? RegistrationService::storePicture($this->reissue_picture) : null,
            'note' => $this->reissue_note,
            'ip_address' => request()->ip(),
        ]);

        $this->reset(['lookup_key', 'lookup_birthday']);
        $this->resetLookup();
        $this->submittedCode = $request->code;
    }

    public function checkStatus()
    {
        $this->validate([
            'status_code' => 'required|string|max:20',
            'status_phone' => ['required', self::PHONE_REGEX],
        ], $this->messages());

        $limiterKey = 'registration-status:' . request()->ip();
        if (RateLimiter::tooManyAttempts($limiterKey, 20)) {
            $this->addError('status_code', 'Bạn tra cứu quá nhiều lần. Vui lòng thử lại sau.');
            return;
        }
        RateLimiter::hit($limiterKey, 600);

        $request = RegistrationRequest::with('user')
            ->where('code', strtoupper(trim($this->status_code)))
            ->where('contact_phone', $this->status_phone)
            ->first();

        if (!$request) {
            $this->statusResult = null;
            $this->addError('status_code', 'Không tìm thấy hồ sơ. Vui lòng kiểm tra lại mã hồ sơ và số điện thoại.');
            return;
        }

        $this->statusResult = [
            'code' => $request->code,
            'type' => $request->type_label,
            'child' => $request->child_name,
            'status' => $request->status,
            'status_label' => $request->status_label,
            'created_at' => $request->created_at->format('H:i d/m/Y'),
            'admin_note' => $request->admin_note,
            'account_code' => $request->status === 'approved' ? $request->user?->account_code : null,
        ];
    }

    private function isBot(): bool
    {
        if (filled($this->website)) {
            $this->submittedCode = 'DK' . now()->format('ymd') . '-' . strtoupper(substr(md5(microtime()), 0, 4));
            return true;
        }

        return false;
    }

    private function hitRateLimit(): bool
    {
        $limiterKey = 'registration-submit:' . request()->ip();

        if (RateLimiter::tooManyAttempts($limiterKey, 5)) {
            $this->addError('form', 'Bạn đã gửi quá nhiều đơn. Vui lòng thử lại sau ' . ceil(RateLimiter::availableIn($limiterKey) / 60) . ' phút.');
            return false;
        }

        RateLimiter::hit($limiterKey, 3600);

        return true;
    }

    protected function messages()
    {
        return [
            'holyName.required' => 'Tên Thánh không được để trống',
            'fullName.required' => 'Họ và tên không được để trống',
            'birthday.required' => 'Ngày sinh không được để trống',
            'birthday.date' => 'Ngày sinh không đúng định dạng',
            'birthday.before' => 'Ngày sinh không hợp lệ',
            'birthday.after' => 'Ngày sinh không hợp lệ',
            'address.required' => 'Địa chỉ không được để trống',
            'phone.regex' => 'Số điện thoại không đúng định dạng',
            'phoneFather.regex' => 'Số điện thoại không đúng định dạng',
            'phoneMother.regex' => 'Số điện thoại không đúng định dạng',
            'phoneFather.required_without' => 'Cần ít nhất một số điện thoại của cha hoặc mẹ',
            'phoneMother.required_without' => 'Cần ít nhất một số điện thoại của cha hoặc mẹ',
            'picture.required' => 'Vui lòng chọn ảnh thẻ của em',
            'picture.image' => 'Tệp phải là hình ảnh',
            'picture.mimes' => 'Ảnh phải có định dạng jpg, png hoặc webp',
            'picture.max' => 'Ảnh không được lớn hơn 8MB',
            'agree.accepted' => 'Vui lòng đồng ý để Xứ Đoàn lưu thông tin của em',
            'lookup_key.required' => 'Vui lòng nhập mã thiếu nhi hoặc số điện thoại phụ huynh',
            'lookup_birthday.required' => 'Vui lòng nhập ngày sinh của em',
            'selected_user_id.required' => 'Vui lòng chọn thiếu nhi',
            'selected_user_id.in' => 'Vui lòng chọn thiếu nhi',
            'reason.required' => 'Vui lòng chọn lý do',
            'contact_phone.required' => 'Vui lòng nhập số điện thoại liên hệ',
            'contact_phone.regex' => 'Số điện thoại không đúng định dạng',
            'reissue_picture.required' => 'Vui lòng chọn ảnh mới',
            'reissue_picture.image' => 'Tệp phải là hình ảnh',
            'reissue_picture.mimes' => 'Ảnh phải có định dạng jpg, png hoặc webp',
            'reissue_picture.max' => 'Ảnh không được lớn hơn 8MB',
            'status_code.required' => 'Vui lòng nhập mã hồ sơ',
            'status_phone.required' => 'Vui lòng nhập số điện thoại',
            'status_phone.regex' => 'Số điện thoại không đúng định dạng',
        ];
    }

    public function render()
    {
        return view('livewire.front.registration', [
            'listSectors' => Sector::orderBy('ordering')->get(),
            'listCourses' => Course::orderBy('ordering')->get(),
            'reasons' => RegistrationRequest::REASONS,
        ]);
    }
}
