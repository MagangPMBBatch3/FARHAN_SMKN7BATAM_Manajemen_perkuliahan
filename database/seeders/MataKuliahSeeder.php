<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mata_kuliah')->insert([
            // Teknik Informatika
            ['kode_mk' => 'TI101', 'nama_mk' => 'Pengantar Teknologi Informasi', 'jurusan_id' => 1, 'sks' => 3, 'semester_rekomendasi' => 1, 'jenis' => 'Wajib', 'deskripsi' => 'Mata kuliah pengantar mengenai dasar-dasar teknologi informasi', 'created_at' => now(), 'updated_at' => now()],
            ['kode_mk' => 'TI102', 'nama_mk' => 'Algoritma dan Pemrograman', 'jurusan_id' => 1, 'sks' => 4, 'semester_rekomendasi' => 1, 'jenis' => 'Wajib', 'deskripsi' => 'Pembelajaran algoritma dasar dan pemrograman komputer', 'created_at' => now(), 'updated_at' => now()],
            ['kode_mk' => 'TI103', 'nama_mk' => 'Matematika Diskrit', 'jurusan_id' => 1, 'sks' => 3, 'semester_rekomendasi' => 1, 'jenis' => 'Wajib', 'deskripsi' => 'Matematika untuk ilmu komputer', 'created_at' => now(), 'updated_at' => now()],
            ['kode_mk' => 'TI201', 'nama_mk' => 'Struktur Data', 'jurusan_id' => 1, 'sks' => 3, 'semester_rekomendasi' => 3, 'jenis' => 'Wajib', 'deskripsi' => 'Struktur data dan algoritma lanjutan', 'created_at' => now(), 'updated_at' => now()],
            ['kode_mk' => 'TI202', 'nama_mk' => 'Basis Data', 'jurusan_id' => 1, 'sks' => 3, 'semester_rekomendasi' => 3, 'jenis' => 'Wajib', 'deskripsi' => 'Desain dan implementasi basis data', 'created_at' => now(), 'updated_at' => now()],
            ['kode_mk' => 'TI301', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'jurusan_id' => 1, 'sks' => 3, 'semester_rekomendasi' => 5, 'jenis' => 'Wajib', 'deskripsi' => 'Metodologi pengembangan perangkat lunak', 'created_at' => now(), 'updated_at' => now()],
            ['kode_mk' => 'TI302', 'nama_mk' => 'Kecerdasan Buatan', 'jurusan_id' => 1, 'sks' => 3, 'semester_rekomendasi' => 5, 'jenis' => 'Pilihan', 'deskripsi' => 'Pengantar artificial intelligence', 'created_at' => now(), 'updated_at' => now()],

            // Sistem Informasi
            ['kode_mk' => 'SI101', 'nama_mk' => 'Pengantar Sistem Informasi', 'jurusan_id' => 2, 'sks' => 3, 'semester_rekomendasi' => 1, 'jenis' => 'Wajib', 'deskripsi' => 'Konsep dasar sistem informasi', 'created_at' => now(), 'updated_at' => now()],
            ['kode_mk' => 'SI102', 'nama_mk' => 'Pemrograman Web', 'jurusan_id' => 2, 'sks' => 3, 'semester_rekomendasi' => 2, 'jenis' => 'Wajib', 'deskripsi' => 'Pengembangan aplikasi berbasis web', 'created_at' => now(), 'updated_at' => now()],
            ['kode_mk' => 'SI201', 'nama_mk' => 'Analisis dan Perancangan Sistem', 'jurusan_id' => 2, 'sks' => 3, 'semester_rekomendasi' => 3, 'jenis' => 'Wajib', 'deskripsi' => 'Metodologi analisis sistem informasi', 'created_at' => now(), 'updated_at' => now()],
            ['kode_mk' => 'SI202', 'nama_mk' => 'Manajemen Basis Data', 'jurusan_id' => 2, 'sks' => 3, 'semester_rekomendasi' => 4, 'jenis' => 'Wajib', 'deskripsi' => 'Administrasi dan optimasi basis data', 'created_at' => now(), 'updated_at' => now()],

            // Teknik Elektro
            ['kode_mk' => 'TE101', 'nama_mk' => 'Rangkaian Listrik', 'jurusan_id' => 3, 'sks' => 3, 'semester_rekomendasi' => 1, 'jenis' => 'Wajib', 'deskripsi' => 'Analisis rangkaian listrik dasar', 'created_at' => now(), 'updated_at' => now()],
            ['kode_mk' => 'TE102', 'nama_mk' => 'Elektronika Dasar', 'jurusan_id' => 3, 'sks' => 4, 'semester_rekomendasi' => 2, 'jenis' => 'Wajib', 'deskripsi' => 'Komponen dan rangkaian elektronika', 'created_at' => now(), 'updated_at' => now()],
            ['kode_mk' => 'TE201', 'nama_mk' => 'Sistem Digital', 'jurusan_id' => 3, 'sks' => 3, 'semester_rekomendasi' => 3, 'jenis' => 'Wajib', 'deskripsi' => 'Desain sistem digital', 'created_at' => now(), 'updated_at' => now()],

            // Manajemen
            ['kode_mk' => 'MN101', 'nama_mk' => 'Pengantar Manajemen', 'jurusan_id' => 4, 'sks' => 3, 'semester_rekomendasi' => 1, 'jenis' => 'Wajib', 'deskripsi' => 'Konsep dasar ilmu manajemen', 'created_at' => now(), 'updated_at' => now()],
            ['kode_mk' => 'MN102', 'nama_mk' => 'Manajemen Sumber Daya Manusia', 'jurusan_id' => 4, 'sks' => 3, 'semester_rekomendasi' => 3, 'jenis' => 'Wajib', 'deskripsi' => 'Pengelolaan SDM dalam organisasi', 'created_at' => now(), 'updated_at' => now()],
            ['kode_mk' => 'MN201', 'nama_mk' => 'Manajemen Pemasaran', 'jurusan_id' => 4, 'sks' => 3, 'semester_rekomendasi' => 4, 'jenis' => 'Wajib', 'deskripsi' => 'Strategi dan implementasi pemasaran', 'created_at' => now(), 'updated_at' => now()],

            // Mata Kuliah Umum
            ['kode_mk' => 'MKU101', 'nama_mk' => 'Bahasa Indonesia', 'jurusan_id' => 1, 'sks' => 2, 'semester_rekomendasi' => 1, 'jenis' => 'Wajib', 'deskripsi' => 'Kemampuan berbahasa Indonesia', 'created_at' => now(), 'updated_at' => now()],
            ['kode_mk' => 'MKU102', 'nama_mk' => 'Pancasila', 'jurusan_id' => 1, 'sks' => 2, 'semester_rekomendasi' => 1, 'jenis' => 'Wajib', 'deskripsi' => 'Ideologi dan nilai-nilai Pancasila', 'created_at' => now(), 'updated_at' => now()],
            ['kode_mk' => 'MKU103', 'nama_mk' => 'Bahasa Inggris', 'jurusan_id' => 1, 'sks' => 2, 'semester_rekomendasi' => 2, 'jenis' => 'Wajib', 'deskripsi' => 'Kemampuan berbahasa Inggris', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}