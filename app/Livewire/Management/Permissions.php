<?php

namespace App\Livewire\Management;

use Livewire\Component;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class Permissions extends Component
{
    public $permission_id, $permission_name, $permission_display_name, $permission_isShow;
    public $isUpdate = false;
    public $isCallOrdering = true;

    public $selectedRoutes = [];

    protected $listeners = ['deletePermissionAction', 'togglePermission', 'refreshPermissionList' => '$refresh', 'updatePermissionsOrdering'];

    protected function getRoutes()
    {
        $routes = [];
        $router = Route::getRoutes();

        foreach ($router as $route) {
            $routeName = str_replace('/', '.', $route->uri);
            if (Str::contains($routeName, 'admin') && !in_array($routeName, $routes)) {
                $exists = Permission::where('name', $routeName)->exists();
                if (!$exists) {
                    $routes[] = $routeName;
                }
            }
        }

        return $routes;
    }

    public function updatePermissionsOrdering($positions = null)
    {
        DB::beginTransaction();

        try {
            if ($positions == null) {
                $permissions = Permission::orderBy('ordering')->get();
                $positions = $permissions->map(function ($permission, $index) {
                    return [$permission->id, $index + 1];
                });
            }

            foreach ($positions as $position) {
                $permissionId = $position[0];
                $newPosition = $position[1];
                Permission::where('id', $permissionId)->update(['ordering' => $newPosition]);
            }
            DB::commit();
            if ($this->isCallOrdering) {
                 $this->dispatch('showToastr', ['message' => 'Đã thay đổi vị trí cá quyền!', 'type' => 'success']);
            }
           
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['message' => 'Đã có lỗi xảy ra: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function addPermission()
    {
        $this->permission_name = $this->permission_display_name = null;
        $this->isUpdate = false;
        $this->showPermissionModal();
    }

    public function createPermission()
    {
        $this->validate([
            'selectedRoutes' => 'required|array|min:1',
        ], [

            'selectedRoutes.required' => 'Bạn phải chọn ít nhất một quyền.',
            'selectedRoutes.array' => 'Các quyền được chọn phải là một mảng.',
            'selectedRoutes.min' => 'Bạn phải chọn ít nhất một quyền.',
        ]);

        DB::beginTransaction();
        try {

            $roleAdmin = Role::where('name', 'admin')->first();

            foreach ($this->selectedRoutes as $route) {
                $permission = new Permission();
                $permission->name = $route;
                $permission->display_name = ucwords(strtolower(trim(Str::replaceFirst('admin/', '', $route))));
                $permission->save();

                // Gán quyền này cho role BOD
                if ($roleAdmin) {
                    $roleAdmin->permissions()->syncWithoutDetaching([$permission->id]);
                }
            }

            DB::commit();
            $this->dispatch('showToastr', ['message' => 'Quyền ' . $permission->display_name . ' đã được tạo thành công.', 'type' => 'success']);
            $this->hidePermissionModal();
            $this->isCallOrdering = false;
            $this->updatePermissionsOrdering();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['message' => 'Đã có lỗi xảy ra!' .$e->getMessage(), 'type' => 'error']);
        }
    }

    public function editPermission($id)
    {
        $permission = Permission::findOrFail($id);
        $this->permission_id = $permission->id;
        $this->permission_name = $permission->name;
        $this->permission_display_name = ucwords(strtolower(trim($permission->display_name)));
        $this->isUpdate = true;
        $this->showPermissionModal();
    }

    public function updatePermission()
    {
        $this->validate([
            'permission_name' => 'required|string|max:255|unique:permissions,name,' . $this->permission_id,
            'permission_display_name' => 'nullable|string|max:255',
        ], [
            'permission_name.required' => 'Tên quyền là bắt buộc.',
            'permission_name.string' => 'Tên quyền phải là một chuỗi.',
            'permission_name.max' => 'Tên quyền không được vượt quá 255 ký tự.',
            'permission_display_name.string' => 'Tên hiển thị quyền phải là một chuỗi.',
            'permission_display_name.max' => 'Tên hiển thị quyền không được vượt quá 255 ký tự.',
        ]);

        DB::beginTransaction();
        try {
            $permission = Permission::findOrFail($this->permission_id);
            $permission->name = trim($this->permission_name);
            $permission->display_name = ucwords(strtolower(trim($this->permission_display_name)));
            $permission->save();

            DB::commit();
            $this->hidePermissionModal();
            $this->isCallOrdering = false;
            $this->updatePermissionsOrdering();
            $this->dispatch('showToastr', ['message' => 'Quyền đã ' . $permission->display_name . ' cập nhật thành công.', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['message' => 'Đã có lỗi xảy ra!', 'type' => 'error']);
        }
    }

    public function deletePermission($id)
    {
        $permission = Permission::findOrFail($id);
        $this->dispatch('deletePermission', ['id' => $id, 'name' => $permission->name]);
    }

    public function deletePermissionAction($id)
    {
        DB::beginTransaction();
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();
            DB::commit();
            $this->dispatch('showToastr', ['message' => 'Quyền ' . $permission->display_name . ' đã được xóa thành công.', 'type' => 'success']);
            $this->isCallOrdering = false;
            $this->updatePermissionsOrdering();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['message' => 'Đã có lỗi xảy ra', 'type' => 'error']);
        }
    }

    public function showPermissionModal()
    {
        $this->resetErrorBag();
        $this->dispatch('showPermissionModal');
    }

    public function hidePermissionModal()
    {
        $this->dispatch('hidePermissionModal');
        $this->isUpdate = false;
        $this->permission_name = $this->permission_display_name = null;
    }

    public function render()
    {
        return view('livewire.management.permissions',[
            'permissions' => Permission::orderBy('ordering', 'asc')->get(),
            'routes' => $this->getRoutes(),
        ]);
    }
}
