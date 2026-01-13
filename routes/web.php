<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Gunakan AdminDashboardController untuk dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.user');
    Route::get('/admin/roles', [AdminController::class, 'roles'])->name('admin.role');
    Route::get('/admin/dosen', [AdminController::class, 'dosen']);
    Route::get('/admin/mahasiswa', [AdminController::class, 'mahasiswa'])->name('admin.mahasiswa');
    Route::get('/admin/krs', [AdminController::class, 'krs']);
    Route::get('/admin/nilai', [AdminController::class, 'nilai'])->name('admin.nilai');
    Route::get('/admin/ruangan', [AdminController::class, 'ruangan']);
    Route::get('/admin/kelas', [AdminController::class, 'kelas']);
    Route::get('/admin/jurusan', [AdminController::class, 'jurusan'])->name('admin.jurusan');
    Route::get('/admin/fakultas', [AdminController::class, 'fakultas'])->name('admin.fakultas');
    Route::get('/admin/jadwal', [AdminController::class, 'jadwal']);
    Route::get('/admin/semester', [AdminController::class, 'semester']);
    Route::get('/admin/mata_kuliah', [AdminController::class, 'mata_kuliah'])->name('admin.mata_kuliah');
    Route::get('/admin/khs', [AdminController::class, 'khs']);
    Route::get('/admin/jadwal', [AdminController::class, 'jadwal'])->name('admin.jadwal');
    Route::get('/admin/sks-limit', [AdminController::class, 'sksLimit'])->name('admin.sksLimit');
    Route::get('/admin/dosen_detail/{id}', [AdminController::class, 'dosen_detail']);
    Route::get('/admin/mahasiswa_detail/{id}', [AdminController::class, 'mahasiswa_detail']);
    Route::get('/admin/krs-detail/{id}', [AdminController::class, 'krs_detail'])->name('admin.krs_detail');
    Route::get('/admin/grade-system', [AdminController::class, 'gradeSystem'])->name('admin.grade-system');
    Route::get('/admin/bobot-nilai', [AdminController::class, 'bobotNilai'])->name('admin.bobot-nilai');
    Route::get('/admin/pertemuan', [AdminController::class, 'pertemuan'])->name('admin.pertemuan');
    Route::get('/admin/kehadiran', [AdminController::class, 'kehadiran'])->name('admin.kehadiran');
    Route::get('/admin/rekap-kehadiran', [AdminController::class, 'rekapKehadiran'])->name('admin.rekapKehadiran');
});

Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen/dashboard', [DosenController::class, 'index'])->name('dosen.dashboard');
});

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'index'])->name('mahasiswa.dashboard');
    Route::get('/mahasiswa/jadwal', [MahasiswaController::class, 'jadwal'])->name('jadwal.index');
    Route::get('/mahasiswa/nilai', [MahasiswaController::class, 'nilai'])->name('nilai.index');
    Route::get('/mahasiswa/khs', [MahasiswaController::class, 'khs'])->name('khs.index');
    Route::get('/mahasiswa/krs', [MahasiswaController::class, 'krs'])->name('krs.index');
});