<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsip_vitals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('arsip_id')->unique()->constrained('arsips')->cascadeOnDelete();
            $table->string('status', 20)->default('aktif')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip_vitals');
    }
};
