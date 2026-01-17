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

    public function all($_, array $args)
    {
        $first = $args['first'] ?? 10;
        $page = $args['page'] ?? 1;
        $search = $args['search'] ?? null;

        $query = KrsDetail::with([
            'krs.mahasiswa',
            'kelas',
            'mataKuliah'
        ]);

        if ($search) {
            $query->whereHas('krs.mahasiswa', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            })
                ->orWhereHas('mataKuliah', function ($q) use ($search) {
                    $q->where('nama_mk', 'like', "%{$search}%")
                        ->orWhere('kode_mk', 'like', "%{$search}%");
                });
        }

        return $query->paginate($first, ['*'], 'page', $page);
    }
}