<?php

namespace App\Models\Kelas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MataKuliah\MataKuliah;
use App\Models\Dosen\Dosen;
use App\Models\Semester\Semester;
use App\Models\JadwalKuliah\JadwalKuliah;
use App\Models\KrsDetail\KrsDetail;

class Kelas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kelas';

    protected $fillable = [
        'kode_kelas',
        'nama_kelas',
        'mata_kuliah_id',
        'dosen_id',
        'semester_id',
        'kapasitas',
        'kuota_terisi',
        'jumlah_mahasiswa',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'kapasitas' => 'integer',
            'kuota_terisi' => 'integer',
            'jumlah_mahasiswa' => 'integer',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function jadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class);
    }

    public function krsDetail()
    {
        return $this->hasMany(KrsDetail::class);
    }
}