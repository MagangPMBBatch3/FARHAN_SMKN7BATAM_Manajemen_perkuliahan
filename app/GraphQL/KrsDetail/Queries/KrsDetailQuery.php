<?php

namespace App\GraphQL\KrsDetail\Queries;

use App\Models\KrsDetail\KrsDetail;
use App\Models\krs\Krs;

class KrsDetailQuery
{
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

    public function byMahasiswa($_, array $args)
    {
        $mahasiswaId = $args['mahasiswa_id'];

        $krsIds = Krs::where('mahasiswa_id', $mahasiswaId)
            ->pluck('id')
            ->toArray();

        return KrsDetail::whereIn('krs_id', $krsIds)
            ->with([
                'krs.mahasiswa:id,nim,nama_lengkap',
                'kelas:id,kode_kelas,nama_kelas',
                'mataKuliah:id,nama_mk,kode_mk',
                'nilai'
            ])
            ->get();
    }

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
     * Get KRS Detail berdasarkan mahasiswa
     */
    public function byMahasiswa($rootValue, array $args)
    {
        return KrsDetail::with([
            'krs.semester',
            'kelas.dosen',
            'kelas.jadwalKuliah.ruangan',
            'mataKuliah',
            'nilai'
        ])
        ->whereHas('krs', function($q) use ($args) {
            $q->where('mahasiswa_id', $args['mahasiswa_id']);
        })
        ->ge
}