<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kelas')->insert([
            ['kode_kelas' => 'TI101-A-2024', 'nama_kelas' => 'TI101 Kelas A', 'mata_kuliah_id' => 1, 'dosen_id' => 1, 'semester_id' => 3, 'kapasitas' => 40, 'kuota_terisi' => 0, 'status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kelas' => 'TI102-A-2024', 'nama_kelas' => 'TI102 Kelas A', 'mata_kuliah_id' => 2, 'dosen_id' => 1, 'semester_id' => 3, 'kapasitas' => 35, 'kuota_terisi' => 0, 'status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kelas' => 'TI103-A-2024', 'nama_kelas' => 'TI103 Kelas A', 'mata_kuliah_id' => 3, 'dosen_id' => 1, 'semester_id' => 3, 'kapasitas' => 40, 'kuota_terisi' => 0, 'status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kelas' => 'SI101-A-2024', 'nama_kelas' => 'SI101 Kelas A', 'mata_kuliah_id' => 8, 'dosen_id' => 2, 'semester_id' => 3, 'kapasitas' => 30, 'kuota_terisi' => 0, 'status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kelas' => 'SI102-A-2024', 'nama_kelas' => 'SI102 Kelas A', 'mata_kuliah_id' => 9, 'dosen_id' => 2, 'semester_id' => 3, 'kapasitas' => 25, 'kuota_terisi' => 0, 'status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kelas' => 'TE101-A-2024', 'nama_kelas' => 'TE101 Kelas A', 'mata_kuliah_id' => 12, 'dosen_id' => 3, 'semester_id' => 3, 'kapasitas' => 35, 'kuota_terisi' => 0, 'status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kelas' => 'MKU101-A-2024', 'nama_kelas' => 'Bahasa Indonesia A', 'mata_kuliah_id' => 18, 'dosen_id' => 1, 'semester_id' => 3, 'kapasitas' => 50, 'kuota_terisi' => 0, 'status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kelas' => 'MKU102-A-2024', 'nama_kelas' => 'Pancasila A', 'mata_kuliah_id' => 19, 'dosen_id' => 2, 'semester_id' => 3, 'kapasitas' => 50, 'kuota_terisi' => 0, 'status' => 'Aktif', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}