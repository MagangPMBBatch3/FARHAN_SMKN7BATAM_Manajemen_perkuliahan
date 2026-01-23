<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengaturanKehadiranSeeder extends Seeder
{
    public function run(): void
    {
        $kelasIds = [1, 2, 3, 4, 5, 6, 7, 8];
        
        foreach ($kelasIds as $kelasId) {
            DB::table('pengaturan_kehadiran')->insert([
                'kelas_id' => $kelasId,
                'minimal_kehadiran' => 75.00,
                'auto_generate_pertemuan' => true,
                'keterangan' => 'Minimal kehadiran 75% untuk ikut UAS',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}