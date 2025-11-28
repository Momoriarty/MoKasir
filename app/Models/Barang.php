<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $primaryKey = 'id_barang'; // primary key

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

    // Jika tabel tidak ada created_at & updated_at, ubah menjadi false
    public $timestamps = true;

    // Casting kolom numerik agar query aman
    protected $casts = [
        'harga_modal_kardus' => 'integer',
        'harga_modal_ecer'   => 'integer',
        'harga_jual_kardus'  => 'integer',
        'harga_jual_ecer'    => 'integer',
        'isi_per_kardus'     => 'integer',
        'stok_kardus'        => 'integer',
        'stok_ecer'          => 'integer',
    ];

    // Scope untuk search nama barang
    public function scopeSearchNama($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('nama_barang', 'like', "%{$keyword}%");
        }
        return $query;
    }

    // Relasi
    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class, 'id_barang', 'id_barang');
    }

    public function barangMasuks()
    {
        return $this->hasMany(BarangMasuk::class, 'id_barang', 'id_barang');
    }

    public function barangRusaks()
    {
        return $this->hasMany(BarangRusak::class, 'id_barang', 'id_barang');
    }
}
