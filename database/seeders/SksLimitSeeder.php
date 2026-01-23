<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SksLimitSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sks_limits')->insert([
            ['min_ipk' => 3.50, 'max_ipk' => 4.00, 'max_sks' => 24, 'keterangan' => 'IPK >= 3.50', 'created_at' => now(), 'updated_at' => now()],
            ['min_ipk' => 3.00, 'max_ipk' => 3.49, 'max_sks' => 22, 'keterangan' => 'IPK 3.00 - 3.49', 'created_at' => now(), 'updated_at' => now()],
            ['min_ipk' => 2.50, 'max_ipk' => 2.99, 'max_sks' => 20, 'keterangan' => 'IPK 2.50 - 2.99', 'created_at' => now(), 'updated_at' => now()],
            ['min_ipk' => 2.00, 'max_ipk' => 2.49, 'max_sks' => 18, 'keterangan' => 'IPK 2.00 - 2.49', 'created_at' => now(), 'updated_at' => now()],
            ['min_ipk' => 0.00, 'max_ipk' => 1.99, 'max_sks' => 16, 'keterangan' => 'IPK < 2.00', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}