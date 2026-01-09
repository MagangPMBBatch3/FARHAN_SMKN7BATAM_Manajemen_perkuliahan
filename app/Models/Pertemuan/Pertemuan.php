<?php

namespace App\Models\Pertemuan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Kelas\Kelas;
use App\Models\Ruangan\Ruangan;
use App\Models\User\User;

class Pertemuan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pertemuan';

    protected $fillable = [
        'kelas_id',
        'pertemuan_ke',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'materi',
        'metode',
        'ruangan_id',
        'status_pertemuan',
        'link_daring',
        'catatan',
        'created_by'
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'waktu_mulai' => 'datetime:H:i',
            'waktu_selesai' => 'datetime:H:i',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'pertemuan_id');
    }

    // Check status pertemuan
    public function isDijadwalkan()
    {
        return $this->status_pertemuan === 'Dijadwalkan';
    }

    public function isBerlangsung()
    {
        return $this->status_pertemuan === 'Berlangsung';
    }

    public function isSelesai()
    {
        return $this->status_pertemuan === 'Selesai';
    }

    public function isDibatalkan()
    {
        return $this->status_pertemuan === 'Dibatalkan';
    }

    // Check metode
    public function isTatapMuka()
    {
        return $this->metode === 'Tatap Muka';
    }

    public function isDaring()
    {
        return $this->metode === 'Daring';
    }

    public function isHybrid()
    {
        return $this->metode === 'Hybrid';
    }

    // Scope untuk filter by kelas
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    // Scope untuk filter by tanggal
    public function scopeByTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }

    // Scope untuk filter by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_pertemuan', $status);
    }

    // Scope untuk filter dijadwalkan
    public function scopeDijadwalkan($query)
    {
        return $query->where('status_pertemuan', 'Dijadwalkan');
    }

    // Scope untuk filter berlangsung
    public function scopeBerlangsung($query)
    {
        return $query->where('status_pertemuan', 'Berlangsung');
    }

    // Scope untuk filter selesai
    public function scopeSelesai($query)
    {
        return $query->where('status_pertemuan', 'Selesai');
    }

    // Scope untuk filter dibatalkan
    public function scopeDibatalkan($query)
    {
        return $query->where('status_pertemuan', 'Dibatalkan');
    }

    // Scope untuk filter by metode
    public function scopeByMetode($query, $metode)
    {
        return $query->where('metode', $metode);
    }

    // Scope untuk filter tatap muka
    public function scopeTatapMuka($query)
    {
        return $query->where('metode', 'Tatap Muka');
    }

    // Scope untuk filter daring
    public function scopeDaring($query)
    {
        return $query->where('metode', 'Daring');
    }

    // Scope untuk pertemuan hari ini
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', today());
    }

    // Scope untuk pertemuan mendatang
    public function scopeMendatang($query)
    {
        return $query->where('tanggal', '>=', today())
            ->where('status_pertemuan', 'Dijadwalkan');
    }

    // Get durasi pertemuan dalam menit
    public function getDurasiMenit()
    {
        $mulai = \Carbon\Carbon::parse($this->waktu_mulai);
        $selesai = \Carbon\Carbon::parse($this->waktu_selesai);
        return $mulai->diffInMinutes($selesai);
    }

    // Get persentase kehadiran untuk pertemuan ini
    public function getPersentaseKehadiran()
    {
        $total = $this->kehadiran()->count();
        if ($total === 0) return 0;
        
        $hadir = $this->kehadiran()->kehadiranEfektif()->count();
        return ($hadir / $total) * 100;
    }

    // Get statistik kehadiran
    public function getStatistikKehadiran()
    {
        return [
            'total' => $this->kehadiran()->count(),
            'hadir' => $this->kehadiran()->hadir()->count(),
            'izin' => $this->kehadiran()->izin()->count(),
            'sakit' => $this->kehadiran()->sakit()->count(),
            'alpa' => $this->kehadiran()->alpa()->count(),
        ];
    }
}