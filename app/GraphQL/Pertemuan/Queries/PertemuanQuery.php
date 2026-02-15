<?php
namespace App\GraphQL\Pertemuan\Queries;

use App\Models\Pertemuan\Pertemuan;
use Illuminate\Support\Facades\Auth;

class PertemuanQuery
{
    /**
     * Get pertemuan untuk mahasiswa (existing)
     */
    public function forMahasiswa($root, array $args)
    {
        $user = Auth::user();
        
        \Log::info('User for pertemuan:', ['user' => $user]);
        
        $perPage = $args['first'] ?? 10;
        $page = $args['page'] ?? 1;
        
        if (!$user) {
            \Log::warning('No authenticated user for pertemuan');
            
            return [
                'data' => [],
                'paginatorInfo' => [
                    'hasMorePages' => false,
                    'currentPage' => $page,
                    'lastPage' => 1,
                    'perPage' => $perPage,
                    'total' => 0,
                ],
            ];
        }
        
        $mahasiswa = \App\Models\Mahasiswa\Mahasiswa::where('user_id', $user->id)
            ->orWhere('email_pribadi', $user->email)
            ->first();
        
        if (!$mahasiswa) {
            \Log::warning('Mahasiswa not found for user in pertemuan', ['user_id' => $user->id]);
            
            return [
                'data' => [],
                'paginatorInfo' => [
                    'hasMorePages' => false,
                    'currentPage' => $page,
                    'lastPage' => 1,
                    'perPage' => $perPage,
                    'total' => 0,
                ],
            ];
        }
        
        $mahasiswaId = $mahasiswa->id;
        \Log::info('Mahasiswa ID for pertemuan:', ['id' => $mahasiswaId]);
        
        $kelasIds = \DB::table('krs_detail')
            ->join('krs', 'krs_detail.krs_id', '=', 'krs.id')
            ->where('krs.mahasiswa_id', $mahasiswaId)
            ->where('krs.status', 'Disetujui')
            ->whereNull('krs_detail.deleted_at')
            ->whereNull('krs.deleted_at')
            ->pluck('krs_detail.kelas_id')
            ->toArray();
        
        \Log::info('Kelas IDs:', ['ids' => $kelasIds]);
        
        if (empty($kelasIds)) {
            \Log::warning('No kelas found for mahasiswa', ['mahasiswa_id' => $mahasiswaId]);
            
            return [
                'data' => [],
                'paginatorInfo' => [
                    'hasMorePages' => false,
                    'currentPage' => $page,
                    'lastPage' => 1,
                    'perPage' => $perPage,
                    'total' => 0,
                ],
            ];
        }
        
        $query = Pertemuan::query()
            ->whereIn('kelas_id', $kelasIds)
            ->with([
                'kelas.mataKuliah', 
                'kelas.dosen', 
                'kelas.semester', 
                'ruangan'
            ])
            ->whereNull('deleted_at')
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu_mulai', 'desc');
        
        if (!empty($args['search'])) {
            $search = $args['search'];
            $query->where(function ($q) use ($search) {
                $q->where('materi', 'like', "%{$search}%")
                  ->orWhereHas('kelas.mataKuliah', function ($q2) use ($search) {
                      $q2->where('nama_mk', 'like', "%{$search}%")
                         ->orWhere('kode_mk', 'like', "%{$search}%");
                  });
            });
        }
        
        if (!empty($args['semester_id'])) {
            $query->whereHas('kelas', function ($q) use ($args) {
                $q->where('semester_id', $args['semester_id']);
            });
        }
        
        if (!empty($args['status_pertemuan'])) {
            $query->where('status_pertemuan', $args['status_pertemuan']);
        }
        
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);
        
