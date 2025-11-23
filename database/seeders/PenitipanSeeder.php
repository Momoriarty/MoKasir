<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penitipan;

class PenitipanSeeder extends Seeder
{
    public function run(): void
    {
        $penitipanData = [
            [
                'nama_penitip' => 'Penitipan A',
                'tanggal_titip' => '2024-01-15',
            ],
            [
                'nama_penitip' => 'Penitipan B',
                'tanggal_titip' => '2024-01-16',
            ],
            [
                'nama_penitip' => 'Penitipan C',
                'tanggal_titip' => '2024-02-01',
            ],
            [
                'nama_penitip' => 'Penitipan D',
                'tanggal_titip' => '2024-02-05',
            ],
        ];

        foreach ($penitipanData as $item) {
            Penitipan::create($item);
        }
    }
}
