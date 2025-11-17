<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::all();
        return view('data.barang.index', compact('barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'harga_modal_kardus' => 'required|numeric|min:0',
            'harga_modal_ecer' => 'required|numeric|min:0',
            'harga_jual_kardus' => 'required|numeric|min:0',
            'harga_jual_ecer' => 'required|numeric|min:0',
            'isi_per_kardus' => 'required|integer|min:1',
            'stok_kardus' => 'required|integer|min:0',
            'stok_ecer' => 'required|integer|min:0',
        ]);

        Barang::create($validated);

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'harga_modal_kardus' => 'required|numeric|min:0',
            'harga_modal_ecer' => 'required|numeric|min:0',
            'harga_jual_kardus' => 'required|numeric|min:0',
            'harga_jual_ecer' => 'required|numeric|min:0',
            'isi_per_kardus' => 'required|integer|min:1',
            'stok_kardus' => 'required|integer|min:0',
            'stok_ecer' => 'required|integer|min:0',
        ]);

        $barang->update($validated);

        return redirect()->back()->with('success', 'Barang berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus.');
    }
}
