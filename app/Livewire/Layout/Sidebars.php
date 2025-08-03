<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Sidebars extends Component
{
    //Manage permissions
    public $isShowManageMenu = false;

    public $hasRolePermission;
    public $hasManagerPermission;
    public $hasGeneralSettingsPermission;
    public $hasSchedulePermission;
    public $hasBiblePermission;
    public $hasSectorPermission;
    public $hasCoursePermission;
    public $hasRegulationPermission;
    public $hasNoticePermission;
    public $hasActivityLogsPermission;
    public $hasTransactionPermission;
    //Manage Personnel
    public $isShowPersonnelMenu = false;

    public $hasScouterPermission;
    public $hasChildrenPermission;

    //Attendance
    public $isShowAttendanceMenu = false;

    public $hasRewardPermission;
    public $hasConfirmPermission;
    public $hasDisciplinePermission;

    //Support
    public $hasAssignedPermission;
    public $hasResolvePermission;


    public function mount()
    {
        //Settings
        $this->hasGeneralSettingsPermission = false;
        //Manage permissions
        $this->hasRolePermission = false;
        $this->hasManagerPermission = false;
        $this->hasSchedulePermission = false;
        $this->hasBiblePermission = false;
        $this->hasSectorPermission = false;
        $this->hasCoursePermission = false;
        $this->hasRegulationPermission = false;
        $this->hasNoticePermission = false;
        $this->hasActivityLogsPermission = false;
        $this->hasTransactionPermission = false;
        //Manage Personnel
        $this->hasScouterPermission = false;
        $this->hasChildrenPermission = false;
        //Attendance
        $this->hasRewardPermission = false;
        $this->hasConfirmPermission = false;
        $this->hasDisciplinePermission = false;

        //Support
        $this->hasAssignedPermission = false;
        $this->hasResolvePermission = false;


        $this->isShowManageMenu = false;
        $this->isShowPersonnelMenu = false;
        $this->isShowAttendanceMenu = false;
        $this->getMenu();
    }

    public function getMenu()
    {
        $user = User::findOrFail(Auth::user()->id);
       
        //Manage Personnel
        $this->hasRegulationPermission = $user->hasPermission('admin.management.regulation');
        $this->hasCoursePermission = $user->hasPermission('admin.management.course');
        $this->hasSectorPermission = $user->hasPermission('admin.management.sector');
        $this->hasBiblePermission = $user->hasPermission('admin.management.bible');
        $this->hasSchedulePermission = $user->hasPermission('admin.management.schedule');
        $this->hasRolePermission = $user->hasPermission('admin.management.role');
        $this->hasManagerPermission = $user->hasPermission('admin.management.permission');
        $this->hasNoticePermission = $user->hasPermission('admin.management.notice');
        $this->hasActivityLogsPermission = $user->hasPermission('admin.management.activity-logs');
        $this->hasTransactionPermission = $user->hasPermission('admin.management.transaction');
        
        $this->isShowManageMenu =
            $this->hasRegulationPermission ||
            $this->hasCoursePermission ||
            $this->hasSectorPermission ||
            $this->hasBiblePermission ||
            $this->hasSchedulePermission ||
            $this->hasRolePermission ||
            $this->hasManagerPermission||
            $this->hasNoticePermission||
            $this->hasActivityLogsPermission||
            $this->hasTransactionPermission;

        //Manage Personnel
        $this->hasScouterPermission = $user->hasPermission('admin.personnel.scouter');
        $this->hasChildrenPermission = $user->hasPermission('admin.personnel.children');

        $this->isShowPersonnelMenu =
            $this->hasScouterPermission ||
            $this->hasChildrenPermission;

        //Attendance
        $this->hasRewardPermission = $user->hasPermission('admin.attendance.reward');
        $this->hasConfirmPermission = $user->hasPermission('admin.attendance.confirm');
        $this->hasDisciplinePermission = $user->hasPermission('admin.attendance.discipline');

        $this->isShowAttendanceMenu =
            $this->hasRewardPermission ||
            $this->hasConfirmPermission ||
            $this->hasDisciplinePermission;

        // Settings
        $this->hasGeneralSettingsPermission = $user->hasPermission('admin.settings');

        //Support
        $this->hasAssignedPermission = $user->hasPermission('admin.support.assigned');
        $this->hasResolvePermission = $user->hasPermission('admin.support.resolve');

    }

    public function render()
    {
        return view('livewire.layout.sidebars');
    }
}
