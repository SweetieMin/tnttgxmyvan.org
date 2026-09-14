<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mật khẩu cột "Xử lý" trong file DS vắng lễ
    |--------------------------------------------------------------------------
    |
    | Cột "Xử lý" trong file excel xuất ra bị khoá, chỉ ai biết mật khẩu này
    | mới sửa được. Để trống thì dùng chung mật khẩu khoá sheet (MV + ngày).
    | Đặt trong file .env: ATTENDANCE_PROCESS_PASSWORD=matkhaucuaban
    |
    */

    'process_password' => env('ATTENDANCE_PROCESS_PASSWORD'),

];
