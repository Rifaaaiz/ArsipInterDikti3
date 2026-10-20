<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const DEFAULT_SIFAT = 'Biasa';

    private const REQUIRED_SIFAT = [
        'Biasa' => 'biasa',
        'Rahasia' => 'rahasia',
        'Sangat Rahasia' => 'sangat-rahasia',
        'Terbatas' => 'terbatas',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('arsips')) {
            throw new RuntimeException('Tabel arsips tidak ditemukan.');
        }

        if (! Schema::hasTable('sifat_surats')) {
            Schema::create('sifat_surats', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->unique();
            });
        }

        if (! Schema::hasColumns('sifat_surats', ['id', 'name', 'slug'])) {
            throw new RuntimeException('Struktur tabel sifat_surats tidak sesuai: dibutuhkan id, name, dan slug.');
        }

        foreach (self::REQUIRED_SIFAT as $name => $slug) {
            if (! DB::table('sifat_surats')->where('name', $name)->exists()) {
                DB::table('sifat_surats')->insert([
                    'name' => $name,
                    'slug' => $slug,
                ]);
            }
        }

        if (Schema::hasTable('sifat_surats')) {
            try {
                DB::statement('ALTER TABLE sifat_surats MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
            } catch (\Throwable $e) {
                // Ignore if already bigint unsigned or unsupported
            }
        }

        if (! Schema::hasColumn('arsips', 'sifat_surat_id')) {
            Schema::table('arsips', function (Blueprint $table) {
                $table->foreignId('sifat_surat_id')
                    ->nullable()
                    ->after('klasifikasi_arsip_id')
                    ->constrained('sifat_surats')
                    ->nullOnDelete();
            });
        } else {
            $hasFk = collect(DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'arsips' AND CONSTRAINT_TYPE = 'FOREIGN KEY' AND CONSTRAINT_NAME = 'arsips_sifat_surat_id_foreign'"))->isNotEmpty();
            if (! $hasFk) {
                Schema::table('arsips', function (Blueprint $table) {
                    $table->foreign('sifat_surat_id', 'arsips_sifat_surat_id_foreign')
                        ->references('id')
                        ->on('sifat_surats')
                        ->nullOnDelete();
                });
            }
        }

        if (Schema::hasColumn('arsips', 'sifat_surat')) {
            $sifatSurats = DB::table('sifat_surats')->get(['id', 'name']);
            $knownNames = [];

            foreach ($sifatSurats as $sifatSurat) {
                $normalizedName = mb_strtolower(trim($sifatSurat->name));
                $knownNames[$normalizedName] = $sifatSurat->id;

                DB::table('arsips')
                    ->whereRaw('LOWER(TRIM(sifat_surat)) = ?', [$normalizedName])
                    ->update(['sifat_surat_id' => $sifatSurat->id]);
            }

            $defaultSifatId = $knownNames[mb_strtolower(self::DEFAULT_SIFAT)] ?? null;
            if ($defaultSifatId === null) {
                throw new RuntimeException('Sifat surat default "Biasa" tidak tersedia pada tabel sifat_surats.');
            }

            DB::table('arsips')
                ->whereNull('sifat_surat')
                ->update(['sifat_surat_id' => $defaultSifatId]);

            $unmappedValues = DB::table('arsips')
                ->whereNotNull('sifat_surat')
                ->distinct()
                ->pluck('sifat_surat')
                ->filter(fn ($value) => ! array_key_exists(mb_strtolower(trim($value)), $knownNames))
                ->values()
                ->all();

            if ($unmappedValues !== []) {
                throw new RuntimeException(
                    'Migrasi dibatalkan karena terdapat sifat_surat yang tidak dikenal: ' . implode(', ', $unmappedValues)
                );
            }

            Schema::table('arsips', function (Blueprint $table) {
                $table->dropColumn('sifat_surat');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('arsips') || ! Schema::hasColumn('arsips', 'sifat_surat_id')) {
            return;
        }

        if (! Schema::hasColumn('arsips', 'sifat_surat')) {
            Schema::table('arsips', function (Blueprint $table) {
                $table->string('sifat_surat', 32)->default(self::DEFAULT_SIFAT);
            });
        }

        DB::table('arsips')
            ->leftJoin('sifat_surats', 'sifat_surats.id', '=', 'arsips.sifat_surat_id')
            ->update([
                'arsips.sifat_surat' => DB::raw("COALESCE(sifat_surats.name, '" . self::DEFAULT_SIFAT . "')"),
            ]);

        Schema::table('arsips', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sifat_surat_id');
        });
    }
};
