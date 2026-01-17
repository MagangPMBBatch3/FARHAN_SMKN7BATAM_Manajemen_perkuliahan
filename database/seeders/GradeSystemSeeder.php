<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSystemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('grade_system')->insert([
            ['grade' => 'A', 'min_score' => 85.00, 'max_score' => 100.00, 'grade_point' => 4.00, 'status_kelulusan' => 'Lulus', 'keterangan' => 'Sangat Baik', 'Status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['grade' => 'A-', 'min_score' => 80.00, 'max_score' => 84.99, 'grade_point' => 3.75, 'status_kelulusan' => 'Lulus', 'keterangan' => 'Sangat Baik', 'Status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['grade' => 'B+', 'min_score' => 75.00, 'max_score' => 79.99, 'grade_point' => 3.50, 'status_kelulusan' => 'Lulus', 'keterangan' => 'Baik', 'Status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['grade' => 'B', 'min_score' => 70.00, 'max_score' => 74.99, 'grade_point' => 3.00, 'status_kelulusan' => 'Lulus', 'keterangan' => 'Baik', 'Status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['grade' => 'B-', 'min_score' => 65.00, 'max_score' => 69.99, 'grade_point' => 2.75, 'status_kelulusan' => 'Lulus', 'keterangan' => 'Cukup Baik', 'Status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['grade' => 'C+', 'min_score' => 60.00, 'max_score' => 64.99, 'grade_point' => 2.50, 'status_kelulusan' => 'Lulus', 'keterangan' => 'Cukup', 'Status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['grade' => 'C', 'min_score' => 55.00, 'max_score' => 59.99, 'grade_point' => 2.00, 'status_kelulusan' => 'Lulus', 'keterangan' => 'Cukup', 'Status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['grade' => 'D', 'min_score' => 50.00, 'max_score' => 54.99, 'grade_point' => 1.00, 'status_kelulusan' => 'Tidak Lulus', 'keterangan' => 'Kurang', 'Status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['grade' => 'E', 'min_score' => 0.00, 'max_score' => 49.99, 'grade_point' => 0.00, 'status_kelulusan' => 'Tidak Lulus', 'keterangan' => 'Sangat Kurang', 'Status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
