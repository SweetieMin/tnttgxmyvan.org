<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SeoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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

    public function registrationView()
    {
        SeoService::setDefaultSeo('Đơn đăng ký');
        $data = [
            'pageTitle' => 'Đơn đăng ký'
        ];
        return view('back.personnel.registration', $data);
    }

    /**
     * Tạo token_v2 cho các tài khoản chưa có (cùng kiểu với cột token: chuỗi ngẫu nhiên 64 ký tự).
     * Chỉ điền cho người đang trống nên gọi lại nhiều lần cũng không đổi mã đã có.
     */
    public function generateTokenV2()
    {
        if (!Auth::user()->roles()->whereIn('name', ['admin', 'Admin'])->exists()) {
            abort(403, 'Chỉ Admin mới được tạo token_v2.');
        }

        if (!Schema::hasColumn('users', 'token_v2')) {
            return response()->json([
                'success' => false,
                'message' => 'Bảng users chưa có cột token_v2.',
            ], 422);
        }

        $generated = 0;

        DB::table('users')
            ->whereNull('deleted_at')
            ->where(fn($query) => $query->whereNull('token_v2')->orWhere('token_v2', ''))
            ->orderBy('id')
            ->select('id')
            ->chunkById(200, function ($users) use (&$generated) {
                foreach ($users as $user) {
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['token_v2' => $this->uniqueTokenV2()]);
                    $generated++;
                }
            });

        return response()->json([
            'success' => true,
            'message' => "Đã tạo token_v2 cho {$generated} tài khoản.",
            'generated' => $generated,
            'total_users' => DB::table('users')->whereNull('deleted_at')->count(),
            'missing_after' => DB::table('users')
                ->whereNull('deleted_at')
                ->where(fn($query) => $query->whereNull('token_v2')->orWhere('token_v2', ''))
                ->count(),
        ]);
    }

    private function uniqueTokenV2(): string
    {
        do {
            $token = Str::random(64);
        } while (
            DB::table('users')->where('token_v2', $token)->orWhere('token', $token)->exists()
        );

        return $token;
    }
}
