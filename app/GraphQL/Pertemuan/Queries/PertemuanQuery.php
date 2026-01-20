<?php
namespace App\GraphQL\Pertemuan\Queries;

use App\Models\Pertemuan\Pertemuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
class PertemuanQuery
{
    public function forMahasiswa($root, array $args)
    {
        $user = Auth::user();
        
        // Debug
        \Log::info('User for pertemuan:', ['user' => $user]);
        
        $perPage = $args['first'] ?? 10;
        $page = $args['page'] ?? 1;
        
        if (!$user) {
            \Log::warning('No authenticated user for pertemuan');
            
            // ← FIX: Return empty paginator dengan struktur yang benar
            return [
                'data' => [], // ← Harus array kosong, bukan null
                'paginatorInfo' => [
                    'hasMorePages' => false,
                    'currentPage' => $page,
                    'lastPage' => 1,
                    'perPage' => $perPage,
                    'total' => 0,
                ],
            ];
        }
        
        // Cari mahasiswa berdasarkan user_id atau email
        $mahasiswa = \App\Models\Mahasiswa\Mahasiswa::where('user_id', $user->id)
            ->orWhere('email_pribadi', $user->email)
            ->first();
        
        if (!$mahasiswa) {
            \Log::warning('Mahasiswa not found for user in pertemuan', ['user_id' => $user->id]);
            
            // ← FIX: Return empty paginator
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
        
        // Get kelas IDs dari KRS yang disetujui
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
            
            // ← FIX: Return empty paginator
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
        
        // Filter by search
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
        
        // Filter by semester
        if (!empty($args['semester_id'])) {
            $query->whereHas('kelas', function ($q) use ($args) {
                $q->where('semester_id', $args['semester_id']);
            });
        }
        
        // Filter by status
        if (!empty($args['status_pertemuan'])) {
            $query->where('status_pertemuan', $args['status_pertemuan']);
        }
        
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);
        
        \Log::info('Pertemuan result:', ['total' => $paginator->total()]);
        
        // ← FIX: Return struktur yang konsisten
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
        $query = Pertemuan::query()->with(['kelas', 'ruangan', 'createdBy']);
        
        if (!empty($args['search'])) {
            $query->where(function($q) use ($args) {
                $q->where('materi', 'like', '%' . $args['search'] . '%')
                  ->orWhereHas('kelas', function($kq) use ($args) {
                      $kq->where('nama_kelas', 'like', '%' . $args['search'] . '%');
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
    
    public function allArsip($_, array $args)
    {
        $query = Pertemuan::onlyTrashed()->with(['kelas', 'ruangan']);
        
        if (!empty($args['search'])) {
            $query->where('materi', 'like', '%' . $args['search'] . '%');
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
    
    public function byKelas($_, array $args): array
    {
        return Pertemuan::byKelas($args['kelas_id'])
            ->orderBy('pertemuan_ke', 'asc')
            ->get()
            ->toArray();
    }
    
    public function hariIni($_, array $args): array
    {
        return Pertemuan::hariIni()
            ->with(['kelas', 'ruangan'])
            ->orderBy('waktu_mulai', 'asc')
            ->get()
            ->toArray();
    }
    
    public function mendatang($_, array $args): array
    {
        $query = Pertemuan::mendatang()->with(['kelas', 'ruangan']);
        
        if (!empty($args['kelas_id'])) {
            $query->where('kelas_id', $args['kelas_id']);
        }
        
        return $query->orderBy('tanggal', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->get()
            ->toArray();
    }
    
    public function getDurasiMenit($pertemuan): int
    {
        return $pertemuan->getDurasiMenit();
    }
    
    public function getPersentaseKehadiran($pertemuan): float
    {
        return $pertemuan->getPersentaseKehadiran();
    }
    
    public function getStatistikKehadiran($pertemuan): array
    {
        return $pertemuan->getStatistikKehadiran();
    }
}