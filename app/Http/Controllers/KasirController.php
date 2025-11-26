<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PenitipanDetail;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\TransaksiDetailPenitipan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        try {
            // Ambil semua barang
            $barangs = Barang::orderBy('nama_barang')->get();

            // Ambil penitipan details yang masih ada sisa, sekaligus relasi penitipan
            $penitipanDetails = PenitipanDetail::with('penitipan')
                ->where('jumlah_sisa', '>', 0)
                ->orderBy('nama_barang')
                ->get();

            // Kirim ke view
            return view('kasir.kasir', compact('barangs', 'penitipanDetails'));
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
                'total_harga'                     => 'required|numeric|min:0',
                'total_bayar'                     => 'required|numeric|min:0',
                'metode'                          => 'required|in:Tunai,Qris',
                'barang'                          => 'nullable|array',
                'barang.*.id_barang'              => 'required|exists:barangs,id_barang',
                'barang.*.jumlah_kardus'          => 'required|integer|min:0',
                'barang.*.jumlah_ecer'            => 'required|integer|min:0',
                'barang.*.harga_kardus'           => 'required|numeric|min:0', // ✅ UBAH jadi required
                'barang.*.harga_ecer'             => 'required|numeric|min:0', // ✅ UBAH jadi required
                'barang.*.subtotal'               => 'required|numeric|min:0',
                'penitipan'                       => 'nullable|array',
                'penitipan.*.id_penitipan_detail' => 'required|exists:penitipan_details,id_penitipan_detail',
                'penitipan.*.jumlah'              => 'required|integer|min:1',
                'penitipan.*.harga_jual'          => 'required|numeric|min:0',
                'penitipan.*.subtotal'            => 'required|numeric|min:0',
            ]);

            // Validasi minimal harus ada barang atau penitipan
            if (
                (! $request->has('barang') || count($request->barang) == 0) &&
                (! $request->has('penitipan') || count($request->penitipan) == 0)
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong, minimal harus ada 1 barang',
                ], 400);
            }

            // Validasi pembayaran
            if ($request->total_bayar < $request->total_harga) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah bayar kurang dari total harga',
                ], 400);
            }

            DB::beginTransaction();

            // ========== VALIDASI STOK BARANG ==========
            if ($request->has('barang') && count($request->barang) > 0) {
                foreach ($request->barang as $item) {
                    $barang = Barang::lockForUpdate()->find($item['id_barang']);

                    if (! $barang) {
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
            }

            // ========== VALIDASI STOK PENITIPAN ==========
            if ($request->has('penitipan') && count($request->penitipan) > 0) {
                foreach ($request->penitipan as $item) {
                    $penitipanDetail = PenitipanDetail::lockForUpdate()->find($item['id_penitipan_detail']);

                    if (! $penitipanDetail) {
                        throw new \Exception("Penitipan ID {$item['id_penitipan_detail']} tidak ditemukan");
                    }

                    // Cek stok penitipan
                    if ($item['jumlah'] > $penitipanDetail->jumlah_sisa) {
                        throw new \Exception("Stok penitipan {$penitipanDetail->nama_barang} tidak mencukupi. Tersedia: {$penitipanDetail->jumlah_sisa}, Diminta: {$item['jumlah']}");
                    }
                }
            }

            // Hitung total pajak/biaya admin untuk QRIS
            $biayaAdmin = 0;
            if ($request->metode === 'Qris') {
                $biayaAdmin = 1000;
            }

            $totalHargaFinal = $request->total_harga + $biayaAdmin;

            // ========== BUAT TRANSAKSI ==========
            $transaksi = Transaksi::create([
                'tanggal'     => Carbon::now(),
                'id_user'     => Auth::id(),
                'total_harga' => $totalHargaFinal,
                'total_bayar' => $request->total_bayar,
                'metode'      => $request->metode,
            ]);

            \Log::info('Transaksi created:', ['id' => $transaksi->id_transaksi]);

            // ========== SIMPAN DETAIL BARANG STOK ==========
            if ($request->has('barang') && count($request->barang) > 0) {
                foreach ($request->barang as $item) {
                    // Ambil barang dengan lock
                    $barang = Barang::lockForUpdate()->find($item['id_barang']);

                    // ✅ SIMPAN detail transaksi DENGAN harga_kardus dan harga_ecer
                    TransaksiDetail::create([
                        'id_transaksi'  => $transaksi->id_transaksi,
                        'id_barang'     => $item['id_barang'],
                        'jumlah_kardus' => $item['jumlah_kardus'],
                        'harga_kardus'  => $item['harga_kardus'], // ✅ TAMBAH INI
                        'jumlah_ecer'   => $item['jumlah_ecer'],
                        'harga_ecer'    => $item['harga_ecer'], // ✅ TAMBAH INI
                        'subtotal'      => $item['subtotal'],
                    ]);

                    // Update stok barang
                    $barang->stok_kardus -= $item['jumlah_kardus'];
                    $barang->stok_ecer -= $item['jumlah_ecer'];
                    $barang->save();

                    \Log::info("Stok barang updated", [
                        'barang'                => $barang->nama_barang,
                        'kardus'                => -$item['jumlah_kardus'],
                        'ecer'                  => -$item['jumlah_ecer'],
                        'stok_kardus_remaining' => $barang->stok_kardus,
                        'stok_ecer_remaining'   => $barang->stok_ecer,
                    ]);
                }
            }

            // ========== SIMPAN DETAIL BARANG PENITIPAN ==========
            if ($request->has('penitipan') && count($request->penitipan) > 0) {
                foreach ($request->penitipan as $item) {
                    // Ambil penitipan detail dengan lock
                    $penitipanDetail = PenitipanDetail::lockForUpdate()->find($item['id_penitipan_detail']);

                    // Simpan detail transaksi penitipan
                    TransaksiDetailPenitipan::create([
                        'id_transaksi'        => $transaksi->id_transaksi,
                        'id_penitipan_detail' => $item['id_penitipan_detail'],
                        'jumlah'              => $item['jumlah'],
                        'harga_jual'          => $item['harga_jual'],
                        'subtotal'            => $item['subtotal'],
                    ]);

                    // Update stok penitipan
                    $penitipanDetail->jumlah_terjual += $item['jumlah'];
                    $penitipanDetail->jumlah_sisa -= $item['jumlah'];
                    $penitipanDetail->save();

                    \Log::info("Stok penitipan updated", [
                        'barang'                => $penitipanDetail->nama_barang,
                        'jumlah_terjual'        => $item['jumlah'],
                        'jumlah_sisa_remaining' => $penitipanDetail->jumlah_sisa,
                    ]);
                }
            }

            DB::commit();

            \Log::info('Transaction completed successfully');

            // ✅ Load relationships untuk data struk
            $transaksi->load('details.barang', 'detailPenitipans.penitipanDetail.penitipan');

            // ✅ Format detail_transaksi untuk response
            $detailTransaksi = [];

            foreach ($transaksi->details as $detail) {
                $detailTransaksi[] = [
                    'nama_barang'   => $detail->barang->nama_barang,
                    'jumlah_kardus' => $detail->jumlah_kardus,
                    'harga_kardus'  => $detail->harga_kardus,
                    'jumlah_ecer'   => $detail->jumlah_ecer,
                    'harga_ecer'    => $detail->harga_ecer,
                    'subtotal'      => $detail->subtotal,
                ];
            }

            foreach ($transaksi->detailPenitipans as $detail) {
                $detailTransaksi[] = [
                    'nama_barang' => $detail->penitipanDetail->nama_barang,
                    'jumlah'      => $detail->jumlah,
                    'harga_jual'  => $detail->harga_jual,
                    'subtotal'    => $detail->subtotal,
                    'penitip'     => $detail->penitipanDetail->penitipan->nama_penitip,
                ];
            }

            return response()->json([
                'success'   => true,
                'message'   => 'Transaksi berhasil disimpan',
                'transaksi' => [
                    'id_transaksi'     => $transaksi->id_transaksi,
                    'total_harga'      => $transaksi->total_harga,
                    'total_bayar'      => $transaksi->total_bayar,
                    'metode'           => $transaksi->metode,
                    'kembalian'        => $transaksi->total_bayar - $transaksi->total_harga,
                    'tanggal'          => $transaksi->tanggal->format('Y-m-d H:i:s'),
                    'detail_transaksi' => $detailTransaksi, // ✅ TAMBAH INI untuk struk
                ],
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            \Log::error('Validation Error:', [
                'errors'  => $e->errors(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Kasir Store Error: ' . $e->getMessage(), [
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(), // ✅ Return pesan error asli, bukan generic
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // Method untuk cek stok real-time (AJAX)
    public function checkStok($id_barang)
    {
        try {
            $barang = Barang::findOrFail($id_barang);

            return response()->json([
                'success'     => true,
                'stok_kardus' => $barang->stok_kardus,
                'stok_ecer'   => $barang->stok_ecer,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan',
            ], 404);
        }
    }

    // Method untuk cek stok penitipan real-time (AJAX)
    public function checkStokPenitipan($id_penitipan_detail)
    {
        try {
            $penitipanDetail = PenitipanDetail::with('penitipan')->findOrFail($id_penitipan_detail);

            return response()->json([
                'success'      => true,
                'jumlah_sisa'  => $penitipanDetail->jumlah_sisa,
                'harga_jual'   => $penitipanDetail->harga_jual,
                'nama_barang'  => $penitipanDetail->nama_barang,
                'nama_penitip' => $penitipanDetail->penitipan->nama_penitip,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Barang penitipan tidak ditemukan',
            ], 404);
        }
    }

    // Method untuk print struk
    public function printStruk($id_transaksi)
    {
        try {
            $transaksi = Transaksi::with([
                'user',
                'details.barang',
                'detailPenitipans.penitipanDetail.penitipan',
            ])->findOrFail($id_transaksi);

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
                'success'    => true,
                'transaksis' => $transaksis,
                'summary'    => [
                    'total_penjualan' => $totalPenjualan,
                    'total_transaksi' => $totalTransaksi,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data transaksi',
            ], 500);
        }
    }
}
