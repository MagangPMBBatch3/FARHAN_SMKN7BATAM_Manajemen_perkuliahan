<?php

namespace App\Http\Controllers;

class MahasiswaController extends Controller
{
    public function index()
    {
        return view('mahasiswa.dashboard.index');
    }

    public function jadwal()
    {
        return view('mahasiswa.jadwal.index');
    }

    public function nilai()
    {
        return view('mahasiswa.nilai.index');
    }

    public function khs()
    {
        return view('mahasiswa.khs.index');
    }

    public function krs()
    {
        return view('mahasiswa.krs.index');
    }

    public function krsHistory()
    {
        return view('mahasiswa.krsHistory.index');
    }
}
