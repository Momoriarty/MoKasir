<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return view('kasir', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_harga' => 'required|numeric',
            'total_bayar' => 'required|numeric',
            'metode' => 'required|string',
            'barang' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $transaksi = Transaksi::create([
                'tanggal' => Carbon::now(),
                'id_user' => Auth::id(),
                'total_harga' => $request->total_harga,
                'total_bayar' => $request->total_bayar,
                'metode' => $request->metode,
            ]);

            foreach ($request->barang as $item) {
                TransaksiDetail::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                    'harga_jual' => $item['harga_jual'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan.',
                'id_transaksi' => $transaksi->id_transaksi
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
