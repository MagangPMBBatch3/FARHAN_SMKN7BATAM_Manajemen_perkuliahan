<?php

namespace App\Models\RekapKehadiran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Mahasiswa\Mahasiswa;
use App\Models\Kelas\Kelas;
use App\Models\Semester\Semester;

class RekapKehadiran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rekap_kehadiran';
    
    protected $fillable = [
        'mahasiswa_id',
        'kelas_id',
        'semester_id',
        'total_pertemuan',
        'total_hadir',
        'total_izin',
        'total_sakit',
        'total_alpa',
        'nilai_kehadiran',
        'status_minimal',
        'keterangan'
    ];

    protected function casts(): array
    {
        return [
            'nilai_kehadiran' => 'decimal:2',
            'persentase_kehadiran' => 'decimal:2',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    // Accessor untuk persentase kehadiran (dihitung otomatis di DB)
    public function getPersentaseKehadiranAttribute()
    {
        if ($this->total_pertemuan > 0) {
            return (($this->total_hadir + $this->total_izin + $this->total_sakit) / $this->total_pertemuan) * 100;
        }
        return 0;
    }

    // Check apakah memenuhi minimal
    public function isMemenuhiMinimal()
    {
        return $this->status_minimal === 'Memenuhi';
    }

    // Get total kehadiran efektif (Hadir + Izin + Sakit)
    public function getTotalKehadiranEfektif()
    {
        return $this->total_hadir + $this->total_izin + $this->total_sakit;
    }

    // Scope untuk filter by mahasiswa
    public function scopeByMahasiswa($query, $mahasiswaId)
    {
        return $query->where('mahasiswa_id', $mahasiswaId);
    }

    // Scope untuk filter by kelas
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    // Scope untuk filter by semester
    public function scopeBySemester($query, $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }

    // Scope untuk filter yang memenuhi minimal
    public function scopeMemenuhiMinimal($query)
    {
        return $query->where('status_minimal', 'Memenuhi');
    }

    // Scope untuk filter yang tidak memenuhi minimal
    public function scopeTidakMemenuhiMinimal($query)
    {
        return $query->where('status_minimal', 'Tidak Memenuhi');
    }
}