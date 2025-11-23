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

    // ==================== KPI ====================
    public function kpiData(Request $request)
    {
        try {
            $start = $request->start_date ?? date('Y-m-01');
            $end = $request->end_date ?? date('Y-m-t');

            $startCarbon = Carbon::parse($start);
            $endCarbon = Carbon::parse($end);
            $diff = $startCarbon->diffInDays($endCarbon);

            $prevStart = $startCarbon->copy()->subDays($diff + 1)->format('Y-m-d');
            $prevEnd = $startCarbon->copy()->subDay()->format('Y-m-d');

            $current = $this->calculateKPI($start, $end, $request);
            $previous = $this->calculateKPI($prevStart, $prevEnd, $request);

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

    private function calculateKPI($start, $end, $request = null)
    {
        $start = Carbon::parse($start)->startOfDay();
        $end = Carbon::parse($end)->endOfDay();

        // ==================== Pendapatan & Terjual ====================
        $queryDetail = TransaksiDetail::whereHas('transaksi', function ($q) use ($start, $end, $request) {
            $q->whereBetween('tanggal', [$start, $end]);
            if ($request && $request->user)
                $q->where('id_user', $request->user);
            if ($request && $request->metode)
                $q->where('metode', $request->metode);
        });

        if ($request && $request->kategori) {
            $queryDetail->whereHas('barang', fn($q) => $q->where('kategori', $request->kategori));
        }

        $pendapatan = (float) $queryDetail->sum('subtotal');
        $terjual = (float) $queryDetail->sum(DB::raw('jumlah_kardus + jumlah_ecer'));
        $transaksiCount = $queryDetail->count();
        $avgTransaksi = $transaksiCount > 0 ? $pendapatan / $transaksiCount : 0;

        // ==================== Pengeluaran ====================
        $queryMasuk = DB::table('barang_masuks')
            ->join('barangs', 'barang_masuks.id_barang', '=', 'barangs.id_barang')
            ->whereBetween('tanggal_masuk', [$start, $end]);

        if ($request && $request->kategori) {
            $queryMasuk->where('barangs.kategori', $request->kategori);
        }

        if ($request && $request->stok_status == 'kritis') {
            $queryMasuk->where(function ($q) {
                $q->where('stok_kardus', '<', 5)
                    ->orWhere('stok_ecer', '<', 5);
            });
        } elseif ($request && $request->stok_status == 'aman') {
            $queryMasuk->where('stok_kardus', '>=', 5)
                ->where('stok_ecer', '>=', 5);
        }

        $pengeluaran = (float) $queryMasuk->sum(DB::raw('jumlah_kardus * harga_modal_kardus + jumlah_ecer * harga_modal_ecer'));

        $margin = $pendapatan - $pengeluaran;
        $marginPercent = $pendapatan > 0 ? ($margin / $pendapatan) * 100 : 0;

        return compact('pendapatan', 'pengeluaran', 'margin', 'terjual', 'transaksiCount', 'avgTransaksi', 'marginPercent');
    }

    private function calculateGrowth($previous, $current)
    {
        if ($previous == 0)
            return $current > 0 ? 100 : 0;
        return round((($current - $previous) / $previous) * 100, 2);
    }

    // ==================== Chart Data ====================
    public function chartData(Request $request)
    {
        $start = Carbon::parse($request->start_date ?? date('Y-m-01'))->startOfDay();
        $end = Carbon::parse($request->end_date ?? date('Y-m-t'))->endOfDay();

        // Pendapatan per hari
        $transaksiQuery = Transaksi::select(
            DB::raw('DATE(tanggal) as tgl'),
            DB::raw('SUM(total_bayar) as total_bayar')
        )->whereBetween('tanggal', [$start, $end]);

        if ($request->user)
            $transaksiQuery->where('id_user', $request->user);
        if ($request->metode)
            $transaksiQuery->where('metode', $request->metode);
        if ($request->kategori)
            $transaksiQuery->whereHas('details.barang', fn($q) => $q->where('kategori', $request->kategori));

        $pendapatanPerHari = $transaksiQuery->groupBy('tgl')->pluck('total_bayar', 'tgl');

        // Pengeluaran per hari
        $barangMasuk = DB::table('barang_masuks')
            ->join('barangs', 'barang_masuks.id_barang', '=', 'barangs.id_barang')
            ->whereBetween('tanggal_masuk', [$start, $end]);

        if ($request->kategori)
            $barangMasuk->where('barangs.kategori', $request->kategori);
        if ($request->stok_status == 'kritis') {
            $barangMasuk->where(function ($q) {
                $q->where('stok_kardus', '<', 5)
                    ->orWhere('stok_ecer', '<', 5);
            });
        } elseif ($request->stok_status == 'aman') {
            $barangMasuk->where('stok_kardus', '>=', 5)
                ->where('stok_ecer', '>=', 5);
        }

        $pengeluaranPerHari = $barangMasuk
            ->select(
                DB::raw('DATE(tanggal_masuk) as tgl'),
                DB::raw('SUM(jumlah_kardus * harga_modal_kardus + jumlah_ecer * harga_modal_ecer) as total_modal')
            )
            ->groupBy('tgl')
            ->pluck('total_modal', 'tgl');

        $labels = $pendapatan = $margin = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $tgl = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $pend = $pendapatanPerHari[$tgl] ?? 0;
            $peng = $pengeluaranPerHari[$tgl] ?? 0;
            $pendapatan[] = $pend;
            $margin[] = $pend - $peng;
        }

        // Top 5 barang
        $top5Query = TransaksiDetail::select('id_barang', DB::raw('SUM(jumlah_kardus + jumlah_ecer) as total'))
            ->whereHas('transaksi', fn($q) => $q->whereBetween('tanggal', [$start, $end]));

        if ($request->user)
            $top5Query->whereHas('transaksi', fn($q) => $q->where('id_user', $request->user));
        if ($request->metode)
            $top5Query->whereHas('transaksi', fn($q) => $q->where('metode', $request->metode));
        if ($request->kategori)
            $top5Query->whereHas('barang', fn($q) => $q->where('kategori', $request->kategori));

        $top5 = $top5Query->groupBy('id_barang')->orderByDesc('total')->limit(5)->get();
        $top5Labels = $top5->map(fn($t) => $t->barang->nama_barang ?? 'Unknown');
        $top5Data = $top5->pluck('total');

        return response()->json(compact('labels', 'pendapatan', 'margin', 'top5Labels', 'top5Data'));
    }

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


    // ==================== Metode Pembayaran ====================
    public function paymentMethodData(Request $request)
    {
        $start = Carbon::parse($request->start_date ?? date('Y-m-01'))->startOfDay();
        $end = Carbon::parse($request->end_date ?? date('Y-m-t'))->endOfDay();

        $data = Transaksi::whereBetween('tanggal', [$start, $end]);
        if ($request->user)
            $data->where('id_user', $request->user);
        if ($request->metode)
            $data->where('metode', $request->metode);
        if ($request->kategori)
            $data->whereHas('details.barang', fn($q) => $q->where('kategori', $request->kategori));

        $result = $data->select('metode', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_bayar) as total'))
            ->groupBy('metode')->get();

        return response()->json([
            'labels' => $result->pluck('metode'),
            'counts' => $result->pluck('count'),
            'totals' => $result->pluck('total')
        ]);
    }

    // ==================== Performa Kasir ====================
    public function cashierPerformance(Request $request)
    {
        $start = Carbon::parse($request->start_date ?? date('Y-m-01'))->startOfDay();
        $end = Carbon::parse($request->end_date ?? date('Y-m-t'))->endOfDay();

        $data = Transaksi::whereBetween('tanggal', [$start, $end]);
        if ($request->user)
            $data->where('id_user', $request->user);
        if ($request->metode)
            $data->where('metode', $request->metode);
        if ($request->kategori)
            $data->whereHas('details.barang', fn($q) => $q->where('kategori', $request->kategori));

        $result = $data->select(
            'id_user',
            DB::raw('COUNT(*) as transaksi_count'),
            DB::raw('SUM(total_bayar) as total_penjualan')
        )->groupBy('id_user')->with('user')->get();

        return response()->json([
            'labels' => $result->map(fn($d) => $d->user->name ?? 'Unknown'),
            'transaksiCount' => $result->pluck('transaksi_count'),
            'totalPenjualan' => $result->pluck('total_penjualan')
        ]);
    }

    // ==================== Jam Tersibuk ====================
    public function peakHours(Request $request)
    {
        $start = Carbon::parse($request->start_date ?? date('Y-m-01'))->startOfDay();
        $end = Carbon::parse($request->end_date ?? date('Y-m-t'))->endOfDay();

        $data = Transaksi::whereBetween('tanggal', [$start, $end]);
        if ($request->user)
            $data->where('id_user', $request->user);
        if ($request->metode)
            $data->where('metode', $request->metode);
        if ($request->kategori)
            $data->whereHas('details.barang', fn($q) => $q->where('kategori', $request->kategori));

        $result = $data->select(
            DB::raw('HOUR(tanggal) as jam'),
            DB::raw('COUNT(*) as transaksi_count'),
            DB::raw('SUM(total_bayar) as total')
        )->groupBy('jam')->orderBy('jam')->get();

        return response()->json([
            'labels' => $result->pluck('jam')->map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'),
            'transaksiCount' => $result->pluck('transaksi_count'),
            'total' => $result->pluck('total')
        ]);
    }

    // ==================== Stok Alerts ====================
    public function stokAlerts(Request $request)
    {
        $kritis = Barang::query();
        if ($request->kategori)
            $kritis->where('kategori', $request->kategori);
        $kritis->where(function ($q) {
            $q->where('stok_kardus', '<', 5)
                ->orWhere('stok_ecer', '<', 5);
        });

        $habis = Barang::query();
        if ($request->kategori)
            $habis->where('kategori', $request->kategori);
        $habis->where('stok_kardus', 0)->where('stok_ecer', 0);

        return response()->json([
            'kritis' => $kritis->get()->map(fn($b) => [
                'id' => $b->id_barang,
                'nama' => $b->nama_barang,
                'kategori' => $b->kategori,
                'stok_kardus' => $b->stok_kardus,
                'stok_ecer' => $b->stok_ecer
            ]),
            'habis' => $habis->get()->map(fn($b) => [
                'id' => $b->id_barang,
                'nama' => $b->nama_barang,
                'kategori' => $b->kategori
            ])
        ]);
    }

    // ==================== Stok Chart ====================
    public function stokChartData(Request $request)
    {
        $barangs = Barang::query();
        if ($request->kategori)
            $barangs->where('kategori', $request->kategori);

        if ($request->stok_status == 'kritis') {
            $barangs->where(function ($q) {
                $q->where('stok_kardus', '<', 5)
                    ->orWhere('stok_ecer', '<', 5);
            });
        } elseif ($request->stok_status == 'aman') {
            $barangs->where('stok_kardus', '>=', 5)
                ->where('stok_ecer', '>=', 5);
        }

        $barangs = $barangs->get();
        return response()->json([
            'labels' => $barangs->pluck('nama_barang'),
            'stokKardus' => $barangs->pluck('stok_kardus'),
            'stokEcer' => $barangs->pluck('stok_ecer'),
            'colorsKardus' => $barangs->pluck('stok_kardus')->map(fn($v) => $v < 5 ? 'rgba(239,68,68,0.8)' : 'rgba(34,197,94,0.8)'),
            'colorsEcer' => $barangs->pluck('stok_ecer')->map(fn($v) => $v < 5 ? 'rgba(239,68,68,0.8)' : 'rgba(59,130,246,0.8)'),
            'ids' => $barangs->pluck('id_barang')
        ]);
    }

    // ==================== Detail Stok ====================
    public function detailStok($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);
        $start = request()->start_date ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $end = request()->end_date ?? Carbon::now()->format('Y-m-d');

        $period = [];
        $labels = [];
        $startCarbon = Carbon::parse($start);
        $endCarbon = Carbon::parse($end);

        for ($date = $startCarbon->copy(); $date->lte($endCarbon); $date->addDay()) {
            $period[] = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
        }

        $masuk = $rusak = $terjual = [];

        foreach ($period as $p) {
            $masuk[] = DB::table('barang_masuks')->where('id_barang', $id_barang)->whereDate('tanggal_masuk', $p)->sum(DB::raw('jumlah_kardus + jumlah_ecer'));
            $rusak[] = DB::table('barang_rusaks')->where('id_barang', $id_barang)->whereDate('tanggal_rusak', $p)->sum(DB::raw('jumlah_kardus + jumlah_ecer'));
            $terjual[] = TransaksiDetail::where('id_barang', $id_barang)->whereHas('transaksi', fn($q) => $q->whereDate('tanggal', $p))->sum(DB::raw('jumlah_kardus + jumlah_ecer'));
        }

        return response()->json([
            'nama_barang' => $barang->nama_barang,
            'labels' => $labels,
            'masuk' => $masuk,
            'rusak' => $rusak,
            'terjual' => $terjual
        ]);
    }
}
