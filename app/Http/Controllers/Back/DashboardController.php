<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Bible;
use Illuminate\Support\Facades\Cache;
use App\Models\GeneralSetting;
use App\Models\Notice;
use Illuminate\Support\Facades\File;
use SawaStacks\Utils\Kropify;
use App\Services\SeoService;


class DashboardController extends Controller
{
    public function dashboard()
    {
        SeoService::setDefaultSeo('Trang chủ');
        $user = User::findOrFail(Auth::id());

        $randomBible = Cache::remember('bible_' . date('Y-m-d'), 1440, function () {
            return Bible::query()
                ->where('ordering', '>', Bible::count() - 5)
                ->inRandomOrder()
                ->limit(1)
                ->first();
        });

        $data = [
            'user' => $user,
            'bible' => $randomBible->bible ?? null,
            'pageTitle' => 'Trang chủ',
        ];

        $roleNames = $user->roles->pluck('name')->toArray();

        if (in_array('Thiếu Nhi', $roleNames)) {
            return view('back.dashboard.child-Dashboard', $data);
        }

        if (array_intersect($roleNames, ['Đội Trưởng', 'Dự Trưởng', 'Huynh Trưởng'])) {
            return view('back.dashboard.scouter-Dashboard', $data);
        }
        return view('back.dashboard.leader-Dashboard', $data);
    }

    public function logoutHandler(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('admin.login')->with('success', 'Đăng xuất thành công!');
    }

    public function profileView(Request $request)
    {
        SeoService::setDefaultSeo('Tài khoản');
        $data = [
            'pageTitle' => 'Tài khoản'
        ];
        return view('back.settings.profile', $data);
    }

    public function generalSettings(Request $request)
    {
        SeoService::setDefaultSeo('Cài đặt');
        $data = [
            'pageTitle' => 'Cài đặt'
        ];
        return view('back.settings.general_setting', $data);
    }

    public function updateLogo(Request $request)
    {
        $settings = GeneralSetting::take(1)->first();

        if (!is_null($settings)) {
            $path = 'images/site/';
            $old_logo = $settings->site_logo;
            $file = $request->file('site_logo');
            $filename = 'LOGO_' . uniqid() . '.png';

            if ($request->hasFile('site_logo')) {
                $upload = $file->move(public_path($path), $filename);

                if ($upload) {
                    if ($old_logo != null && File::exists(public_path($path . $old_logo))) {
                        File::delete(public_path($path . $old_logo));
                    }
                    $settings->update(['site_logo' => $filename]);

                    return response()->json(['status' => 1, 'image_path' => $path . $filename, 'message' => 'Logo của trang đã cập nhật thành công.']);
                } else {
                    return response()->json(['status' => 0, 'message' => 'Đã có vẫn đề xảy ra. Bạn hãy thử lại sau nhé.']);
                }
            }
        } else {
            return response()->json(['status' => 0, 'message' => 'Hãy đảm bảo bạn đã chọn hình Logo hoặc Favicon.']);
        }
    }
    public function updateFavicon(Request $request)
    {
        $settings = GeneralSetting::take(1)->first();
        if (!is_null($settings)) {
            $path = 'images/site/';
            $old_favicon = $settings->site_favicon;
            $file = $request->file('site_favicon');
            $filename = 'FAVICON_' . uniqid() . '.png';

            if ($request->hasFile('site_favicon')) {
                $upload = $file->move(public_path($path), $filename);

                if ($upload) {
                    if ($old_favicon != null && File::exists(public_path($path . $old_favicon))) {
                        File::delete(public_path($path . $old_favicon));
                    }
                    $settings->update(['site_favicon' => $filename]);

                    return response()->json(['status' => 1, 'image_path' => $path . $filename, 'message' => 'Favicon của trang đã cập nhật thành công.']);
                } else {
                    return response()->json(['status' => 0, 'message' => 'Đã có vẫn đề xảy ra. Bạn hãy thử lại sau nhé.']);
                }
            }
        } else {
            return response()->json(['status' => 0, 'message' => 'Hãy đảm bảo bạn đã chọn hình Logo hoặc Favicon.']);
        }
    }

    public function scoreView(Request $request)
    {
        SeoService::setDefaultSeo('Thành tích');
        $data = [
            'pageTitle' => 'Thành tich',
        ];
        return view('back.attendance.score', $data);
    }

    public function updateProfilePicture(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $path = 'images/users/';
        $file = $request->file('profilePictureFile');
        if (!$file) {
            return response()->json(['status' => 0, 'message' => 'Không tìm thấy tệp ảnh hồ sơ.']);
        }

        $old_picture = $user->getAttributes()['picture'];
        $filename = $user->account_code . "-" . uniqid() . '.png';
        try {
            $upload = Kropify::getFile($file, $filename)->maxWoH(255)->save(public_path($path));
            if ($upload) {
                if ($old_picture && File::exists(public_path($path . $old_picture))) {
                    File::delete(public_path($path . $old_picture));
                }
                $user->update([
                    'picture' => $filename,
                ]);

                return response()->json(['status' => 1, 'message' => 'Ảnh đại diện được cập nhật thành công!']);
            } else {
                return response()->json(['status' => 0, 'message' => 'Không thể lưu ảnh đại diện.']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Đã xảy ra lỗi khi cập nhật ảnh đại diện: ' . $e->getMessage(),
            ], 500);
        }
    }

}
