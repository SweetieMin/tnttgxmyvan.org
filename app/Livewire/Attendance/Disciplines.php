<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\Regulation;
use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\Sector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogService;
use Livewire\WithPagination;

class Disciplines extends Component
{
    use WithPagination;
    public $listSectors = [];
    public $listRoles = [];
    public $disciplinesModal = [];
    public $disciplinesModalChildren = [];
    public $showDisciplinesChildren, $showDisciplinesScouter;
    public $selected_permissions = [];
    public $search, $role, $sector;
    public $user_id, $user_holy, $user_name, $user_sector;
    public $user_note, $user_discipline_name;

    public $listeners = [
        'chooseDataSort',
        'submitRecordFormModal'
    ];

    public function submitRecordFormModal($recordDisciplineScouter, $recordDisciplineChildren)
    {
        $recordDiscipline = [];
        if ($this->showDisciplinesScouter) {
            $recordDiscipline = $recordDisciplineScouter;
        } else {
            $recordDiscipline = $recordDisciplineChildren;
        }

        $this->validate(
            [
                'user_discipline_name' => 'required',
            ],
            [
                'user_discipline_name.required' => 'Vui lòng ghi lỗi vi phạm',
            ]
        );


        $user = User::find($this->user_id);

        DB::beginTransaction();
        try {

            $attendance = new Attendance();

            $attendance->name = $this->user_discipline_name;
            $attendance->regulation_id = $recordDiscipline;
            $attendance->user_id = $this->user_id;
            $attendance->sector_name = $this->user_sector;
            $attendance->note = $this->user_note;
            $attendance->submit_by = Auth::user()->id;
            $attendance->status = 1;
            $attendance->save();

            DB::commit();

            ActivityLogService::log(
                'Ghi nhận lỗi vi phạm',
                $this->user_discipline_name,
                $this->user_id,
                $user->SimpleName,
            );

            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Đã ghi nhận lỗi vi phạm cho ' . $user->SimpleName,
            ]);
            $this->dispatch('hideRecordModal');
        } catch (\Exception $e) {
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.' . $e->getMessage(),
            ]);
            DB::rollBack();
        }
    }

    public function resetModal()
    {
        $this->user_discipline_name = null;
        $this->user_note = null;
    }

    public function recordUser($userId)
    {
        $this->resetModal();
        $user = User::find($userId);
        $user_role = $user->roles->first()?->name;
        $this->user_id = $user->id;
        $this->user_holy = $user->holyName;
        $this->user_name = $user->SimpleName;
        $this->user_sector = $user->sectors->isNotEmpty()
            ? $user->roles->first()?->name . ' ' . $user->sectors->pluck('name')->implode(', ')
            : $user->roles->first()?->name;

        // Reset trạng thái 2 biến trước
        $this->showDisciplinesChildren = false;
        $this->showDisciplinesScouter = false;

        // Tùy theo vai trò, chọn loại kỷ luật phù hợp
        if ($user_role === 'Thiếu Nhi') {
            $this->showDisciplinesChildren = true;
            $this->disciplinesModalChildren = Regulation::where('type', 'minus')
                ->whereJsonContains('applicable_object', $user_role)
                ->get();
        } else {
            $this->showDisciplinesScouter = true;
            $this->disciplinesModal = Regulation::where('type', 'minus')
                ->whereJsonContains('applicable_object', $user_role)
                ->get();
        }

        $this->dispatch('showRecordModal');
    }


    public function mount()
    {
        $user = Auth::user();
        $managerData = $this->getManagerData($user);

        $this->listSectors = $managerData['managerSector'];
        $this->listRoles = $managerData['managerRole'];

        $this->disciplinesModal = Regulation::where('type', 'minus')
            ->get();

        $this->disciplinesModalChildren = Regulation::where('type', 'minus')
            ->whereJsonContains('applicable_object', 'Thiếu Nhi')
            ->get();

        $this->getData();
    }

    public function chooseDataSort($selectedSectors, $selectedRoles)
    {
        $this->resetPage();
        $this->sector = $selectedSectors;
        $this->role = $selectedRoles;
        $this->getData(); // Load lại khi filter thay đổi
    }

    public function getData()
    {
        $user = Auth::user();
        $managerData = $this->getManagerData($user);

        $managerRole = $managerData['managerRole'];
        $managerSector = $managerData['managerSector'];
        $roleName = $managerData['roleName'];

        $listChildren = User::query()
            ->select('users.*', 'roles.ordering as role_ordering', 'sectors.ordering as sector_ordering')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftJoin('sector_user', 'users.id', '=', 'sector_user.user_id')
            ->leftJoin('sectors', 'sector_user.sector_id', '=', 'sectors.id')
            ->whereIn('roles.id', $managerRole->pluck('id'))
            ->when(
                in_array($roleName, ['Xứ Đoàn Trưởng', 'Xứ Đoàn Phó', 'Admin']),
                fn($query) => $query->where(
                    fn($q) => $q->whereIn('sectors.id', $managerSector->pluck('id'))
                        ->orWhereNull('sectors.id')
                ),
                fn($query) => $query->whereIn('sectors.id', $managerSector->pluck('id'))
            )
            ->when(
                !empty($this->sector),
                fn($query) => $query->whereIn('sector_user.sector_id', $this->sector)
            )
            ->when(
                !empty($this->role),
                fn($query) => $query->whereIn('roles.id', $this->role)
            )
            ->when(
                !empty($this->search),
                fn($query) => $query->where(function ($q) {
                    $q->whereRaw("CONCAT(users.name, ' ', users.lastName) LIKE ?", ['%' . $this->search . '%'])
                        ->orWhereRaw("CONCAT(users.lastName, ' ', users.name) LIKE ?", ['%' . $this->search . '%']);
                })
            )
            ->orderBy('role_ordering')
            ->orderBy('sector_ordering')
            ->orderBy('users.name')
            ->distinct()
            ->paginate(15);

        return $listChildren;
    }

    public function getManagerData($user)
    {
        $roleName = $user->roles->first()?->name ?? '';
        $managerRole = Role::whereIn('name', ['Thiếu Nhi', 'Huynh Trưởng', 'Dự Trưởng', 'Đội Trưởng'])
            ->orderBy('ordering')
            ->get();
        $managerSector = collect();

        $sectorMap = [
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
            $managerSector = Sector::orderBy('ordering')->get();
            $managerRole = Role::whereNotIn('name', ['Admin', 'Cha Tuyên Úy'])
                ->orderBy('ordering')
                ->get();
        } elseif (isset($sectorMap[$roleName])) {
            $managerSector = Sector::where('name', 'LIKE', $sectorMap[$roleName])
                ->orderBy('ordering')
                ->get();
        }

        return [
            'managerRole' => $managerRole,
            'managerSector' => $managerSector,
            'roleName' => $roleName,
        ];
    }

    public function updatedSearch()
    {
        $this->search = trim($this->search);
        $this->getData();
        $this->resetPage();
    }


    public function render()
    {

        return view('livewire.attendance.disciplines', [
            'listChildren' => $this->getData(),
        ]);
    }
}
