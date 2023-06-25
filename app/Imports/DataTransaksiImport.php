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
            'kode' => $row[0],
            'nama_barang' => $row[1],
            'stok_awal' => $row[2],
            'ttl_penjualan' => $row[3],
            'stok_akhir' => $row[4],
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
