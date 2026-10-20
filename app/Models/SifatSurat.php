<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SifatSurat extends Model
{
    use HasFactory;

    protected $table = 'sifat_surats';

    protected $fillable = [
        'name',
        'slug',
    ];

    public $timestamps = false;

    public function arsips()
    {
        return $this->hasMany(Arsip::class, 'sifat_surat_id');
    }
}
