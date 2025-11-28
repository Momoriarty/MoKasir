<?php
namespace App\Http\Controllers;

use App\Models\Penitipan;
use Illuminate\Http\Request;

class PenitipanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // Query tanpa eager loading dulu
        $query = Penitipan::query();

        // Filter berdasarkan nama penitip
        if ($request->filled('nama_penitip')) {
            $query->where('nama_penitip', 'like', '%' . $request->nama_penitip . '%');
        }

        // Filter berdasarkan tanggal dari
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_titip', '>=', $request->tanggal_dari);
        }

        // Filter berdasarkan tanggal sampai
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_titip', '<=', $request->tanggal_sampai);
        }

        $penitipans = $query->latest('created_at')->paginate(10);
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
            'nama_penitip'  => 'required|string|max:100',
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
            'nama_penitip'  => 'required|string|max:100',
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
