<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\HistoryEngine;
use Illuminate\Http\Request;

class HistoryEngineController extends Controller
{
    public function store(Request $request)
    {
        $attr = $request->validate([
            'kode' => 'required',
            'nama_barang' => 'nullable',
            'stok_awal' => 'required',
            'ttl_penjualan' => 'nullable',
            'stok_akhir' => 'nullable',
            'cluster' => 'nullable',
            'engine_id' => 'nullable',
        ]);
        $attr["nama_barang"] = $attr["nama_barang"] ?? "-";
        try {
            HistoryEngine::create($attr);
            return response()->json([], 200);
        } catch (\Throwable $th) {
            return response()->json([], 400);
        }
    }
}
