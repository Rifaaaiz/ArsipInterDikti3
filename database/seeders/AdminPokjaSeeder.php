<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role;
use App\Models\Pokja;

class AdminPokjaSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::updateOrCreate(
            ['slug' => 'admin_pokja'],
            ['name' => 'Admin Pokja']
        );

        $ketuaRole = Role::updateOrCreate(
            ['slug' => 'ketua_pokja'],
            ['name' => 'Ketua Pokja']
        );

        $adminTuRole = Role::updateOrCreate(
            ['slug' => 'admin_tu'],
            ['name' => 'Admin TU']
        );

        $superadminRole = Role::updateOrCreate(
            ['slug' => 'superadmin'],
            ['name' => 'Super Admin']
        );

        $pokjaNames = [
            'Kelembagaan dan Kemitraan',
            'Penjaminan Mutu',
            'Anti Dosa Pendidikan dan Integritas Akademik',
            'Sumber Daya',
            'Pembelajaran, Kemahasiswaan, dan Prestasi',
            'Sistem Informasi dan PDDikti',
            'Riset dan Pengabdian Masyarakat',
            'Perencanaan dan Keuangan',
            'Hukum, Kepegawaian, dan Tata Laksana',
            'Tata Usaha dan Barang Milik Negara',
            'Hubungan Masyarakat dan Kerja Sama',
        ];

        $pokjaMap = [];
        foreach ($pokjaNames as $pokjaName) {
            $pokja = Pokja::updateOrCreate(
                ['slug' => Str::slug($pokjaName)],
                ['name' => $pokjaName]
            );
            $pokjaMap[$pokjaName] = $pokja;
        }

        $defaultPokja = $pokjaMap['Kelembagaan dan Kemitraan'];

        User::updateOrCreate(
            ['username' => 'adminpokja'],
            [
                'name' => 'Admin Pokja Kelembagaan',
                'email' => 'adminpokja@simarsip.test',
                'password' => Hash::make('12345678'),
                'role_id' => $role->id,
                'pokja_id' => $defaultPokja->id,
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['username' => 'ketuapokja'],
            [
                'name' => 'Ketua Pokja Kelembagaan',
                'email' => 'ketuapokja@simarsip.test',
                'password' => Hash::make('12345678'),
                'role_id' => $ketuaRole->id,
                'pokja_id' => $defaultPokja->id,
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