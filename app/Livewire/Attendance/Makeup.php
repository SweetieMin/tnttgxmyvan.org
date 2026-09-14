<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\{Attendance, AttendanceSchedule, Sector, User};
use App\Services\ActivityLogService;

class Makeup extends Component
{
    use WithPagination;

    /** Các vai trò được điểm danh bù */
    private const ATTENDEE_ROLES = [
        'Thiếu Nhi',
        'Huynh Trưởng',
        'Dự Trưởng',
        'Đội Trưởng',
    ];

    /** Các vai trò được thao tác trên mọi ngành (giống Personnel\Children) */
    private const FULL_SECTOR_ACCESS_ROLES = [
        'admin',
        'Admin',
        'Cha Tuyên Úy',
        'Xứ Đoàn Trưởng',
        'Xứ Đoàn Phó',
    ];

    public $schedule_id;
    public $search;
    public $note;
    public $perPage = 10;

    protected $listeners = ['updateTable' => '$refresh'];

    public function updated($name)
    {
        if (in_array($name, ['search', 'schedule_id'], true)) {
            $this->resetPage();
        }
    }

    /**
     * Thêm một bản ghi điểm danh bù cho thiếu nhi vào buổi đã chọn.
     */
    public function addMakeup($childId)
    {
        $schedule = AttendanceSchedule::find($this->schedule_id);

        if (!$schedule) {
            $this->dispatchError('Vui lòng chọn lịch điểm danh trước.');
            return;
        }

        if (!$schedule->regulation_id) {
            $this->dispatchError('Lịch này chưa gắn hạng mục điểm danh.');
            return;
        }

        $child = $this->getChildrenQuery()->where('users.id', $childId)->first();

        if (!$child) {
            $this->dispatchError('Bạn không có quyền điểm danh người này.');
            return;
        }

        // Bản ghi mang ngày giờ của buổi được bù, vì hệ thống dựa vào created_at để biết là buổi nào
        $attendanceAt = $this->getScheduleDateTime($schedule);

        DB::beginTransaction();
        try {
            $exists = Attendance::where('user_id', $child->id)
                ->where('regulation_id', $schedule->regulation_id)
                ->where('status', 1)
                ->whereDate('created_at', $attendanceAt->toDateString())
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                DB::rollBack();
                $this->dispatchError('Em này đã có điểm danh ở buổi này rồi.');
                return;
            }

            $attendance = new Attendance([
                'name' => $schedule->name,
                'regulation_id' => $schedule->regulation_id,
                'user_id' => $child->id,
                'sector_name' => $this->buildSectorName($child),
                'note' => $this->note,
                'submit_by' => Auth::id(),
                'status' => 1,
                'isConfirm' => 0,
            ]);
            $attendance->created_at = $attendanceAt;
            $attendance->updated_at = now();
            $attendance->save();

            DB::commit();

            ActivityLogService::log(
                'Điểm danh bù',
                $schedule->name,
                $child->id,
                $child->SimpleName,
            );

            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Đã thêm điểm danh bù cho ' . $child->SimpleName . '.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchError('Có lỗi xảy ra khi thêm điểm danh bù.');
        }
    }

    /**
     * Ngày giờ của buổi được bù (ngày của lịch + giờ bắt đầu).
     */
    private function getScheduleDateTime(AttendanceSchedule $schedule): Carbon
    {
        $date = $schedule->date instanceof Carbon
            ? $schedule->date->format('Y-m-d')
            : Carbon::parse($schedule->date)->format('Y-m-d');

        return Carbon::parse($date . ' ' . $schedule->start_time);
    }

    private function buildSectorName($child): string
    {
        $roles = implode(', ', $child->roles->pluck('name')->toArray());
        $sector = mb_strtoupper(optional($child->sectors->first())->name ?? '', 'UTF-8');

        $sectorName = trim("$roles $sector");

        return $sectorName !== '' ? $sectorName : (optional($child->roles->first())->name ?? '');
    }

    /**
     * Các ngành mà người đang đăng nhập được quyền quản lý.
     */
    private function getManagerSectors($user)
    {
        $roleName = optional($user->roles->first())->name;

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

        if (in_array($roleName, self::FULL_SECTOR_ACCESS_ROLES, true)) {
            return Sector::all();
        }

        if (isset($sectorPatterns[$roleName])) {
            return Sector::where('name', 'LIKE', $sectorPatterns[$roleName])->get();
        }

        return $user->sectors;
    }

    private function isFullAccess($user): bool
    {
        return in_array(optional($user->roles->first())->name, self::FULL_SECTOR_ACCESS_ROLES, true);
    }

    private function getChildrenQuery()
    {
        $user = Auth::user();
        $managerSectorIds = $this->getManagerSectors($user)->pluck('id')->all();
        $keyword = trim((string) $this->search);

        return User::query()
            ->select('users.*', 'sectors.ordering as sector_ordering', 'courses.ordering as course_ordering')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftJoin('sector_user', 'users.id', '=', 'sector_user.user_id')
            ->leftJoin('sectors', 'sector_user.sector_id', '=', 'sectors.id')
            ->leftJoin('course_user', 'users.id', '=', 'course_user.user_id')
            ->leftJoin('courses', 'course_user.course_id', '=', 'courses.id')
            ->whereIn('roles.name', self::ATTENDEE_ROLES)
            ->where('users.is_attendance', 1) // chỉ người thuộc diện điểm danh
            ->when(
                $this->isFullAccess($user),
                fn($query) => $query->where(
                    fn($q) => $q->whereIn('sectors.id', $managerSectorIds)->orWhereNull('sectors.id')
                ),
                fn($query) => $query->whereIn('sectors.id', $managerSectorIds)
            )
            ->when($keyword !== '', fn($query) => $query->where(function ($q) use ($keyword) {
                $like = '%' . $keyword . '%';
                $q->whereRaw("CONCAT(users.lastName, ' ', users.name) LIKE ?", [$like])
                    ->orWhereRaw("CONCAT(users.name, ' ', users.lastName) LIKE ?", [$like])
                    ->orWhere('users.account_code', 'LIKE', $like)
                    ->orWhere('users.holyName', 'LIKE', $like);
            }))
            ->orderBy('courses.ordering', 'asc')
            ->orderBy('sectors.ordering', 'asc')
            ->orderBy('users.name', 'asc')
            ->distinct();
    }

    private function dispatchError($message)
    {
        $this->dispatch('showToastr', ['type' => 'error', 'message' => $message]);
    }

    public function render()
    {
        $listSchedule = AttendanceSchedule::with('regulation')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $schedule = $this->schedule_id
            ? $listSchedule->firstWhere('id', (int) $this->schedule_id)
            : null;

        $presentIds = [];
        $listAdded = collect();

        if ($schedule && $schedule->regulation_id) {
            $attendanceDate = $this->getScheduleDateTime($schedule)->toDateString();

            $presentIds = Attendance::where('regulation_id', $schedule->regulation_id)
                ->whereDate('created_at', $attendanceDate)
                ->where('status', 1)
                ->pluck('user_id')
                ->all();

            $listAdded = Attendance::with(['user', 'submittedBy'])
                ->where('regulation_id', $schedule->regulation_id)
                ->whereDate('created_at', $attendanceDate)
                ->where('status', 1)
                ->where('submit_by', Auth::id())
                ->orderBy('id', 'desc')
                ->get();
        }

        // Chỉ liệt kê những em chưa có điểm danh ở buổi được chọn
        $childrenQuery = $this->getChildrenQuery()
            ->with(['roles', 'sectors', 'courses'])
            ->when(
                $schedule && $schedule->regulation_id,
                fn($query) => $query->whereNotIn('users.id', $presentIds),
                fn($query) => $query->whereRaw('1 = 0') // chưa chọn buổi thì không hiện ai
            );

        return view('livewire.attendance.makeup', [
            'listSchedule' => $listSchedule,
            'schedule' => $schedule,
            'listChildren' => $childrenQuery->paginate($this->perPage),
            'listAdded' => $listAdded,
        ]);
    }
}
