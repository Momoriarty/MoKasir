<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
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
