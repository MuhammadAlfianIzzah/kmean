<?php

namespace App\Imports;

use App\Models\TransaksiDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DataTransaksiImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new TransaksiDetail([
            'kode' => $row[1],
            'nama_barang' => $row[2],
            'stok_awal' => $row[3],
            'stok_masuk' => $row[4],
            'ttl_penjualan' => $row[5],
            'stok_akhir' => $row[6],
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
