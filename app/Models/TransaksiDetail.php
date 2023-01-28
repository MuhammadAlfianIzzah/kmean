<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $fillable = ["tanggal", "produk", "nomor_struk", "nama_operator", "metode_pembayaran", "quantity", "harga", "subtotal", "diskon"];
    use HasFactory;
}
