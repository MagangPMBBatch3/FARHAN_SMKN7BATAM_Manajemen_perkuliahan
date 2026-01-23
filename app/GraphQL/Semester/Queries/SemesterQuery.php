<?php 
namespace App\GraphQL\Semester\Queries;

use App\Models\Semester\Semester;
use Illuminate\Pagination\LengthAwarePaginator; 
use Illuminate\Support\Facades\Auth;
class SemesterQuery {
    public function current($rootValue, array $args)
    {
        return Semester::orderBy('id', 'DESC')
            ->first();
    }
    public function forMahasiswa($root, array $args)
    {
        $user = Auth::user();
        
        \Log::info('User:', ['user' => $user]);
        
        if (!$user) {
            \Log::warning('No authenticated user');
            return [];
        }
        
        $mahasiswa = \App\Models\Mahasiswa\Mahasiswa::where('user_id', $user->id)
            ->orWhere('email_pribadi', $user->email)
            ->first();
        
        if (!$mahasiswa) {
            \Log::warning('Mahasiswa not found for user', ['user_id' => $user->id]);
            return [];
        }
        
        $mahasiswaId = $mahasiswa->id;
        \Log::info('Mahasiswa ID:', ['id' => $mahasiswaId]);
        
        return Semester::query()
            ->whereHas('kelas.krsDetail', function ($q) use ($mahasiswaId) {
                $q->whereHas('krs', function ($q2) use ($mahasiswaId) {
                    $q2->where('mahasiswa_id', $mahasiswaId);
                });
            })
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->orderBy('nama_semester', 'desc')
            ->get();
    }
    public function all($_, array $args)
    {
        $query = Semester::query();
        if (!empty($args['search'])) {
            $query->where('nama_semester', 'like', '%' . $args['search'] . '%');
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
        $query = Semester::query();
        if (!empty($args['search'])) {
            $query->where('nama_semester', 'like', '%' . $args['search'] . '%');
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