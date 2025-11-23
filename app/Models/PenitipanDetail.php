<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenitipanDetail extends Model
{
    protected $table = 'penitipan_details';
    protected $primaryKey = 'id_penitipan_detail';
    protected $fillable = [
        'id_penitipan',
        'nama_barang',
        'harga_modal',
        'harga_jual',
        'jumlah_titip',
        'jumlah_terjual',
        'jumlah_sisa',
    ];

    public function penitipan()
    {
        return $this->belongsTo(Penitipan::class, 'id_penitipan', 'id_penitipan');
    }


}
