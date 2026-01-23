<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BobotNilaiSeeder extends Seeder
{
    public function run(): void
    {
        // Bobot nilai untuk setiap mata kuliah di semester 3
        $mataKuliahIds = [1, 2, 3, 8, 9, 12, 18, 19];

        foreach ($mataKuliahIds as $mkId) {
            DB::table('bobot_nilai')->insert([
                'mata_kuliah_id' => $mkId,
                'tugas' => 20.00,
                'quiz' => 20.00,
                'uts' => 30.00,
                'uas' => 30.00,
                'kehadiran' => 0.00,
                'praktikum' => 0.00,
                'semester_id' => 3,
                'keterangan' => 'Bobot standar',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}