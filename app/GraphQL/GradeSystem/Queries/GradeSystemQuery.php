<?php

namespace App\GraphQL\GradeSystem\Queries;

use App\Models\GradeSystem\GradeSystem;

class GradeSystemQuery
{
    public function all($_, array $args)
    {
        $query = GradeSystem::query();
        
        if (!empty($args['search'])) {
            $query->where(function($q) use ($args) {
                $q->where('grade', 'like', '%' . $args['search'] . '%')
                  ->orWhere('keterangan', 'like', '%' . $args['search'] . '%');
            });
        }
        
        if (!empty($args['status_kelulusan'])) {
            $query->where('status_kelulusan', $args['status_kelulusan']);
        }
        
        $query->orderBy('grade_point', 'desc');
        
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
        $query = GradeSystem::onlyTrashed();
        
        if (!empty($args['search'])) {
            $query->where('grade', 'like', '%' . $args['search'] . '%');
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
    
    public function getByScore($_, array $args): ?GradeSystem
    {
        return GradeSystem::getGradeByScore($args['score']);
    }
    
    public function aktif($_, array $args): array
    {
        return GradeSystem::aktif()->orderBy('grade_point', 'desc')->get()->toArray();
    }
}