<?php
namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    public function run()
    {
        // Pastikan sudah ada data user & barang
        $user   = User::first();
        $barang = Barang::all();

        if (! $user || $barang->count() == 0) {
            $this->command->warn("Seeder Transaksi di-skip karena user/barang belum ada.");
            return;
        }

        // Buat 5 Transaksi
        for ($i = 1; $i <= 5; $i++) {

            $transaksi = Transaksi::create([
                'tanggal'     => now()->subDays(rand(1, 30)),
                'id'          => $user->id,
                'total_bayar' => 0, // nanti akan di-update
            ]);

            $total = 0;

            // Tambahkan 1–3 detail untuk setiap transaksi
            for ($j = 1; $j <= rand(1, 3); $j++) {
                $pilihBarang = $barang->random();
                $jumlah      = rand(1, 5);
                $harga       = $pilihBarang->harga_jual ?? rand(5000, 50000);
                $subtotal    = $jumlah * $harga;

                TransaksiDetail::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'barang_id'    => $pilihBarang->id_barang,
                    'jumlah'       => $jumlah,
                    'harga_jual'   => $harga,
                    'subtotal'     => $subtotal,
                ]);

                $total += $subtotal;
            }

            // Update total bayar transaksi
            $transaksi->update(['total_bayar' => $total]);
        }
    }
}
