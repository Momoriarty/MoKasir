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

        return view('barang_masuk.index', compact('barangMasuks', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'jumlah_kardus' => 'required|integer|min:0',
            'jumlah_ecer' => 'required|integer|min:0',
            'tanggal_masuk' => 'required|date',
        ]);

        BarangMasuk::create($request->all());

        return back()->with('success', 'Barang masuk berhasil ditambahkan.');
    }

    public function update(Request $request, BarangMasuk $barang_masuk)
    {
        $request->validate([
            'id_barang' => 'required',
            'jumlah_kardus' => 'required|integer|min:0',
            'jumlah_ecer' => 'required|integer|min:0',
            'tanggal_masuk' => 'required|date',
        ]);

        $barang_masuk->update($request->all());

        return back()->with('success', 'Barang masuk berhasil diperbarui.');
    }

    public function destroy(BarangMasuk $barang_masuk)
    {
        $barang_masuk->delete();

        return back()->with('success', 'Barang masuk berhasil dihapus.');
    }
}
