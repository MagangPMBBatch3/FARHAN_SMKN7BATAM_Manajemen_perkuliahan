<?php
namespace App\GraphQL\BobotNilai\Queries;

use App\Models\BobotNilai\BobotNilai;

class BobotNilaiQuery
{
    public function all($_, array $args)
    {
        $query = BobotNilai::query()->with(['mataKuliah', 'semester']);
        
        if (!empty($args['search'])) {
            $query->whereHas('mataKuliah', function($q) use ($args) {
                $q->where('nama_mk', 'like', '%' . $args['search'] . '%')
                  ->orWhere('kode_mk', 'like', '%' . $args['search'] . '%');
            });
        }
        
        if (!empty($args['mata_kuliah_id'])) {
            $query->where('mata_kuliah_id', $args['mata_kuliah_id']);
        }
        
        if (!empty($args['semester_id'])) {
            $query->where('semester_id', $args['semester_id']);
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
        $query = BobotNilai::onlyTrashed()->with(['mataKuliah', 'semester']);
        
        if (!empty($args['search'])) {
            $query->whereHas('mataKuliah', function($q) use ($args) {
                $q->where('nama_mk', 'like', '%' . $args['search'] . '%');
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
}