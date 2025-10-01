<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Notice;
use App\Models\Regulation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\SeoService;

class ProfileController extends Controller
{
    public function profileView($token = null)
    {

        SeoService::setDefaultSeo('Xem hồ sơ');

        $user = User::with('roles')->where('token', $token)->firstOrFail();

        $userRoles = $user->roles->pluck('name')->toArray();
        $regulations = Regulation::where(function ($query) use ($userRoles) {
            foreach ($userRoles as $role) {
                $query->orWhereJsonContains('applicable_object', $role);
            }
        })->get();

        $totalRewardPoints = 0;
        $totalDisciplinePoints = 0;
        $rewards = [];
        $disciplines = [];

        foreach ($regulations as $index => $regulation) {
            $attendances = Attendance::where('user_id', $user->id)
                ->where('isConfirm', true)
                ->where('status', 1)
                ->where('regulation_id', $regulation->id)
                ->get();

            $soLan = $attendances->count();
            $score = $regulation->points * $soLan;

            if ($soLan > 0) {
                $record = [
                    'stt' => $index + 1,
                    'noi_dung' => $regulation->description,
                    'so_lan' => $soLan,
                    'so_diem' => $score,
                    'ghi_chu' => $regulation->id,
                ];

                if ($regulation->type === 'plus') {
                    $totalRewardPoints += $score;
                    $rewards[] = $record;
                } else {
                    $totalDisciplinePoints += $score;
                    $disciplines[] = $record;
                }
            }
        }

        $totalScore = $totalRewardPoints - $totalDisciplinePoints;

        $noticePopUp = Notice::query()
            ->where('is_popup', 1)
            ->where('is_active', 1)
            ->take(1)
            ->get()
            ->filter(function ($notice) use ($user) {
                return $notice->isApplicableToUser($user);
            })
            ->first();

        $data = [
            'user' => $user,
            'totalScore' => $totalScore,
            'noticePopUp' => $noticePopUp
        ];
        return view('front.profile', $data);
    }
}
