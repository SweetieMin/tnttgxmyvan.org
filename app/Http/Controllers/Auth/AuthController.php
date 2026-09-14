<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Services\SeoService;

class AuthController extends Controller
{
    public function loginForm()
    {
        SeoService::setDefaultSeo('Đăng nhập - Đoàn Thiếu Nhi Thánh Thể Giáo Xứ Mỹ Vân');

        $data = [
            'pageTitle' => 'Đăng nhập - Đoàn Thiếu Nhi Thánh Thể Giáo Xứ Mỹ Vân'
        ];
        return view('auth.login', $data);
    }

    public function forgotForm()
    {
        SeoService::setDefaultSeo('Quên mật khẩu');
        $data = [
            'pageTitle' => 'Quên mật khẩu'
        ];
        return view('auth.forgot', $data);
    }

    public function resetForm(Request $request, $token = null)
    {
        SeoService::setDefaultSeo('Đặt lại mật khẩu');
        $isTokenExists = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();

        if (!$isTokenExists) {
            return redirect()->route('admin.forgot')->with('fail', 'Mã xác minh không tồn tại, Bạn hãy tạo mã xác minh mới nhé!');
        } else {

            $diffMins = Carbon::createFromFormat('Y-m-d H:i:s', $isTokenExists->created_at)->diffInMinutes(Carbon::now());

            if ($diffMins > 15) {
                return redirect()->route('admin.forgot')->with('fail', 'Mã xác minh đã hết bạn, Bạn hãy tạo mã xác minh mới nhé!');
            }

            $data = [
                'pageTitle' => 'Đặt lại mật khẩu',
                'token' => $token
            ];
        }

        return view('auth.reset', $data);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'new_password' => [
                'required',
                'min:5',
                'max:45',
                'required_with:new_password_confirmation',
                'same:new_password_confirmation'
            ],
            'new_password_confirmation' => 'required'
        ], [
            // Validation messages for 'new_password'
            'new_password.required' => 'Mật khẩu là bắt buộc.',
            'new_password.min' => 'Mật khẩu phải có ít nhất 5 ký tự.',
            'new_password.max' => 'Mật khẩu không được vượt quá 45 ký tự.',
            'new_password.required_with' => 'Mật khẩu mới phải được nhập cùng với xác nhận mật khẩu.',
            'new_password.same' => 'Mật khẩu mới và xác nhận mật khẩu không khớp.',

            // Validation messages for 'new_password_confirmation'
            'new_password_confirmation.required' => 'Xác nhận mật khẩu là bắt buộc.'
        ]);

        $dbToken = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->first();

        $user = User::query()->where('email', $dbToken->email)->first();

        User::query()->where('email', $user->email)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $data = array(
            'user' => $user,
            'new_password' => $request->new_password
        );

        //return view('Mail.auth.notify-changed-password',$data);

        try {
            $sendMail = Mail::send('Mail.auth.notify-changed-password', $data, function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Thay đổi mật khẩu');
            });
            DB::table('password_reset_tokens')->where([
                'email' => $dbToken->email,
                'token' => $dbToken->token
            ])->delete();
            return redirect()->route('admin.login')
                ->with('success', 'Chúng mình đã thay đổi mật khẩu cho bạn. Bạn hãy dùng mật khẩu mới để đăng nhập nhé.');
        } catch (\Exception $e) {

            //dd('Gửi email thất bại: ' . $e->getMessage());

            return redirect()->route('admin.reset_password_form', ['token' => $dbToken->token])
                ->with('fail', 'Có lỗi xảy ra trong quá trình gửi email. Vui lòng thử lại sau. Mã lỗi: ' . $e->getCode());
        }
    }

    public function verifyEmail(Request $request, $token = null)
    {
        $isTokenExists = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();

        if (!$isTokenExists) {
            $data = [
                'pageTitle' => 'Xác minh tài khoản',
                'status' => 'error',
                'notify' => 'Mã xác minh không tồn tại. Bạn hãy gửi lại yêu cầu khác nhé',
                'token' => $token
            ];
            return view('Mail.auth.notify-verify-email', $data);
        } else {
            $diffMins = Carbon::createFromFormat('Y-m-d H:i:s', $isTokenExists->created_at)->diffInMinutes(Carbon::now());

            if ($diffMins > 15) {
                $data = [
                    'pageTitle' => 'Xác minh tài khoản',
                    'status' => 'info',
                    'notify' => 'Mã xác minh đã quá hạn. Bạn hãy gửi lại yêu cầu khác nhé',
                    'token' => $token
                ];
                return view('Mail.auth.notify-verify-email', $data);
            }
        }

        DB::beginTransaction();

        try {
            $dbToken = DB::table('password_reset_tokens')
                ->where('token', $request->token)
                ->first();

            $user = User::query()->where('email', $dbToken->email)->first();
            $user->email_verified_at = Carbon::now();
            $verify = $user->save();

            if (!$verify) {
                DB::rollBack();
                $data = [
                    'pageTitle' => 'Xác minh tài khoản',
                    'status' => 'error',
                    'notify' => 'Đã có vấn đề xảy ra. Bạn hãy thử lại sau nhé!',
                    'token' => $token
                ];
                return view('Mail.auth.notify-verify-email', $data);
            }
            DB::table('password_reset_tokens')->where([
                'email' => $dbToken->email,
                'token' => $dbToken->token
            ])->delete();
            DB::commit();
            $data = [
                'pageTitle' => 'Xác minh tài khoản',
                'status' => 'success',
                'notify' => 'Đã xác minh email thành công',
                'token' => $token
            ];
            return view('Mail.auth.notify-verify-email', $data);
        } catch (\Exception $e) {
            DB::rollBack();
            $data = [
                'pageTitle' => 'Xác minh tài khoản',
                'status' => 'error',
                'notify' => 'Đã có lỗi xảy ra, vui lòng thử lại sau!',
                'token' => $token
            ];
            return view('Mail.auth.notify-verify-email', $data);
        }
    }
}
