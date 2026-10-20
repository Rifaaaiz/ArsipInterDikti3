<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('peminjaman_validations')) {
            return;
        }

        Schema::create('peminjaman_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjamans')->cascadeOnDelete();
            $table->foreignId('validator_id')->constrained('users')->cascadeOnDelete();
            $table->string('status');
            $table->string('alasan_penolakan', 500)->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->index(['peminjaman_id', 'status']);
        });
    }

    public function down(): void
    {
        if (
            Schema::hasTable('peminjaman_validations')
            && ! DB::table('peminjaman_validations')->exists()
        ) {
            Schema::drop('peminjaman_validations');
        }
    }
};
