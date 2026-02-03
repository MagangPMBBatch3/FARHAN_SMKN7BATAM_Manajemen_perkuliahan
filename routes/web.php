<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| GraphiQL (DEV ONLY)
|--------------------------------------------------------------------------
*/
Route::get('/graphiql', function () {
    abort(404);
})->middleware('graphiql.admin');
Route::get('/graphql', function () {
    abort(404);
})->middleware('graphiql.admin');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/users', [AdminController::class, 'users'])->name('user');
    Route::get('/roles', [AdminController::class, 'roles'])->name('role');
    Route::get('/dosen', [AdminController::class, 'dosen'])->name('dosen');
    Route::get('/mahasiswa', [AdminController::class, 'mahasiswa'])->name('mahasiswa');
    Route::get('/jurusan', [AdminController::class, 'jurusan'])->name('jurusan');
    Route::get('/fakultas', [AdminController::class, 'fakultas'])->name('fakultas');
    Route::get('/mata_kuliah', [AdminController::class, 'mata_kuliah'])->name('mata_kuliah');
    Route::get('/ruangan', [AdminController::class, 'ruangan'])->name('ruangan');
    Route::get('/semester', [AdminController::class, 'semester'])->name('semester');

    Route::get('/kelas', [AdminController::class, 'kelas'])->name('kelas');
    Route::get('/kelas/{id}', [AdminController::class, 'kelas_detail'])->name('kelas.detail');

    Route::get('/jadwal', [AdminController::class, 'jadwal'])->name('jadwal');

    Route::get('/krs', [AdminController::class, 'krs'])->name('krs');
    Route::get('/krs-detail/{id}', [AdminController::class, 'krs_detail'])->name('krs.detail');

    Route::get('/nilai', [AdminController::class, 'nilai'])->name('nilai');
    Route::get('/khs', [AdminController::class, 'khs'])->name('khs');
    Route::get('/bobot-nilai', [AdminController::class, 'bobotNilai'])->name('bobot-nilai');
    Route::get('/grade-system', [AdminController::class, 'gradeSystem'])->name('grade-system');
    Route::get('/sks-limit', [AdminController::class, 'sksLimit'])->name('sksLimit');

    Route::get('/pertemuan', [AdminController::class, 'pertemuan'])->name('pertemuan');
    Route::get('/kehadiran', [AdminController::class, 'kehadiran'])->name('kehadiran');
    Route::get('/rekap-kehadiran', [AdminController::class, 'rekapKehadiran'])->name('rekapKehadiran');
    Route::get('/pengaturan-kehadiran', [AdminController::class, 'pengaturanKehadiran'])->name('pengaturanKehadiran');

    Route::get('/dosen-detail/{id}', [AdminController::class, 'dosen_detail'])->name('dosen.detail');
    Route::get('/mahasiswa-detail/{id}', [AdminController::class, 'mahasiswa_detail'])->name('mahasiswa.detail');
});

/*
|--------------------------------------------------------------------------
| Dosen Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DosenController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Mahasiswa Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'index'])->name('dashboard');
    Route::get('/jadwal', [MahasiswaController::class, 'jadwal'])->name('jadwal');
    Route::get('/nilai', [MahasiswaController::class, 'nilai'])->name('nilai');
    Route::get('/khs', [MahasiswaController::class, 'khs'])->name('khs');
    Route::get('/krs', [MahasiswaController::class, 'krs'])->name('krs');
    Route::get('/krs-history', [MahasiswaController::class, 'krsHistory'])->name('krs-history');
});
