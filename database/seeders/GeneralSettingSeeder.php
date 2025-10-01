<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GeneralSetting;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $setting = [
            'site_title' => 'Đoàn TNTT Giáo Xứ Mỹ Vân',
            'site_email' => 'tntt.myvan@gmail.com',
            'site_phone' => '',
            'site_meta_keywords' => 'Nhà thờ, giáo xứ, Mỹ Vân, Huynh Trưởng, Giáo Lý Viên, Thiếu Nhi, Quản lý, Điểm danh,...',
            'site_meta_description' => 'Đây là trang web của đoàn TNTT giáo xứ Mỹ Vân để truyền tải thông tin và để quản lý các em thiếu nhi,...',
            'facebook_url' => 'https://www.facebook.com/profile.php?id=100069752143507',
            'youtube_url' => 'https://www.youtube.com/@TNTTGiaoXuMyVan',
            'tikTok_url' => 'https://www.tiktok.com/@xudoangiusevien',
            'site_logo' => 'LOGO_default.png',
            'site_favicon' => 'FAVICON_default.png',
        ];

        GeneralSetting::create($setting);

        
    }
}
