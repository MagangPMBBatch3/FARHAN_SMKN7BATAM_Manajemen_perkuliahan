<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function kelas()
    {
        return view('admin.kelas.index');
    }
    
    public function kelas_detail($id)
    {
        // Validasi ID
        if (!is_numeric($id)) {
            abort(404);
        }

        return view('admin.kelas.detail', [
            'kelasId' => $id
        ]);
    }
}