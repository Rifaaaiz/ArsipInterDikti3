<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Pokja;

class AdminPokjaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat role Admin Pokja
        $role = Role::updateOrCreate(
            ['slug' => 'admin_pokja'],
            [
                'name' => 'Admin Pokja',
            ]
        );

        Role::updateOrCreate(
            ['slug' => 'ketua_pokja'],
            [
                'name' => 'Ketua Pokja',
            ]
        );

        $adminTuRole = Role::updateOrCreate(
            ['slug' => 'admin_tu'],
            [
                'name' => 'Admin TU',
            ]
        );

        $superadminRole = Role::updateOrCreate(
            ['slug' => 'superadmin'],
            [
                'name' => 'Super Admin',
            ]
        );

        // 2. Buat Pokja Kelembagaan
        $pokja = Pokja::updateOrCreate(
            ['slug' => 'kelembagaan'],
            [
                'name' => 'Kelembagaan',
            ]
        );

        // 3. Buat user Admin Pokja
        User::updateOrCreate(
            ['username' => 'adminpokja'],
            [
                'name' => 'Admin Pokja Kelembagaan',
                'email' => 'adminpokja@simarsip.test',
                'password' => Hash::make('12345678'),
                'role_id' => $role->id,
                'pokja_id' => $pokja->id,
                'status' => 'active',
            ]
        );

        $ketuaRole = Role::where('slug', 'ketua_pokja')->firstOrFail();

        User::updateOrCreate(
            ['username' => 'ketuapokja'],
            [
                'name' => 'Ketua Pokja Kelembagaan',
                'email' => 'ketuapokja@simarsip.test',
                'password' => Hash::make('12345678'),
                'role_id' => $ketuaRole->id,
                'pokja_id' => $pokja->id,
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['username' => 'admintu'],
            [
                'name' => 'Admin TU',
                'email' => 'admintu@simarsip.test',
                'password' => Hash::make('12345678'),
                'role_id' => $adminTuRole->id,
                'pokja_id' => null,
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@simarsip.test',
                'password' => Hash::make('12345678'),
                'role_id' => $superadminRole->id,
                'pokja_id' => null,
                'status' => 'active',
            ]
        );
    }
}