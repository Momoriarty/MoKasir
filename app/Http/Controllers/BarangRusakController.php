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
        return view('data.barang_rusak.index', compact('barangRusaks', 'barangs'));
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

        $barang = Barang::find($request->id_barang);
        if ($barang) {
            $barang->stok_kardus -= $request->jumlah_kardus;
            $barang->stok_ecer -= $request->jumlah_ecer;

            $barang->stok_kardus = max($barang->stok_kardus, 0);
            $barang->stok_ecer = max($barang->stok_ecer, 0);

            $barang->save();
        }

        BarangRusak::create($request->all());

        return back()->with('success', 'Data barang rusak berhasil ditambahkan dan stok diperbarui.');
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

        $barang = Barang::find($request->id_barang);
        if ($barang) {
            // Hitung selisih
            $selisihKardus = $request->jumlah_kardus - $barang_rusak->jumlah_kardus;
            $selisihEcer = $request->jumlah_ecer - $barang_rusak->jumlah_ecer;

            $barang->stok_kardus -= $selisihKardus;
            $barang->stok_ecer -= $selisihEcer;

            $barang->stok_kardus = max($barang->stok_kardus, 0);
            $barang->stok_ecer = max($barang->stok_ecer, 0);

            $barang->save();
        }

        $barang_rusak->update($request->all());

        return back()->with('success', 'Data barang rusak berhasil diperbarui dan stok disesuaikan.');
    }

    public function destroy(BarangRusak $barang_rusak)
    {
        $barang = Barang::find($barang_rusak->id_barang);
        if ($barang) {
            $barang->stok_kardus += $barang_rusak->jumlah_kardus;
            $barang->stok_ecer += $barang_rusak->jumlah_ecer;
            $barang->save();
        }

        $barang_rusak->delete();

        return back()->with('success', 'Data barang rusak berhasil dihapus dan stok dikembalikan.');
    }

}

