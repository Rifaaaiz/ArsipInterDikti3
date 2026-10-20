<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('arsips', 'sifat_surat')) {
            Schema::table('arsips', function (Blueprint $table) {
                $table->string('sifat_surat', 32)->default('Biasa');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('arsips', 'sifat_surat')) {
            Schema::table('arsips', function (Blueprint $table) {
                $table->dropColumn('sifat_surat');
            });
        }
    }
};
