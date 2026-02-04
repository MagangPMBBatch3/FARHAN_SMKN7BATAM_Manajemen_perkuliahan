<?php

namespace App\Http\Controllers;

class AkademikController extends Controller
{
    public function index()
    {
        return view('akademik.dashboard.index');
    }

    public function mahasiswa()
    {
        return view('akademik.mahasiswa.index');
    }

    public function dosen()
    {
        return view('akademik.dosen.index');
    }

    public function jadwal()
    {
        return view('akademik.jadwal.index');
    }

    public function krs()
    {
        return view('akademik.krs.index');
    }

    public function khs()
    {
        return view('akademik.khs.index');
    }
}
