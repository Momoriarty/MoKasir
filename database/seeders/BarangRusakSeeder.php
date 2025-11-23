<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BarangRusak;

class BarangRusakSeeder extends Seeder
{
    public function run(): void
    {
        $barangRusakData = [
            [
                'id_barang' => 1, // Aqua Botol 600ml
                'jumlah_kardus' => 2,
                'jumlah_ecer' => 10,
                'keterangan' => 'Rusak karena bocor',
                'tanggal_rusak' => '2024-09-15',
            ],
            [
                'id_barang' => 2, // Indomie Goreng
                'jumlah_kardus' => 1,
                'jumlah_ecer' => 5,
                'keterangan' => 'Kemasan penyok',
                'tanggal_rusak' => '2024-09-20',
            ],
            [
                'id_barang' => 1, // Aqua Botol 600ml
                'jumlah_kardus' => 1,
                'jumlah_ecer' => 3,
                'keterangan' => 'Kadaluarsa mendekat',
                'tanggal_rusak' => '2024-09-25',
            ],
            [
                'id_barang' => 3, // Susu Beruang
                'jumlah_kardus' => 1,
                'jumlah_ecer' => 2,
                'keterangan' => 'Dikemas pecah',
                'tanggal_rusak' => '2024-10-01',
            ],
            [
                'id_barang' => 5, // Beras Ramos 5kg
                'jumlah_kardus' => 2,
                'jumlah_ecer' => 0,
                'keterangan' => 'Hama serang',
                'tanggal_rusak' => '2024-10-05',
            ],
        ];

        foreach ($barangRusakData as $item) {
            BarangRusak::create($item);
        }
    }
}
