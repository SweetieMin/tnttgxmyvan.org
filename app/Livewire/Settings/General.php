<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\GeneralSetting;

class General extends Component
{
    public $tab = null;
    public $tabname = 'general_settings';
    protected $queryString = ['tab'=>['keep'=>true]];

    public $site_title, $site_email,$site_phone,$site_meta_keywords,$site_meta_description, $facebook_url, $tikTok_url, $youtube_url, $instagram_url,$site_logo,$site_favicon;

    public function selectTab($tab){
        $this->tab = $tab;
    }

    public function mount(){
        $this->tab = Request('tab') ? Request('tab') : $this->tabname;      
        
        $settings = GeneralSetting::take(1)->first();

        if( !is_null($settings)){
            $this->site_title = $settings->site_title;
            $this->site_email = $settings->site_email;
            $this->site_phone = $settings->site_phone;
            $this->site_meta_keywords = $settings->site_meta_keywords;
            $this->site_meta_description = $settings->site_meta_description;

            $this->facebook_url = $settings->facebook_url;
            $this->tikTok_url = $settings->tikTok_url;
            $this->youtube_url = $settings->youtube_url;
            $this->instagram_url = $settings->instagram_url;       

            $this->site_logo = $settings->site_logo;
            $this->site_favicon = $settings->site_favicon;
        }
    }

    public function updateSiteInfo(){
        $this->validate([
            'site_title' => 'required',
            'site_email' => 'required|email',
            'site_phone' => 'nullable',
            'site_meta_keywords' => 'nullable|max:255',
            'site_meta_description' => 'nullable|max:500',
        ],[
            'site_title.required' => 'Tiêu đề là trường bắt buộc',
            'site_email.required' => 'Email là trường bắt buộc',
            'site_email.email' => 'Email không hợp lệ',
            'site_meta_keywords.max' => 'Từ khóa không được vượt quá 255 ký tự',
            'site_meta_description.max' => 'Mô tả không được vượt quá 500 ký tự',
        ]);

        $settings = GeneralSetting::take(1)->first();

        $data = array(
            'site_title' => $this->site_title,
            'site_email' => $this->site_email,
            'site_phone' => $this->site_phone,
            'site_meta_keywords' => $this->site_meta_keywords,
            'site_meta_description' => $this->site_meta_description,
        );

        if (!is_null($settings)) {
            $query = $settings->update($data);
        } else {
            $query = GeneralSetting::insert($data);
        }

        if ($query) {
            # code...
            $this->dispatch('showToastr',['type'=>'success','message'=>'Cài đặt chung đã cập nhật thành công.']);
        } else {
            # code...
            $this->dispatch('showToastr',['type'=>'fail','message'=>'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé!.']);
        }
        
    }

    public function updateSiteSocialLinks(){
        $this->validate([
            'facebook_url' => 'url|nullable',
            'instagram_url' => 'url|nullable',
            'youtube_url' => 'url|nullable',
            'tikTok_url' => 'url|nullable',
        ],[
           'facebook_url.url' => 'Địa chỉ Facebook bạn nhập không hợp lệ.', 
           'instagram_url.url' => 'Địa chỉ Instagram bạn nhập không hợp lệ.', 
           'youtube_url.url' => 'Địa chỉ Youtube bạn nhập không hợp lệ.', 
           'tikTok_url.url' => 'Địa chỉ TikTok bạn nhập không hợp lệ.', 
        ]);

        $settings = GeneralSetting::take(1)->first();

        $data = array(
            'facebook_url' => $this->facebook_url,
            'instagram_url' => $this->instagram_url,
            'youtube_url' => $this->youtube_url,
            'tikTok_url' => $this->tikTok_url,
        );

        if (!is_null($settings)) {
            $query = $settings->update($data);
        } else {
            $query = GeneralSetting::insert($data);
        }

        if ($query) {
            # code...
            $this->dispatch('showToastr',['type'=>'success','message'=>'Mạng xã hội đã cập nhật thành công.']);
        } else {
            # code...
            $this->dispatch('showToastr',['type'=>'fail','message'=>'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé!.']);
        }

    }

    

    public function render()
    {
        return view('livewire.settings.general');
    }
}
