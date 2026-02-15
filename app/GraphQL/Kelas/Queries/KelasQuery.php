<?php 
namespace App\GraphQL\Kelas\Queries;

use App\Models\Kelas\Kelas;

class KelasQuery {
    public function allArsip($_, array $args)
    {
        $query = Kelas::onlyTrashed();
        if (!empty($args['search'])) {
            $query->where('nama_kelas', 'like', '%' . $args['search'] . '%');
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
        $query = Kelas::query();
        if (!empty($args['search'])) {
            $query->where('nama_kelas', 'like', '%' . $args['search'] . '%');
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
    public function __invoke($rootValue, array $args)
    {
        $user = auth()->user();
        
        if (!$user || !$user->dosen) {
            return [];
        }
        
        $dosenId = $user->dosen->id;
        
        return Kelas::where('dosen_id', $dosenId)
            ->with(['mataKuliah', 'semester', 'jurusan'])
            ->orderBy('semester_id', 'desc')
            ->get();
    }
}