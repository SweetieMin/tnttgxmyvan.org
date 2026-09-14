<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SeoService;

class AttendanceController extends Controller
{
    public function rewardView(Request $request)
    {
        SeoService::setDefaultSeo('Khen thưởng');
        $data = [
            'pageTitle' => 'Khen thưởng',
        ];

        return view('back.attendance.reward', $data);
    }

    public function disciplineView()
    {
        SeoService::setDefaultSeo('Kỷ luật');
        $data = [
            'pageTitle' => 'Kỷ luật',
        ];

        return view('back.attendance.discipline', $data);
    }

    public function makeupView()
    {
        SeoService::setDefaultSeo('Điểm danh bù');
        $data = [
            'pageTitle' => 'Điểm danh bù',
        ];

        return view('back.attendance.makeup', $data);
    }

    public function confirmView(Request $request)
    {
        SeoService::setDefaultSeo('Xét duyệt');
        $data = [
            'pageTitle' => 'Xét duyệt',
        ];

        return view('back.attendance.confirm', $data);
    }
}
