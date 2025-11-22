<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangRusak;
use App\Models\PenitipanDetail;
use DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', today()->subDays(6));
        $endDate = $request->input('end_date', today());

        // ===== KPI =====
        $pendapatanHariIni = Transaksi::whereDate('tanggal', today())->sum('total_bayar');
        $totalTransaksiHariIni = Transaksi::whereDate('tanggal', today())->count();
        $jumlahBarangTerjual = TransaksiDetail::whereHas('transaksi', fn($q) => $q->whereDate('tanggal', today()))
            ->sum('jumlah');
        $jumlahProduk = Barang::count();

        $barangMasukHariIni = BarangMasuk::join('barangs', 'barang_masuks.id_barang', '=', 'barangs.id_barang')
            ->whereDate('tanggal_masuk', today())
            ->sum(DB::raw('jumlah_ecer + jumlah_kardus * barangs.isi_per_kardus'));

        $barangRusakHariIni = BarangRusak::join('barangs', 'barang_rusaks.id_barang', '=', 'barangs.id_barang')
            ->whereDate('tanggal_rusak', today())
            ->sum(DB::raw('jumlah_ecer + jumlah_kardus * barangs.isi_per_kardus'));

        $penitipanTerjualHariIni = PenitipanDetail::whereDate('created_at', today())
            ->sum('jumlah_terjual');

        // ===== Margin Keuntungan =====
        $marginHariIni = TransaksiDetail::whereHas('transaksi', fn($q) => $q->whereDate('tanggal', today()))
            ->join('barangs', 'transaksi_details.id_barang', '=', 'barangs.id_barang')
            ->select(DB::raw('SUM(transaksi_details.subtotal - transaksi_details.jumlah * barangs.harga_modal_ecer) as margin'))
            ->value('margin') ?? 0;

        // ===== Transaksi Terbaru =====
        $transaksiTerbaru = Transaksi::with('user')->orderBy('tanggal', 'desc')->limit(5)->get();

        // ===== Barang =====
        $barangs = Barang::with([
            'masukHariIni' => fn($q) => $q->whereDate('tanggal_masuk', today()),
            'rusakHariIni' => fn($q) => $q->whereDate('tanggal_rusak', today())
        ])->get()->map(function ($b) {
            // total masuk hari ini
            $b->total_masuk_hari_ini = $b->masukHariIni instanceof \Illuminate\Support\Collection
                ? $b->masukHariIni->sum(fn($m) => $m->jumlah_ecer + $m->jumlah_kardus * $b->isi_per_kardus)
                : 0;

            // total rusak hari ini
            $b->total_rusak_hari_ini = $b->rusakHariIni instanceof \Illuminate\Support\Collection
                ? $b->rusakHariIni->sum(fn($r) => $r->jumlah_ecer + $r->jumlah_kardus * $b->isi_per_kardus)
                : 0;

            return $b;
        });


        // ===== Chart Pendapatan =====
        $pendapatanData = Transaksi::select(DB::raw('DATE(tanggal) as tanggal'), DB::raw('SUM(total_bayar) as total'))
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy('tanggal')
            ->get();

        $pendapatanLabels = $pendapatanData->pluck('tanggal');
        $pendapatanValues = $pendapatanData->pluck('total');

        // ===== Chart Barang Terlaris =====
        $barangTerlarisData = TransaksiDetail::select('id_barang', DB::raw('SUM(jumlah) as total'))
            ->whereHas('transaksi', fn($q) => $q->whereBetween('tanggal', [$startDate, $endDate]))
            ->groupBy('id_barang')
            ->with('barang')
            ->get();

        $barangLabels = $barangTerlarisData->map(fn($item) => $item->barang->nama_barang);
        $barangValues = $barangTerlarisData->pluck('total');

        // ===== Stok Kritis =====
        $stokKritis = Barang::whereRaw('(stok_ecer + stok_kardus * isi_per_kardus) <= 5')->get();

        return view('dashboard', compact(
            'pendapatanHariIni',
            'totalTransaksiHariIni',
            'jumlahBarangTerjual',
            'jumlahProduk',
            'barangMasukHariIni',
            'barangRusakHariIni',
            'penitipanTerjualHariIni',
            'marginHariIni',
            'transaksiTerbaru',
            'barangs',
            'pendapatanLabels',
            'pendapatanValues',
            'barangLabels',
            'barangValues',
            'stokKritis'
        ));
    }
}
