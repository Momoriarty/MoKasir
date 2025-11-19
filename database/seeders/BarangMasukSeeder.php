<?php
namespace Database\Seeders;

use App\Models\BarangMasuk;
use Illuminate\Database\Seeder;

class BarangMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BarangMasuk::create([
            'id_barang_masuk' => 'BM001',
            'id_barang'       => 1,
            'jumlah_kardus'   => 10,
            'jumlah_ecer'     => 50,
            'tanggal_masuk'   => '2024-09-01',
        ]);

        BarangMasuk::create([
            'id_barang_masuk' => 'BM002',
            'id_barang'       => 2,
            'jumlah_kardus'   => 5,
            'jumlah_ecer'     => 20,
            'tanggal_masuk'   => '2024-09-05',
        ]);

        BarangMasuk::create([
            'id_barang_masuk' => 'BM003',
            'id_barang'       => 1,
            'jumlah_kardus'   => 8,
            'jumlah_ecer'     => 30,
            'tanggal_masuk'   => '2024-09-10',
        ]);
    }
}
