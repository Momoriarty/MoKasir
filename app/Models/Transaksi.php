<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'tanggal',
        'id_user',
        'total_harga',
        'total_bayar',
        'metode'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'id_transaksi');
    }

    public function detailPenitipans()
    {
        return $this->hasMany(TransaksiDetailPenitipan::class, 'id_transaksi', 'id_transaksi');
    }
}
