<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Carbon\Carbon;

class KasirController extends Controller
{
    public function index()
    {
        try {
            $barangs = Barang::orderBy('nama_barang')->get();
            return view('kasir', compact('barangs'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat halaman kasir: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Log request untuk debugging
            \Log::info('Kasir Store Request:', $request->all());

            // Validasi input
            $validated = $request->validate([
                'total_harga' => 'required|numeric|min:0',
                'total_bayar' => 'required|numeric|min:0',
                'metode' => 'required|in:Tunai,Qris',
                'barang' => 'required|array|min:1',
                'barang.*.id_barang' => 'required|exists:barangs,id_barang',
                'barang.*.jumlah_kardus' => 'required|integer|min:0',
                'barang.*.jumlah_ecer' => 'required|integer|min:0',
                'barang.*.harga_kardus' => 'nullable|numeric|min:0',
                'barang.*.harga_ecer' => 'nullable|numeric|min:0',
                'barang.*.subtotal' => 'required|numeric|min:0',
            ]);

            // Validasi pembayaran
            if ($request->total_bayar < $request->total_harga) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah bayar kurang dari total harga'
                ], 400);
            }

            DB::beginTransaction();

            // Validasi stok untuk setiap barang
            foreach ($request->barang as $item) {
                $barang = Barang::lockForUpdate()->find($item['id_barang']);

                if (!$barang) {
                    throw new \Exception("Barang ID {$item['id_barang']} tidak ditemukan");
                }

                // Cek stok kardus
                if ($item['jumlah_kardus'] > $barang->stok_kardus) {
                    throw new \Exception("Stok kardus {$barang->nama_barang} tidak mencukupi. Tersedia: {$barang->stok_kardus}, Diminta: {$item['jumlah_kardus']}");
                }

                // Cek stok ecer
                if ($item['jumlah_ecer'] > $barang->stok_ecer) {
                    throw new \Exception("Stok ecer {$barang->nama_barang} tidak mencukupi. Tersedia: {$barang->stok_ecer}, Diminta: {$item['jumlah_ecer']}");
                }

                // Minimal harus ada transaksi (kardus atau ecer)
                if ($item['jumlah_kardus'] <= 0 && $item['jumlah_ecer'] <= 0) {
                    throw new \Exception("Jumlah kardus dan ecer tidak boleh nol untuk {$barang->nama_barang}");
                }
            }

            // Hitung total pajak/biaya admin untuk QRIS
            $biayaAdmin = 0;
            if ($request->metode === 'Qris') {
                $biayaAdmin = 1000;
            }

            $totalHargaFinal = $request->total_harga + $biayaAdmin;

            // Buat transaksi
            $transaksi = Transaksi::create([
                'tanggal' => Carbon::now(),
                'id_user' => Auth::id(),
                'total_harga' => $totalHargaFinal,
                'total_bayar' => $request->total_bayar,
                'metode' => $request->metode,
            ]);

            \Log::info('Transaksi created:', ['id' => $transaksi->id_transaksi]);

            // Simpan detail transaksi dan update stok
            foreach ($request->barang as $item) {
                // Ambil barang dengan lock
                $barang = Barang::lockForUpdate()->find($item['id_barang']);

                // Simpan detail transaksi
                TransaksiDetail::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_barang' => $item['id_barang'],
                    'jumlah_kardus' => $item['jumlah_kardus'],
                    'jumlah_ecer' => $item['jumlah_ecer'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Update stok barang
                $barang->stok_kardus -= $item['jumlah_kardus'];
                $barang->stok_ecer -= $item['jumlah_ecer'];
                $barang->save();

                \Log::info("Stok updated", [
                    'barang' => $barang->nama_barang,
                    'kardus' => -$item['jumlah_kardus'],
                    'ecer' => -$item['jumlah_ecer'],
                    'stok_kardus_remaining' => $barang->stok_kardus,
                    'stok_ecer_remaining' => $barang->stok_ecer
                ]);
            }

            DB::commit();

            \Log::info('Transaction completed successfully');

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'transaksi' => [
                    'id_transaksi' => $transaksi->id_transaksi,
                    'total_harga' => $transaksi->total_harga,
                    'total_bayar' => $transaksi->total_bayar,
                    'metode' => $transaksi->metode,
                    'kembalian' => $transaksi->total_bayar - $transaksi->total_harga,
                    'tanggal' => $transaksi->tanggal->format('Y-m-d H:i:s'),
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            \Log::error('Validation Error:', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'error' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Kasir Store Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Method untuk cek stok real-time (AJAX)
    public function checkStok($id_barang)
    {
        try {
            $barang = Barang::findOrFail($id_barang);

            return response()->json([
                'success' => true,
                'stok_kardus' => $barang->stok_kardus,
                'stok_ecer' => $barang->stok_ecer,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }
    }

    // Method untuk print struk
    public function printStruk($id_transaksi)
    {
        try {
            $transaksi = Transaksi::with(['user', 'details.barang'])->findOrFail($id_transaksi);

            return view('kasir.print-struk', compact('transaksi'));
        } catch (\Exception $e) {
            return back()->with('error', 'Transaksi tidak ditemukan');
        }
    }

    // Method untuk history transaksi hari ini
    public function todayTransactions()
    {
        try {
            $today = Carbon::today();

            $transaksis = Transaksi::with(['user'])
                ->whereDate('tanggal', $today)
                ->where('id_user', Auth::id())
                ->orderBy('tanggal', 'desc')
                ->get();

            $totalPenjualan = $transaksis->sum('total_harga');
            $totalTransaksi = $transaksis->count();

            return response()->json([
                'success' => true,
                'transaksis' => $transaksis,
                'summary' => [
                    'total_penjualan' => $totalPenjualan,
                    'total_transaksi' => $totalTransaksi,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data transaksi'
            ], 500);
        }
    }
}