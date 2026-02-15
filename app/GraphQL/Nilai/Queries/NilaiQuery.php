<?php 
namespace App\GraphQL\Nilai\Queries;

use App\Models\Nilai\Nilai;
use App\Models\Kelas\Kelas;
use App\Models\BobotNilai\BobotNilai;
use App\Models\KrsDetail\KrsDetail;
use App\Models\Semester\Semester;

class NilaiQuery {
    
    /**
     * Get nilai by mahasiswa and mata kuliah (existing)
     */
    public function byMahasiswaAndMataKuliah($root, array $args)
    {
        return Nilai::whereHas('krsDetail', function ($query) use ($args) {
            $query->where('mata_kuliah_id', $args['mata_kuliah_id'])
                  ->whereHas('krs', function ($krsQuery) use ($args) {
                      $krsQuery->where('mahasiswa_id', $args['mahasiswa_id']);
                  });
        })
        ->with(['krsDetail.krs.semester'])
        ->orderBy('created_at', 'desc')
        ->get();
    }

    /**
     * Get semua nilai mahasiswa (existing)
     */
    public function byMahasiswa($rootValue, array $args)
    {
        return Nilai::with(['krsDetail.krs.semester', 'krsDetail.mataKuliah', 'krsDetail.kelas'])
            ->whereHas('krsDetail.krs', function($q) use ($args) {
                $q->where('mahasiswa_id', $args['mahasiswa_id']);
            })
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'DESC')
            ->get();
    }
    
    /**
     * Get nilai by mahasiswa dan semester (existing)
     */
    public function byMahasiswaSemester($root, array $args)
    {
        return Nilai::whereHas('krsDetail.krs', function($query) use ($args) {
                $query->where('mahasiswa_id', $args['mahasiswa_id']);
            })
            ->whereHas('krsDetail.kelas', function($query) use ($args) {
                $query->where('semester_id', $args['semester_id']);
            })
            ->whereNull('deleted_at')
            ->with(['krsDetail.mataKuliah', 'krsDetail.kelas.semester'])
            ->get();
    }

    /**
     * Get nilai kumulatif (existing)
     */
    public function kumulatif($root, array $args)
    {
        return Nilai::whereHas('krsDetail.krs', function($query) use ($args) {
                $query->where('mahasiswa_id', $args['mahasiswa_id']);
            })
            ->whereHas('krsDetail.kelas.semester', function($query) use ($args) {
                $selectedSemester = Semester::find($args['semester_id']);
                $query->where('tanggal_mulai', '<=', $selectedSemester->tanggal_selesai);
            })
            ->whereNull('deleted_at')
            ->with(['krsDetail.mataKuliah'])
            ->get();
    }
    
    /**
     * Get kelas by semester (existing)
     */
    public function kelasBySemester($root, array $args)
    {
        $semesterId = (int) $args['semester_id'];
        
        return Kelas::where('semester_id', $semesterId)
            ->whereNull('deleted_at')
            ->with(['mataKuliah', 'dosen'])
            ->get();
    }

    /**
     * Get bobot nilai by mata kuliah dan semester (existing)
     */
    public function bobotNilaiByMataKuliahSemester($root, array $args)
    {
        $mataKuliahId = (int) $args['mata_kuliah_id'];
        $semesterId = (int) $args['semester_id'];
        
        return BobotNilai::where('mata_kuliah_id', $mataKuliahId)
            ->where('semester_id', $semesterId)
            ->whereNull('deleted_at')
            ->first();
    }

    /**
     * Get KRS detail by kelas (existing)
     */
    public function krsDetailByKelas($root, array $args)
    {
        $kelasId = (int) $args['kelas_id'];
        
        return KrsDetail::where('kelas_id', $kelasId)
            ->whereNull('deleted_at')
            ->with(['krs.mahasiswa', 'nilai'])
            ->get();
    }

    /**
     * Get nilai by kelas (existing)
     */
    public function nilaiByKelas($root, array $args)
    {
        $kelasId = (int) $args['kelas_id'];
        
        return Nilai::whereHas('krsDetail', function($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId);
            })
            ->whereNull('deleted_at')
            ->with(['krsDetail.krs.mahasiswa', 'krsDetail.mataKuliah', 'krsDetail.kelas'])
            ->get();
    }
    
    /**
     * Get all nilai dengan pagination (untuk admin)
     */
    public function all($_, array $args)
    {
        $query = Nilai::query()->with([
            'krsDetail.krs.mahasiswa',
            'krsDetail.mataKuliah',
            'krsDetail.kelas.semester'
        ]);
        
        if (!empty($args['search'])) {
            $query->where(function($q) use ($args) {
                $q->whereHas('krsDetail.krs.mahasiswa', function($mq) use ($args) {
                    $mq->where('nama_lengkap', 'like', '%' . $args['search'] . '%')
                       ->orWhere('nim', 'like', '%' . $args['search'] . '%');
                })
                ->orWhereHas('krsDetail.mataKuliah', function($mkq) use ($args) {
                    $mkq->where('nama_mk', 'like', '%' . $args['search'] . '%')
                        ->orWhere('kode_mk', 'like', '%' . $args['search'] . '%');
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
     * Get nilai arsip (untuk admin)
     */
    public function allArsip($_, array $args)
    {
        $query = Nilai::onlyTrashed()->with([
            'krsDetail.krs.mahasiswa',
            'krsDetail.mataKuliah',
            'krsDetail.kelas'
        ]);
        
        if (!empty($args['search'])) {
            $query->whereHas('krsDetail.krs.mahasiswa', function($mq) use ($args) {
                $mq->where('nama_lengkap', 'like', '%' . $args['search'] . '%')
                   ->orWhere('nim', 'like', '%' . $args['search'] . '%');
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
     * Get nilai untuk dosen yang sedang login (BARU - untuk currentDosenNilai)
     */
    public function allByDosen($_, array $args)
    {
        $user = auth()->user();
        
        if (!$user || !$user->dosen) {
            return $this->emptyPaginator($args);
        }
        
        $dosenId = $user->dosen->id;
        
        $query = Nilai::whereHas('krsDetail.kelas', function($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })
        ->with([
            'krsDetail.krs.mahasiswa',
            'krsDetail.kelas.semester',
            'krsDetail.mataKuliah',
            'bobotNilai'
        ])
        ->whereNull('deleted_at');
        
        // Filter berdasarkan kelas
        if (isset($args['kelas_id'])) {
            $query->whereHas('krsDetail', function($q) use ($args) {
                $q->where('kelas_id', $args['kelas_id']);
            });
        }
        
        // Filter berdasarkan status
        if (isset($args['status'])) {
            $query->where('status', $args['status']);
        }
        
        // Search mahasiswa
        if (isset($args['search']) && $args['search'] !== '') {
            $search = $args['search'];
            $query->where(function($q) use ($search) {
                $q->whereHas('krsDetail.krs.mahasiswa', function($mq) use ($search) {
                    $mq->where('nama_lengkap', 'LIKE', "%{$search}%")
                       ->orWhere('nim', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('krsDetail.mataKuliah', function($mkq) use ($search) {
                    $mkq->where('nama_mk', 'LIKE', "%{$search}%")
                        ->orWhere('kode_mk', 'LIKE', "%{$search}%");
                });
            });
        }
        
        $query->orderBy('created_at', 'desc');
        
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
     * Get nilai arsip untuk dosen yang sedang login (BARU)
     */
    public function allArsipDosen($_, array $args)
    {
        $user = auth()->user();
        
        if (!$user || !$user->dosen) {
            return $this->emptyPaginator($args);
        }
        
        $dosenId = $user->dosen->id;
        
        $query = Nilai::whereHas('krsDetail.kelas', function($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })
        ->with([
            'krsDetail.krs.mahasiswa',
            'krsDetail.kelas',
            'krsDetail.mataKuliah'
        ])
        ->onlyTrashed();
        
        // Search
        if (isset($args['search']) && $args['search'] !== '') {
            $search = $args['search'];
            $query->where(function($q) use ($search) {
                $q->whereHas('krsDetail.krs.mahasiswa', function($mq) use ($search) {
                    $mq->where('nama_lengkap', 'LIKE', "%{$search}%")
                       ->orWhere('nim', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('krsDetail.mataKuliah', function($mkq) use ($search) {
                    $mkq->where('nama_mk', 'LIKE', "%{$search}%");
                });
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
     * Get nilai by multiple kelas IDs (untuk dashboard)
     */
    public function byMultipleKelas($_, array $args): array
    {
        $query = Nilai::whereHas('krsDetail', function($q) use ($args) {
            $q->whereIn('kelas_id', $args['kelas_ids']);
        })
        ->whereNull('deleted_at');
        
        if (isset($args['status']) && !empty($args['status'])) {
            $query->whereIn('status', $args['status']);
        }
        
        return $query->with(['krsDetail.krs.mahasiswa', 'krsDetail.mataKuliah'])
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