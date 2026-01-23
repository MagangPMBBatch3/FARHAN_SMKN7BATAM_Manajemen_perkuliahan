<?php 
namespace App\GraphQL\Mahasiswa\Queries;

use App\Models\Mahasiswa\Mahasiswa;
use App\Models\Nilai\Nilai;
class MahasiswaQuery {
    // public function byJurusan($rootValue, array $args)
    // {
    //     return Mahasiswa::where('jurusan_id', $args['jurusan_id'])
    //         ->orderBy('nama_lengkap')
    //         ->get();
    // }
    public function byUserId($rootValue, array $args)
    {
        return Mahasiswa::with(['jurusan.fakultas'])
            ->where('user_id', $args['user_id'])
            ->first();
    }
    public function mahasiswaProfile()
{
    return auth()->user()->mahasiswa;
}

public function nilaiByMahasiswa($_, array $args)
{
    return Nilai::whereHas('krsDetail.krs', function($q) use ($args) {
        $q->where('mahasiswa_id', $args['mahasiswa_id']);
    })
    ->with([
        'krsDetail.krs.semester',
        'krsDetail.kelas.dosen',
        'krsDetail.mataKuliah'
    ])
    ->get();
}
    public function byJurusan($root, array $args)
    {
        return \App\Models\Mahasiswa\Mahasiswa::where('jurusan_id', $args['jurusan_id'])
            ->orderBy('nim')
            ->get();
    }
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