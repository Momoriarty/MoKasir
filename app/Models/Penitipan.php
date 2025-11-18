<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penitipan extends Model
{
    protected $table = 'penitipans';

    protected $primaryKey = 'id_penitipan';
    protected $fillable = [
        'nama_penitip',
        'tanggal_titip',
    ];

    public function Penitipan_detail()
    {
        return $this->belongsTo(Penitipan_detail::class, 'id_penitipan_detail');
    }
}
