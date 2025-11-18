<?php

namespace App\Http\Controllers;

use App\Models\Penitipan;
use Illuminate\Http\Request;

class PenitipanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penitipans = Penitipan::all();
        return view('data.penitipan.index', compact('penitipans'));
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
            'nama_penitip' => 'required|string|max:100',
            'tanggal_titip' => 'required|date',
        ]);

        $data = Penitipan::create($request->all());

        return back()->with('success', 'Penitipan berhasil ditambahkan.');

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
            'nama_penitip' => 'required|string|max:100',
            'tanggal_titip' => 'required|date',
        ]);

        $data = Penitipan::findOrFail($id);
        $data->update($request->all());

        return back()->with('success', 'Penitipan berhasi di ubah.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Penitipan::findOrFail($id)->delete();
        return back()->with('success', 'Penitipan berhasil dihapus.');
    }
}
