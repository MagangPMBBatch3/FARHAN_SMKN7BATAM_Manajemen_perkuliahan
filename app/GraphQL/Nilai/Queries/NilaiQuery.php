<?php 
namespace App\GraphQL\Nilai\Queries;

use App\Models\Nilai\Nilai;
use App\Models\Kelas\Kelas;
use App\Models\BobotNilai\BobotNilai;
use App\Models\KrsDetail\KrsDetail;

class NilaiQuery {
    public function kelasBySemester($root, array $args)
    {
        // Cast to int if needed for database query
        $semesterId = (int) $args['semester_id'];
        
        return Kelas::where('semester_id', $semesterId)
            ->whereNull('deleted_at')
            ->with(['mataKuliah', 'dosen'])
            ->get();
    }

    public function bobotNilaiByMataKuliahSemester($root, array $args)
    {
        $mataKuliahId = (int) $args['mata_kuliah_id'];
        $semesterId = (int) $args['semester_id'];
        
        return BobotNilai::where('mata_kuliah_id', $mataKuliahId)
            ->where('semester_id', $semesterId)
            ->whereNull('deleted_at')
            ->first();
    }

    public function krsDetailByKelas($root, array $args)
    {
        $kelasId = (int) $args['kelas_id'];
        
        return KrsDetail::where('kelas_id', $kelasId)
            ->whereNull('deleted_at')
            ->with(['krs.mahasiswa', 'nilai'])
            ->get();
    }

    public function nilaiByKelas($root, array $args)
    {
        $kelasId = (int) $args['kelas_id'];
        
        return Nilai::whereHas('krsDetail', function($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId);
            })
            ->whereNull('deleted_at')
            ->with(['krsDetail.krs.mahasiswa'])
            ->get();
    }
    public function allArsip($_, array $args)
    {
        $query = Nilai::onlyTrashed();
        if (!empty($args['search'])) {
            $query->where('tugas', 'like', '%' . $args['search'] . '%');
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
        $query = Nilai::query();
        if (!empty($args['search'])) {
            $query->where('tugas', 'like', '%' . $args['search'] . '%');
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