        \Log::info('Pertemuan result:', ['total' => $paginator->total()]);
        
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
     * Get all pertemuan dengan pagination (untuk admin)
     */
    public function all($_, array $args)
    {
        $query = Pertemuan::query()->with(['kelas.mataKuliah', 'kelas.dosen', 'ruangan']);
        
        if (!empty($args['search'])) {
            $query->where(function($q) use ($args) {
                $q->where('materi', 'like', '%' . $args['search'] . '%')
                  ->orWhereHas('kelas', function($kq) use ($args) {
                      $kq->where('nama_kelas', 'like', '%' . $args['search'] . '%')
                         ->orWhere('kode_kelas', 'like', '%' . $args['search'] . '%');
                  })
                  ->orWhereHas('kelas.mataKuliah', function($mkq) use ($args) {
                      $mkq->where('nama_mk', 'like', '%' . $args['search'] . '%');
                  });
            });
        }
        
        if (!empty($args['kelas_id'])) {
            $query->where('kelas_id', $args['kelas_id']);
        }
        
        if (!empty($args['status_pertemuan'])) {
            $query->where('status_pertemuan', $args['status_pertemuan']);
        }
        
        if (!empty($args['metode'])) {
            $query->where('metode', $args['metode']);
        }
        
        if (!empty($args['tanggal'])) {
            $query->whereDate('tanggal', $args['tanggal']);
        }
        
        $query->orderBy('tanggal', 'desc')->orderBy('waktu_mulai', 'asc');
        
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
     * Get pertemuan arsip (untuk admin)
     */
    public function allArsip($_, array $args)
    {
        $query = Pertemuan::onlyTrashed()->with(['kelas.mataKuliah', 'ruangan']);
        
        if (!empty($args['search'])) {
            $query->where('materi', 'like', '%' . $args['search'] . '%')
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
     * Get pertemuan untuk dosen yang sedang login (BARU - untuk currentDosenPertemuan)
     */
    public function __invoke($rootValue, array $args)
    {
        $user = auth()->user();
        
        if (!$user || !$user->dosen) {
            return $this->emptyPaginator($args);
        }
        
        $dosenId = $user->dosen->id;
        
        $query = Pertemuan::whereHas('kelas', function($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })
        ->with(['kelas.mataKuliah', 'kelas.semester', 'ruangan'])
        ->whereNull('deleted_at');
        
        // Filter berdasarkan kelas
        if (isset($args['kelas_id'])) {
            $query->where('kelas_id', $args['kelas_id']);
        }
        
        // Filter berdasarkan status
        if (isset($args['status_pertemuan'])) {
            $query->where('status_pertemuan', $args['status_pertemuan']);
        }
        
        // Search
        if (isset($args['search']) && $args['search'] !== '') {
            $search = $args['search'];
            $query->where(function($q) use ($search) {
                $q->whereHas('kelas', function($kq) use ($search) {
                    $kq->where('kode_kelas', 'LIKE', "%{$search}%")
                       ->orWhere('nama_kelas', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('kelas.mataKuliah', function($mq) use ($search) {
                    $mq->where('nama_mk', 'LIKE', "%{$search}%")
                       ->orWhere('kode_mk', 'LIKE', "%{$search}%");
                })
                ->orWhere('materi', 'LIKE', "%{$search}%");
            });
        }
        
        $query->orderBy('tanggal', 'desc')
              ->orderBy('pertemuan_ke', 'desc');
        
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
     * Get pertemuan arsip untuk dosen yang sedang login (BARU)
     */
    public function allArsipDosen($_, array $args)
    {
        $user = auth()->user();
        
        if (!$user || !$user->dosen) {
            return $this->emptyPaginator($args);
        }
        
        $dosenId = $user->dosen->id;
        
        $query = Pertemuan::whereHas('kelas', function($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })
        ->with(['kelas.mataKuliah', 'ruangan'])
        ->onlyTrashed();
        
        // Search
        if (isset($args['search']) && $args['search'] !== '') {
            $search = $args['search'];
            $query->where(function($q) use ($search) {
                $q->whereHas('kelas', function($kq) use ($search) {
                    $kq->where('kode_kelas', 'LIKE', "%{$search}%")
                       ->orWhere('nama_kelas', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('kelas.mataKuliah', function($mq) use ($search) {
                    $mq->where('nama_mk', 'LIKE', "%{$search}%");
                })
                ->orWhere('materi', 'LIKE', "%{$search}%");
            });
        }
        
        $query->orderBy('deleted_at', 'desc');
        
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
     * Get pertemuan by multiple kelas IDs (untuk dashboard)
     */
    public function byMultipleKelas($_, array $args): array
    {
        $query = Pertemuan::whereIn('kelas_id', $args['kelas_ids'])
            ->whereNull('deleted_at');
        
        if (isset($args['status']) && !empty($args['status'])) {
            $query->whereIn('status_pertemuan', $args['status']);
        }
        
        return $query->with(['kelas.mataKuliah', 'ruangan'])
            ->orderBy('tanggal', 'desc')
            ->get()
            ->toArray();
    }
    
    /**
     * Get pertemuan by single kelas ID
     */
    public function byKelas($_, array $args): array
    {
        return Pertemuan::where('kelas_id', $args['kelas_id'])
            ->whereNull('deleted_at')
            ->orderBy('pertemuan_ke', 'asc')
            ->get()
            ->toArray();
    }
    
    /**
     * Get pertemuan hari ini
     */
    public function hariIni($_, array $args): array
    {
        return Pertemuan::whereDate('tanggal', today())
            ->whereNull('deleted_at')
            ->with(['kelas.mataKuliah', 'ruangan'])
            ->orderBy('waktu_mulai', 'asc')
            ->get()
            ->toArray();
    }
    
    /**
     * Get pertemuan mendatang
     */
    public function mendatang($_, array $args): array
    {
        $query = Pertemuan::where('tanggal', '>', today())
            ->whereNull('deleted_at')
            ->with(['kelas.mataKuliah', 'ruangan']);
        
        if (!empty($args['kelas_id'])) {
            $query->where('kelas_id', $args['kelas_id']);
        }
        
        return $query->orderBy('tanggal', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->get()
            ->toArray();
    }
    
    /**
     * Helper: Empty paginator
     */
    private function emptyPaginator($args)
    {
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
}