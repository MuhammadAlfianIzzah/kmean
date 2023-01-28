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
            'tanggal' => $row[0],
            'produk' => $row[1],
            'nomor_struk' => $row[2],
            'nama_operator' => $row[3],
            'metode_pembayaran' => $row[4],
            'quantity' => $row[5],
            'harga' => $row[6],
            'subtotal' => $row[7],
            'diskon' => $row[8],
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
