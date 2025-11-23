<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiDetailPenitipan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'transaksi_detail_penitipans';

    /**
     * Primary key
     */
    protected $primaryKey = 'id_transaksi_detail_penitipan';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'id_transaksi',
        'id_penitipan_detail',
        'jumlah',
        'harga_jual',
        'subtotal',
    ];

    /**
     * Casting attributes
     */
    protected $casts = [
        'jumlah' => 'integer',
        'harga_jual' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */

    // Relasi ke Transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    // Relasi ke PenitipanDetail
    public function penitipanDetail()
    {
        return $this->belongsTo(PenitipanDetail::class, 'id_penitipan_detail', 'id_penitipan_detail');
    }

    /**
     * Accessor - Get nama barang dari penitipan detail
     */
    public function getNamaBarangAttribute()
    {
        return $this->penitipanDetail->nama_barang ?? 'N/A';
    }

    /**
     * Accessor - Get nama penitip
     */
    public function getNamaPenitipAttribute()
    {
        return $this->penitipanDetail->penitipan->nama_penitip ?? 'N/A';
    }

    /**
     * Scope untuk filter by transaksi
     */
    public function scopeByTransaksi($query, $id_transaksi)
    {
        return $query->where('id_transaksi', $id_transaksi);
    }

    /**
     * Scope untuk eager load semua relasi
     */
    public function scopeWithRelations($query)
    {
        return $query->with(['transaksi', 'penitipanDetail.penitipan']);
    }
}