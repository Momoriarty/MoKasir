<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BarangMasukController extends Controller
{
    public function index()
    {
    $barangMasuks = BarangMasuk::with('barang')->paginate(10);
    $barangs = Barang::all(); // <-- ini wajib ditambahkan

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
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'id_barang' => 'required|exists:barangs,id_barang', // sesuaikan dengan kolom PK tabel barangs
            'jumlah_kardus' => 'required|integer|min:0',
            'jumlah_ecer' => 'required|integer|min:0',
            'tanggal_masuk' => 'required|date',
        ]);

        // Ambil record BarangMasuk lama dari DB
        $barang_masuk = BarangMasuk::findOrFail($id);

        $newBarangId = $request->id_barang;
        $newJumlahKardus = (int) $request->jumlah_kardus;
        $newJumlahEcer = (int) $request->jumlah_ecer;

        // 1️⃣ Kurangi stok barang lama
        $oldBarangId = $barang_masuk->id_barang;
        $oldBarang = Barang::find($oldBarangId);
        if ($oldBarang) {
            $oldBarang->stok_kardus = max(0, $oldBarang->stok_kardus - $barang_masuk->jumlah_kardus);
            $oldBarang->stok_ecer = max(0, $oldBarang->stok_ecer - $barang_masuk->jumlah_ecer);
            $oldBarang->save();
        }

        // 2️⃣ Tambah stok barang baru
        $newBarang = Barang::find($newBarangId);
        if ($newBarang) {
            $newBarang->stok_kardus += $newJumlahKardus;
            $newBarang->stok_ecer += $newJumlahEcer;
            $newBarang->save();
        }

        // 3️⃣ Update record BarangMasuk
        $barang_masuk->update($request->only('id_barang', 'jumlah_kardus', 'jumlah_ecer', 'tanggal_masuk'));

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
