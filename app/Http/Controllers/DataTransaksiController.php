<?php

namespace App\Http\Controllers;

use App\Imports\DataTransaksiImport;
use App\Jobs\ImportDataTransaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class DataTransaksiController extends Controller
{
    public function index()
    {
        $datas = TransaksiDetail::paginate(10);
        return view("pages.data.index", compact("datas"));
    }
    public function importData(Request $request)
    {
        $attr = $request->validate([
            "file" => "file||mimes:xlsx,csv"
        ]);
        Excel::import(new DataTransaksiImport, $request->file("file"));
        return back()->with('success', 'All good!');
    }
    public function destroy()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TransaksiDetail::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return back()->with('success', 'All good!');
    }
}
