<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('semester')->insert([
            [
                'kode_semester' => '20231',
                'nama_semester' => 'Semester Ganjil 2023/2024',
                'tahun_ajaran' => '2023/2024',
                'periode' => 'Ganjil',
                'tanggal_mulai' => '2023-09-04',
                'tanggal_selesai' => '2024-01-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_semester' => '20232',
                'nama_semester' => 'Semester Genap 2023/2024',
                'tahun_ajaran' => '2023/2024',
                'periode' => 'Genap',
                'tanggal_mulai' => '2024-02-03',
                'tanggal_selesai' => '2024-06-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_semester' => '20241',
                'nama_semester' => 'Semester Ganjil 2024/2025',
                'tahun_ajaran' => '2024/2025',
                'periode' => 'Ganjil',
                'tanggal_mulai' => '2024-09-02',
                'tanggal_selesai' => '2025-01-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_semester' => '20242',
                'nama_semester' => 'Semester Genap 2024/2025',
                'tahun_ajaran' => '2024/2025',
                'periode' => 'Genap',
                'tanggal_mulai' => '2025-02-03',
                'tanggal_selesai' => '2025-06-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}