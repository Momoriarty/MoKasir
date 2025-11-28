<?php
namespace App\Http\Controllers;

use App\Models\Penitipan;
use App\Models\PenitipanDetail;
use Illuminate\Http\Request;

class PenitipanDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $penitipanDetail = PenitipanDetail::with('penitipan')->get();
        $penitipans      = Penitipan::all();
        return view('data.penitipan_detail.index', compact('penitipanDetail', 'penitipans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_penitipan'   => 'required|exists:penitipans,id_penitipan',
            'nama_barang'    => 'required|string|max:100',
            'harga_modal'    => 'required|numeric|min:0',
            'harga_jual'     => 'required|numeric|min:0',
            'jumlah_titip'   => 'required|integer|min:1',
            'jumlah_terjual' => 'nullable|integer|min:0',
            'jumlah_sisa'    => 'required|integer|min:0',
        ]);

        $data = PenitipanDetail::create($request->all());

        return back()->with('success', 'Penitipan detail berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_penitipan'   => 'required|exists:penitipans,id_penitipan',
            'nama_barang'    => 'required|string|max:100',
            'harga_modal'    => 'required|numeric|min:0',
            'harga_jual'     => 'required|numeric|min:0',
            'jumlah_titip'   => 'required|integer|min:1',
            'jumlah_terjual' => 'nullable|integer|min:0',
            'jumlah_sisa'    => 'required|integer|min:0',
        ]);

        $data = PenitipanDetail::findOrFail($id);
        $data->update($request->all());

        return back()->with('success', 'Penitipan detail berhasil diPerbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        PenitipanDetail::findOrFail($id)->delete();
        return back()->with('success', 'Penitipan berhasil ditambahkan.');
    }
}
