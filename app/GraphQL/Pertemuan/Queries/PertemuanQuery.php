<?php
namespace App\GraphQL\Pertemuan\Queries;

use App\Models\Pertemuan\Pertemuan;

class PertemuanQuery
{
    public function all($_, array $args)
    {
        $query = Pertemuan::query()->with(['kelas', 'ruangan', 'createdBy']);
        
        if (!empty($args['search'])) {
            $query->where(function($q) use ($args) {
                $q->where('materi', 'like', '%' . $args['search'] . '%')
                  ->orWhereHas('kelas', function($kq) use ($args) {
                      $kq->where('nama_kelas', 'like', '%' . $args['search'] . '%');
                  });
            });
        }
        
        if (!empty($args['kelas_id'])) {
            $query->where('kelas_id', $args['kelas_id']);
        }
        
        if (!empty($args['status_pertemuan'])) {
            $query->where('status_pertemuan', $args['status_pertemuan']);
        }
        
        if (!empty($args['metode'])) {
            $query->where('metode', $args['metode']);
        }
        
        if (!empty($args['tanggal'])) {
            $query->whereDate('tanggal', $args['tanggal']);
        }
        
        $query->orderBy('tanggal', 'desc')->orderBy('waktu_mulai', 'asc');
        
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
        $query = Pertemuan::onlyTrashed()->with(['kelas', 'ruangan']);
        
        if (!empty($args['search'])) {
            $query->where('materi', 'like', '%' . $args['search'] . '%');
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
    
    public function byKelas($_, array $args): array
    {
        return Pertemuan::byKelas($args['kelas_id'])
            ->orderBy('pertemuan_ke', 'asc')
            ->get()
            ->toArray();
    }
    
    public function hariIni($_, array $args): array
    {
        return Pertemuan::hariIni()
            ->with(['kelas', 'ruangan'])
            ->orderBy('waktu_mulai', 'asc')
            ->get()
            ->toArray();
    }
    
    public function mendatang($_, array $args): array
    {
        $query = Pertemuan::mendatang()->with(['kelas', 'ruangan']);
        
        if (!empty($args['kelas_id'])) {
            $query->where('kelas_id', $args['kelas_id']);
        }
        
        return $query->orderBy('tanggal', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->get()
            ->toArray();
    }
    
    public function getDurasiMenit($pertemuan): int
    {
        return $pertemuan->getDurasiMenit();
    }
    
    public function getPersentaseKehadiran($pertemuan): float
    {
        return $pertemuan->getPersentaseKehadiran();
    }
    
    public function getStatistikKehadiran($pertemuan): array
    {
        return $pertemuan->getStatistikKehadiran();
    }
}