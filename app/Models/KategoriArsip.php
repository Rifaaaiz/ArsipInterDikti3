<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriArsip extends Model
{
    use HasFactory;

    protected $table = 'kategori_arsips';

    protected $fillable = [
        'name',
        'slug',
    ];

    public $timestamps = false;

    public function arsips()
    {
        return $this->hasMany(
            Arsip::class,
            'kategori_arsip_id'
        );
    }
}