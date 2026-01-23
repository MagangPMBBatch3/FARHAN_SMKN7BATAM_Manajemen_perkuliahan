<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalKuliahSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jadwal_kuliah')->insert([
            ['kelas_id' => 1, 'ruangan_id' => 1, 'hari' => 'Senin', 'jam_mulai' => '08:00:00', 'jam_selesai' => '10:30:00', 'keterangan' => null, 'created_at' => now(), 'updated_at' => now()],
            ['kelas_id' => 2, 'ruangan_id' => 4, 'hari' => 'Selasa', 'jam_mulai' => '10:45:00', 'jam_selesai' => '13:15:00', 'keterangan' => null, 'created_at' => now(), 'updated_at' => now()],
            ['kelas_id' => 3, 'ruangan_id' => 2, 'hari' => 'Rabu', 'jam_mulai' => '08:00:00', 'jam_selesai' => '10:30:00', 'keterangan' => null, 'created_at' => now(), 'updated_at' => now()],
            ['kelas_id' => 4, 'ruangan_id' => 3, 'hari' => 'Kamis', 'jam_mulai' => '13:30:00', 'jam_selesai' => '16:00:00', 'keterangan' => null, 'created_at' => now(), 'updated_at' => now()],
            ['kelas_id' => 5, 'ruangan_id' => 4, 'hari' => 'Jumat', 'jam_mulai' => '08:00:00', 'jam_selesai' => '10:30:00', 'keterangan' => null, 'created_at' => now(), 'updated_at' => now()],
            ['kelas_id' => 6, 'ruangan_id' => 5, 'hari' => 'Senin', 'jam_mulai' => '13:30:00', 'jam_selesai' => '16:00:00', 'keterangan' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}