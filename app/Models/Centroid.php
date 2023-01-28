<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centroid extends Model
{
    use HasFactory;
    protected $fillable = ["data_proses_id", "transaksi_detail_id", "nama", "c1", "c2", "c3", "c4", "c5", "c6", "literasi"];
}
