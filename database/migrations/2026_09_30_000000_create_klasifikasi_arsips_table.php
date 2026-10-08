<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klasifikasi_arsips', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->index();
            $table->string('bab');
            $table->string('nomor')->nullable();
            $table->string('judul');
            $table->text('uraian');
            $table->unsignedSmallInteger('masa_aktif_tahun')->nullable();
            $table->string('masa_aktif_catatan')->nullable();
            $table->unsignedSmallInteger('masa_inaktif_tahun')->nullable();
            $table->string('keterangan');
            $table->string('klasifikasi_keamanan');
            $table->timestamps();
        });

        Schema::table('arsips', function (Blueprint $table) {
            $table->foreignId('klasifikasi_arsip_id')->nullable()->after('kategori_arsip_id')->constrained('klasifikasi_arsips')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('arsips', function (Blueprint $table) {
            $table->dropConstrainedForeignId('klasifikasi_arsip_id');
        });
        Schema::dropIfExists('klasifikasi_arsips');
    }
};
