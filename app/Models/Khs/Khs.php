<?php

namespace App\Models\Khs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Mahasiswa\Mahasiswa;
use App\Models\Semester\Semester;
use App\Models\Nilai\Nilai;

class Khs extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'khs';

    protected $fillable = [
        'mahasiswa_id',
        'semester_id',
        'sks_semester',
        'sks_kumulatif',
        'ip_semester',
        'ipk',
    ];

    protected function casts(): array
    {
        return [
            'sks_semester' => 'integer',
            'sks_kumulatif' => 'integer',
            'ip_semester' => 'decimal:2',
            'ipk' => 'decimal:2',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
    public function nilai()
{
    return $this->hasMany(Nilai::class, 'khs_id');
}
}