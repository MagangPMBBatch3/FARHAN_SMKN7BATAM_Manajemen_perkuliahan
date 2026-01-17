<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'nama_role' => 'Admin', 'deskripsi' => 'Administrator sistem', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama_role' => 'Dosen', 'deskripsi' => 'Dosen/Pengajar', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama_role' => 'Mahasiswa', 'deskripsi' => 'Mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama_role' => 'Akademik', 'deskripsi' => 'Staff Akademik', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}