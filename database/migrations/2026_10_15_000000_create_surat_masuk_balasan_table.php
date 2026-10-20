<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_masuk_balasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arsip_id')->constrained('arsips')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->text('isi_balasan');
            $table->timestamps();

            $table->index(['arsip_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuk_balasan');
    }
};
