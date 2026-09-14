<?php

namespace App\Livewire\Management;

use Livewire\Component;
use App\Models\ActivityLog;
use Livewire\WithPagination;

class ActivityLogs extends Component
{
    use WithPagination;

    public $activityLogs_action, $activityLogs_user;
    public $search_action, $search_user;
    public $activeLog_description, $activeLog_action;

    public function viewDetailLog($id)
    {
        $activeLogs = ActivityLog::findOrFail($id);
        $this->activeLog_action = $activeLogs->action;
        $this->activeLog_description = $activeLogs->description;
        $this->dispatch('showDetailLog');
    }

    protected $listeners = [
        'chooseDataSort',
    ];

    public function chooseDataSort($action, $user)
    {
        $this->search_action = $action;
        $this->search_user = $user;
    }

    public function render()
    {
        $this->activityLogs_action = ActivityLog::select('action')->distinct()->get();
        $this->activityLogs_user = ActivityLog::select('user_id')->distinct()->get();
        $activeLogs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->when(
                !empty($this->search_action),
                fn($query) => $query->whereIn('action', $this->search_action)
            )
            ->when(
                !empty( $this->search_user),
                fn($query) => $query->whereIn('user_id',  $this->search_user)
            )
            ->paginate(10);

        return view('livewire.management.activity-logs',[
            'activeLogs' => $activeLogs,
            'activityLogs_action' => $this->activityLogs_action,
            'activityLogs_user' => $this->activityLogs_user,
        ]);
    }
}
