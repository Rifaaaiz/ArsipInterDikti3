<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanValidation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'peminjaman_validations';

    protected $fillable = [
        'peminjaman_id',
        'validator_id',
        'status',
        'alasan_penolakan',
        'validated_at',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(
            Peminjaman::class,
            'peminjaman_id'
        );
    }

    public function validator()
    {
        return $this->belongsTo(
            User::class,
            'validator_id'
        );
    }
}