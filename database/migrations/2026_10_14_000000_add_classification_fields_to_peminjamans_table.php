<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->foreignId('sifat_surat_id')->nullable()->after('kategori_id')->constrained('sifat_surats')->nullOnDelete();
            $table->foreignId('klasifikasi_arsip_id')->nullable()->after('sifat_surat_id')->constrained('klasifikasi_arsips')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropForeign(['sifat_surat_id']);
            $table->dropForeign(['klasifikasi_arsip_id']);
            $table->dropColumn(['sifat_surat_id', 'klasifikasi_arsip_id']);
        });
    }
};
