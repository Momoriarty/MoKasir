<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PenitipanDetail;

class PenitipanDetailSeeder extends Seeder
{
    public function run(): void
    {
        $penitipanDetailData = [
            [
                'id_penitipan' => 1,
                'nama_barang' => 'Keripik Pisang',
                'harga_modal' => 10000,
                'harga_jual' => 15000,
                'jumlah_titip' => 20,
                'jumlah_terjual' => 5,
                'jumlah_sisa' => 15,
            ],
            [
                'id_penitipan' => 1,
                'nama_barang' => 'Brownies Kukus',
                'harga_modal' => 20000,
                'harga_jual' => 25000,
                'jumlah_titip' => 10,
                'jumlah_terjual' => 3,
                'jumlah_sisa' => 7,
            ],
            [
                'id_penitipan' => 2,
                'nama_barang' => 'Kue Lapis',
                'harga_modal' => 15000,
                'harga_jual' => 20000,
                'jumlah_titip' => 15,
                'jumlah_terjual' => 4,
                'jumlah_sisa' => 11,
            ],
            [
                'id_penitipan' => 2,
                'nama_barang' => 'Roti Manis',
                'harga_modal' => 12000,
                'harga_jual' => 17000,
                'jumlah_titip' => 10,
                'jumlah_terjual' => 6,
                'jumlah_sisa' => 4,
            ],
            [
                'id_penitipan' => 3,
                'nama_barang' => 'Kue Cubit',
                'harga_modal' => 8000,
                'harga_jual' => 12000,
                'jumlah_titip' => 25,
                'jumlah_terjual' => 10,
                'jumlah_sisa' => 15,
            ],
            [
                'id_penitipan' => 4,
                'nama_barang' => 'Pisang Goreng',
                'harga_modal' => 5000,
                'harga_jual' => 8000,
                'jumlah_titip' => 30,
                'jumlah_terjual' => 12,
                'jumlah_sisa' => 18,
            ],
        ];

        foreach ($penitipanDetailData as $item) {
            PenitipanDetail::create($item);
        }
    }
}
