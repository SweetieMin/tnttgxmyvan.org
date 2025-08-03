<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\{Attendance, Role, Sector, User, AttendanceSchedule, Regulation};

class Reward extends Component
{
    public $isAttendanceOpen = false;
    public $msgAttendance, $schedule_name;
    public $user_id, $user_picture, $user_name, $user_holy, $user_course, $user_sector, $user_note, $attendance_name;
    public $checkSchedule;

    protected $listeners = ['updateTable' => '$refresh', 'attendanceUser'];

    public function attendanceUser($token)
    {
        $userAttendance = User::where('token', $token)->firstOrFail();
        $admin = Auth::user();
        extract($this->getManagerData($admin));

        $listChildrenIds = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftJoin('sector_user', 'users.id', '=', 'sector_user.user_id')
            ->leftJoin('sectors', 'sector_user.sector_id', '=', 'sectors.id')
            ->whereIn('roles.id', $managerRole->pluck('id'))
            ->when(
                in_array($roleName, ['Xứ Đoàn Trưởng', 'Xứ Đoàn Phó', 'Admin']),
                fn($q) => $q->where(fn($q) => $q->whereIn('sectors.id', $managerSector->pluck('id'))->orWhereNull('sectors.id')),
                fn($q) => $q->whereIn('sectors.id', $managerSector->pluck('id'))
            )
            ->distinct()->pluck('users.id');

        if (!$listChildrenIds->contains($userAttendance->id)) {
            $this->dispatchError('Bạn không có quyền điểm danh người này');
            return;
        }

        if (Attendance::where('user_id', $userAttendance->id)->where('status', '1')->where('isConfirm', '0')->whereDate('created_at', today())->exists()) {
            $this->dispatchError('Người này đã được điểm danh hôm nay');
            $this->dispatch('continueScan');
            return;
        }

        $this->fillUserProfile($userAttendance);
        $this->dispatch('showProfileModal', ['pictureUrl' => $this->user_picture]);
    }

    public function attendanceUserConfirm()
    {
        $data = [
            'name' => $this->attendance_name,
            'regulation_id' => $this->checkSchedule->regulation_id,
            'user_id' => $this->user_id,
            'sector_name' => $this->user_sector,
            'note' => $this->user_note,
            'submit_by' => Auth::id(),
            'status' => '1',
            'isConfirm' => '0',
        ];

        DB::beginTransaction();
        try {
            Attendance::create($data);
            DB::commit();
            $this->dispatchSuccess('Điểm danh thành công.');
            $this->hideProfile();
            $this->dispatch('continueScan');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchError('Có lỗi xảy ra khi cập nhật điểm danh.');
        }
    }

    public function hideProfile()
    {
        $this->dispatch('hideProfileModal');
    }

    public function getTodayAttendances()
    {
        return Attendance::where('submit_by', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->with(['user.roles', 'user.sectors', 'submittedBy'])
            ->get();
    }

    private function fillUserProfile($userAttendance)
    {
        $this->user_id = $userAttendance->id;
        $this->user_name = trim("{$userAttendance->lastName} {$userAttendance->name}");
        $this->user_holy = $userAttendance->holyName;
        $this->user_picture = $userAttendance->getRawOriginal('picture');

        $roles = implode(', ', $userAttendance->roles->pluck('name')->toArray());
        $sector = mb_strtoupper(optional($userAttendance->sectors->first())->name, 'UTF-8');

        $this->user_sector = trim("$roles $sector");

        if (empty($this->user_sector)) {
            $this->user_sector = optional($userAttendance->roles->first())->name;
        }
    }

    private function getManagerData($user)
    {
        $roleName = optional($user->roles->first())->name;
        $managerRole = Role::where('name', 'Thiếu Nhi')->get();
        $managerSector = collect();

        $sectorPatterns = [
            'Trưởng Ngành Thiếu' => 'Thiếu%',
            'Phó Ngành Thiếu' => 'Thiếu%',
            'Trưởng Ngành Tiền Ấu' => 'Tiền%',
            'Phó Ngành Tiền Ấu' => 'Tiền%',
            'Trưởng Ngành Ấu' => 'Ấu%',
            'Phó Ngành Ấu' => 'Ấu%',
            'Trưởng Ngành Nghĩa' => 'Nghĩa%',
            'Phó Ngành Nghĩa' => 'Nghĩa%',
        ];

        if (in_array($roleName, ['Xứ Đoàn Trưởng', 'Xứ Đoàn Phó', 'Admin'])) {
            $managerSector = Sector::all();
            $managerRole = Role::whereNotIn('name', ['Admin', 'Cha Tuyên Úy'])->get();
        } elseif (isset($sectorPatterns[$roleName])) {
            $managerSector = Sector::where('name', 'LIKE', $sectorPatterns[$roleName])->get();
        } elseif (in_array($roleName, ['Huynh Trưởng', 'Dự Trưởng', 'Đội Trưởng'])) {
            $managerSector = $user->sectors;
        }

        return compact('managerRole', 'managerSector', 'roleName');
    }

    public function checkSchedule()
    {
        $this->checkSchedule = AttendanceSchedule::whereDate('date', today())
            ->where('status', '!=', 'closed')
            ->first();

        if (!$this->checkSchedule) {
            $this->isAttendanceOpen = false;
            $this->msgAttendance = 'Không có lịch điểm danh hôm nay';
            return;
        }

        $now = now();
        $start = Carbon::parse($this->checkSchedule->start_time)->setDate($now->year, $now->month, $now->day);
        $end = Carbon::parse($this->checkSchedule->end_time)->setDate($now->year, $now->month, $now->day);

        if ($now->lt($start)) {
            $diffInSeconds = $now->diffInSeconds($start);

            $hours = floor($diffInSeconds / 3600);
            $minutes = ceil(($diffInSeconds % 3600) / 60);

            $formattedTime = '';
            if ($hours > 0) {
                $formattedTime .= "{$hours} giờ ";
            }
            if ($minutes > 0) {
                $formattedTime .= "{$minutes} phút";
            }

            $this->isAttendanceOpen = false;
            $this->msgAttendance = "Chưa đến giờ điểm danh. Vui lòng quay lại sau {$formattedTime}.";
            return;
        }

        if ($now->gt($end)) {
            $this->isAttendanceOpen = false;
            $this->msgAttendance = 'Đã hết thời gian điểm danh.';
            return;
        }

        $remaining = $now->diffInSeconds($end);
        $this->isAttendanceOpen = true;
        $this->msgAttendance = 'Còn lại ' . floor($remaining / 60) . ' phút ' . ($remaining % 60) . ' giây để điểm danh.';
        $this->schedule_name = optional(Regulation::find($this->checkSchedule->regulation_id))->description;
        $this->attendance_name = $this->checkSchedule->name;
    }

    private function dispatchSuccess($message)
    {
        $this->dispatch('showToastr', ['type' => 'success', 'message' => $message]);
    }

    private function dispatchError($message)
    {
        $this->dispatch('showToastr', ['type' => 'error', 'message' => $message]);
    }

    public function render()
    {
        $this->checkSchedule();
        return view('livewire.attendance.reward', [
            'listUser' => $this->getTodayAttendances(),
        ]);
    }
}
