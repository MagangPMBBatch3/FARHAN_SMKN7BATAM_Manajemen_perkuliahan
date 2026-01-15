<?php 
namespace App\GraphQL\Mahasiswa\Queries;

use App\Models\Mahasiswa\Mahasiswa;

class MahasiswaQuery {
    public function byAngkatan($root, array $args)
{
    return Mahasiswa::where('angkatan', $args['angkatan'])
        ->where('deleted_at', null)
        ->with(['jurusan'])
        ->get();
}
    public function allArsip($_, array $args)
    {
        $query = Mahasiswa::onlyTrashed();
        if (!empty($args['search'])) {
            $query->where('nama_lengkap', 'like', '%' . $args['search'] . '%');
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
        $query = Mahasiswa::query();
        if (!empty($args['search'])) {
            $query->where('nama_lengkap', 'like', '%' . $args['search'] . '%');
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