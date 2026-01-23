<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KrsSeeder extends Seeder
{
    public function run(): void
    {
        // KRS untuk mahasiswa angkatan 2024 (semester 1)
        $mahasiswaAngkatan2024 = [1, 2, 5, 6, 7, 8, 9, 10];

        foreach ($mahasiswaAngkatan2024 as $index => $mhsId) {
            $krsId = DB::table('krs')->insertGetId([
                'mahasiswa_id' => $mhsId,
                'semester_id' => 3,
                'tanggal_pengisian' => now()->subDays(30),
                'tanggal_persetujuan' => now()->subDays(28),
                'status' => 'Disetujui',
                'total_sks' => 18,
                'catatan' => 'KRS disetujui',
                'dosen_pa_id' => ($index % 3) + 1, // Rotasi dosen PA
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // KRS Detail untuk setiap mahasiswa (3 mata kuliah)
            $kelasIds = [1, 2, 7]; // TI101, TI102, MKU101
            $mataKuliahIds = [1, 2, 18];
            $sksArray = [3, 4, 2];

            foreach ($kelasIds as $key => $kelasId) {
                DB::table('krs_detail')->insert([
                    'krs_id' => $krsId,
                    'kelas_id' => $kelasId,
                    'mata_kuliah_id' => $mataKuliahIds[$key],
                    'sks' => $sksArray[$key],
                    'status_ambil' => 'Baru',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update kuota terisi kelas
                DB::table('kelas')->where('id', $kelasId)->increment('kuota_terisi');
            }
        }
    }
}