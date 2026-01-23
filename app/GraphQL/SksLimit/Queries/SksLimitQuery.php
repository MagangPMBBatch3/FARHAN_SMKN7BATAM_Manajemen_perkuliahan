<?php 
namespace App\GraphQL\SksLimit\Queries;

use App\Models\SksLimit\SksLimit;

class SksLimitQuery {
    public function byIpk($rootValue, array $args)
    {
        $ipk = $args['ipk'];

        return SksLimit::where('min_ipk', '<=', $ipk)
            ->where('max_ipk', '>=', $ipk)
            ->first();
    }
    public function allArsip($_, array $args)
    {
        $query = SksLimit::onlyTrashed();
        if (!empty($args['search'])) {
            $query->where('max_sks', 'like', '%' . $args['search'] . '%');
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
        $query = SksLimit::query();
        if (!empty($args['search'])) {
            $query->where('max_sks', 'like', '%' . $args['search'] . '%');
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