<?php 
namespace App\GraphQL\Krs\Queries;

use App\Models\Krs\Krs;
use App\Models\Semester\Semester;
class KrsQuery {
    public function currentByMahasiswa($rootValue, array $args)
{
    $currentSemester = Semester::orderBy('tahun_ajaran', 'desc')
        ->orderBy('id', 'desc')
        ->first();

    // DEBUG: Log semester yang ditemukan
    \Log::info('Current Semester:', ['semester' => $currentSemester]);

    if (!$currentSemester) {
        return null;
    }

    $krs = Krs::with([
        'semester:id,nama_semester,tahun_ajaran',
        'dosen_pa_id:id,nama_lengkap',
        'krsDetail.kelas:id,nama_kelas,kapasitas,kuota_terisi',
        'krsDetail.kelas.dosen:id,nama_lengkap',
        'krsDetail.kelas.jadwalKuliah:id,kelas_id,hari,jam_mulai,jam_selesai,ruangan_id',
        'krsDetail.kelas.jadwalKuliah.ruangan:id,nama_ruangan',
        'krsDetail.mataKuliah:id,kode_mk,nama_mk,sks,semester_rekomendasi'
    ])
    ->where('mahasiswa_id', $args['mahasiswa_id'])
    ->where('semester_id', $currentSemester->id)
    ->first();

    // DEBUG: Log hasil query
    \Log::info('KRS Query Result:', ['krs' => $krs, 'mahasiswa_id' => $args['mahasiswa_id'], 'semester_id' => $currentSemester->id]);

    return $krs;
}
public function historyByMahasiswa($rootValue, array $args)
{
    return Krs::with([
        'semester',
        'mahasiswa.jurusan',
        'dosen_pa_id',
        'krsDetail.kelas.dosen',
        'krsDetail.kelas.jadwalKuliah.ruangan',
        'krsDetail.mataKuliah',
        'krsDetail.nilai'
    ])
    ->where('mahasiswa_id', $args['mahasiswa_id'])
    ->orderBy('semester_id', 'DESC')
    ->get();
}

    /**
     * Get KRS dengan detail lengkap
     */
    public function withDetails($rootValue, array $args)
    {
        return Krs::with([
            'mahasiswa.jurusan',
            'semester',
            'dosen_pa_id',
            'krsDetail.kelas.dosen',
            'krsDetail.kelas.jadwalKuliah.ruangan',
            'krsDetail.mataKuliah',
            'krsDetail.nilai'
        ])
        ->find($args['id']);
    }

    /**
     * Get all KRS dengan pagination (untuk admin)
     */
    public function all($rootValue, array $args)
{
    $query = Krs::with([
        'mahasiswa',
        'semester',
        'dosen_pa_id'
    ]);

    if (!empty($args['search'])) {
        $search = $args['search'];
        $query->whereHas('mahasiswa', function($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('nim', 'like', "%{$search}%");
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

    public function allArsip($_, array $args)
    {
        $query = Krs::onlyTrashed();
        if (!empty($args['search'])) {
            $query->where('status', 'like', '%' . $args['search'] . '%');
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