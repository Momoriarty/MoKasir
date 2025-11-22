<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Barang extends Model
{
    protected $table = 'barangs';
    protected $primaryKey = 'id_barang';
    protected $fillable = [
        'nama_barang',
        'kategori',
        'harga_modal_kardus',
        'harga_modal_ecer',
        'harga_jual_kardus',
        'harga_jual_ecer',
        'isi_per_kardus',
        'stok_kardus',
        'stok_ecer'
    ];

    public function barangMasuks()
    {
        return $this->hasMany(BarangMasuk::class, 'id_barang', 'id_barang');
    }

    public function barangRusaks()
    {
        return $this->hasMany(BarangRusak::class, 'id_barang', 'id_barang');
    }

    public function masukHariIni()
    {
        return $this->hasMany(BarangMasuk::class, 'id_barang');
    }

    public function rusakHariIni()
    {
        return $this->hasMany(BarangRusak::class, 'id_barang');
    }

    public function getMasukHariIniAttribute()
    {
        return $this->barangMasuks()
            ->join('barangs', 'barang_masuks.id_barang', '=', 'barangs.id_barang')
            ->whereDate('tanggal_masuk', today())
            ->sum(DB::raw('jumlah_ecer + jumlah_kardus * barangs.isi_per_kardus'));
    }

    public function getRusakHariIniAttribute()
    {
        return $this->barangRusaks()
            ->join('barangs', 'barang_rusaks.id_barang', '=', 'barangs.id_barang')
            ->whereDate('tanggal_rusak', today())
            ->sum(DB::raw('jumlah_ecer + jumlah_kardus * barangs.isi_per_kardus'));
    }
}
