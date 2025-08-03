<?php

namespace App\Services;

use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;

class SeoService
{
    /**
     * Set default SEO meta tags for the page.
     *
     * @param string|null $customTitle Optional custom title for the page.
     * @return void
     */
    public static function setDefaultSeo($customTitle = null)
    {
        $title = $customTitle ?? $customTitle ?? 'Đăng nhập';
        $description = settings()->site_meta_description ?? 'Đăng nhập vào hệ thống';
        $imageURL = settings()->site_favicon ? url(settings()->site_favicon) : asset('/images/site/FAVICON_default.png');
        $keywords = settings()->site_meta_keywords ?? 'Đăng nhập, hệ thống, thiếu nhi mỹ vân, thiếu nhi, mỹ vân, mỹ vân thiếu nhi';
        $author = settings()->site_author ?? 'Toma Nguyễn Khắc Huấn';
        $currentURL = url()->current();

        // Basic SEO
        SEOTools::setTitle($title,false);
        SEOTools::setDescription($description);
        SEOMeta::setKeywords($keywords);
        SEOMeta::addMeta('author', $author);
        SEOMeta::setCanonical($currentURL);

        // OpenGraph (for Facebook, Zalo, LinkedIn, etc.)
        SEOTools::opengraph()->setUrl($currentURL);
        SEOTools::opengraph()->addImage($imageURL);
        SEOTools::opengraph()->addProperty('type', 'website');
        SEOTools::opengraph()->setTitle($title);
        SEOTools::opengraph()->setDescription($description);

        // Twitter Card
        SEOTools::twitter()->setTitle($title);
        SEOTools::twitter()->setDescription($description);
        SEOTools::twitter()->setImage($imageURL);
    }
}
