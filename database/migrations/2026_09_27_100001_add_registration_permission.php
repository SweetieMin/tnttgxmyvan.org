<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private string $permission = 'admin.personnel.registration';

    /**
     * Thêm quyền duyệt đơn đăng ký, cấp sẵn cho các chức vụ đang có quyền quản lý Thiếu nhi.
     */
    public function up(): void
    {
        if (DB::table('permissions')->where('name', $this->permission)->exists()) {
            return;
        }

        $permissionId = DB::table('permissions')->insertGetId([
            'name' => $this->permission,
            'display_name' => 'Duyệt đơn đăng ký / cấp lại thẻ',
            'ordering' => (DB::table('permissions')->max('ordering') ?? 0) + 1,
            'isShow' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $roleIds = DB::table('permission_role')
            ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
            ->where('permissions.name', 'admin.personnel.children')
            ->pluck('permission_role.role_id');

        DB::table('permission_role')->insert(
            $roleIds->map(fn($roleId) => [
                'permission_id' => $permissionId,
                'role_id' => $roleId,
                'created_at' => now(),
                'updated_at' => now(),
            ])->all()
        );
    }

    public function down(): void
    {
        $permissionId = DB::table('permissions')->where('name', $this->permission)->value('id');

        if ($permissionId) {
            DB::table('permission_role')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }
    }
};
