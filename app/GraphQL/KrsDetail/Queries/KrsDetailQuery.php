<?php 
namespace App\GraphQL\KrsDetail\Queries;

use App\Models\KrsDetail\KrsDetail;

class KrsDetailQuery {
    public function all($_, array $args)
    {
        $query = KrsDetail::query();
        if (!empty($args['search'])) {
            $query->where('nama_mk', 'like', '%' . $args['search'] . '%');
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
    public function byMahasiswa($root, array $args)
{
    return \App\Models\KrsDetail\KrsDetail::whereHas('krs', function($query) use ($args) {
        $query->where('mahasiswa_id', $args['mahasiswa_id']);
    })->with(['mataKuliah', 'krs.mahasiswa'])->get();
}
}