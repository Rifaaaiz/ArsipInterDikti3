<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipVital extends Model
{
    protected $fillable = [
        'arsip_id',
        'status',
    ];

    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }
}
