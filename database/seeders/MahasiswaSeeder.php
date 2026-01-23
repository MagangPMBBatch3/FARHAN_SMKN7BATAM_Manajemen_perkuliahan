<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswa = [
            ['user_id' => 5, 'nim' => '20240001', 'nama_lengkap' => 'Ahmad Fauzi', 'jurusan_id' => 1, 'angkatan' => 2024, 'jenis_kelamin' => 'L', 'tempat_lahir' => 'Jakarta', 'tanggal_lahir' => '2005-03-15'],
            ['user_id' => 6, 'nim' => '20240002', 'nama_lengkap' => 'Sari Indah Putri', 'jurusan_id' => 1, 'angkatan' => 2024, 'jenis_kelamin' => 'P', 'tempat_lahir' => 'Yogyakarta', 'tanggal_lahir' => '2005-07-22'],
            ['user_id' => 7, 'nim' => '20230001', 'nama_lengkap' => 'Budi Setiawan', 'jurusan_id' => 1, 'angkatan' => 2023, 'jenis_kelamin' => 'L', 'tempat_lahir' => 'Medan', 'tanggal_lahir' => '2004-11-08'],
            ['user_id' => 8, 'nim' => '20230002', 'nama_lengkap' => 'Rina Wati', 'jurusan_id' => 2, 'angkatan' => 2023, 'jenis_kelamin' => 'P', 'tempat_lahir' => 'Surabaya', 'tanggal_lahir' => '2004-05-12'],
            ['user_id' => 9, 'nim' => '20240003', 'nama_lengkap' => 'Joko Susanto', 'jurusan_id' => 2, 'angkatan' => 2024, 'jenis_kelamin' => 'L', 'tempat_lahir' => 'Semarang', 'tanggal_lahir' => '2005-09-20'],
            ['user_id' => 10, 'nim' => '20240004', 'nama_lengkap' => 'Dewi Lestari', 'jurusan_id' => 3, 'angkatan' => 2024, 'jenis_kelamin' => 'P', 'tempat_lahir' => 'Malang', 'tanggal_lahir' => '2005-12-03'],
            ['user_id' => 11, 'nim' => '20240005', 'nama_lengkap' => 'Hendra Gunawan', 'jurusan_id' => 4, 'angkatan' => 2024, 'jenis_kelamin' => 'L', 'tempat_lahir' => 'Bekasi', 'tanggal_lahir' => '2005-06-18'],
            ['user_id' => 12, 'nim' => '20240006', 'nama_lengkap' => 'Fitri Handayani', 'jurusan_id' => 4, 'angkatan' => 2024, 'jenis_kelamin' => 'P', 'tempat_lahir' => 'Tangerang', 'tanggal_lahir' => '2005-04-25'],
            ['user_id' => 13, 'nim' => '20240007', 'nama_lengkap' => 'Eko Prasetyo', 'jurusan_id' => 5, 'angkatan' => 2024, 'jenis_kelamin' => 'L', 'tempat_lahir' => 'Solo', 'tanggal_lahir' => '2005-10-11'],
            ['user_id' => 14, 'nim' => '20240008', 'nama_lengkap' => 'Linda Wijayanti', 'jurusan_id' => 6, 'angkatan' => 2024, 'jenis_kelamin' => 'P', 'tempat_lahir' => 'Bogor', 'tanggal_lahir' => '2005-08-30'],
        ];

        foreach ($mahasiswa as $mhs) {
            DB::table('mahasiswa')->insert(array_merge($mhs, [
                'alamat' => 'Jl. Kenanga No. 123',
                'no_hp' => '0812345678' . rand(10, 99),
                'email_pribadi' => strtolower(str_replace(' ', '.', $mhs['nama_lengkap'])) . '@gmail.com',
                'nama_ayah' => 'Ayah ' . $mhs['nama_lengkap'],
                'nama_ibu' => 'Ibu ' . $mhs['nama_lengkap'],
                'no_hp_ortu' => '0812345678' . rand(10, 99),
                'status' => 'Aktif',
                'semester_saat_ini' => $mhs['angkatan'] == 2024 ? 1 : 3,
                'ip_semester' => 0.00,
                'ipk' => 0.00,
                'total_sks' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}