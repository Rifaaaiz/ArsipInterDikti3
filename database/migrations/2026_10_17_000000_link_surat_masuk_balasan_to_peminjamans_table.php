<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            'ALTER TABLE surat_masuk_balasan MODIFY arsip_id BIGINT UNSIGNED NULL'
        );

        Schema::table('surat_masuk_balasan', function (Blueprint $table) {
            $table->foreignId('peminjaman_id')
                ->nullable()
                ->after('arsip_id')
                ->constrained('peminjamans')
                ->cascadeOnDelete();
            $table->index(['peminjaman_id', 'created_at']);
        });
    }

    public function down(): void
    {
        if (DB::table('surat_masuk_balasan')->whereNull('arsip_id')->exists()) {
            throw new RuntimeException(
                'Cannot roll back: loan replies without an arsip_id still exist.'
            );
        }

        Schema::table('surat_masuk_balasan', function (Blueprint $table) {
            $table->dropForeign(['peminjaman_id']);
            $table->dropIndex(['peminjaman_id', 'created_at']);
            $table->dropColumn('peminjaman_id');
        });

        DB::statement(
            'ALTER TABLE surat_masuk_balasan MODIFY arsip_id BIGINT UNSIGNED NOT NULL'
        );
    }
};
