<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('arsips', 'submission_token')) {
            Schema::table('arsips', function (Blueprint $table) {
                $table->uuid('submission_token')->nullable()->unique();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('arsips', 'submission_token')) {
            Schema::table('arsips', function (Blueprint $table) {
                $table->dropUnique(['submission_token']);
                $table->dropColumn('submission_token');
            });
        }
    }
};
