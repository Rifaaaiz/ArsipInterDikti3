<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arsip extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'arsips';

    protected $fillable = [
        'nomor_dokumen',
        'nama_arsip',
        'kategori_arsip_id',
        'pokja_id',
        'tanggal_dokumen',
        'file_path',
        'file_type',
        'file_size',
        'status',
        'uploaded_by',
        'masa_retensi_sampai',
    ];

    protected $casts = [
        'tanggal_dokumen' => 'date',
        'masa_retensi_sampai' => 'date',
    ];

    public function kategori()
    {
        return $this->belongsTo(
            KategoriArsip::class,
            'kategori_arsip_id'
        );
    }

    public function pokja()
    {
        return $this->belongsTo(
            Pokja::class,
            'pokja_id'
        );
    }

    public function uploader()
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    public function peminjamans()
    {
        return $this->hasMany(
            Peminjaman::class,
            'arsip_id'
        );
    }
}