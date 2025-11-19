<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BarangRusak;

class BarangRusakSeeder extends Seeder
{
    public function run(): void
    {
        BarangRusak::create([
            'id_barang'     => 1,
            'jumlah_kardus' => 2,
            'jumlah_ecer'   => 10,
            'keterangan'    => 'Rusak karena bocor',
            'tanggal_rusak'       => '2024-09-15',
        ]);

        BarangRusak::create([
            'id_barang'     => 2,
            'jumlah_kardus' => 1,
            'jumlah_ecer'   => 5,
            'keterangan'    => 'Kemasan penyok',
            'tanggal_rusak'       => '2024-09-20',
        ]);

        BarangRusak::create([
            'id_barang'     => 1,
            'jumlah_kardus' => 1,
            'jumlah_ecer'   => 3,
            'keterangan'    => 'Kadaluarsa mendekat',
            'tanggal_rusak'       => '2024-09-25',
        ]);
    }
}
