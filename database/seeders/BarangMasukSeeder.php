<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BarangMasuk;

class BarangMasukSeeder extends Seeder
{
    public function run(): void
    {
        $barangMasukData = [
            [
                'id_barang' => 1, // Aqua Botol 600ml
                'jumlah_kardus' => 20,
                'jumlah_ecer' => 50,
                'tanggal_masuk' => '2025-01-01',
            ],
            [
                'id_barang' => 2, // Indomie Goreng
                'jumlah_kardus' => 15,
                'jumlah_ecer' => 100,
                'tanggal_masuk' => '2025-01-02',
            ],
            [
                'id_barang' => 3, // Susu Beruang
                'jumlah_kardus' => 10,
                'jumlah_ecer' => 20,
                'tanggal_masuk' => '2025-01-03',
            ],
            [
                'id_barang' => 4, // Telur Ayam 1 Rak
                'jumlah_kardus' => 8,
                'jumlah_ecer' => 0,
                'tanggal_masuk' => '2025-01-05',
            ],
            [
                'id_barang' => 5, // Beras Ramos 5kg
                'jumlah_kardus' => 30,
                'jumlah_ecer' => 0,
                'tanggal_masuk' => '2025-01-06',
            ],
            [
                'id_barang' => 6, // Gula Pasir 1kg
                'jumlah_kardus' => 50,
                'jumlah_ecer' => 200,
                'tanggal_masuk' => '2025-01-07',
            ],
            [
                'id_barang' => 7, // Garam Halus 500g
                'jumlah_kardus' => 30,
                'jumlah_ecer' => 100,
                'tanggal_masuk' => '2025-01-08',
            ],
            [
                'id_barang' => 8, // Minyak Goreng 1L
                'jumlah_kardus' => 40,
                'jumlah_ecer' => 120,
                'tanggal_masuk' => '2025-01-09',
            ],
            [
                'id_barang' => 9, // Tepung Terigu 1kg
                'jumlah_kardus' => 25,
                'jumlah_ecer' => 50,
                'tanggal_masuk' => '2025-01-10',
            ],
            [
                'id_barang' => 10, // Mie Instan Indomie Goreng
                'jumlah_kardus' => 30,
                'jumlah_ecer' => 200,
                'tanggal_masuk' => '2025-01-11',
            ],
            [
                'id_barang' => 11, // Kopi Sachet 20g
                'jumlah_kardus' => 20,
                'jumlah_ecer' => 500,
                'tanggal_masuk' => '2025-01-12',
            ],
            [
                'id_barang' => 12, // Permen Cokelat
                'jumlah_kardus' => 10,
                'jumlah_ecer' => 1000,
                'tanggal_masuk' => '2025-01-13',
            ],
            [
                'id_barang' => 13, // Keripik Kentang 50g
                'jumlah_kardus' => 15,
                'jumlah_ecer' => 500,
                'tanggal_masuk' => '2025-01-14',
            ],
            [
                'id_barang' => 14, // Sabun Mandi Batang
                'jumlah_kardus' => 20,
                'jumlah_ecer' => 200,
                'tanggal_masuk' => '2025-01-15',
            ],
            [
                'id_barang' => 15, // Detergen Bubuk 1kg
                'jumlah_kardus' => 30,
                'jumlah_ecer' => 60,
                'tanggal_masuk' => '2025-01-16',
            ],
            [
                'id_barang' => 16, // Sirup ABC Jeruk 460ml
                'jumlah_kardus' => 10,
                'jumlah_ecer' => 120,
                'tanggal_masuk' => '2025-01-17',
            ],
        ];

        foreach ($barangMasukData as $item) {
            BarangMasuk::create($item);
        }
    }
}
