<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // ===================== BARANG LAMA =====================
            [
                'nama_barang' => 'Aqua Botol 600ml',
                'kategori' => 'Minuman',
                'harga_modal_kardus' => 48000,
                'harga_modal_ecer' => 2500,
                'harga_jual_kardus' => 55000,
                'harga_jual_ecer' => 3000,
                'isi_per_kardus' => 24,
                'stok_kardus' => 20,
                'stok_ecer' => 50,
            ],
            [
                'nama_barang' => 'Indomie Goreng',
                'kategori' => 'Makanan',
                'harga_modal_kardus' => 72000,
                'harga_modal_ecer' => 2800,
                'harga_jual_kardus' => 80000,
                'harga_jual_ecer' => 3500,
                'isi_per_kardus' => 40,
                'stok_kardus' => 15,
                'stok_ecer' => 100,
            ],
            [
                'nama_barang' => 'Susu Beruang',
                'kategori' => 'Minuman',
                'harga_modal_kardus' => 220000,
                'harga_modal_ecer' => 8000,
                'harga_jual_kardus' => 250000,
                'harga_jual_ecer' => 10000,
                'isi_per_kardus' => 24,
                'stok_kardus' => 10,
                'stok_ecer' => 20,
            ],
            [
                'nama_barang' => 'Telur Ayam 1 Rak',
                'kategori' => 'Sembako',
                'harga_modal_kardus' => 40000,
                'harga_modal_ecer' => 1500,
                'harga_jual_kardus' => 50000,
                'harga_jual_ecer' => 2000,
                'isi_per_kardus' => 30,
                'stok_kardus' => 8,
                'stok_ecer' => 0,
            ],
            [
                'nama_barang' => 'Beras Ramos 5kg',
                'kategori' => 'Sembako',
                'harga_modal_kardus' => 55000,
                'harga_modal_ecer' => 0,
                'harga_jual_kardus' => 65000,
                'harga_jual_ecer' => 0,
                'isi_per_kardus' => 1,
                'stok_kardus' => 30,
                'stok_ecer' => 0,
            ],

            // ===================== TAMBAHAN BARANG GROSIR =====================
            [
                'nama_barang' => 'Gula Pasir 1kg',
                'kategori' => 'Sembako',
                'harga_modal_kardus' => 16000 * 10,
                'harga_modal_ecer' => 16000,
                'harga_jual_kardus' => 17000 * 10,
                'harga_jual_ecer' => 17000,
                'isi_per_kardus' => 10,
                'stok_kardus' => 50,
                'stok_ecer' => 200,
            ],
            [
                'nama_barang' => 'Garam Halus 500g',
                'kategori' => 'Sembako',
                'harga_modal_kardus' => 5000 * 20,
                'harga_modal_ecer' => 5000,
                'harga_jual_kardus' => 5500 * 20,
                'harga_jual_ecer' => 5500,
                'isi_per_kardus' => 20,
                'stok_kardus' => 30,
                'stok_ecer' => 100,
            ],
            [
                'nama_barang' => 'Minyak Goreng 1L',
                'kategori' => 'Sembako',
                'harga_modal_kardus' => 14000 * 12,
                'harga_modal_ecer' => 14000,
                'harga_jual_kardus' => 15000 * 12,
                'harga_jual_ecer' => 15000,
                'isi_per_kardus' => 12,
                'stok_kardus' => 40,
                'stok_ecer' => 120,
            ],
            [
                'nama_barang' => 'Tepung Terigu 1kg',
                'kategori' => 'Sembako',
                'harga_modal_kardus' => 13000 * 20,
                'harga_modal_ecer' => 13000,
                'harga_jual_kardus' => 14000 * 20,
                'harga_jual_ecer' => 14000,
                'isi_per_kardus' => 20,
                'stok_kardus' => 25,
                'stok_ecer' => 50,
            ],
            [
                'nama_barang' => 'Mie Instan Indomie Goreng',
                'kategori' => 'Makanan',
                'harga_modal_kardus' => 3500 * 40,
                'harga_modal_ecer' => 3500,
                'harga_jual_kardus' => 4000 * 40,
                'harga_jual_ecer' => 4000,
                'isi_per_kardus' => 40,
                'stok_kardus' => 30,
                'stok_ecer' => 200,
            ],
            [
                'nama_barang' => 'Kopi Sachet 20g',
                'kategori' => 'Minuman',
                'harga_modal_kardus' => 2000 * 50,
                'harga_modal_ecer' => 2000,
                'harga_jual_kardus' => 2200 * 50,
                'harga_jual_ecer' => 2200,
                'isi_per_kardus' => 50,
                'stok_kardus' => 20,
                'stok_ecer' => 500,
            ],
            [
                'nama_barang' => 'Permen Cokelat (batang kecil)',
                'kategori' => 'Snack',
                'harga_modal_kardus' => 1000 * 100,
                'harga_modal_ecer' => 1000,
                'harga_jual_kardus' => 1200 * 100,
                'harga_jual_ecer' => 1200,
                'isi_per_kardus' => 100,
                'stok_kardus' => 10,
                'stok_ecer' => 1000,
            ],
            [
                'nama_barang' => 'Keripik Kentang 50g',
                'kategori' => 'Snack',
                'harga_modal_kardus' => 2500 * 60,
                'harga_modal_ecer' => 2500,
                'harga_jual_kardus' => 3000 * 60,
                'harga_jual_ecer' => 3000,
                'isi_per_kardus' => 60,
                'stok_kardus' => 15,
                'stok_ecer' => 500,
            ],
            [
                'nama_barang' => 'Sabun Mandi Batang',
                'kategori' => 'Kebersihan',
                'harga_modal_kardus' => 4000 * 50,
                'harga_modal_ecer' => 4000,
                'harga_jual_kardus' => 4500 * 50,
                'harga_jual_ecer' => 4500,
                'isi_per_kardus' => 50,
                'stok_kardus' => 20,
                'stok_ecer' => 200,
            ],
            [
                'nama_barang' => 'Detergen Bubuk 1kg',
                'kategori' => 'Kebersihan',
                'harga_modal_kardus' => 5000 * 20,
                'harga_modal_ecer' => 5000,
                'harga_jual_kardus' => 5500 * 20,
                'harga_jual_ecer' => 5500,
                'isi_per_kardus' => 20,
                'stok_kardus' => 30,
                'stok_ecer' => 60,
            ],
            [
                'nama_barang' => 'Sirup ABC Jeruk 460ml',
                'kategori' => 'Minuman',
                'harga_modal_kardus' => 6000 * 12,
                'harga_modal_ecer' => 6000,
                'harga_jual_kardus' => 7000 * 12,
                'harga_jual_ecer' => 7000,
                'isi_per_kardus' => 12,
                'stok_kardus' => 10,
                'stok_ecer' => 120,
            ],
        ];

        foreach ($data as $item) {
            $barang = Barang::create($item);

            // Jika stok awal > 0, buat record barang masuk
            if ($item['stok_kardus'] > 0 || $item['stok_ecer'] > 0) {
                BarangMasuk::create([
                    'id_barang' => $barang->id_barang,
                    'jumlah_kardus' => $item['stok_kardus'],
                    'jumlah_ecer' => $item['stok_ecer'],
                    'tanggal_masuk' => now(),
                ]);
            }
        }

    }
}
