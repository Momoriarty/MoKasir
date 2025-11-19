<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_barang'        => 'Aqua Botol 600ml',
                'kategori'           => 'Minuman',
                'harga_modal_kardus' => 48000,
                'harga_modal_ecer'   => 2500,
                'harga_jual_kardus'  => 55000,
                'harga_jual_ecer'    => 3000,
                'isi_per_kardus'     => 24,
                'stok_kardus'        => 20,
                'stok_ecer'          => 50,
            ],
            [
                'nama_barang'        => 'Indomie Goreng',
                'kategori'           => 'Makanan',
                'harga_modal_kardus' => 72000,
                'harga_modal_ecer'   => 2800,
                'harga_jual_kardus'  => 80000,
                'harga_jual_ecer'    => 3500,
                'isi_per_kardus'     => 40,
                'stok_kardus'        => 15,
                'stok_ecer'          => 100,
            ],
            [
                'nama_barang'        => 'Susu Beruang',
                'kategori'           => 'Minuman',
                'harga_modal_kardus' => 220000,
                'harga_modal_ecer'   => 8000,
                'harga_jual_kardus'  => 250000,
                'harga_jual_ecer'    => 10000,
                'isi_per_kardus'     => 24,
                'stok_kardus'        => 10,
                'stok_ecer'          => 20,
            ],
            [
                'nama_barang'        => 'Telur Ayam 1 Rak',
                'kategori'           => 'Sembako',
                'harga_modal_kardus' => 40000,
                'harga_modal_ecer'   => 1500,
                'harga_jual_kardus'  => 50000,
                'harga_jual_ecer'    => 2000,
                'isi_per_kardus'     => 30, // 1 rak = 30 butir
                'stok_kardus'        => 8,
                'stok_ecer'          => 0,
            ],
            [
                'nama_barang'        => 'Beras Ramos 5kg',
                'kategori'           => 'Sembako',
                'harga_modal_kardus' => 55000,
                'harga_modal_ecer'   => 0,
                'harga_jual_kardus'  => 65000,
                'harga_jual_ecer'    => 0,
                'isi_per_kardus'     => 1,
                'stok_kardus'        => 30,
                'stok_ecer'          => 0,
            ],
        ];

        foreach ($data as $item) {
            Barang::create($item);
        }
    }
}
