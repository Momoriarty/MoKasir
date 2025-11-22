<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\Barang;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barangMasuks = BarangMasuk::with('barang')->get();
        $barangs = Barang::all();

        return view('data.barang_masuk.index', compact('barangMasuks', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'jumlah_kardus' => 'required|integer|min:0',
            'jumlah_ecer' => 'required|integer|min:0',
            'tanggal_masuk' => 'required|date',
        ]);

        $barangMasuk = BarangMasuk::create($request->all());

        $barang = Barang::find($request->id_barang);
        if ($barang) {
            $barang->stok_kardus += $request->jumlah_kardus;
            $barang->stok_ecer += $request->jumlah_ecer;
            $barang->save();
        }

        return back()->with('success', 'Barang masuk berhasil ditambahkan dan stok diperbarui.');
    }


    public function update(Request $request, BarangMasuk $barang_masuk)
    {
        $request->validate([
            'id_barang' => 'required',
            'jumlah_kardus' => 'required|integer|min:0',
            'jumlah_ecer' => 'required|integer|min:0',
            'tanggal_masuk' => 'required|date',
        ]);

        // Ambil barang terkait
        $barang = Barang::find($request->id_barang);
        if ($barang) {
            // Hitung selisih stok
            $selisihKardus = $request->jumlah_kardus - $barang_masuk->jumlah_kardus;
            $selisihEcer = $request->jumlah_ecer - $barang_masuk->jumlah_ecer;

            $barang->stok_kardus += $selisihKardus;
            $barang->stok_ecer += $selisihEcer;
            $barang->save();
        }

        // Update record barang_masuks
        $barang_masuk->update($request->all());

        return back()->with('success', 'Barang masuk berhasil diperbarui dan stok disesuaikan.');
    }


    public function destroy(BarangMasuk $barang_masuk)
    {
        // Ambil barang terkait
        $barang = Barang::find($barang_masuk->id_barang);
        if ($barang) {
            // Kurangi stok sesuai jumlah barang masuk yang dihapus
            $barang->stok_kardus -= $barang_masuk->jumlah_kardus;
            $barang->stok_ecer -= $barang_masuk->jumlah_ecer;

            // Pastikan stok tidak negatif
            $barang->stok_kardus = max($barang->stok_kardus, 0);
            $barang->stok_ecer = max($barang->stok_ecer, 0);

            $barang->save();
        }

        // Hapus record barang_masuks
        $barang_masuk->delete();

        return back()->with('success', 'Barang masuk berhasil dihapus dan stok diperbarui.');
    }

}
