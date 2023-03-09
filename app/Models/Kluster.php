<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kluster extends Model
{
    protected $fillable = ["transaksi_detail_id", "nama", "c1", "c2", "c3", "c4", "c5", "c6", "c_min", "literasi", "data_proses_id", "centroid_id", "nilai_cmin"];
    use HasFactory;
    public function transaksi_detail()
    {
        return $this->belongsTo(TransaksiDetail::class, "transaksi_detail_id");
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('nama', 'ilike', '%' . $search . '%');
            });
        });
        $query->when($filters['c_min'] ?? false, function ($query, $c_min) {
            return $query->where(function ($query) use ($c_min) {
                $query->where('c_min', $c_min);
            });
        });
    }
}
