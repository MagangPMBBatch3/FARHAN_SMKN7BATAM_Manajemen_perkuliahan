<?php 
namespace App\GraphQL\Jurusan\Queries;

use App\Models\Jurusan\Jurusan;

class JurusanQuery {
    public function byFakultas($rootValue, array $args)
    {
        return Jurusan::where('fakultas_id', $args['fakultas_id'])
            ->orderBy('nama_jurusan')
            ->get();
    }
    public function byFakultas($root, array $args)
    {
        return \App\Models\Jurusan\Jurusan::where('fakultas_id', $args['fakultas_id'])
            ->orderBy('nama_jurusan')
            ->get();
    }
    public function all($_, array $args)
{
    $query = Jurusan::with('fakultas'); // EAGER LOAD!
    if (!empty($args['search'])) {
        $query->where('nama_jurusan', 'like', '%' . $args['search'] . '%');
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
    $query = Jurusan::onlyTrashed()->with('fakultas'); // EAGER LOAD!
    if (!empty($args['search'])) {
        $query->where('nama_jurusan', 'like', '%' . $args['search'] . '%');
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