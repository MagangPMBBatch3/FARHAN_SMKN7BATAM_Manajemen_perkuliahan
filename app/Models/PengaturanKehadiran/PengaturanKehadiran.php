<?php

namespace App\Models\PengaturanKehadiran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Kelas\Kelas;

class PengaturanKehadiran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengaturan_kehadiran';
    
    protected $fillable = [
        'kelas_id',
        'minimal_kehadiran',
        'auto_generate_pertemuan',
        'keterangan',
        'aktif'
    ];

    protected function casts(): array
    {
        return [
            'minimal_kehadiran' => 'decimal:2',
            'auto_generate_pertemuan' => 'boolean',
            'aktif' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Check apakah mahasiswa memenuhi minimal kehadiran
    public function isMemenuhiMinimal($persentaseKehadiran)
    {
        return $persentaseKehadiran >= $this->minimal_kehadiran;
    }

    // Scope untuk filter by kelas
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    // Scope untuk filter yang aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Scope untuk filter auto generate
    public function scopeAutoGenerate($query)
    {
        return $query->where('auto_generate_pertemuan', true);
    }

    // Get or create default settings untuk kelas
    public static function getOrCreateForKelas($kelasId)
    {
        return static::firstOrCreate(
            ['kelas_id' => $kelasId],
            [
                'minimal_kehadiran' => 75.00,
                'auto_generate_pertemuan' => true,
                'aktif' => true,
                'keterangan' => 'Minimal kehadiran untuk ikut UAS'
            ]
        );
    }
}