<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sector;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $sectors = [
            ['name' => 'Nghĩa Sĩ', 'description' => 'Dành cho các em từ 14 đến 16 tuổi'],
            ['name' => 'Thiếu 3', 'description' => 'Dành cho các em 13 tuổi'],
            ['name' => 'Thiếu 2', 'description' => 'Dành cho các em 12 tuổi'],
            ['name' => 'Thiếu 1', 'description' => 'Dành cho các em 11 tuổi'],
            ['name' => 'Ấu 3', 'description' => 'Dành cho các em 10 tuổi'],
            ['name' => 'Ấu 2', 'description' => 'Dành cho các em 9 tuổi'],
            ['name' => 'Ấu 1', 'description' => 'Dành cho các em 8 tuổi'],
            ['name' => 'Tiền Ấu', 'description' => 'Dành cho các em từ 4 đến 7 tuổi'],
        ];


        foreach ($sectors as $sector) {
            Sector::create($sector);
        }
    }
}
