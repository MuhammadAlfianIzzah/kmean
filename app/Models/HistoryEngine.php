<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryEngine extends Model
{
    use HasFactory;
    protected $fillable = [
        "kode", "nama_barang", "stok_awal", "ttl_penjualan", "stok_akhir", "cluster", "engine_id"
    ];
}
