<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('arsip_id')
                ->constrained('arsips')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('keperluan');

            $table->date('tanggal_mulai');

            $table->date('tanggal_selesai');

            $table->text('keterangan')->nullable();

            $table->enum('status', [
                'menunggu_validasi',
                'disetujui',
                'ditolak',
                'aktif',
                'selesai',
            ])->default('menunggu_validasi');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};