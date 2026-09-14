<?php

use App\Models\GeneralSetting;

if (!function_exists('settings')) {
    function settings()
    {
        $settings = GeneralSetting::first();

        if (!is_null($settings)) {
            return $settings;
        }
        return null;
    }
}
