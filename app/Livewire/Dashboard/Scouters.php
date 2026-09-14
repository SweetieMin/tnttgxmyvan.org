<?php

namespace App\Livewire\Dashboard;

use App\Models\Notice;
use App\Models\User;
use App\Services\ScoreService;
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
    public $scoreUpdatedAt;

    public function showAll()
    {
        $ranking = app(ScoreService::class)->scouterRanking();

        $this->allUsers = $ranking['users'];
        $this->topUsers = $ranking['users']->take(10);
        $this->scoreUpdatedAt = $ranking['updated_at'];

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

        $ranking = app(ScoreService::class)->scouterRanking(10);

        $this->topUsers = $ranking['users'];
        $this->scoreUpdatedAt = $ranking['updated_at'];
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
