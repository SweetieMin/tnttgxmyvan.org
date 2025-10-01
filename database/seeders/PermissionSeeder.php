<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ["admin.login", "Đăng nhập", 0],
            ["admin.forgot-password", "Quên mật khẩu", 0],
            ["admin.send-password-reset-link", "Gửi liên kết đặt lại mật khẩu", 0],
            ["admin.password.reset", "Form đặt lại mật khẩu", 0],
            ["admin.reset-password", "Đặt lại mật khẩu", 0],
            ["admin.verify-email.{token}", "Xác minh email", 0],
            ["admin.dashboard", "Bảng điều khiển", 0],
            ["admin.logout", "Đăng xuất", 0],
            ["admin.profile", "Thông tin cá nhân", 0],
            ["admin.update-logo", "Cập nhật logo", 0],
            ["admin.update-favicon", "Cập nhật favicon", 0],

            ["admin.settings", "Cài đặt chung", 1],
            ["admin.management.role", "Quản lý Vai trò", 1],
            ["admin.management.permission", "Quản lý Quyền hạn", 1],
            
            ["admin.update-profile-picture", "Cập nhật ảnh đại diện", 1],
            ["admin.attendance.reward", "Điểm danh khen thưởng", 1],
            ["admin.attendance.discipline", "Điểm danh kỷ luật", 1],
            ["admin.attendance.confirm", "Xác nhận điểm danh", 1],
            ["admin.personnel.scouter", "Quản lý Huynh trưởng", 1],
            ["admin.personnel.children", "Quản lý Thiếu nhi", 1],
            ["admin.management.course", "Quản lý Khóa lớp giáo lý", 1],
            ["admin.management.sector", "Quản lý Ngành sinh hoạt", 1],
            ["admin.management.bible", "Quản lý câu Kinh Thánh", 1],
            ["admin.management.schedule", "Quản lý Lịch điểm danh", 1],
            ["admin.management.regulation", "Quản lý nội quy", 1],
            ["admin.management.notice", "Quản lý thông báo", 1],
           
        ];

        foreach ($permissions as $index => [$name, $display_name, $isShow]) {
            Permission::create([
                'name' => $name,
                'display_name' => $display_name,
                'ordering' => $index + 1,
                'isShow' => $isShow,
            ]);
        }
    }
}
