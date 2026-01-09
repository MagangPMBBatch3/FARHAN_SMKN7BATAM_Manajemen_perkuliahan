<?php
namespace App\GraphQL\PengaturanKehadiran\Queries;

use App\Models\PengaturanKehadiran\PengaturanKehadiran;

class PengaturanKehadiranQuery
{
    public function all($_, array $args)
    {
        $query = PengaturanKehadiran::query()->with(['kelas']);
        
        if (!empty($args['search'])) {
            $query->whereHas('kelas', function($q) use ($args) {
                $q->where('nama_kelas', 'like', '%' . $args['search'] . '%')
                  ->orWhere('kode_kelas', 'like', '%' . $args['search'] . '%');
            });
        }
        
        if (!empty($args['kelas_id'])) {
            $query->where('kelas_id', $args['kelas_id']);
        }
        
        if (isset($args['aktif'])) {
            $query->where('aktif', $args['aktif']);
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
    
    public function allArsip($_, array $args)
    {
        $query = PengaturanKehadiran::onlyTrashed()->with(['kelas']);
        
        if (!empty($args['search'])) {
            $query->whereHas('kelas', function($q) use ($args) {
                $q->where('nama_kelas', 'like', '%' . $args['search'] . '%');
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
    
    public function byKelas($_, array $args): ?PengaturanKehadiran
    {
        return PengaturanKehadiran::byKelas($args['kelas_id'])->first();
    }
    
    public function aktif($_, array $args): array
    {
        return PengaturanKehadiran::aktif()
            ->with(['kelas'])
            ->get()
            ->toArray();
    }
}