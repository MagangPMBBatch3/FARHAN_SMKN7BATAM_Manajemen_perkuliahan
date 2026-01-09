<?php

namespace App\GraphQL\Kehadiran\Queries;

use App\Models\Kehadiran\Kehadiran;

class KehadiranQuery
{
    public function all($_, array $args)
    {
        $query = Kehadiran::query()->with(['pertemuan', 'mahasiswa', 'krsDetail']);
        
        if (!empty($args['search'])) {
            $query->whereHas('mahasiswa', function($q) use ($args) {
                $q->where('nama_lengkap', 'like', '%' . $args['search'] . '%')
                  ->orWhere('nim', 'like', '%' . $args['search'] . '%');
            });
        }
        
        if (!empty($args['pertemuan_id'])) {
            $query->where('pertemuan_id', $args['pertemuan_id']);
        }
        
        if (!empty($args['mahasiswa_id'])) {
            $query->where('mahasiswa_id', $args['mahasiswa_id']);
        }
        
        if (!empty($args['status_kehadiran'])) {
            $query->where('status_kehadiran', $args['status_kehadiran']);
        }
        
        $query->orderBy('created_at', 'desc');
        
        $perPage = $args['first'] ?? 10;
        $page = $args['page'] ?? 1;
        
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);
        
        return [
            'data' => $paginator->items(),
            'paginatorInfo' => [
                'hasMorePages' => $paginator->hasMorePages(),
                'currentPage' => $paginator->currentPage(),
                'lastPage' => $paginator->lastPage(),
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }
    
    public function allArsip($_, array $args)
    {
        $query = Kehadiran::onlyTrashed()->with(['pertemuan', 'mahasiswa']);
        
        if (!empty($args['search'])) {
            $query->whereHas('mahasiswa', function($q) use ($args) {
                $q->where('nama_lengkap', 'like', '%' . $args['search'] . '%');
            });
        }
        
        $perPage = $args['first'] ?? 10;
        $page = $args['page'] ?? 1;
        
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);
        
        return [
            'data' => $paginator->items(),
            'paginatorInfo' => [
                'hasMorePages' => $paginator->hasMorePages(),
                'currentPage' => $paginator->currentPage(),
                'lastPage' => $paginator->lastPage(),
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }
    
    public function byPertemuan($_, array $args): array
    {
        return Kehadiran::byPertemuan($args['pertemuan_id'])
            ->with(['mahasiswa'])
            ->get()
            ->toArray();
    }
    
    public function byMahasiswa($_, array $args): array
    {
        $query = Kehadiran::byMahasiswa($args['mahasiswa_id'])
            ->with(['pertemuan', 'pertemuan.kelas']);
        
        if (!empty($args['kelas_id'])) {
            $query->byKelas($args['kelas_id']);
        }
        
        return $query->orderBy('created_at', 'desc')->get()->toArray();
    }
    
    public function totalKehadiranMahasiswa($_, array $args): array
    {
        $data = Kehadiran::getTotalKehadiranMahasiswa(
            $args['mahasiswa_id'],
            $args['kelas_id']
        );
        
        $persentase = $data->total_pertemuan > 0
            ? (($data->total_hadir + $data->total_izin + $data->total_sakit) / $data->total_pertemuan) * 100
            : 0;
        
        return [
            'total_pertemuan' => $data->total_pertemuan ?? 0,
            'total_hadir' => $data->total_hadir ?? 0,
            'total_izin' => $data->total_izin ?? 0,
            'total_sakit' => $data->total_sakit ?? 0,
            'total_alpa' => $data->total_alpa ?? 0,
            'persentase_kehadiran' => round($persentase, 2),
        ];
    }
}
