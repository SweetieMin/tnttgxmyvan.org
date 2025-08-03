<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Login extends Component
{
    public $login_id, $password, $remember = false;

    public function login()
    {
        $this->resetErrorBag();
        $fieldType = filter_var($this->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'account_code';

        $this->validate($this->rules($fieldType), $this->messages());

        $credentials = [
            $fieldType => $this->login_id,
            'password' => $this->password
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            $user = Auth::user();

            // Kiểm tra trạng thái
            if ($user->status === 'inactive') {
                return $this->logoutWithMessage('Tài khoản của bạn hiện đang bị khóa. Vui lòng liên hệ trang Thiếu Nhi để được xử lý.');
            }

            // Nếu login bằng email và chưa xác minh
            if ($fieldType === 'email' && $user->email_verified_at === null) {
                return $this->logoutWithMessage('Email này chưa được xác minh để đăng nhập. Vui lòng sử dụng Mã tài khoản.');
            }

            // Remember cookie
            if ($this->remember) {
                Cookie::queue('login_id', $this->login_id, 60 * 24 * 7);
            } else {
                Cookie::queue(Cookie::forget('login_id'));
            }

            $currentSessionId = session()->getId();
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('id', '!=', $currentSessionId)
                ->delete();

            Session::forget('login_attempts_' . $this->login_id);
            sleep(1);
            return redirect()->intended(route('admin.dashboard'));
        }

        $this->handleFailedLogin($fieldType);

        return redirect()->route('admin.login')
            ->withInput()
            ->with('fail', 'Đăng nhập thất bại! Vui lòng thử lại.');
    }

    private function logoutWithMessage($message)
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        session()->flash('fail', $message);
        return redirect()->route('admin.login');
    }

    private function handleFailedLogin($fieldType)
    {
        $attemptsKey = 'login_attempts_' . $this->login_id;
        $attempts = Session::get($attemptsKey, 0) + 1;
        Session::put($attemptsKey, $attempts);

        if ($attempts >= 5) {
            $user = User::where($fieldType, $this->login_id)->first();
            if ($user) {
                $user->update(['status' => 'inactive']);
            }
            Session::forget($attemptsKey);
            redirect()->route('admin.login')
                ->withInput()
                ->with('fail', 'Bạn đã nhập sai 5 lần. Tài khoản của bạn đã bị khóa. Vui lòng liên hệ trang Thiếu Nhi để được hướng dẫn.')
                ->send();
            exit;
        }
    }

    private function rules($fieldType)
    {
        if ($fieldType === 'email') {
            return [
                'login_id' => 'required|email|exists:users,email',
                'password' => 'required|min:5|max:45',
            ];
        }

        return [
            'login_id' => 'required|exists:users,account_code',
            'password' => 'required|min:5|max:45',
        ];
    }

    private function messages()
    {
        return [
            'login_id.required' => 'Mã tài khoản/Email là bắt buộc',
            'login_id.email' => 'Địa chỉ email không hợp lệ',
            'login_id.exists' => 'Thông tin đăng nhập không hợp lệ',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min' => 'Mật khẩu phải có trên 5 ký tự',
            'password.max' => 'Mật khẩu tối đa 45 ký tự',
        ];
    }

    public function mount()
    {
        $this->login_id = old('login_id');
        $this->password = old('password');
        $this->remember = old('remember', false);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
