<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Note: we use a raw ALTER TABLE instead of Blueprint::change()
     * because doctrine/dbal is not installed in this project, and
     * change() requires it. The foreign key constraint on arsip_id
     * (arsips.id, cascade on delete) is preserved — MySQL allows
     * toggling NULL/NOT NULL on a column without touching its FK,
     * as long as the column type/name stay the same.
     */
    public function up(): void
    {
        DB::statement(
            'ALTER TABLE peminjamans MODIFY arsip_id BIGINT UNSIGNED NULL'
        );
    }

    public function down(): void
    {
        DB::statement(
            'ALTER TABLE peminjamans MODIFY arsip_id BIGINT UNSIGNED NOT NULL'
        );
    }
};
