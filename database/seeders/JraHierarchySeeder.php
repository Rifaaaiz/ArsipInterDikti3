<?php

namespace Database\Seeders;

use App\Models\KlasifikasiArsip;
use Illuminate\Database\Seeder;

class JraHierarchySeeder extends Seeder
{
    public function run(): void
    {
        $babOrder = [
            'DT' => 1, 'KL' => 2, 'AL' => 3, 'KM' => 4, 'TU' => 5, 'RT' => 6,
            'LK' => 7, 'HM' => 8, 'PR' => 9, 'KU' => 10, 'KP' => 11, 'HK' => 12,
            'OT' => 13, 'WS' => 14,
        ];

        KlasifikasiArsip::query()->orderBy('id')->each(function (KlasifikasiArsip $klasifikasi) use ($babOrder): void {
            $label = trim($klasifikasi->bab);
            $prefix = strtoupper(substr($label, 0, 2));
            $klasifikasi->bab_urutan = $babOrder[$prefix] ?? 99;
            $klasifikasi->kolom_tampilan = null;
            $klasifikasi->save();
        });

        // Posisi BAB I mengikuti struktur JRA, bukan awalan kode.
        $babSatu = [
            'DT.04.01' => [1, 10],
            'AL.03' => [2, 20],
            'AL.04', 'AL.04.00', 'AL.04.01', 'AL.04.02', 'AL.04.04', 'AL.04.05' => [2, 30],
            'DT.06', 'DT.06.01', 'DT.06.02', 'DT.06.03', 'DT.06.04', 'DT.06.05' => [2, 40],
            'HM.01', 'HM.01.01' => [2, 50],
        ];

        foreach ($babSatu as $kode => $position) {
            KlasifikasiArsip::where('kode', $kode)->update([
                'bab' => 'DT — SUMBER DAYA PERGURUAN TINGGI',
                'bab_urutan' => 1,
                'kolom_tampilan' => $position[0],
                'urutan' => $position[1],
            ]);
        }
    }
}
