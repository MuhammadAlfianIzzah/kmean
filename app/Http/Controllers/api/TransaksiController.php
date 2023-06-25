<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = TransaksiDetail::select(["kode", "stok_awal", "ttl_penjualan", "stok_akhir", "nama_barang"])->get();

        return response()->json([
            "data" => $transaksi
        ], 200);
    }
}
