<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    
    public function index()
    {
        // ==================== STATISTIK UTAMA ====================
        
        // Total Mahasiswa Aktif
        $totalMahasiswa = DB::table('mahasiswa')
            ->whereNull('deleted_at')
            ->where('status', 'aktif')
            ->count();

        // Total Dosen Aktif
        $totalDosen = DB::table('dosen')
            ->whereNull('deleted_at')
            ->where('status', 'aktif')
            ->count();

        // Total Mata Kuliah
        $totalMataKuliah = DB::table('mata_kuliah')
            ->whereNull('deleted_at')
            ->count();

        // ==================== STATISTIK KRS ====================
        
        // KRS Pending
        $krsPending = DB::table('krs')
            ->whereNull('deleted_at')
            ->where('status', 'Draft')
            ->count();

        // KRS Disetujui
        $krsDisetujui = DB::table('krs')
            ->whereNull('deleted_at')
            ->where('status', 'disetujui')
            ->count();

        // KRS Ditolak
        $krsDitolak = DB::table('krs')
            ->whereNull('deleted_at')
            ->where('status', 'ditolak')
            ->count();

        // ==================== STATISTIK MAHASISWA ====================
        
        // Rata-rata IPK
        $avgIpk = DB::table('mahasiswa')
            ->whereNull('deleted_at')
            ->where('status', 'aktif')
            ->avg('ipk');

        // Total SKS yang diambil mahasiswa
        $totalSksAmbil = DB::table('mahasiswa')
            ->whereNull('deleted_at')
            ->where('status', 'aktif')
            ->sum('total_sks');

        // Jumlah Mahasiswa Laki-laki
        $mahasiswaL = DB::table('mahasiswa')
            ->whereNull('deleted_at')
            ->where('status', 'aktif')
            ->where('jenis_kelamin', 'L')
            ->count();

        // Jumlah Mahasiswa Perempuan
        $mahasiswaP = DB::table('mahasiswa')
            ->whereNull('deleted_at')
            ->where('status', 'aktif')
            ->where('jenis_kelamin', 'P')
            ->count();

        // ==================== STATISTIK DOSEN ====================
        
        // Dosen Tetap
        $dosenTetap = DB::table('dosen')
            ->whereNull('deleted_at')
            ->where('status', 'aktif')
            ->where('status_kepegawaian', 'tetap')
            ->count();

        // Dosen Kontrak
        $dosenKontrak = DB::table('dosen')
            ->whereNull('deleted_at')
            ->where('status', 'aktif')
            ->where('status_kepegawaian', 'kontrak')
            ->count();

        // Total Dosen PA (Pembimbing Akademik) - dosen yang punya mahasiswa bimbingan
        $totalDosenPA = DB::table('krs')
            ->whereNull('deleted_at')
            ->whereNotNull('dosen_pa_id')
            ->distinct('dosen_pa_id')
            ->count('dosen_pa_id');

        // ==================== KRS TERBARU ====================
        
        // KRS Terbaru (10 terbaru dengan relasi mahasiswa)
        $krsTerbaru = DB::table('krs')
            ->join('mahasiswa', 'krs.mahasiswa_id', '=', 'mahasiswa.id')
            ->select(
                'krs.id',
                'krs.mahasiswa_id',
                'krs.semester_id',
                'krs.tanggal_pengisian',
                'krs.tanggal_persetujuan',
                'krs.status',
                'krs.total_sks',
                'krs.catatan',
                'mahasiswa.nama_lengkap as mahasiswa_nama',
                'mahasiswa.nim as mahasiswa_nim'
            )
            ->whereNull('krs.deleted_at')
            ->orderBy('krs.created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($krs) {
                // Convert to object with nested mahasiswa relation
                return (object) [
                    'id' => $krs->id,
                    'mahasiswa_id' => $krs->mahasiswa_id,
                    'semester_id' => $krs->semester_id,
                    'tanggal_pengisian' => $krs->tanggal_pengisian,
                    'tanggal_persetujuan' => $krs->tanggal_persetujuan,
                    'status' => $krs->status,
                    'total_sks' => $krs->total_sks,
                    'catatan' => $krs->catatan,
                    'mahasiswa' => (object) [
                        'nama_lengkap' => $krs->mahasiswa_nama,
                        'nim' => $krs->mahasiswa_nim
                    ]
                ];
            });

        // ==================== MATA KULIAH POPULER ====================
        
        // Mata Kuliah Populer berdasarkan jumlah pengambil (dari krs_detail)
        $mataKuliahPopuler = DB::table('mata_kuliah')
            ->leftJoin('krs_detail', 'mata_kuliah.id', '=', 'krs_detail.mata_kuliah_id')
            ->select(
                'mata_kuliah.id',
                'mata_kuliah.kode_mk',
                'mata_kuliah.nama_mk',
                'mata_kuliah.sks',
                'mata_kuliah.jenis',
                'mata_kuliah.semester_rekomendasi',
                DB::raw('COUNT(DISTINCT krs_detail.krs_id) as jumlah_pengambil')
            )
            ->whereNull('mata_kuliah.deleted_at')
            ->whereNull('krs_detail.deleted_at')
            ->groupBy(
                'mata_kuliah.id',
                'mata_kuliah.kode_mk',
                'mata_kuliah.nama_mk',
                'mata_kuliah.sks',
                'mata_kuliah.jenis',
                'mata_kuliah.semester_rekomendasi'
            )
            ->orderBy('jumlah_pengambil', 'desc')
            ->limit(10)
            ->get();

        // ==================== MAHASISWA TERBARU ====================
        
        // Mahasiswa Terbaru (10 terbaru)
        $mahasiswaTerbaru = DB::table('mahasiswa')
            ->whereNull('deleted_at')
            ->where('status', 'aktif')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ==================== DOSEN TERBARU ====================
        
        // Dosen Terbaru (10 terbaru)
        $dosenTerbaru = DB::table('dosen')
            ->whereNull('deleted_at')
            ->where('status', 'aktif')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ==================== RETURN VIEW ====================
        
        return view('admin.dashboard.index', compact(
            // Statistik Utama
            'totalMahasiswa',
            'totalDosen',
            'totalMataKuliah',
            
            // Statistik KRS
            'krsPending',
            'krsDisetujui',
            'krsDitolak',
            
            // Statistik Mahasiswa
            'avgIpk',
            'totalSksAmbil',
            'mahasiswaL',
            'mahasiswaP',
            
            // Statistik Dosen
            'dosenTetap',
            'dosenKontrak',
            'totalDosenPA',
            
            // Data Terbaru
            'krsTerbaru',
            'mataKuliahPopuler',
            'mahasiswaTerbaru',
            'dosenTerbaru'
        ));
    }
    public function users()
    {
        return view('admin.users.index');
    }
    public function roles()
    {
        return view('admin.roles.index');
    }
    public function dosen()
    {
        return view('admin.dosen.index');
    }
    public function mahasiswa()
    {
        return view('admin.mahasiswa.index');
    }
    public function nilai()
    {
        return view('admin.nilai.index');
    }
    public function krs()
    {
        return view('admin.krs.index');
    }
    public function dosen_detail($id)
    {
        return view('admin.dosen_detail.index');
    }
    public function mahasiswa_detail($id)
    {
        return view('admin.mahasiswa_detail.index');
    }
    public function ruangan()
    {
        return view('admin.ruangan.index');
    }
    public function kelas()
    {
        return view('admin.kelas.index');
    }
    public function jurusan()
    {
        return view('admin.jurusan.index');
    }
    public function fakultas()
    {
        return view('admin.fakultas.index');
    }
    public function khs()
    {
        return view('admin.khs.index');
    }
    public function mata_kuliah()
    {
        return view('admin.mata_kuliah.index');
    }
    public function semester()
    {
        return view('admin.semester.index');
    }
    public function jadwal()
    {
        return view('admin.jadwal.index');
    }
    public function krs_detail($id)
    {
        return view('admin.krs_detail.index');
    }
    public function sksLimit()
    {
        return view('admin.sksLimit.index');
    }
    public function gradeSystem(){
        return view('admin.grade-system.index');
    }
    public function bobotNilai(){
        return view('admin.bobot-nilai.index');
    }
    public function pertemuan(){
        return view('admin.pertemuan.index');
    }
    public function kehadiran(){
        return view('admin.kehadiran.index');
    }
    public function rekapKehadiran(){
        return view('admin.rekap-kehadiran.index');
    }
    public function pengaturanKehadiran(){
        return view('admin.pengaturan-kehadiran.index');
    }
    public function kelas_detail($id)
{
    if (!is_numeric($id)) {
        abort(404);
    }
    return view('admin.kelas.detail', ['kelasId' => $id]);
}
}
