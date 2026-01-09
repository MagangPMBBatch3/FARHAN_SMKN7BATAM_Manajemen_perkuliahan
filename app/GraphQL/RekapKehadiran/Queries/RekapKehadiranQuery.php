<?php

namespace App\GraphQL\RekapKehadiran\Queries;

use App\Models\RekapKehadiran\RekapKehadiran;

class RekapKehadiranQuery
{
    public function all($_, array $args)
    {
        $query = RekapKehadiran::query()->with(['mahasiswa', 'kelas', 'semester']);
        
        if (!empty($args['search'])) {
            $query->whereHas('mahasiswa', function($q) use ($args) {
                $q->where('nama_lengkap', 'like', '%' . $args['search'] . '%')
                  ->orWhere('nim', 'like', '%' . $args['search'] . '%');
            });
        }
        
        if (!empty($args['mahasiswa_id'])) {
            $query->where('mahasiswa_id', $args['mahasiswa_id']);
        }
        
        if (!empty($args['kelas_id'])) {
            $query->where('kelas_id', $args['kelas_id']);
        }
        
        if (!empty($args['semester_id'])) {
            $query->where('semester_id', $args['semester_id']);
        }
        
        if (!empty($args['status_minimal'])) {
            $query->where('status_minimal', $args['status_minimal']);
        }
        
        $query->orderBy('persentase_kehadiran', 'desc');
        
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
        $query = RekapKehadiran::onlyTrashed()->with(['mahasiswa', 'kelas', 'semester']);
        
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
    
    public function byMahasiswa($_, array $args): array
    {
        $query = RekapKehadiran::byMahasiswa($args['mahasiswa_id'])
            ->with(['kelas', 'semester']);
        
        if (!empty($args['semester_id'])) {
            $query->where('semester_id', $args['semester_id']);
        }
        
        return $query->get()->toArray();
    }
    
    public function byKelas($_, array $args): array
    {
        return RekapKehadiran::byKelas($args['kelas_id'])
            ->with(['mahasiswa'])
            ->orderBy('persentase_kehadiran', 'desc')
            ->get()
            ->toArray();
    }
    
    public function tidakMemenuhi($_, array $args): array
    {
        $query = RekapKehadiran::tidakMemenuhiMinimal()
            ->with(['mahasiswa', 'kelas', 'semester']);
        
        if (!empty($args['kelas_id'])) {
            $query->where('kelas_id', $args['kelas_id']);
        }
        
        if (!empty($args['semester_id'])) {
            $query->where('semester_id', $args['semester_id']);
        }
        
        return $query->orderBy('persentase_kehadiran', 'asc')
            ->get()
            ->toArray();
    }
}