<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dosen')->insert([
            [
                'user_id' => 2,
                'nidn' => '0123456789',
                'nip' => '197801012005011001',
                'nama_lengkap' => 'Andi Wijaya',
                'gelar_depan' => 'Dr. Ir.',
                'gelar_belakang' => 'M.T.',
                'jurusan_id' => 1,
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1978-01-01',
                'alamat' => 'Jl. Mawar No. 123, Jakarta',
                'no_hp' => '081234567890',
                'email_pribadi' => 'andi.wijaya@gmail.com',
                'status_kepegawaian' => 'Tetap',
                'jabatan' => 'Lektor Kepala',
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'nidn' => '0123456790',
                'nip' => '198202152006022002',
                'nama_lengkap' => 'Maya Sari',
                'gelar_depan' => 'Dr.',
                'gelar_belakang' => 'S.Kom., M.T.',
                'jurusan_id' => 2,
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1982-02-15',
                'alamat' => 'Jl. Melati No. 456, Bandung',
                'no_hp' => '081234567891',
                'email_pribadi' => 'maya.sari@gmail.com',
                'status_kepegawaian' => 'Tetap',
                'jabatan' => 'Lektor',
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'nidn' => '0123456791',
                'nip' => '197512102003121003',
                'nama_lengkap' => 'Rudi Hartono',
                'gelar_depan' => 'Prof. Dr. Ir.',
                'gelar_belakang' => 'M.T.',
                'jurusan_id' => 3,
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1975-12-10',
                'alamat' => 'Jl. Anggrek No. 789, Surabaya',
                'no_hp' => '081234567892',
                'email_pribadi' => 'rudi.hartono@gmail.com',
                'status_kepegawaian' => 'Tetap',
                'jabatan' => 'Profesor',
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}