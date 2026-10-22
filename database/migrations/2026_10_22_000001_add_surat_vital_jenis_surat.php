<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::table('jenis_surats')->where('slug', 'surat_vital')->orWhere('name', 'Surat Vital')->exists()) {
            DB::table('jenis_surats')->insert([
                'name' => 'Surat Vital',
                'slug' => 'surat_vital',
            ]);
        }
    }

    public function down(): void
    {
        $jenisSurat = DB::table('jenis_surats')->where('slug', 'surat_vital')->first();

        if ($jenisSurat && ! DB::table('arsips')->where('kategori_arsip_id', $jenisSurat->id)->exists()) {
            DB::table('jenis_surats')->where('id', $jenisSurat->id)->delete();
        }
    }
};
