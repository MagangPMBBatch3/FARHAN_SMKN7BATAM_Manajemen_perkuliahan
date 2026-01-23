<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * File: database/seeders/DatabaseSeeder.php
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            FakultasSeeder::class,
            JurusanSeeder::class,
            DosenSeeder::class,
            MahasiswaSeeder::class,
            SemesterSeeder::class,
            RuanganSeeder::class,
            MataKuliahSeeder::class,
            GradeSystemSeeder::class,
            SksLimitSeeder::class,
            KelasSeeder::class,
            JadwalKuliahSeeder::class,
            BobotNilaiSeeder::class,
            KrsSeeder::class,
            PertemuanSeeder::class,
            PengaturanKehadiranSeeder::class,
        ]);
    }
}