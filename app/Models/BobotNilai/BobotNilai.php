<?php

namespace App\Models\BobotNilai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MataKuliah\MataKuliah;
use App\Models\Semester\Semester;
use App\Models\Nilai\Nilai;

class BobotNilai extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bobot_nilai';
    
    protected $fillable = [
        'mata_kuliah_id',
        'tugas',
        'quiz',
        'uts',
        'uas',
        'kehadiran',
        'praktikum',
        'semester_id',
        'keterangan'
    ];

    protected function casts(): array
    {
        return [
            'tugas' => 'decimal:2',
            'quiz' => 'decimal:2',
            'uts' => 'decimal:2',
            'uas' => 'decimal:2',
            'kehadiran' => 'decimal:2',
            'praktikum' => 'decimal:2',
            'total_bobot' => 'decimal:2',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'bobot_nilai_id');
    }

    // Validasi total bobot = 100
    public function isTotalBobotValid()
    {
        $total = $this->tugas + $this->quiz + $this->uts + $this->uas + $this->kehadiran + $this->praktikum;
        return $total == 100;
    }

    // Get total bobot
    public function getTotalBobot()
    {
        return $this->tugas + $this->quiz + $this->uts + $this->uas + $this->kehadiran + $this->praktikum;
    }

    // Scope untuk filter by semester aktif
    public function scopeAktif($query)
    {
        return $query->whereHas('semester', function($q) {
            $q->whereNull('deleted_at');
        });
    }

    // Scope untuk filter by mata kuliah
    public function scopeByMataKuliah($query, $mataKuliahId)
    {
        return $query->where('mata_kuliah_id', $mataKuliahId);
    }

    // Scope untuk filter by semester
    public function scopeBySemester($query, $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }
}