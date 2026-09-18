<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriArsip;
use Illuminate\Support\Str;

class KategoriArsipSeeder extends Seeder
{
    /**
     * Sesuaikan daftar nama kategori ini dengan kategori surat/arsip
     * yang sebenarnya dipakai di instansi Anda. Seeder ini hanya
     * memastikan tabel kategori_arsips terisi supaya dropdown pada
     * form peminjaman punya opsi yang valid untuk dipilih.
     */
    public function run(): void
    {
        $kategoris = [
            'Surat Vital',
            'Surat Penting',
            'Surat Biasa',
            'Surat Rahasia',
        ];

        foreach ($kategoris as $nama) {
            KategoriArsip::updateOrCreate(
                ['slug' => Str::slug($nama)],
                ['name' => $nama],
            );
        }
    }
}
