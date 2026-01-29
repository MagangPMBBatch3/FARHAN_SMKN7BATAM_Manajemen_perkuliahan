<?php

namespace App\Models\Semester;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Kelas\Kelas;
use App\Models\Krs\Krs;
use App\Models\Khs\Khs;

class Semester extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'semester';

    protected $fillable = [
        'kode_semester',
        'nama_semester',
        'tahun_ajaran',
        'periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    public function krs()
    {
        return $this->hasMany(Krs::class);
    }

    public function khs()
    {
        return $this->hasMany(Khs::class);
    }
}