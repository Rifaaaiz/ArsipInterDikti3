<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('klasifikasi_arsips', function (Blueprint $table) {
            $table->unsignedTinyInteger('bab_urutan')->nullable()->after('bab');
            $table->unsignedTinyInteger('kolom_tampilan')->nullable()->after('bab_urutan');
            $table->string('kode_induk')->nullable()->after('kode');
            $table->unsignedSmallInteger('urutan')->nullable()->after('nomor');
            $table->index(['bab_urutan', 'urutan']);
            $table->index('kode_induk');
        });
    }

    public function down(): void
    {
        Schema::table('klasifikasi_arsips', function (Blueprint $table) {
            $table->dropIndex(['bab_urutan', 'urutan']);
            $table->dropIndex(['kode_induk']);
            $table->dropColumn(['bab_urutan', 'kolom_tampilan', 'kode_induk', 'urutan']);
        });
    }
};
