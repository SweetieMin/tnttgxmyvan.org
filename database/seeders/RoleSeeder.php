<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'admin', 'description' => 'Quản trị toàn bộ hệ thống và sửa chữa hệ thống theo nhu cầu của đoàn.', 'ordering' => 1, 'type' => 'system'],
            ['name' => 'Cha Tuyên Úy', 'description' => 'Người đứng đầu xứ đoàn, chịu trách nhiệm quản lý và điều hành hoạt động của xứ đoàn.', 'ordering' => 2, 'type' => 'system'],
            ['name' => 'Xứ Đoàn Trưởng', 'description' => 'Đứng đầu xứ đoàn, chịu trách nhiệm quản lý và điều hành hoạt động của xứ đoàn.', 'ordering' => 3, 'type' => 'system'],
            ['name' => 'Xứ Đoàn Phó', 'description' => 'Hỗ trợ Xứ Đoàn Trưởng trong việc điều hành và quản lý các hoạt động của xứ đoàn.', 'ordering' => 4, 'type' => 'system'],
            ['name' => 'Trưởng Ngành Nghĩa', 'description' => 'Đứng đầu và điều hành ngành Nghĩa trong xứ đoàn.', 'ordering' => 5, 'type' => 'system'],
            ['name' => 'Phó Ngành Nghĩa', 'description' => 'Hỗ trợ Trưởng Ngành Nghĩa trong việc quản lý và điều hành ngành.', 'ordering' => 6, 'type' => 'system'],
            ['name' => 'Trưởng Ngành Thiếu', 'description' => 'Đứng đầu và điều hành ngành Thiếu trong xứ đoàn.', 'ordering' => 7, 'type' => 'system'],
            ['name' => 'Phó Ngành Thiếu', 'description' => 'Hỗ trợ Trưởng Ngành Thiếu trong việc quản lý và điều hành ngành.', 'ordering' => 8, 'type' => 'system'],
            ['name' => 'Trưởng Ngành Ấu', 'description' => 'Đứng đầu và điều hành ngành Ấu trong xứ đoàn.', 'ordering' => 9, 'type' => 'system'],
            ['name' => 'Phó Ngành Ấu', 'description' => 'Hỗ trợ Trưởng Ngành Ấu trong việc quản lý và điều hành ngành.', 'ordering' => 10, 'type' => 'system'],
            ['name' => 'Trưởng Ngành Tiền Ấu', 'description' => 'Đứng đầu và điều hành ngành Tiền Ấu trong xứ đoàn.', 'ordering' => 11, 'type' => 'system'],
            ['name' => 'Phó Ngành Tiền Ấu', 'description' => 'Hỗ trợ Trưởng Ngành Tiền Ấu trong việc quản lý và điều hành ngành.', 'ordering' => 12, 'type' => 'system'],
            ['name' => 'Huynh Trưởng', 'description' => 'Hướng dẫn và quản lý các thành viên trong ngành, đồng hành cùng thiếu nhi trong việc phát triển.', 'ordering' => 13, 'type' => 'system'],
            ['name' => 'Dự Trưởng', 'description' => 'Hỗ trợ Huynh Trưởng trong việc quản lý các thành viên, thường là huynh trưởng dự bị.', 'ordering' => 14, 'type' => 'system'],
            ['name' => 'Đội Trưởng', 'description' => 'Quản lý và dẫn dắt một đội nhỏ trong ngành, đảm bảo các hoạt động được thực hiện đúng kế hoạch.', 'ordering' => 15, 'type' => 'system'],
            ['name' => 'Thiếu Nhi', 'description' => 'Thành viên nhỏ tuổi của xứ đoàn, tham gia các hoạt động giáo dục và sinh hoạt.', 'ordering' => 16, 'type' => 'system'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
