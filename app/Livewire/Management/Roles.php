<?php

namespace App\Livewire\Management;

use App\Models\Permission;
use Livewire\Component;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class Roles extends Component
{

    public $isUpdateRoleMode = false;
    public $role_id, $role_name, $role_description;
    public $isSystem;
    public bool $hasChanges = false;
    public $selected_permissions = [];
    public $isUpdateOrdering = true;

    protected $listeners = [
        'updateRoleOrdering',
        'deleteRoleAction',
        'updated',
        'submitRoleForm'
    ];

    public function mount()
    {
        $this->role_name = null;
        $this->role_description = null;
        $this->isSystem = null;
        $this->isUpdateOrdering = true;
    }

    public function submitRoleForm($selected_permissions)
    {
        $this->selected_permissions = $selected_permissions ?? [];
        if ($this->isUpdateRoleMode) {
            $this->updateRole();
        } else {
            $this->createRole();
        }
    }

    public function updated($propertyName)
    {
        $this->hasChanges = true;
    }

    public function addRole()
    {
        $this->selected_permissions = [];
        $this->role_name = null;
        $this->role_description = null;
        $this->isUpdateRoleMode = false;
        $this->showRoleModalForm();
    }

    public function showRoleModalForm()
    {
        $this->resetErrorBag();
        $this->dispatch('showRoleModalForm');
    }

    public function hideRoleModalForm()
    {
        $this->dispatch('hideRoleModalForm');
        $this->isUpdateRoleMode = false;
        $this->role_name = $this->role_description = null;
    }

    public function createRole()
    {
        $this->validate([
            'role_name' => 'required|unique:roles,name',
            'role_description' => 'required|min:5',
            'selected_permissions' => 'array',
            'selected_permissions.*' => 'exists:permissions,id',
        ], [
            'role_name.required' => 'Tên chức vụ là bắt buộc.',
            'role_name.unique' => 'Tên chức vụ đã tồn tại.',
            'role_description.required' => 'Mô tả chức vụ là bắt buộc.',
            'role_description.min' => 'Mô tả ít nhất 5 ký tự.',
            'selected_permissions.array' => 'Danh sách quyền được chọn phải là một mảng.',
            'selected_permissions.*.exists' => 'Một hoặc nhiều quyền được chọn không hợp lệ.',
        ]);

        DB::beginTransaction();

        try {
            $role = new Role();
            $role->name = ucwords($this->role_name);
            $role->description = ucfirst($this->role_description);
            $save = $role->save();

            if ($save) {
                if (!empty($this->selected_permissions)) {
                    $role->permissions()->sync($this->selected_permissions);
                }
                DB::commit();
                $this->hideRoleModalForm();
                $this->isUpdateOrdering = false;
                $this->updateRoleOrdering();
                $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã thêm chức vụ ' . $this->role_name . ' thành công.']);
            } else {
                DB::rollBack();
                $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé.']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function editRole($id)
    {
        $role = Role::query()->findOrFail($id);
        if ($role->type === "System") {
            $this->isSystem = "disabled";
        } else {
            $this->isSystem = null;
        }
        $this->selected_permissions = $role->permissions()->pluck('permissions.id')->toArray();
        $this->role_id = $role->id;
        $this->role_name = ucwords(strtolower($role->name));
        $this->role_description = ucfirst(strtolower($role->description));
        $this->isUpdateRoleMode = true;
        $this->showRoleModalForm();
    }

    public function updateRole()
    {
        $role = Role::query()->findOrFail($this->role_id);
        $this->validate([
            'role_name' => 'required|unique:roles,name,' . $role->id,
            'role_description' => 'required|min:5'
        ], [
            'role_name.required' => 'Tên chức vụ là bắt buộc.',
            'role_name.unique' => 'Tên chức vụ đã tồn tại.',
            'role_description.required' => 'Mô tả chức vụ là bắt buộc.',
            'role_description.min' => 'Mô tả ít nhất 5 ký tự.',
        ]);

        DB::beginTransaction();

        try {
            $role->name = ucwords($this->role_name);
            $role->description = ucfirst($this->role_description);
            $update = $role->save();

            if ($update) {
                $role->permissions()->sync($this->selected_permissions);
                DB::commit();
                $this->hideRoleModalForm();
                $this->isUpdateOrdering = false;
                $this->updateRoleOrdering();
                $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã cập nhật chức vụ ' . $this->role_name . ' thành công.']);
            } else {
                DB::rollBack();
                $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé.']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function updateRoleOrdering($positions = null)
    {

        if (!$positions) {
            $roles = Role::orderBy('ordering')->get();
            $positions = $roles->map(function ($role, $index) {
                return [$role->id, $index + 1]; // Tạo thứ tự mới từ 1 đến n
            });
        }

        foreach ($positions as $position) {
            $index = $position[0];
            $new_position = $position[1];

            Role::query()->where('id', $index)->update([
                'ordering' => $new_position
            ]);
        }
        if ($this->isUpdateOrdering) {
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã cập nhật danh sách chức vụ thành công.']);
        }
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        if ($role->type === 'System') {
            $this->dispatch('showToastr', ['message' => 'Không thể xóa chức vụ của hệ thống!', 'type' => 'info']);
        } else {
            $this->dispatch('deleteRole', ['id' => $id, 'name' => $role->name]);
        }
    }

    public function deleteRoleAction($id)
    {
        $role = Role::query()->findOrFail($id);
        $role_name = ucwords(strtolower($role->name));
        $delete = $role->delete();

        $this->isUpdateOrdering = false;
        $this->updateRoleOrdering();

        if ($delete) {
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã xóa chức vụ ' . $role_name . ' thành công.']);
        } else {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé.']);
        }
    }

    public function render()
    {
        return view('livewire.management.roles', [
            'roles' => Role::orderBy('ordering', 'asc')->get(),
            'permissions' => Permission::where('isShow', 1)->orderBy('ordering', 'asc')->get(),
        ]);
    }
}
