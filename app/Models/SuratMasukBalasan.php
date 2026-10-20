<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasukBalasan extends Model
{
    use HasFactory;

    protected $table = 'surat_masuk_balasan';

    protected $fillable = [
        'arsip_id',
        'peminjaman_id',
        'user_id',
        'isi_balasan',
        'attachment_path',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function arsip()
    {
        return $this->belongsTo(Arsip::class, 'arsip_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
