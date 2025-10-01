<?php

namespace App\Livewire\Dashboard;

use App\Models\Attendance;
use App\Models\Notice;
use App\Models\Regulation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Scouters extends Component
{
    public $notice_type,
        $notice_title,
        $notice_content,
        $notice_created_at;
    public $topUsers = [];
    public $allUsers = [];

    public $showAllUserScoreModal = false;

    public function showAll()
    {
        $users = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['Thiếu Nhi', 'Admin', 'Cha Tuyên Uy']);
        })->with('roles')->get();

        $rankedAllUsers = $users->map(function ($u) {
            $u->setTotalScore($this->calculateScore($u));
            return $u;
        })->sortByDesc('total_score')
            ->values(); // lấy tất cả

        $this->allUsers = $rankedAllUsers;
        $this->topUsers = $rankedAllUsers->take(10);

        $this->showAllUserScoreModal = true;

        $this->dispatch('openAllRankingModal');
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

    public function mount()
    {
        $user = User::findOrFail(Auth::id());
        $popupNotice = Notice::query()
            ->where('is_active', 1)
            ->where('is_popup', 1)
            ->orderBy('created_at', 'desc')
            ->take(1)
            ->get()
            ->first(function ($notice) use ($user) {
                return $notice->isApplicableToUser($user);
            });

        if ($popupNotice) {
            $this->viewNotice($popupNotice->id);
        }

        $users = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['Thiếu Nhi', 'Admin', 'Cha Tuyên Uy']);
        })->with('roles')->get();

        $rankedUsers = $users->map(function ($u) {
            $u->setTotalScore($this->calculateScore($u));
            return $u;
        })->sortByDesc('total_score')
            ->values();

        $this->topUsers = $rankedUsers->take(10);
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
        return view('livewire.dashboard.scouters', [
            'notices' => $notices,
            'topUsers' => $this->topUsers,
            'allUsers' => $this->allUsers,
        ]);
    }
}
