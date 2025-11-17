<?php

namespace App\Http\Controllers;

use App\Models\BarangRusak;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangRusakController extends Controller
{
    public function index()
    {
        $barangRusaks = BarangRusak::with('barang')->get();
        $barangs = Barang::all();
        return view('barang_rusak.index', compact('barangRusaks', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'jumlah_kardus' => 'required|integer|min:0',
            'jumlah_ecer' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'tanggal_rusak' => 'required|date',
        ]);

        BarangRusak::create($request->all());

        return back()->with('success', 'Data barang rusak berhasil ditambahkan.');
    }

    public function update(Request $request, BarangRusak $barang_rusak)
    {
        $request->validate([
            'id_barang' => 'required',
            'jumlah_kardus' => 'required|integer|min:0',
            'jumlah_ecer' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'tanggal_rusak' => 'required|date',
        ]);

        $barang_rusak->update($request->all());

        return back()->with('success', 'Data barang rusak berhasil diperbarui.');
    }

    public function destroy(BarangRusak $barang_rusak)
    {
        $barang_rusak->delete();

        return back()->with('success', 'Data barang rusak berhasil dihapus.');
    }
}

