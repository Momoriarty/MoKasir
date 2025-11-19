<?php

namespace Database\Seeders;

use App\Models\PenitipanDetail;
use Illuminate\Database\Seeder;

class PenitipanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PenitipanDetail::create([
            'id_penitipan'  => 1,       // pastikan ID ini ada di tabel penitipans
            'nama_barang'   => 'Keripik Pisang',
            'harga_modal'   => 10000,
            'harga_jual'    => 15000,
            'jumlah_titip'  => 20,
            'jumlah_terjual'=> 5,
            'jumlah_sisa'   => 15,
        ]);

        PenitipanDetail::create([
            'id_penitipan'  => 1,
            'nama_barang'   => 'Brownies Kukus',
            'harga_modal'   => 20000,
            'harga_jual'    => 25000,
            'jumlah_titip'  => 10,
            'jumlah_terjual'=> 3,
            'jumlah_sisa'   => 7,
        ]);
    }
}
