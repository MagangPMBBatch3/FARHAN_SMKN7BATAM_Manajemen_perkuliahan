<?php 
namespace App\GraphQL\JadwalKuliah\Queries;

use App\Models\JadwalKuliah\JadwalKuliah;

class JadwalKuliahQuery {
    
    /**
     * Get all jadwal dengan pagination (untuk admin)
     */
    public function all($_, array $args)
    {
        $query = JadwalKuliah::query()->with(['kelas.mataKuliah', 'kelas.dosen', 'ruangan']);
        
        if (!empty($args['search'])) {
            $query->where(function($q) use ($args) {
                $q->where('hari', 'like', '%' . $args['search'] . '%')
                  ->orWhereHas('kelas', function($kq) use ($args) {
                      $kq->where('nama_kelas', 'like', '%' . $args['search'] . '%')
                         ->orWhere('kode_kelas', 'like', '%' . $args['search'] . '%');
                  })
                  ->orWhereHas('kelas.mataKuliah', function($mkq) use ($args) {
                      $mkq->where('nama_mk', 'like', '%' . $args['search'] . '%');
                  })
                  ->orWhereHas('ruangan', function($rq) use ($args) {
                      $rq->where('nama_ruangan', 'like', '%' . $args['search'] . '%');
                  });
            });
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
     * Get jadwal arsip (untuk admin)
     */
    public function allArsip($_, array $args)
    {
        $query = JadwalKuliah::onlyTrashed()->with(['kelas.mataKuliah', 'ruangan']);
        
        if (!empty($args['search'])) {
            $query->where('hari', 'like', '%' . $args['search'] . '%')
                  ->orWhereHas('kelas', function($kq) use ($args) {
                      $kq->where('nama_kelas', 'like', '%' . $args['search'] . '%');
                  });
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
     * Get jadwal untuk dosen yang sedang login (BARU - untuk currentDosenJadwal)
     * READ-ONLY - dosen tidak bisa edit/hapus jadwal
     */
    public function __invoke($rootValue, array $args)
    {
        $user = auth()->user();
        
        if (!$user || !$user->dosen) {
            return [
                'data' => [],
                'paginatorInfo' => [
                    'currentPage' => 1,
                    'lastPage' => 1,
                    'total' => 0,
                    'hasMorePages' => false,
                    'perPage' => $args['first'] ?? 10
                ]
            ];
        }
        
        $dosenId = $user->dosen->id;
        
        $query = JadwalKuliah::whereHas('kelas', function($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })
        ->with(['kelas.mataKuliah', 'kelas.semester', 'ruangan'])
        ->whereNull('deleted_at');
        
        // Filter berdasarkan hari
        if (isset($args['hari']) && $args['hari'] !== '') {
            $query->where('hari', $args['hari']);
        }
        
        // Search
        if (isset($args['search']) && $args['search'] !== '') {
            $search = $args['search'];
            $query->where(function($q) use ($search) {
                $q->whereHas('kelas.mataKuliah', function($mq) use ($search) {
                    $mq->where('nama_mk', 'LIKE', "%{$search}%")
                       ->orWhere('kode_mk', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('kelas', function($kq) use ($search) {
                    $kq->where('kode_kelas', 'LIKE', "%{$search}%")
                       ->orWhere('nama_kelas', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('ruangan', function($rq) use ($search) {
                    $rq->where('nama_ruangan', 'LIKE', "%{$search}%")
                       ->orWhere('kode_ruangan', 'LIKE', "%{$search}%");
                })
                ->orWhere('hari', 'LIKE', "%{$search}%");
            });
        }
        
        // Order by hari dan jam
        $hariOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $query->orderByRaw("FIELD(hari, '" . implode("','", $hariOrder) . "')")
              ->orderBy('jam_mulai', 'asc');
        
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
     * Get jadwal by multiple kelas IDs (untuk dashboard)
     */
    public function byMultipleKelas($_, array $args): array
    {
        $hariOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        return JadwalKuliah::whereIn('kelas_id', $args['kelas_ids'])
            ->whereNull('deleted_at')
            ->with(['kelas.mataKuliah', 'ruangan'])
            ->orderByRaw("FIELD(hari, '" . implode("','", $hariOrder) . "')")
            ->orderBy('jam_mulai', 'asc')
            ->get()
            ->toArray();
    }
}