<?php

namespace App\Models\Kehadiran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Mahasiswa\Mahasiswa;
use App\Models\Kelas\Kelas;
use App\Models\KRS\KrsDetail;
use App\Models\User\User;
use App\Models\Pertemuan\Pertemuan;

class Kehadiran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kehadiran';

    protected $fillable = [
        'pertemuan_id',
        'mahasiswa_id',
        'krs_detail_id',
        'status_kehadiran',
        'waktu_input',
        'keterangan',
        'diinput_oleh'
    ];

    protected function casts(): array
    {
        return [
            'waktu_input' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class, 'pertemuan_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function krsDetail()
    {
        return $this->belongsTo(KrsDetail::class, 'krs_detail_id');
    }

    public function inputBy()
    {
        return $this->belongsTo(User::class, 'diinput_oleh');
    }

    // Check status kehadiran
    public function isHadir()
    {
        return $this->status_kehadiran === 'Hadir';
    }

    public function isIzin()
    {
        return $this->status_kehadiran === 'Izin';
    }

    public function isSakit()
    {
        return $this->status_kehadiran === 'Sakit';
    }

    public function isAlpa()
    {
        return $this->status_kehadiran === 'Alpa';
    }

    public function isKehadiranEfektif()
    {
        return in_array($this->status_kehadiran, ['Hadir', 'Izin', 'Sakit']);
    }

    // Scope untuk filter by mahasiswa
    public function scopeByMahasiswa($query, $mahasiswaId)
    {
        return $query->where('mahasiswa_id', $mahasiswaId);
    }

    // Scope untuk filter by pertemuan
    public function scopeByPertemuan($query, $pertemuanId)
    {
        return $query->where('pertemuan_id', $pertemuanId);
    }

    // Scope untuk filter by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_kehadiran', $status);
    }

    // Scope untuk filter hadir
    public function scopeHadir($query)
    {
        return $query->where('status_kehadiran', 'Hadir');
    }

    // Scope untuk filter izin
    public function scopeIzin($query)
    {
        return $query->where('status_kehadiran', 'Izin');
    }

    // Scope untuk filter sakit
    public function scopeSakit($query)
    {
        return $query->where('status_kehadiran', 'Sakit');
    }

    // Scope untuk filter alpa
    public function scopeAlpa($query)
    {
        return $query->where('status_kehadiran', 'Alpa');
    }

    // Scope untuk kehadiran efektif
    public function scopeKehadiranEfektif($query)
    {
        return $query->whereIn('status_kehadiran', ['Hadir', 'Izin', 'Sakit']);
    }

    // Scope untuk filter by kelas (via pertemuan)
    public function scopeByKelas($query, $kelasId)
    {
        return $query->whereHas('pertemuan', function($q) use ($kelasId) {
            $q->where('kelas_id', $kelasId);
        });
    }

    // Get total kehadiran mahasiswa di kelas tertentu
    public static function getTotalKehadiranMahasiswa($mahasiswaId, $kelasId)
    {
        return static::byMahasiswa($mahasiswaId)
            ->byKelas($kelasId)
            ->selectRaw('
                COUNT(*) as total_pertemuan,
                SUM(CASE WHEN status_kehadiran = "Hadir" THEN 1 ELSE 0 END) as total_hadir,
                SUM(CASE WHEN status_kehadiran = "Izin" THEN 1 ELSE 0 END) as total_izin,
                SUM(CASE WHEN status_kehadiran = "Sakit" THEN 1 ELSE 0 END) as total_sakit,
                SUM(CASE WHEN status_kehadiran = "Alpa" THEN 1 ELSE 0 END) as total_alpa
            ')
            ->first();
    }
}