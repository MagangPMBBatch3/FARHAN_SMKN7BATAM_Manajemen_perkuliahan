<?php

namespace App\Models\SksLimit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SksLimit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sks_limits';

    protected $fillable = [
        'min_ipk',
        'max_ipk',
        'max_sks',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }
}