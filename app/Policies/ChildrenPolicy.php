<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChildrenPolicy
{
    protected array $allowedRoles = [
        'Admin',
        'Cha Tuyên Úy',
        'Xứ Đoàn Trưởng',
        'Xứ Đoàn Phó',
        'Trưởng Ngành Thiếu',
        'Phó Ngành Thiếu',
        'Trưởng Ngành Tiền Ấu',
        'Phó Ngành Tiền Ấu',
        'Trưởng Ngành Ấu',
        'Phó Ngành Ấu',
        'Trưởng Ngành Nghĩa',
        'Phó Ngành Nghĩa',
    ];

    /**
     * Kiểm tra xem user có quyền thao tác trên “Thiếu Nhi” không.
     */
    protected function hasAllowedRole(User $user): bool
    {
        return $user->roles()->whereIn('name', $this->allowedRoles)->exists();
    }

    /**
     * Tạo mới học viên.
     */
    public function create(User $user): bool
    {
        return $this->hasAllowedRole($user);
    }

    /**
     * Cập nhật học viên.
     */
    public function update(User $user, User $model): bool
    {
        return $this->hasAllowedRole($user);
    }

    /**
     * Xoá học viên.
     */
    public function delete(User $user, User $model): bool
    {
        return $this->hasAllowedRole($user);
    }

    // Các quyền khác (tuỳ bạn muốn mở thêm)
    public function viewAny(User $user): bool { return false; }
    public function view(User $user, User $model): bool { return false; }
    public function restore(User $user, User $model): bool { return false; }
    public function forceDelete(User $user, User $model): bool { return false; }
}
