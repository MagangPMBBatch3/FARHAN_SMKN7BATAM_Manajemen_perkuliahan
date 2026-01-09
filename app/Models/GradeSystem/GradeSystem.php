<?php

namespace App\Models\GradeSystem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Nilai\Nilai;

class GradeSystem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'grade_system';

    protected $fillable = [
        'grade',
        'min_score',
        'max_score',
        'grade_point',
        'status_kelulusan',
        'keterangan',
        'Status'
    ];

    protected function casts(): array
    {
        return [
            'min_score' => 'decimal:2',
            'max_score' => 'decimal:2',
            'grade_point' => 'decimal:2',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'nilai_huruf', 'grade');
    }

    // Helper method untuk mendapatkan grade berdasarkan nilai
    public static function getGradeByScore($score)
    {
        return static::where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->whereNull('deleted_at')
            ->first();
    }

    // Check apakah lulus
    public function isLulus()
    {
        return $this->status_kelulusan === 'Lulus';
    }

    // Scope untuk filter yang lulus
    public function scopeLulus($query)
    {
        return $query->where('status_kelulusan', 'Lulus');
    }

    // Scope untuk filter yang tidak lulus
    public function scopeTidakLulus($query)
    {
        return $query->where('status_kelulusan', 'Tidak Lulus');
    }

    // Scope untuk filter yang aktif
    public function scopeAktif($query)
    {
        return $query->where('Status', '1');
    }

    // Get grade point berdasarkan nilai
    public static function getGradePoint($score)
    {
        $grade = static::getGradeByScore($score);
        return $grade ? $grade->grade_point : 0;
    }
}