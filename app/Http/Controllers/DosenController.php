<?php

namespace App\Http\Controllers;

class DosenController extends Controller
{
    public function index()
    {
        return view('dosen.dashboard.index');
    }

    public function jadwal()
    {
        return view('dosen.jadwal.index');
    }

    public function pertemuan()
    {
        return view('dosen.pertemuan.index');
    }

    public function nilai()
    {
        return view('dosen.nilai.index');
    }
}
