<?php

namespace App\Livewire\Management;

use App\Models\Notice;
use Livewire\Component;
use App\Models\Role;
use App\Models\Sector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Notices extends Component
{
    public $listNotices = [];
    public $isUpdateNoticeModal = false;
    public $roles = [];
    public $sectors = [];
    public $applicable_roles = [];
    public $applicable_sectors = [];
    public $notice_id, $notice_type, $notice_title, $notice_content, $notice_start_at, $notice_end_at, $notice_is_active = false, $notice_is_popup = false;

    protected $listeners = [
        'submitNoticeFormModal',
        'deleteNoticeAction',
    ];

    public function submitNoticeFormModal($selectedRoles, $selectedSectors)
    {
        $this->applicable_roles = Role::whereIn('id', $selectedRoles)->pluck('name')->toArray();
        $this->applicable_sectors = Sector::whereIn('id', $selectedSectors)->pluck('name')->toArray();

        $this->validate([
            'notice_type' => 'required',
            'notice_title' => 'required|string|max:255',
            'notice_content' => 'required|string',
            'notice_start_at' => 'required|date',
            'notice_end_at' => 'required|date|after:notice_start_at',
        ], [
            'notice_type.required' => 'Loại thông báo không được để trống.',
            'notice_title.required' => 'Tiêu đề không được để trống.',
            'notice_content.required' => 'Nội dung không được để trống.',
            'notice_start_at.required' => 'Thời gian bắt đầu không được để trống.',
            'notice_end_at.required' => 'Thời gian kết thúc không được để trống.',
            'notice_end_at.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        ]);

        if ($this->isUpdateNoticeModal) {
            $this->updateNotice();
        } else {
            $this->createNotice();
        }
    }

    public function toggleNoticeActive($id)
    {
        $notice = Notice::find($id);
        if ($notice) {
            $notice->is_active = !$notice->is_active;
            $notice->save();

            $this->loadNotices();

            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Cập nhật trạng thái thông báo thành công.',
            ]);
        }
    }

    public function toggleNoticePopup($id)
    {
        DB::beginTransaction();
        try {
            $notice = Notice::find($id);
            $statusNotice = $notice->is_popup;

            Notice::where('is_popup', true)->update(['is_popup' => false]);
           
            if ($notice) {
                $notice->is_popup = !$statusNotice;
                $notice->save();
            }

            DB::commit();
            $this->loadNotices();

            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Cập nhật trạng thái thông báo thành công.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra khi cập nhật thông báo.',
            ]);
        }
    }

    public function getManagerData($user)
    {
        $roleName = $user->roles->first()?->name;
        $managerRole = Role::whereIn('name', ['Huynh Trưởng', 'Dự Trưởng', 'Đội Trưởng', 'Thiếu Nhi'])->orderBy('ordering')->get();
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

        if (in_array($roleName, ['Xứ Đoàn Trưởng', 'Xứ Đoàn Phó'])) {
            $managerSector = Sector::all();
        } elseif (isset($sectorMap[$roleName])) {
            $managerSector = Sector::where('name', 'LIKE', $sectorMap[$roleName])->get();
        } elseif (in_array($roleName, ['Huynh Trưởng', 'Dự Trưởng', 'Đội Trưởng'])) {
            $managerSector = $user->sectors;
        }

        return compact('managerRole', 'managerSector', 'roleName');
    }

    public function addNotice()
    {
        $this->resetForm();
        $this->isUpdateNoticeModal = false;
        $this->showNoticeModal();
    }

    public function createNotice()
    {
        $this->notice_start_at = $this->notice_start_at . ' 00:00:00';
        $this->notice_end_at = $this->notice_end_at . ' 23:59:59';

        DB::beginTransaction();
        try {
            $notice = new Notice();
            $notice->type = $this->notice_type;
            $notice->title = ucfirst($this->notice_title);
            $notice->content = $this->notice_content;
            $notice->applicable_roles = $this->applicable_roles;
            $notice->applicable_sectors = $this->applicable_sectors;
            $notice->start_at = $this->notice_start_at;
            $notice->end_at = $this->notice_end_at;
            $notice->is_active = $this->notice_is_active;
            $notice->is_popup = $this->notice_is_popup;
            $notice->created_by = Auth::id();
            $notice->save();

            DB::commit();
            $this->loadNotices();
            $this->hideNoticeModal();
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Thêm thông báo thành công.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra khi thêm thông báo.',
            ]);
        }
    }

    public function editNotice($id)
    {
        $this->resetErrorBag();
        $this->isUpdateNoticeModal = true;
        $this->notice_id = $id;

        $notice = Notice::find($id);

        if ($notice) {
            $this->notice_type = $notice->type;
            $this->notice_title = ucfirst($notice->title);
            $this->notice_content = $notice->content;
            $this->applicable_roles = Role::whereIn('name', $notice->applicable_roles)->pluck('id')->toArray();
            $this->applicable_sectors = Sector::whereIn('name', $notice->applicable_sectors)->pluck('id')->toArray();
            $this->notice_start_at = date('Y-m-d', strtotime($notice->start_at));
            $this->notice_end_at = date('Y-m-d', strtotime($notice->end_at));
            $this->notice_is_active = (bool)$notice->is_active;
            $this->notice_is_popup = (bool)$notice->is_popup;
            $this->showNoticeModal();
        }
    }

    public function updateNotice()
    {
        $this->notice_start_at = $this->notice_start_at . ' 00:00:00';
        $this->notice_end_at = $this->notice_end_at . ' 23:59:59';

        DB::beginTransaction();
        try {
            $notice = Notice::find($this->notice_id);
            if ($notice) {
                $notice->update([
                    'type' => $this->notice_type,
                    'title' => ucfirst($this->notice_title),
                    'content' => $this->notice_content,
                    'applicable_roles' => $this->applicable_roles,
                    'applicable_sectors' => $this->applicable_sectors,
                    'start_at' => $this->notice_start_at,
                    'end_at' => $this->notice_end_at,
                    'is_active' => $this->notice_is_active,
                    'is_popup' => $this->notice_is_popup,
                ]);
            }

            DB::commit();
            $this->loadNotices();
            $this->hideNoticeModal();
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Cập nhật thông báo thành công.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra khi cập nhật thông báo.',
            ]);
        }
    }

    public function deleteNotice($id)
    {
        $notice = Notice::find($id);
        $this->dispatch('deleteNotice', ['id' => $id, 'title' => $notice->title]);
    }

    public function deleteNoticeAction($id)
    {
        DB::beginTransaction();
        try {
            $notice = Notice::find($id);
            if ($notice) {
                $notice->delete();
            }

            DB::commit();
            $this->loadNotices();
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Xóa thông báo thành công.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra khi xóa thông báo.',
            ]);
        }
    }


    public function loadNotices()
    {
        $this->listNotices = Notice::orderBy('created_at', 'desc')->get();
    }

    public function showNoticeModal()
    {
        $this->resetErrorBag();
        $this->dispatch('showNoticeModal');
    }

    public function hideNoticeModal()
    {
        $this->dispatch('hideNoticeModal');
        $this->isUpdateNoticeModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->notice_id = $this->notice_title = $this->notice_content = null;
        $this->notice_start_at = $this->notice_end_at = null;
        $this->applicable_roles = [];
        $this->applicable_sectors = [];
        $this->notice_is_active = false;
        $this->notice_is_popup = false;
        $this->notice_type = null;
    }

    public function mount()
    {
        $user = Auth::user();
        $managerData = $this->getManagerData($user);
        $this->roles = $managerData['managerRole'];
        $this->sectors = $managerData['managerSector'];
        $this->loadNotices();
    }

    public function render()
    {
        return view('livewire.management.notices', [
            'listNotices' => $this->listNotices,
        ]);
    }
}
