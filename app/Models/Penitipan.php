<?php
namespace App\Models;

use App\Models\PenitipanDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Penitipan extends Model
{
    protected $table = 'penitipans';

    protected $primaryKey = 'id_penitipan';
    protected $fillable   = [
        'nama_penitip',
        'tanggal_titip',
    ];

    public function Penitipan_detail()
    {
        return $this->belongsTo(PenitipanDetail::class, 'id_penitipan_detail');
    }

    public function details()
    {
        return $this->hasMany(PenitipanDetail::class, 'id_penitipan', 'id_penitipan');
    }

    public function scopeFilter(Builder $query, $request, array $filterableColumns): Builder
    {
        foreach ($filterableColumns as $column) {
            if ($request->filled($column)) {
                $query->where($column, $request->input($column));
            }
        }
        return $query;
    }

}
