<?php

namespace App\Livewire\Management;

use Livewire\Component;
use App\Models\AttendanceSchedule;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Regulation;


class Schedules extends Component
{
    public $isUpdateScheduleMode = false;
    public $schedule_name, $regulation, $schedule_date, $schedule_start_time, $schedule_end_time, $schedule_apply, $schedule_created_by;
    public $schedule_id;
    public $schedule_applies_to = [];
    public $schedule_type;


    protected $listeners = [
        'submitScheduleFormModal'
    ];

    public function submitScheduleFormModal($regulation_id)
    {

        $this->validate([
            'schedule_name' => 'required|string|max:255',
            'schedule_date' => 'required|date',
            'schedule_start_time' => ['required', 'regex:/^\\d{2}:\\d{2}$/'], // HH:mm
            'schedule_end_time' => ['required', 'regex:/^\\d{2}:\\d{2}$/', 'after:schedule_start_time'],
        ], [
            'schedule_name.required' => 'Tên lịch không được để trống.',
            'schedule_date.required' => 'Ngày không được để trống.',
            'schedule_start_time.required' => 'Giờ bắt đầu không được để trống.',
            'schedule_start_time.regex' => 'Giờ bắt đầu phải có định dạng HH:mm.',
            'schedule_end_time.required' => 'Giờ kết thúc không được để trống.',
            'schedule_end_time.regex' => 'Giờ kết thúc phải có định dạng HH:mm.',
            'schedule_end_time.after' => 'Giờ kết thúc phải sau giờ bắt đầu.',
        ]);


        $this->schedule_start_time =  Carbon::createFromFormat('H:i',  $this->schedule_start_time)->format('H:i:s');
        $this->schedule_end_time =  Carbon::createFromFormat('H:i',  $this->schedule_end_time)->format('H:i:s');

        $this->regulation = Regulation::where('id', $regulation_id)->first();

        $this->resetErrorBag('schedule_end_time');
        if ($this->isUpdateScheduleMode) {
            $this->updateSchedule();
        } else {
            $this->createSchedule();
        }
    }

    public function addSchedule()
    {
        $this->resetSchedule();
        $this->isUpdateScheduleMode = false;
        $this->showSchedule();
        $this->schedule_created_by = Auth::user()->SimpleName;
    }

    public function createSchedule()
    {
        DB::beginTransaction();

        try {

            if (Carbon::now()->between(
                Carbon::createFromFormat('H:i:s', $this->schedule_start_time),
                Carbon::createFromFormat('H:i:s', $this->schedule_end_time)
            )) {
                $status = 'open';
            } else {
                $status = 'pending';
            }

            AttendanceSchedule::create([
                'name' => $this->schedule_name,
                'regulation_id' => optional($this->regulation)->id,
                'date' => $this->schedule_date,
                'start_time' => $this->schedule_start_time,
                'end_time' => $this->schedule_end_time,
                'created_by' => Auth::id(),
                'status' => $status,
            ]);


            DB::commit();
            $this->hideSchedule();
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Thêm lịch thành công.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra khi thêm lịch: ' . $e->getMessage(),
            ]);
        }
    }

    public function editSchedule($id)
    {
        $this->resetErrorBag();
        $this->isUpdateScheduleMode = true;

        $schedule = AttendanceSchedule::find($id);

        if ($schedule->status === 'open') {
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Lịch đang trong thời gian điểm danh, không thể cập nhật.',
            ]);
            return;
        }

        if ($schedule->status === 'closed') {
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Lịch đã điểm danh không thể cập nhật.',
            ]);
            return;
        }

        $this->schedule_name = $schedule->name;
        $this->schedule_date = $schedule->date->format('Y-m-d');
        $this->schedule_start_time = Carbon::createFromFormat('H:i:s', $schedule->start_time)->format('H:i');
        $this->schedule_end_time = Carbon::createFromFormat('H:i:s', $schedule->end_time)->format('H:i');
        $this->schedule_created_by = User::find($schedule->created_by)->SimpleName;

        $this->showSchedule();
    }

    public function updateSchedule()
    {

        DB::beginTransaction();
        try {
            $schedule = AttendanceSchedule::find($this->schedule_id);
            if (Carbon::now()->between(
                Carbon::createFromFormat('H:i:s', $this->schedule_start_time),
                Carbon::createFromFormat('H:i:s', $this->schedule_end_time)
            )) {
                $status = 'open';
            } else {
                $status = 'pending';
            }
            if ($schedule) {
                $schedule->update([
                    'date' => $this->schedule_date,
                    'start_time' => $this->schedule_start_time,
                    'end_time' => $this->schedule_end_time,
                    'status' => $status,
                ]);

                DB::commit();
                $this->hideSchedule();
                $this->dispatch('showToastr', [
                    'type' => 'success',
                    'message' => 'Cập nhật lịch thành công.',
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra khi cập nhật lịch.' . $e->getMessage(),
            ]);
        }
    }

    public function deleteSchedule($scheduleId)
    {

        $schedule = AttendanceSchedule::find($scheduleId);

        if ($schedule->status === 'open') {
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Lịch đang trong thời gian điểm danh, không thể xóa.',
            ]);
            return;
        }

        if ($schedule->status === 'closed') {
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Lịch đã điểm danh không thể xóa.',
            ]);
            return;
        }

        DB::beginTransaction();
        try {
            if ($schedule) {
                $schedule->delete();
                DB::commit();
                $this->dispatch('showToastr', [
                    'type' => 'success',
                    'message' => 'Xóa lịch thành công.',
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra khi xóa lịch: ' . $e->getMessage(),
            ]);
        }
    }

    public function showSchedule()
    {
        $this->resetErrorBag();
        $this->dispatch('showScheduleModal');
    }

    public function hideSchedule()
    {
        $this->dispatch('hideScheduleModal');
        $this->isUpdateScheduleMode = false;
        $this->resetSchedule();
    }

    public function resetSchedule()
    {
        $this->isUpdateScheduleMode = false;
        $this->schedule_id = null;
        $this->schedule_date = null;
        $this->schedule_start_time = null;
        $this->schedule_end_time = null;
        $this->schedule_apply = null;
        $this->schedule_created_by = null;
    }
    public function render()
    {
        $listSchedule = AttendanceSchedule::all(); // hiện lên giao diện
        $listRole = Role::whereNotIn('name', ['admin', 'Cha Tuyên Úy'])->get(); // hiện lên modal
        $listRegulation = Regulation::where('type', '=', 'plus')->get(); // Hiện lên modal
        return view('livewire.management.schedules', [
            'listSchedule' => $listSchedule,
            'listRole' => $listRole,
            'listRegulation' => $listRegulation,
        ]);
    }
}
