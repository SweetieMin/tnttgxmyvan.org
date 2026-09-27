<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\SeoService;

class RegistrationController extends Controller
{
    /**
     * Trang public cho phụ huynh: đăng ký thiếu nhi mới / xin cấp lại thẻ / tra cứu hồ sơ.
     */
    public function registerView()
    {
        SeoService::setDefaultSeo('Đăng ký thiếu nhi');

        return view('front.registration', [
            'pageTitle' => 'Đăng ký thiếu nhi',
        ]);
    }
}
