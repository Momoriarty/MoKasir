<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangRusak extends Model
{
    protected $primaryKey = 'id_barang_rusak';
    protected $fillable = [
        'id_barang',
        'jumlah_kardus',
        'jumlah_ecer',
        'keterangan',
        'tanggal_rusak'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}

