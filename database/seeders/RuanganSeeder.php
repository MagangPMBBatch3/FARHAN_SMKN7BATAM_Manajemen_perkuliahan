<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ruangan')->insert([
            ['kode_ruangan' => 'A101', 'nama_ruangan' => 'Ruang Kuliah A101', 'gedung' => 'Gedung A', 'lantai' => 1, 'kapasitas' => 40, 'jenis_ruangan' => 'Kelas', 'fasilitas' => 'Proyektor, AC, Whiteboard', 'created_at' => now(), 'updated_at' => now()],
            ['kode_ruangan' => 'A102', 'nama_ruangan' => 'Ruang Kuliah A102', 'gedung' => 'Gedung A', 'lantai' => 1, 'kapasitas' => 50, 'jenis_ruangan' => 'Kelas', 'fasilitas' => 'Proyektor, AC, Whiteboard, Sound System', 'created_at' => now(), 'updated_at' => now()],
            ['kode_ruangan' => 'A201', 'nama_ruangan' => 'Ruang Kuliah A201', 'gedung' => 'Gedung A', 'lantai' => 2, 'kapasitas' => 35, 'jenis_ruangan' => 'Kelas', 'fasilitas' => 'Proyektor, AC, Whiteboard', 'created_at' => now(), 'updated_at' => now()],
            ['kode_ruangan' => 'B101', 'nama_ruangan' => 'Lab Komputer 1', 'gedung' => 'Gedung B', 'lantai' => 1, 'kapasitas' => 30, 'jenis_ruangan' => 'Lab', 'fasilitas' => 'PC 30 unit, AC, Proyektor', 'created_at' => now(), 'updated_at' => now()],
            ['kode_ruangan' => 'B102', 'nama_ruangan' => 'Lab Komputer 2', 'gedung' => 'Gedung B', 'lantai' => 1, 'kapasitas' => 25, 'jenis_ruangan' => 'Lab', 'fasilitas' => 'PC 25 unit, AC, Proyektor', 'created_at' => now(), 'updated_at' => now()],
            ['kode_ruangan' => 'C101', 'nama_ruangan' => 'Aula Utama', 'gedung' => 'Gedung C', 'lantai' => 1, 'kapasitas' => 200, 'jenis_ruangan' => 'Aula', 'fasilitas' => 'Sound System, AC, Proyektor, Panggung', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}