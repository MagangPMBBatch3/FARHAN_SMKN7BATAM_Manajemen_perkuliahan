<?php 
namespace App\GraphQL\Dosen\Queries;

use App\Models\Dosen\Dosen;
use App\Models\Kelas\Kelas;

class DosenQuery {
    /**
     * Get all dosen dengan pagination (untuk admin)
     */
    public function all($_, array $args)
    {
        $query = Dosen::query();
        if (!empty($args['search'])) {
            $query->where('nama_lengkap', 'like', '%' . $args['search'] . '%')
                  ->orWhere('nidn', 'like', '%' . $args['search'] . '%')
                  ->orWhere('nip', 'like', '%' . $args['search'] . '%');
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
    
    /**
     * Get dosen arsip (untuk admin)
     */
    public function allArsip($_, array $args)
    {
        $query = Dosen::onlyTrashed();
        if (!empty($args['search'])) {
            $query->where('nama_lengkap', 'like', '%' . $args['search'] . '%')
                  ->orWhere('nidn', 'like', '%' . $args['search'] . '%');
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
    
    /**
     * Get dosen yang sedang login (untuk currentDosen query)
     */
    public function __invoke($rootValue, array $args)
    {
        $user = auth()->user();
        
        if (!$user || !$user->dosen) {
            return null;
        }
        
        return $user->dosen;
    }
    
    /**
     * Get kelas yang diampu oleh dosen yang sedang login
     * FIXED: Menggunakan 'jurusan' bukan 'programStudi'
     */
    public function getKelas($rootValue, array $args)
    {
        $user = auth()->user();
        
        if (!$user || !$user->dosen) {
            return [];
        }
        
        $dosenId = $user->dosen->id;
        
        // Eager load relationships yang ada di model Kelas
        // Sesuaikan dengan relationship yang tersedia
        return Kelas::where('dosen_id', $dosenId)
            ->with(['mataKuliah', 'semester', 'dosen']) // Gunakan 'jurusan' bukan 'programStudi'
            ->whereNull('deleted_at')
            ->orderBy('semester_id', 'desc')
            ->get();
    }
}