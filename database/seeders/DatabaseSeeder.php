<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KategoriArsipSeeder::class,
            KlasifikasiArsipSeeder::class,
            JraHierarchySeeder::class,
            AdminPokjaSeeder::class,
        ]);
    }
}
