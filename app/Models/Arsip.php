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
        'klasifikasi_arsip_id',
        'sifat_surat_id',
        'pokja_id',
        'tanggal_dokumen',
        'tanda_tangan',
        'keterangan',
        'file_path',
        'file_type',
        'file_size',
        'status',
        'uploaded_by',
        'masa_retensi_sampai',
        'submission_token',
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

    public function klasifikasiArsip()
    {
        return $this->belongsTo(KlasifikasiArsip::class);
    }

    public function arsipVital()
    {
        return $this->hasOne(ArsipVital::class);
    }

    public function sifatSurat()
    {
        return $this->belongsTo(SifatSurat::class, 'sifat_surat_id');
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

    public function latestPeminjaman()
    {
        return $this->hasOne(Peminjaman::class, 'arsip_id')
            ->latestOfMany('tanggal_mulai');
    }

    public function balasans()
    {
        return $this->hasMany(SuratMasukBalasan::class, 'arsip_id')->oldest();
    }
}
