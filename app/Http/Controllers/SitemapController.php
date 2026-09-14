<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function generate()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0))  // Trang chủ
            ->add(Url::create('/about')->setPriority(0.8))  // Ví dụ trang về chúng tôi
            // Thêm các URL của bạn vào đây
            ->writeToFile(public_path('sitemap.xml')); // Lưu sitemap tại public/
        
        return response()->download(public_path('sitemap.xml'));
    }
}
