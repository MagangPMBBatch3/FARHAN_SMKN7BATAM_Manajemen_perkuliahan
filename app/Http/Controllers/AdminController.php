<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index');
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
}
