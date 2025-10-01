<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\User;
use App\Models\Notice;
use Illuminate\Support\Facades\Auth;

class Children extends Component
{
    public $notice_type,
           $notice_title,
           $notice_content,
           $notice_created_at;
    public $topUsers = [];
    public $allUsers = [];
    public $topThieuNhi = [];
    public $bangDiemTatCa = [];


    public function mount(){
        $thieuNhi = User::whereHas('roles', function ($q) {
            $q->where('name', 'Thiếu Nhi');
        })->with('roles')->get();
        $rankThieuNhi = $thieuNhi->map(function ($u) {
            $u->setTotalScore($this->calculateScore($u));
            return $u;
        })->sortByDesc('total_score')
            ->values();
        $this->topThieuNhi = $rankThieuNhi->take(10);
    }

    public function viewNotice($noticeId)
    {
        $notice = Notice::findOrFail($noticeId);
        $this->notice_type = $notice->type;
        $this->notice_title = $notice->title;
        $this->notice_content = $notice->content;
        $this->notice_created_at = $notice->created_at->format('d-m-Y');

        $this->dispatch('viewNoticeModal');
    }

    public function calculateScore(User $user): int
    {
        $userRoles = $user->roles->pluck('name')->toArray();

        $regulations = \App\Models\Regulation::where(function ($query) use ($userRoles) {
            foreach ($userRoles as $role) {
                $query->orWhereJsonContains('applicable_object', $role);
            }
        })->get();

        $reward = 0;
        $discipline = 0;

        foreach ($regulations as $regulation) {
            $count = \App\Models\Attendance::where('user_id', $user->id)
                ->where('isConfirm', true)
                ->where('status', 1)
                ->where('regulation_id', $regulation->id)
                ->count();

            $points = $regulation->points * $count;

            if ($regulation->type === 'plus') {
                $reward += $points;
            } else {
                $discipline += $points;
            }
        }

        return $reward - $discipline;
    }

    public function showAllThieuNhi()
    {
        $thieuNhi = User::whereHas('roles', function ($q) {
            $q->where('name', 'Thiếu Nhi');
        })->with('roles')->get();
        $this->bangDiemTatCa = $thieuNhi->map(function ($u) {
            $u->setTotalScore($this->calculateScore($u));
            return $u;
        })->sortByDesc('total_score')
            ->values();

        $this->dispatch('openAllRankingModal');
    }

    public function render()
    {

        $user = User::findOrFail(Auth::id());
        $notices = Notice::query()
        ->where('is_active', 1)
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get()
        ->filter(function ($notice) use ($user) {
            return $notice->isApplicableToUser($user);
        });

        return view('livewire.dashboard.children',[
            'notices' => $notices,
            'topThieuNhi' => $this->topThieuNhi,
        ]);
    }
}
