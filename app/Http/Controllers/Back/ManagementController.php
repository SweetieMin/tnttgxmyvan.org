<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SeoService;

class ManagementController extends Controller
{
    public function courseView()
    {
        SeoService::setDefaultSeo('Lớp Giáo Lý');
        $data = [
            'pageTitle' => 'Lớp Giáo Lý'
        ];
        return view('back.management.course', $data);
    }

    public function sectorView()
    {
        SeoService::setDefaultSeo('Ngành Sinh Hoạt');
        $data = [
            'pageTitle' => 'Ngành Sinh Hoạt'
        ];
        return view('back.management.sector', $data);
    }

    public function bibleView()
    {
        SeoService::setDefaultSeo('Câu Kinh Thánh');
        $data = [
            'pageTitle' => 'Câu Kinh Thánh'
        ];
        return view('back.management.bible', $data);
    }

    public function scheduleView()
    {
        SeoService::setDefaultSeo('Lịch Điểm Danh');
        $data = [
            'pageTitle' => 'Lịch Điểm Danh'
        ];
        return view('back.management.schedule', $data);
    }

    public function roleView()
    {
        SeoService::setDefaultSeo('Chức Vụ');
        $data = [
            'pageTitle' => 'Chức Vụ'
        ];
        return view('back.management.role', $data);
    }

    public function permissionView()
    {
        SeoService::setDefaultSeo('Quyền Truy Cập');
        $data = [
            'pageTitle' => 'Quyền Truy Cập'
        ];
        return view('back.management.permission', $data);
    }

    public function regulationView()
    {
        SeoService::setDefaultSeo('Nội Quy');
        $data = [
            'pageTitle' => 'Nội Quy'
        ];
        return view('back.management.regulation', $data);
    }

    public function noticeView()
    {
        SeoService::setDefaultSeo('Thông Báo');
        $data = [
            'pageTitle' => 'Thông Báo'
        ];
        return view('back.management.notice', $data);
    }

    public function activityLogsView()
    {
        SeoService::setDefaultSeo('Nhật Ký');
        $data = [
            'pageTitle' => 'Nhật Ký'
        ];
        return view('back.management.activity-logs', $data);
    }

    public function transactionView()
    {
        SeoService::setDefaultSeo('Tiền Quỹ');
        $data = [
            'pageTitle' => 'Tiền Quỹ'
        ];
        return view('back.management.transaction', $data);
    }
}
