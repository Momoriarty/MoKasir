<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $primaryKey = 'id_barang_masuk';
    protected $fillable = [
        'id_barang',
        'jumlah_kardus',
        'jumlah_ecer',
        'tanggal_masuk'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }


}
