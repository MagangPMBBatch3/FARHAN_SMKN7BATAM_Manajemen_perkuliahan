<?php 
namespace App\GraphQL\Khs\Queries;

use App\Models\Khs\Khs;

class KhsQuery {
    public function allArsip($_, array $args)
    {
        $query = Khs::onlyTrashed();
        if (!empty($args['search'])) {
            $query->where('mahasiswa_id', 'like', '%' . $args['search'] . '%');
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
    public function all($_, array $args)
    {
        $query = Khs::query();
        if (!empty($args['search'])) {
            $query->where('mahasiswa_id', 'like', '%' . $args['search'] . '%');
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
    public function byMahasiswa($rootValue, array $args)
    {
        return Khs::with([
            'semester',
            'mahasiswa.jurusan'
        ])
        ->where('mahasiswa_id', $args['mahasiswa_id'])
        ->whereNull('deleted_at')
        ->orderBy('semester_id', 'DESC')
        ->get();
    }
    
}