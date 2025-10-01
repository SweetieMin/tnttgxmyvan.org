<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SeoService;

class PersonnelController extends Controller
{
    public function scouterView()
    {
        SeoService::setDefaultSeo('Huynh Trưởng');
        $data = [
            'pageTitle' => 'Huynh Trưởng'
        ];
        return view('back.personnel.scouter', $data);
    }

    public function childrenView()
    {
        SeoService::setDefaultSeo('Thiếu Nhi');
        $data = [
            'pageTitle' => 'Thiếu Nhi'
        ];
        return view('back.personnel.children', $data);
    }
}
