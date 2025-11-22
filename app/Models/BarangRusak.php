<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangRusak extends Model
{
    use HasFactory;

    protected $table = 'barang_rusaks';
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
