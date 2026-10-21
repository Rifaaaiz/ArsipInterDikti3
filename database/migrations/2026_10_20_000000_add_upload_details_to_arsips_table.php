<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('arsips', function (Blueprint $table): void {
            $table->string('tanda_tangan', 20)->nullable();
            $table->text('keterangan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('arsips', function (Blueprint $table): void {
            $table->dropColumn(['tanda_tangan', 'keterangan']);
        });
    }
};
