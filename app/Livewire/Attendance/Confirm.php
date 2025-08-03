<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use Illuminate\Support\Carbon;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use App\Models\AttendanceSchedule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\ActivityLogService;

class Confirm extends Component
{
    public $isShowTableListUserSubmit = false;
    public $listUserOfSubmit = [];
    public $nameUserSubmit;
    public $listData = [];

    protected $listeners = [
        'deleteAttendanceAction',
        'updateTable' => '$refresh',
    ];

    public function closeTableListUserSubmit()
    {
        $this->isShowTableListUserSubmit = false;
        $this->listUserOfSubmit = [];
    }

    public function viewData($user, $nameRecord)
    {
        $this->listUserOfSubmit = Attendance::with('user')
            ->where('submit_by', $user)
            ->where('name', $nameRecord)
            ->where('isConfirm', 0)
            ->get();

        $this->isShowTableListUserSubmit = true;
        $submitter = User::find($user);
        $this->nameUserSubmit = $submitter?->SimpleName ?? 'Không rõ';
    }

    public function getData()
    {
        return Attendance::with('submittedBy')
            ->where('isConfirm', 0)
            ->whereNotNull('submit_by')
            ->get()
            ->groupBy(function ($item) {
                return $item->submit_by . '|' . $item->name;
            })
            ->map(function ($group) {
                return $group->all();
            })
            ->toArray();
    }

    public function deleteAttendance($id)
    {
        $attendance = Attendance::find($id);

        if (!$attendance) {
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Không tìm thấy dữ liệu!',
            ]);
            return;
        }

        $this->dispatch('deleteAttendance', [
            'id' => $attendance->id,
            'name' => $attendance->user->SimpleName,
        ]);
    }

    public function deleteAttendanceAction($id)
    {
        DB::beginTransaction();
        try {
            $attendance = Attendance::findOrFail($id);

            $attendance->update(['status' => 0]);

            DB::commit();
            $this->dispatch('updateTable');
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Đã xóa thành công',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.',
            ]);
        }
    }

    public function undoAttendance($id)
    {
        DB::beginTransaction();
        try {
            $attendance = Attendance::findOrFail($id);

            $attendance->update(['status' => 1]);

            DB::commit();
            $this->dispatch('updateTable');
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Đã hoàn tác thành công',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.',
            ]);
        }
    }

    public function confirmAttendance($user, $nameRecord)
    {
        DB::beginTransaction();
        try {

            $listUserOfConfirm = Attendance::with('user')
                ->where('submit_by', $user)
                ->where('name', $nameRecord)
                ->where('isConfirm', 0)
                ->where('status', 1)
                ->get();

            foreach ($listUserOfConfirm as $attendance) {
                $attendance->update(['isConfirm' => 1]);
            }

            $attendance_name = $listUserOfConfirm->first()?->name;

            $listUserDeleted = Attendance::where('submit_by', $user)
                ->where('name', $nameRecord)
                ->where('isConfirm', 0)
                ->where('status', 0)
                ->get();

            foreach ($listUserDeleted as $attendance) {
                $attendance->delete();
            }

            AttendanceSchedule::where('name', $attendance_name)
                ->whereDate('created_at', Carbon::today())
                ->update(['status' => 'closed']);

            $userNames = $listUserOfConfirm->pluck('user')->filter()->map(function ($user) {
                return $user->simple_name;
            })->implode(', ');


            DB::commit();

            ActivityLogService::log(
                'Xét duyệt',
                $attendance_name,
                null,
                $userNames,
            );

            $this->listData = $this->getData();
            $this->isShowTableListUserSubmit = false;
            $this->dispatch('updateTable');
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Đã xác nhận thành công',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau. ' . $e->getMessage(),
            ]);
        }
    }

    public function mount()
    {
        $this->listData = $this->getData();
    }

    public function render()
    {
        return view('livewire.attendance.confirm', [
            'listData' => $this->listData,
            'listUserOfSubmit' => $this->listUserOfSubmit,
        ]);
    }
}
