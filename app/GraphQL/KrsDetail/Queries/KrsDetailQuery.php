<?php

namespace App\GraphQL\KrsDetail\Queries;

use App\Models\KrsDetail\KrsDetail;
use App\Models\Krs\Krs;

class KrsDetailQuery
{
    /**
     * Get KRS Detail by Kelas ID
     */
    public function byKelas($_, array $args)
    {
        $kelasId = $args['kelas_id'];

        return KrsDetail::where('kelas_id', $kelasId)
            ->with([
                'krs.mahasiswa:id,nim,nama_lengkap',
                'kelas:id,kode_kelas,nama_kelas',
                'mataKuliah:id,nama_mk,kode_mk'
            ])
            ->get()
            ->sortBy('krs.mahasiswa.nim');
    }

    /**
     * Get KRS Detail by Mahasiswa ID
     */
    public function byMahasiswa($_, array $args)
    {
        $mahasiswaId = $args['mahasiswa_id'];

        // Get all KRS IDs for this mahasiswa
        $krsIds = Krs::where('mahasiswa_id', $mahasiswaId)
            ->pluck('id')
            ->toArray();

        return KrsDetail::whereIn('krs_id', $krsIds)
            ->with([
                'krs.mahasiswa:id,nim,nama_lengkap',
                'krs.semester',
                'kelas:id,kode_kelas,nama_kelas',
                'kelas.dosen',
                'kelas.jadwalKuliah.ruangan',
                'mataKuliah:id,nama_mk,kode_mk,sks',
                'nilai'
            ])
            ->get();
    }

    /**
     * Get all KRS Detail with pagination
     */
    public function all($rootValue, array $args)
    {
        $query = KrsDetail::with([
            'krs.mahasiswa',
            'krs.semester',
            'kelas.dosen',
            'mataKuliah',
            'nilai'
        ]);

        if (!empty($args['search'])) {
            $search = $args['search'];
            $query->whereHas('mataKuliah', function($q) use ($search) {
                $q->where('nama_mk', 'like', "%{$search}%")
                  ->orWhere('kode_mk', 'like', "%{$search}%");
            })
            ->orWhereHas('krs.mahasiswa', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        return $query->paginate(
            $args['first'] ?? 10,
            ['*'],
            'page',
            $args['page'] ?? 1
        );
    }

    /**
     * Get KRS Detail dengan filter lengkap
     */
    public function byMahasiswaAndSemester($rootValue, array $args)
    {
        $query = KrsDetail::with([
            'krs.semester',
            'kelas.dosen',
            'kelas.jadwalKuliah.ruangan',
            'mataKuliah',
            'nilai'
        ])
        ->whereHas('krs', function($q) use ($args) {
            $q->where('mahasiswa_id', $args['mahasiswa_id']);
            
            if (!empty($args['semester_id'])) {
                $q->where('semester_id', $args['semester_id']);
            }
        });

        return $query->get();
    }
}