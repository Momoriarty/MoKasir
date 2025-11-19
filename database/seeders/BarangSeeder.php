<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        Barang::create([
            'nama_barang'          => 'Aqua Botol 600ml',
            'harga_modal_kardus'   => 45000.00,
            'harga_modal_ecer'     => 2000.00,
            'harga_jual_kardus'    => 55000.00,
            'harga_jual_ecer'      => 3000.00,
            'isi_per_kardus'       => 12,
            'stok_kardus'          => 10,
            'stok_ecer'            => 30,
        ]);

        Barang::create([
            'nama_barang'          => 'Indomie Goreng',
            'harga_modal_kardus'   => 85000.00,
            'harga_modal_ecer'     => 3500.00,
            'harga_jual_kardus'    => 95000.00,
            'harga_jual_ecer'      => 4500.00,
            'isi_per_kardus'       => 40,
            'stok_kardus'          => 5,
            'stok_ecer'            => 15,
        ]);
    }
}
