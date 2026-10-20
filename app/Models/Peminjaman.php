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
    'sifat_surat_id',
    'klasifikasi_arsip_id',
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

    public function balasans()
    {
        return $this->hasMany(SuratMasukBalasan::class, 'peminjaman_id')->oldest();
    }

    public function kategori()
    {
        return $this->belongsTo(
            KategoriArsip::class,
            'kategori_id'
        );
    }

    public function sifatSurat()
    {
        return $this->belongsTo(SifatSurat::class, 'sifat_surat_id');
    }

    public function klasifikasiArsip()
    {
        return $this->belongsTo(KlasifikasiArsip::class, 'klasifikasi_arsip_id');
    }

    public function scopeNeedsKetuaValidation(Builder $query): Builder
    {
        return $query->where('status', 'menunggu_validasi')
            ->whereDoesntHave('validations', function (Builder $validation) {
                $validation->whereHas('validator.role', function (Builder $role) {
                    $role->where('slug', 'ketua_pokja');
                });
            });
    }

    public function scopeReadyForFinalValidation(Builder $query): Builder
    {
        return $query->where('status', 'menunggu_validasi')
            ->whereHas('validations', function (Builder $validation) {
                $validation->where('status', 'disetujui')
                    ->whereHas('validator.role', function (Builder $role) {
                        $role->where('slug', 'ketua_pokja');
                    });
            });
    }

    public function scopeActiveNow(Builder $query): Builder
    {
        return $query->where(function (Builder $subQuery) {
                $subQuery->where('status', 'aktif')
                    ->orWhere('status', 'disetujui');
            })
            ->whereDate('tanggal_mulai', '<=', now()->toDateString())
            ->whereDate('tanggal_selesai', '>=', now()->toDateString());
    }

    public function getDisplayStatusAttribute(): string
    {
        $status = strtolower((string) ($this->status ?? ''));
        $now = now();

        if ($status === 'ditolak') {
            return 'Ditolak';
        }

        if ($status === 'menunggu_validasi') {
            return 'Menunggu';
        }

        if (in_array($status, ['selesai', 'dikembalikan'], true)) {
            return 'Selesai';
        }

        if (! in_array($status, ['aktif', 'disetujui'], true)) {
            return 'Menunggu';
        }

        if ($this->tanggal_selesai && $now->greaterThan($this->tanggal_selesai->endOfDay())) {
            return 'Selesai';
        }

        if ($this->tanggal_mulai && $now->lessThan($this->tanggal_mulai->startOfDay())) {
            return 'Diterima';
        }

        if ($this->tanggal_mulai && $this->tanggal_selesai && $now->between($this->tanggal_mulai->startOfDay(), $this->tanggal_selesai->endOfDay())) {
            return 'Aktif';
        }

        return $this->tanggal_mulai && $now->lt($this->tanggal_mulai) ? 'Diterima' : 'Aktif';
    }

    public function scopeCompletedNow(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->where('status', 'selesai')
                ->orWhere(function (Builder $subQuery) {
                    $subQuery->whereIn('status', ['aktif', 'disetujui'])
                        ->whereDate('tanggal_selesai', '<', now()->toDateString());
                });
        });
    }
}
