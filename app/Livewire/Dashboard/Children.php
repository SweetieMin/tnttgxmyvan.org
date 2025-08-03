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

    public function viewNotice($noticeId)
    {
        $notice = Notice::findOrFail($noticeId);
        $this->notice_type = $notice->type;
        $this->notice_title = $notice->title;
        $this->notice_content = $notice->content;
        $this->notice_created_at = $notice->created_at->format('d-m-Y');

        $this->dispatch('viewNoticeModal');
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
        ]);
    }
}
