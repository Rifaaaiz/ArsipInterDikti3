<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KlasifikasiArsip extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode', 'kode_induk', 'bab', 'bab_urutan', 'kolom_tampilan', 'nomor', 'urutan', 'judul', 'uraian', 'masa_aktif_tahun',
        'masa_aktif_catatan', 'masa_inaktif_tahun', 'keterangan', 'klasifikasi_keamanan',
    ];

    public function arsips()
    {
        return $this->hasMany(Arsip::class);
    }
}
