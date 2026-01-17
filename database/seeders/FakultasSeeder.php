<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakultasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fakultas')->insert([
            [
                'kode_fakultas' => 'FTIK',
                'nama_fakultas' => 'Fakultas Teknik dan Ilmu Komputer',
                'dekan' => 'Prof. Dr. Ir. Budi Santoso, M.T.',
                'alamat' => 'Jl. Raya Universitas No. 1',
                'telepon' => '02112345678',
                'email' => 'ftik@univ.ac.id',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_fakultas' => 'FEKON',
                'nama_fakultas' => 'Fakultas Ekonomi dan Bisnis',
                'dekan' => 'Dr. Siti Nurhaliza, S.E., M.M.',
                'alamat' => 'Jl. Raya Universitas No. 2',
                'telepon' => '02112345679',
                'email' => 'fekon@univ.ac.id',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_fakultas' => 'FISIP',
                'nama_fakultas' => 'Fakultas Ilmu Sosial dan Politik',
                'dekan' => 'Dr. Ahmad Rahman, S.Sos., M.Si.',
                'alamat' => 'Jl. Raya Universitas No. 3',
                'telepon' => '02112345680',
                'email' => 'fisip@univ.ac.id',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}