<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password'); // Default password untuk semua user

        DB::table('users')->insert([
            // Admin
            ['id' => 1, 'username' => 'admin', 'email' => 'admin@univ.ac.id', 'password' => $password, 'role_id' => 1, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],

            // Dosen (3 users)
            ['id' => 2, 'username' => 'dosen1', 'email' => 'dosen1@univ.ac.id', 'password' => $password, 'role_id' => 2, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'username' => 'dosen2', 'email' => 'dosen2@univ.ac.id', 'password' => $password, 'role_id' => 2, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'username' => 'dosen3', 'email' => 'dosen3@univ.ac.id', 'password' => $password, 'role_id' => 2, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],

            // Mahasiswa (10 users)
            ['id' => 5, 'username' => 'mahasiswa1', 'email' => 'mahasiswa1@student.univ.ac.id', 'password' => $password, 'role_id' => 3, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'username' => 'mahasiswa2', 'email' => 'mahasiswa2@student.univ.ac.id', 'password' => $password, 'role_id' => 3, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'username' => 'mahasiswa3', 'email' => 'mahasiswa3@student.univ.ac.id', 'password' => $password, 'role_id' => 3, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'username' => 'mahasiswa4', 'email' => 'mahasiswa4@student.univ.ac.id', 'password' => $password, 'role_id' => 3, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'username' => 'mahasiswa5', 'email' => 'mahasiswa5@student.univ.ac.id', 'password' => $password, 'role_id' => 3, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'username' => 'mahasiswa6', 'email' => 'mahasiswa6@student.univ.ac.id', 'password' => $password, 'role_id' => 3, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'username' => 'mahasiswa7', 'email' => 'mahasiswa7@student.univ.ac.id', 'password' => $password, 'role_id' => 3, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'username' => 'mahasiswa8', 'email' => 'mahasiswa8@student.univ.ac.id', 'password' => $password, 'role_id' => 3, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'username' => 'mahasiswa9', 'email' => 'mahasiswa9@student.univ.ac.id', 'password' => $password, 'role_id' => 3, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'username' => 'mahasiswa10', 'email' => 'mahasiswa10@student.univ.ac.id', 'password' => $password, 'role_id' => 3, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],

            // Staff Akademik
            ['id' => 15, 'username' => 'akademik', 'email' => 'akademik@univ.ac.id', 'password' => $password, 'role_id' => 4, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}