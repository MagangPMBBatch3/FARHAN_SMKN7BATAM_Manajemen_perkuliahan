<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PertemuanSeeder extends Seeder
{
    public function run(): void
    {
        // Generate pertemuan untuk kelas TI101-A-2024 (16 pertemuan)
        $startDate = Carbon::parse('2024-09-02');

        for ($i = 1; $i <= 16; $i++) {
            $tanggalPertemuan = $startDate->copy()->addWeeks($i - 1);

            // Skip jika hari libur (contoh: skip tanggal 17 Agustus)
            if ($tanggalPertemuan->format('m-d') == '08-17') {
                $tanggalPertemuan->addWeek();
            }

            $status = $i <= 8 ? 'Selesai' : 'Dijadwalkan';
            $materi = $this->getMateri($i);

            DB::table('pertemuan')->insert([
                'kelas_id' => 1, // TI101-A
                'pertemuan_ke' => $i,
                'tanggal' => $tanggalPertemuan->format('Y-m-d'),
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '10:30:00',
                'materi' => $materi,
                'metode' => $i == 10 ? 'Daring' : 'Tatap Muka',
                'ruangan_id' => 1,
                'status_pertemuan' => $status,
                'link_daring' => $i == 10 ? 'https://zoom.us/j/123456789' : null,
                'catatan' => $i == 8 ? 'Ujian Tengah Semester' : null,
                'created_by' => 2, // Dosen 1
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Generate pertemuan untuk kelas TI102-A-2024 (8 pertemuan pertama)
        $startDate2 = Carbon::parse('2024-09-03');

        for ($i = 1; $i <= 8; $i++) {
            $tanggalPertemuan = $startDate2->copy()->addWeeks($i - 1);

            DB::table('pertemuan')->insert([
                'kelas_id' => 2, // TI102-A
                'pertemuan_ke' => $i,
                'tanggal' => $tanggalPertemuan->format('Y-m-d'),
                'waktu_mulai' => '10:45:00',
                'waktu_selesai' => '13:15:00',
                'materi' => 'Algoritma dan Pemrograman - Pertemuan ' . $i,
                'metode' => 'Tatap Muka',
                'ruangan_id' => 4,
                'status_pertemuan' => 'Selesai',
                'link_daring' => null,
                'catatan' => $i == 8 ? 'UTS - Ujian Praktikum' : null,
                'created_by' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function getMateri($pertemuan)
    {
        $materiList = [
            1 => 'Pengenalan Teknologi Informasi',
            2 => 'Komponen Sistem Komputer',
            3 => 'Sistem Operasi',
            4 => 'Jaringan Komputer Dasar',
            5 => 'Database Fundamental',
            6 => 'Pemrograman Dasar',
            7 => 'Web Technology',
            8 => 'UTS - Ujian Tengah Semester',
            9 => 'Keamanan Informasi',
            10 => 'Cloud Computing',
            11 => 'Big Data Introduction',
            12 => 'Internet of Things',
            13 => 'Mobile Technology',
            14 => 'AI & Machine Learning Intro',
            15 => 'Review & Diskusi',
            16 => 'UAS - Ujian Akhir Semester',
        ];

        return $materiList[$pertemuan] ?? 'Materi Pertemuan ' . $pertemuan;
    }
}