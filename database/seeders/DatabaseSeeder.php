<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserSeeder::class,
            BibleSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            GeneralSettingSeeder::class,
            CourseSeeder::class,
            SectorSeeder::class,
            RegulationSeeder::class,
        ]);

        // Roles

        DB::table('role_user')->insert([
            ['user_id' => 1, 'role_id' => 1], // admin

            ['user_id' => 3, 'role_id' => 3], // Chớp
            ['user_id' => 6, 'role_id' => 4], // Vân

            ['user_id' => 2, 'role_id' => 7], // Huấn
            ['user_id' => 4, 'role_id' => 11], // Vy
            ['user_id' => 5, 'role_id' => 9], // Dung
            ['user_id' => 9, 'role_id' => 5], //Nam


            ['user_id' => 7, 'role_id' => 13], // Minh
            ['user_id' => 8, 'role_id' => 13], //Nhật

           
            ['user_id' => 10, 'role_id' => 16],
            ['user_id' => 11, 'role_id' => 16],
            ['user_id' => 12, 'role_id' => 16],
            ['user_id' => 13, 'role_id' => 16],

        ]);

        // Courses
        DB::table('course_user')->insert([

            ['user_id' => 10, 'course_id' => 1],
            ['user_id' => 11, 'course_id' => 2],
            ['user_id' => 12, 'course_id' => 3],
            ['user_id' => 13, 'course_id' => 4],
            
        ]);

        // Sectors
        DB::table('sector_user')->insert([

            ['user_id' => 7, 'sector_id' => 6], // Minh
            ['user_id' => 8, 'sector_id' => 3], //Nhật

            ['user_id' => 10, 'sector_id' => 8],
            ['user_id' => 11, 'sector_id' => 7],
            ['user_id' => 12, 'sector_id' => 6],
            ['user_id' => 13, 'sector_id' => 5],
        ]); 

        DB::table('permission_role')->insert([
            //admin
            ['permission_id' => 12, 'role_id' => 1],
            ['permission_id' => 13, 'role_id' => 1],
            ['permission_id' => 14, 'role_id' => 1],
            ['permission_id' => 15, 'role_id' => 1],
            ['permission_id' => 16, 'role_id' => 1],
            ['permission_id' => 17, 'role_id' => 1],
            ['permission_id' => 18, 'role_id' => 1],
            ['permission_id' => 19, 'role_id' => 1],
            ['permission_id' => 20, 'role_id' => 1],
            ['permission_id' => 21, 'role_id' => 1],
            ['permission_id' => 22, 'role_id' => 1],
            ['permission_id' => 23, 'role_id' => 1],
            ['permission_id' => 24, 'role_id' => 1],
            ['permission_id' => 25, 'role_id' => 1],
            // XĐT
            ['permission_id' => 15, 'role_id' => 3],
            ['permission_id' => 16, 'role_id' => 3],
            ['permission_id' => 17, 'role_id' => 3],
            ['permission_id' => 18, 'role_id' => 3],
            ['permission_id' => 19, 'role_id' => 3],
            ['permission_id' => 20, 'role_id' => 3],
            ['permission_id' => 21, 'role_id' => 3],
            ['permission_id' => 22, 'role_id' => 3],
            ['permission_id' => 23, 'role_id' => 3],
            ['permission_id' => 24, 'role_id' => 3],
            ['permission_id' => 25, 'role_id' => 3],
            
            //XĐP
            ['permission_id' => 15, 'role_id' => 4],
            ['permission_id' => 16, 'role_id' => 4],
            ['permission_id' => 17, 'role_id' => 4],
            ['permission_id' => 18, 'role_id' => 4],
            ['permission_id' => 19, 'role_id' => 4],
            ['permission_id' => 20, 'role_id' => 4],
            ['permission_id' => 21, 'role_id' => 4],
            ['permission_id' => 22, 'role_id' => 4],
            ['permission_id' => 23, 'role_id' => 4],
            ['permission_id' => 24, 'role_id' => 4],
            ['permission_id' => 25, 'role_id' => 4],
            // Trưởng ngành Nghĩa
            ['permission_id' => 16, 'role_id' => 5],
            ['permission_id' => 17, 'role_id' => 5],
            ['permission_id' => 19, 'role_id' => 5],
            ['permission_id' => 22, 'role_id' => 5],
            ['permission_id' => 25, 'role_id' => 5],
            //Phó ngành Nghĩa
            ['permission_id' => 16, 'role_id' => 6],
            ['permission_id' => 17, 'role_id' => 6],
            ['permission_id' => 19, 'role_id' => 6],
            ['permission_id' => 22, 'role_id' => 6],
            ['permission_id' => 25, 'role_id' => 5],
            //Trưởng ngành Thiếu
            ['permission_id' => 16, 'role_id' => 7],
            ['permission_id' => 17, 'role_id' => 7],
            ['permission_id' => 19, 'role_id' => 7],
            ['permission_id' => 22, 'role_id' => 7],
            ['permission_id' => 25, 'role_id' => 5],
            //Phó ngành Thiếu
            ['permission_id' => 16, 'role_id' => 8],
            ['permission_id' => 17, 'role_id' => 8],
            ['permission_id' => 19, 'role_id' => 8],
            ['permission_id' => 22, 'role_id' => 8],
            ['permission_id' => 25, 'role_id' => 5],
            //Trưởng ngành Ấu
            ['permission_id' => 16, 'role_id' => 9],
            ['permission_id' => 17, 'role_id' => 9],
            ['permission_id' => 19, 'role_id' => 9],
            ['permission_id' => 22, 'role_id' => 9],
            ['permission_id' => 25, 'role_id' => 5],
            //Phó ngành Ấu
            ['permission_id' => 16, 'role_id' => 10],
            ['permission_id' => 17, 'role_id' => 10],
            ['permission_id' => 19, 'role_id' => 10],
            ['permission_id' => 22, 'role_id' => 10],
            ['permission_id' => 25, 'role_id' => 5],
            //Trưởng ngành Tiền Ấu
            ['permission_id' => 16, 'role_id' => 11],
            ['permission_id' => 17, 'role_id' => 11],
            ['permission_id' => 19, 'role_id' => 11],
            ['permission_id' => 22, 'role_id' => 11],
            ['permission_id' => 25, 'role_id' => 5],
            //Phó ngành Tiền Ấu
            ['permission_id' => 16, 'role_id' => 12],
            ['permission_id' => 17, 'role_id' => 12],
            ['permission_id' => 19, 'role_id' => 12],
            ['permission_id' => 22, 'role_id' => 12],
            ['permission_id' => 25, 'role_id' => 5],
            //Huynh Trưởng
            ['permission_id' => 16, 'role_id' => 13],
            ['permission_id' => 17, 'role_id' => 13],
            //Dự Trưởng
            ['permission_id' => 16, 'role_id' => 14],
            ['permission_id' => 17, 'role_id' => 14],
            //Đội Trưởng
            ['permission_id' => 16, 'role_id' => 15],
            ['permission_id' => 17, 'role_id' => 15],


        ]);

    }
}
