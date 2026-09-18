<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
    'arsip_id',
    'nama_surat',
    'kategori_id',
    'user_id',
    'keperluan',
    'tanggal_mulai',
    'tanggal_selesai',
    'keterangan',
    'file_pengajuan',
    'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function arsip()
    {
        return $this->belongsTo(Arsip::class, 'arsip_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function validations()
    {
        return $this->hasMany(
            PeminjamanValidation::class,
            'peminjaman_id'
        );
    }

    public function kategori()
    {
        return $this->belongsTo(
            KategoriArsip::class,
            'kategori_id'
        );
    }

    public function scopeNeedsKetuaValidation(Builder $query): Builder
    {
        return $query->where('status', 'menunggu_validasi')
            ->whereHas('user.role', function (Builder $role) {
                $role->where('slug', 'admin_pokja');
            })
            ->whereDoesntHave('validations', function (Builder $validation) {
                $validation->whereHas('validator.role', function (Builder $role) {
                    $role->where('slug', 'ketua_pokja');
                });
            });
    }

    public function scopeReadyForFinalValidation(Builder $query): Builder
    {
        return $query->where('status', 'menunggu_validasi')
            ->where(function (Builder $outer) {
                $outer->whereHas('user.role', function (Builder $role) {
                    $role->where('slug', 'ketua_pokja');
                })->orWhereHas('validations', function (Builder $validation) {
                    $validation->where('status', 'disetujui')
                        ->whereHas('validator.role', function (Builder $role) {
                            $role->where('slug', 'ketua_pokja');
                        });
                });
            });
    }

    public function scopeActiveNow(Builder $query): Builder
    {
        return $query->where('status', 'aktif')
            ->whereDate('tanggal_mulai', '<=', now()->toDateString())
            ->whereDate('tanggal_selesai', '>=', now()->toDateString());
    }

    public function scopeCompletedNow(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->where('status', 'selesai')
                ->orWhere(function (Builder $subQuery) {
                    $subQuery->where('status', 'aktif')
                        ->whereDate('tanggal_selesai', '<', now()->toDateString());
                });
        });
    }
}