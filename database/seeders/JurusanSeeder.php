<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jurusan')->insert([
            // FTIK
            ['kode_jurusan' => 'TI', 'nama_jurusan' => 'Teknik Informatika', 'fakultas_id' => 1, 'jenjang' => 'S1', 'akreditasi' => 'A', 'kaprodi' => 'Dr. Ir. Andi Wijaya, M.T.', 'created_at' => now(), 'updated_at' => now()],
            ['kode_jurusan' => 'SI', 'nama_jurusan' => 'Sistem Informasi', 'fakultas_id' => 1, 'jenjang' => 'S1', 'akreditasi' => 'B', 'kaprodi' => 'Dr. Maya Sari, S.Kom., M.T.', 'created_at' => now(), 'updated_at' => now()],
            ['kode_jurusan' => 'TE', 'nama_jurusan' => 'Teknik Elektro', 'fakultas_id' => 1, 'jenjang' => 'S1', 'akreditasi' => 'A', 'kaprodi' => 'Prof. Dr. Ir. Rudi Hartono, M.T.', 'created_at' => now(), 'updated_at' => now()],

            // FEKON
            ['kode_jurusan' => 'MN', 'nama_jurusan' => 'Manajemen', 'fakultas_id' => 2, 'jenjang' => 'S1', 'akreditasi' => 'A', 'kaprodi' => 'Dr. Lina Marlina, S.E., M.M.', 'created_at' => now(), 'updated_at' => now()],
            ['kode_jurusan' => 'AK', 'nama_jurusan' => 'Akuntansi', 'fakultas_id' => 2, 'jenjang' => 'S1', 'akreditasi' => 'B', 'kaprodi' => 'Dr. Bambang Suryono, S.E., M.Ak.', 'created_at' => now(), 'updated_at' => now()],

            // FISIP
            ['kode_jurusan' => 'HI', 'nama_jurusan' => 'Hubungan Internasional', 'fakultas_id' => 3, 'jenjang' => 'S1', 'akreditasi' => 'B', 'kaprodi' => 'Dr. Dewi Ratna, S.IP., M.Si.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}