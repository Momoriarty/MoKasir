<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $table      = 'transaksi_details';
    protected $primaryKey = 'id_transaksi_detail';

    protected $fillable = [
        'id_transaksi',
        'id_barang',
        'jumlah_kardus',
        'harga_kardus', // ✅ TAMBAH INI
        'jumlah_ecer',
        'harga_ecer', // ✅ TAMBAH INI
        'subtotal',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

}
