<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('peminjamans')) {
            return;
        }

        if (! Schema::hasColumn('peminjamans', 'kategori_id')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->foreignId('kategori_id')
                    ->after('nama_surat')
                    ->nullable()
                    ->constrained('jenis_surats')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('peminjamans') || ! Schema::hasColumn('peminjamans', 'kategori_id')) {
            return;
        }

        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });
    }
};