<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil keyword search
        $search = $request->input('nama_barang');

        // Query dengan scope search
        $barangs = Barang::searchNama($search)
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString(); // agar pagination tetap menyertakan query search

        return view('data.barang.index', compact('barangs', 'search'));
    }

    public function create()
    {
        return view('data.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'kategori'    => 'required|string|max:50',
            'harga_modal_kardus' => 'required|integer',
            'harga_modal_ecer'   => 'required|integer',
            'harga_jual_kardus'  => 'required|integer',
            'harga_jual_ecer'    => 'required|integer',
            'isi_per_kardus'     => 'required|integer',
            'stok_kardus'        => 'required|integer',
            'stok_ecer'          => 'required|integer',
        ]);

        Barang::create($request->all());

        return back()->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $barang = Barang::findOrFail($id);
        return view('data.barang.edit', compact('barang'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'kategori'    => 'required|string|max:50',
            'harga_modal_kardus' => 'required|integer',
            'harga_modal_ecer'   => 'required|integer',
            'harga_jual_kardus'  => 'required|integer',
            'harga_jual_ecer'    => 'required|integer',
            'isi_per_kardus'     => 'required|integer',
            'stok_kardus'        => 'required|integer',
            'stok_ecer'          => 'required|integer',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return back()->with('success', 'Barang berhasil diubah.');
    }

    public function destroy(string $id)
    {
        Barang::findOrFail($id)->delete();
        return back()->with('success', 'Barang berhasil dihapus.');
    }
}
