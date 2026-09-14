<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPassword extends Component
{

    public $email;

    public function sendPasswordResetLink()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Địa chỉ email không hợp lệ',
            'email.exists' => 'Email không tồn tại trong hệ thống',
        ]);

        $user = User::query()->where('email', $this->email)->first();

        if ($user->email_verified_at == null) {
            return redirect()->route('admin.forgot')
                ->with('fail', 'Email của bạn chưa được xác minh. Vui lòng liên hệ với trang Thiếu Nhi để được xử lý.');
        }

        $oldToken = DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->first();

        if ($oldToken && Carbon::parse($oldToken->created_at)->addMinutes(15)->isFuture()) {
            session()->flash('fail', 'Liên kết đặt lại mật khẩu đã được gửi trước đó và vẫn còn hiệu lực trong 15 phút. Bạn vui lòng kiểm tra email hoặc trong thư rác nhé!');
            return;
        }

        $token = Str::random(64);

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

        $actionLink = route('admin.reset_password_form', ['token' => $token]);

        $data = [
            'actionLink' => $actionLink,
            'user' => $user
        ];

        //return view('Mail.auth.forgot-password',$data);

        try {
            Mail::send('Mail.auth.forgot-password', $data, function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Quên mật khẩu');
            });

            session()->flash('success', 'Chúng mình đã gửi liên kết đặt lại mật khẩu đến email của bạn.');
        } catch (\Exception $e) {

            //dd('Gửi email thất bại: ' . $e->getMessage());
            session()->flash('fail', 'Có lỗi xảy ra trong quá trình gửi email. Vui lòng thử lại sau.' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
