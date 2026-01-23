<?php

namespace App\Models\Nilai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\KrsDetail\KrsDetail;

class Nilai extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'nilai';
    
    protected $fillable = [
        'krs_detail_id',
        'bobot_nilai_id',
        'tugas',
        'quiz',
        'uts',
        'uas',
        'kehadiran',
        'praktikum',
        'nilai_akhir',
        'nilai_huruf',
        'nilai_mutu',
        'status',
        'input_by'
    ];
    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    public function krsDetail()
    {
        return $this->belongsTo(KrsDetail::class, 'krs_detail_id');
    }

    public function bobotNilai()
    {
        return $this->belongsTo(BobotNilai::class, 'bobot_nilai_id');
    }

    public function inputBy()
    {
        return $this->belongsTo(User::class, 'input_by');
    }
}
