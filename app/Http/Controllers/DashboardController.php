<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $barangs = Barang::all();
            $users = User::all();
            $categories = Barang::select('kategori')->distinct()->get();

            return view('dashboard', compact('barangs', 'users', 'categories'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat dashboard: ' . $e->getMessage());
        }
    }

    // KPI dengan perbandingan periode
    public function kpiData(Request $request)
    {
        try {
            $start = $request->start_date ?? date('Y-m-01');
            $end = $request->end_date ?? date('Y-m-t');

            // Hitung periode sebelumnya
            $startCarbon = Carbon::parse($start);
            $endCarbon = Carbon::parse($end);
            $diff = $startCarbon->diffInDays($endCarbon);

            $prevStart = $startCarbon->copy()->subDays($diff + 1)->format('Y-m-d');
            $prevEnd = $startCarbon->copy()->subDay()->format('Y-m-d');

            // Current period
            $current = $this->calculateKPI($start, $end, $request);

            // Previous period
            $previous = $this->calculateKPI($prevStart, $prevEnd, $request);

            // Calculate growth
            $growth = [
                'pendapatan' => $this->calculateGrowth($previous['pendapatan'], $current['pendapatan']),
                'pengeluaran' => $this->calculateGrowth($previous['pengeluaran'], $current['pengeluaran']),
                'margin' => $this->calculateGrowth($previous['margin'], $current['margin']),
                'terjual' => $this->calculateGrowth($previous['terjual'], $current['terjual']),
            ];

            return response()->json([
                'current' => $current,
                'previous' => $previous,
                'growth' => $growth
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function calculateKPI($start, $end, $request)
    {
        // Transaksi query
        $queryTransaksi = Transaksi::whereBetween('tanggal', [$start, $end]);
        if ($request->user)
            $queryTransaksi->where('id_user', $request->user);
        if ($request->metode)
            $queryTransaksi->where('metode', $request->metode);

        $pendapatan = (float) $queryTransaksi->sum('total_bayar');
        $transaksiCount = $queryTransaksi->count();
        $avgTransaksi = $transaksiCount > 0 ? $pendapatan / $transaksiCount : 0;

        // Pengeluaran
        $queryMasuk = DB::table('barang_masuks')
            ->join('barangs', 'barang_masuks.id_barang', '=', 'barangs.id_barang')
            ->whereBetween('tanggal_masuk', [$start, $end]);

        if ($request->kategori)
            $queryMasuk->where('barangs.kategori', $request->kategori);

        $pengeluaran = (float) $queryMasuk->sum(DB::raw('barang_masuks.jumlah_kardus * barangs.harga_modal_kardus + barang_masuks.jumlah_ecer * barangs.harga_modal_ecer'));

        $margin = $pendapatan - $pengeluaran;
        $marginPercent = $pendapatan > 0 ? ($margin / $pendapatan) * 100 : 0;

        // Barang terjual
        $queryDetail = TransaksiDetail::whereHas('transaksi', function ($q) use ($start, $end, $request) {
            $q->whereBetween('tanggal', [$start, $end]);
            if ($request->user)
                $q->where('id_user', $request->user);
            if ($request->metode)
                $q->where('metode', $request->metode);
        });

        if ($request->kategori) {
            $queryDetail->whereHas('barang', fn($q) => $q->where('kategori', $request->kategori));
        }

        $terjual = (float) $queryDetail->sum(DB::raw('jumlah_kardus + jumlah_ecer'));

        return compact('pendapatan', 'pengeluaran', 'margin', 'terjual', 'transaksiCount', 'avgTransaksi', 'marginPercent');
    }

    private function calculateGrowth($previous, $current)
    {
        if ($previous == 0)
            return $current > 0 ? 100 : 0;
        return round((($current - $previous) / $previous) * 100, 2);
    }

    // Chart Data
    public function chartData(Request $request)
    {
        try {
            $start = $request->start_date ?? date('Y-m-01');
            $end = $request->end_date ?? date('Y-m-t');

            $startCarbon = Carbon::parse($start);
            $endCarbon = Carbon::parse($end);

            // Pendapatan per hari
            $transaksiQuery = Transaksi::select(
                DB::raw('DATE(tanggal) as tgl'),
                DB::raw('SUM(total_bayar) as total_bayar')
            )->whereBetween('tanggal', [$start, $end]);

            if ($request->user)
                $transaksiQuery->where('id_user', $request->user);
            if ($request->metode)
                $transaksiQuery->where('metode', $request->metode);

            $pendapatanPerHari = $transaksiQuery->groupBy('tgl')->pluck('total_bayar', 'tgl');

            // Pengeluaran per hari
            $barangMasuk = DB::table('barang_masuks')
                ->join('barangs', 'barang_masuks.id_barang', '=', 'barangs.id_barang')
                ->whereBetween('tanggal_masuk', [$start, $end]);

            if ($request->kategori)
                $barangMasuk->where('barangs.kategori', $request->kategori);

            $pengeluaranPerHari = $barangMasuk
                ->select(
                    DB::raw('DATE(tanggal_masuk) as tgl'),
                    DB::raw('SUM(jumlah_kardus * harga_modal_kardus + jumlah_ecer * harga_modal_ecer) as total_modal')
                )
                ->groupBy('tgl')
                ->pluck('total_modal', 'tgl');

            $labels = [];
            $pendapatan = [];
            $margin = [];

            for ($date = $startCarbon->copy(); $date->lte($endCarbon); $date->addDay()) {
                $tgl = $date->format('Y-m-d');
                $labels[] = $date->format('d M');
                $pend = $pendapatanPerHari[$tgl] ?? 0;
                $peng = $pengeluaranPerHari[$tgl] ?? 0;
                $pendapatan[] = $pend;
                $margin[] = $pend - $peng;
            }

            // Top 5 barang
            $top5Query = TransaksiDetail::select('id_barang', DB::raw('SUM(jumlah_kardus + jumlah_ecer) as total'))
                ->whereHas('transaksi', function ($q) use ($start, $end, $request) {
                    $q->whereBetween('tanggal', [$start, $end]);
                    if ($request->user)
                        $q->where('id_user', $request->user);
                    if ($request->metode)
                        $q->where('metode', $request->metode);
                });

            if ($request->kategori) {
                $top5Query->whereHas('barang', fn($q) => $q->where('kategori', $request->kategori));
            }

            $top5 = $top5Query->groupBy('id_barang')->orderByDesc('total')->limit(5)->get();
            $top5Labels = $top5->map(fn($t) => $t->barang->nama_barang ?? 'Unknown');
            $top5Data = $top5->pluck('total');

            return response()->json(compact('labels', 'pendapatan', 'margin', 'top5Labels', 'top5Data'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Metode pembayaran analytics
    public function paymentMethodData(Request $request)
    {
        try {
            $start = $request->start_date ?? date('Y-m-01');
            $end = $request->end_date ?? date('Y-m-t');

            $data = Transaksi::select('metode', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_bayar) as total'))
                ->whereBetween('tanggal', [$start, $end]);

            if ($request->user)
                $data->where('id_user', $request->user);

            $result = $data->groupBy('metode')->get();

            return response()->json([
                'labels' => $result->pluck('metode'),
                'counts' => $result->pluck('count'),
                'totals' => $result->pluck('total')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Performance per kasir
    public function cashierPerformance(Request $request)
    {
        try {
            $start = $request->start_date ?? date('Y-m-01');
            $end = $request->end_date ?? date('Y-m-t');

            $data = Transaksi::select(
                'id_user',
                DB::raw('COUNT(*) as transaksi_count'),
                DB::raw('SUM(total_bayar) as total_penjualan')
            )
                ->whereBetween('tanggal', [$start, $end])
                ->groupBy('id_user')
                ->with('user')
                ->get();

            return response()->json([
                'labels' => $data->map(fn($d) => $d->user->name ?? 'Unknown'),
                'transaksiCount' => $data->pluck('transaksi_count'),
                'totalPenjualan' => $data->pluck('total_penjualan')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Kategori analytics
    public function categoryAnalytics(Request $request)
    {
        try {
            $start = $request->start_date ?? date('Y-m-01');
            $end = $request->end_date ?? date('Y-m-t');

            $data = TransaksiDetail::select(
                'barangs.kategori',
                DB::raw('SUM(transaksi_details.jumlah_kardus + transaksi_details.jumlah_ecer) as total_terjual'),
                DB::raw('SUM(transaksi_details.subtotal) as total_pendapatan')
            )
                ->join('barangs', 'transaksi_details.id_barang', '=', 'barangs.id_barang')
                ->whereHas('transaksi', function ($q) use ($start, $end) {
                    $q->whereBetween('tanggal', [$start, $end]);
                })
                ->groupBy('barangs.kategori')
                ->get();

            return response()->json([
                'labels' => $data->pluck('kategori'),
                'terjual' => $data->pluck('total_terjual'),
                'pendapatan' => $data->pluck('total_pendapatan')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Peak hours analysis
    public function peakHours(Request $request)
    {
        try {
            $start = $request->start_date ?? date('Y-m-01');
            $end = $request->end_date ?? date('Y-m-t');

            $data = Transaksi::select(
                DB::raw('HOUR(tanggal) as jam'),
                DB::raw('COUNT(*) as transaksi_count'),
                DB::raw('SUM(total_bayar) as total')
            )
                ->whereBetween('tanggal', [$start, $end])
                ->groupBy('jam')
                ->orderBy('jam')
                ->get();

            return response()->json([
                'labels' => $data->pluck('jam')->map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'),
                'transaksiCount' => $data->pluck('transaksi_count'),
                'total' => $data->pluck('total')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Stok alerts
    public function stokAlerts(Request $request)
    {
        try {
            $kritis = Barang::where(function ($q) {
                $q->where('stok_kardus', '<', 5)
                    ->orWhere('stok_ecer', '<', 5);
            })->get();

            $habis = Barang::where('stok_kardus', 0)
                ->where('stok_ecer', 0)
                ->get();

            return response()->json([
                'kritis' => $kritis->map(fn($b) => [
                    'id' => $b->id_barang,
                    'nama' => $b->nama_barang,
                    'kategori' => $b->kategori,
                    'stok_kardus' => $b->stok_kardus,
                    'stok_ecer' => $b->stok_ecer
                ]),
                'habis' => $habis->map(fn($b) => [
                    'id' => $b->id_barang,
                    'nama' => $b->nama_barang,
                    'kategori' => $b->kategori
                ])
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Stok chart
    public function stokChartData(Request $request)
    {
        try {
            $kategori = $request->kategori;
            $stok_status = $request->stok_status;

            $barangs = Barang::query();
            if ($kategori)
                $barangs->where('kategori', $kategori);

            $barangs = $barangs->get();

            if ($stok_status == 'kritis') {
                $barangs = $barangs->filter(fn($b) => $b->stok_kardus < 5 || $b->stok_ecer < 5);
            } elseif ($stok_status == 'aman') {
                $barangs = $barangs->filter(fn($b) => $b->stok_kardus >= 5 && $b->stok_ecer >= 5);
            }

            $labels = $barangs->pluck('nama_barang');
            $stokKardus = $barangs->pluck('stok_kardus');
            $stokEcer = $barangs->pluck('stok_ecer');
            $colorsKardus = $stokKardus->map(fn($v) => $v < 5 ? 'rgba(239, 68, 68, 0.8)' : 'rgba(34, 197, 94, 0.8)');
            $colorsEcer = $stokEcer->map(fn($v) => $v < 5 ? 'rgba(239, 68, 68, 0.8)' : 'rgba(59, 130, 246, 0.8)');

            return response()->json([
                'labels' => $labels,
                'stokKardus' => $stokKardus,
                'stokEcer' => $stokEcer,
                'colorsKardus' => $colorsKardus,
                'colorsEcer' => $colorsEcer,
                'ids' => $barangs->pluck('id_barang')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Detail stok per barang
    public function detailStok($id_barang)
    {
        try {
            $barang = Barang::findOrFail($id_barang);

            $start = request()->start_date ?? Carbon::now()->subDays(30)->format('Y-m-d');
            $end = request()->end_date ?? Carbon::now()->format('Y-m-d');

            $period = [];
            $labels = [];
            $startCarbon = Carbon::parse($start);
            $endCarbon = Carbon::parse($end);

            for ($date = $startCarbon->copy(); $date->lte($endCarbon); $date->addDay()) {
                $labels[] = $date->format('d M');
                $period[] = $date->format('Y-m-d');
            }

            $masuk = $rusak = $terjual = [];

            foreach ($period as $p) {
                $masuk[] = DB::table('barang_masuks')
                    ->where('id_barang', $id_barang)
                    ->whereDate('tanggal_masuk', $p)
                    ->sum(DB::raw('jumlah_kardus + jumlah_ecer'));

                $rusak[] = DB::table('barang_rusaks')
                    ->where('id_barang', $id_barang)
                    ->whereDate('tanggal_rusak', $p)
                    ->sum(DB::raw('jumlah_kardus + jumlah_ecer'));

                $terjual[] = TransaksiDetail::where('id_barang', $id_barang)
                    ->whereHas('transaksi', fn($q) => $q->whereDate('tanggal', $p))
                    ->sum(DB::raw('jumlah_kardus + jumlah_ecer'));
            }

            return response()->json([
                'nama_barang' => $barang->nama_barang,
                'labels' => $labels,
                'masuk' => $masuk,
                'rusak' => $rusak,
                'terjual' => $terjual
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}