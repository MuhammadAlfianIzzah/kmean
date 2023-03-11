<?php

namespace App\Http\Controllers;

use App\Jobs\ProsesKmeanJob;
use App\Models\Centroid;
use App\Models\DataProses;
use App\Models\Kluster;
use App\Models\TransaksiDetail;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Throwable;

class KmeanController extends Controller
{
    public function index()
    {
        return view("pages.kmean.auto.hitung_kmean");
    }
    public function prosesKmean(Request $request)
    {
        $attr = $request->validate([
            "max_literasi" => "required|numeric",
            "jumlah_centroid" => "required|numeric|max:6"
        ]);
        try {
            $dataProsesId = DataProses::create(["user_id" => auth()->user()->id ?? null])->id;
            $batch = Bus::batch([
                new ProsesKmeanJob($attr, $dataProsesId),
            ])->then(function (Batch $batch) {
                return $batch;
            })->catch(function (Batch $batch, Throwable $e) {
                return $batch;
            })->finally(function (Batch $batch) {
                return $batch;
            })->dispatch();
            $modelDataProses = DataProses::where(["id" => $dataProsesId])->first();
            $modelDataProses->update([
                "progress_id" => $batch->id
            ]);
            return redirect()->route("hasil.kmean", [$dataProsesId, "progress_id" => $batch->id])->with("success", "berhasil memproses");
        } catch (\Throwable $e) {
            return abort(404);
        }
    }
    public function checkProgress($id)
    {
        $progress = Bus::findBatch($id);
        if ($progress == null) {
            return response([
                "code" => 400,
                "message" => "data tidak ditemukan",
                "data" => Bus::findBatch($id)
            ], 404);
        }
        return response([
            "code" => 200,
            "message" => "data berhasil ditemukan",
            "data" => Bus::findBatch($id)
        ], 200);
    }
    public function calculate($data_proses_id, $literasi_satu, $literasi_dua, $persen = false)
    {
        $kluster_min_satu = Kluster::select(["nama", "c_min"])->where(["literasi" => $literasi_satu, "data_proses_id" => $data_proses_id])->get();
        $kluster_akhir = [];
        foreach ($kluster_min_satu  as $kluster_min) {
            $data = Kluster::where(["nama" => $kluster_min->nama, "c_min" => $kluster_min->c_min, "data_proses_id" => $data_proses_id, "literasi" => $literasi_dua]);
            if ($data->exists()) {
                $kluster_akhir[] = $data->first();
            }
        }
        if ($persen) {
            return (count($kluster_akhir) * 100) / $kluster_min_satu->count();
        } else {
            if ($kluster_min_satu->count() == count($kluster_akhir)) {
                return true;
            } else {
                return false;
            }
        }
    }
    public function hasil(Request $request, DataProses $data_proses)
    {
        $max_literasi = Kluster::where("data_proses_id", $data_proses->id)->get()->max("literasi");
        $data_klusters = [];
        $data_line_chart = [];
        for ($literasi = 1; $literasi <= $max_literasi; $literasi++) {
            $data_klusters[] = Kluster::where(["data_proses_id" => $data_proses->id, "literasi" => $literasi])->paginate(10, ['*'], 'kluster' . $literasi);
            if ($literasi > 0 and $literasi != $max_literasi) {
                // $data_line_chart[]["label"] = $literasi;
                $data_line_chart[] = [
                    "label" => "literasi " . $literasi + 1 . " & literasi $literasi",
                    "persen" => $this->calculate($data_proses->id, $literasi, $literasi + 1, true),
                ];
            }
        }

        $hasilElbow = Kluster::where(["data_proses_id" => $data_proses->id])->select(DB::raw('sum(nilai_cmin) as total, literasi'))->groupBy('literasi')->get();
        // dd($dataProses);
        // dd("oke");
        // dd($hasilElbow->pluck("literasi"));
        $data_line_chart = collect($data_line_chart);
        $data_chart = Kluster::where(["literasi" => $max_literasi, "data_proses_id" => $data_proses->id])->select(DB::raw('count(*) as total, c_min'))
            ->groupBy('c_min')->orderBy("c_min")->get();

        return view("pages.kmean.auto.hasil", compact("data_proses", "data_klusters", "data_chart", "data_line_chart", "hasilElbow"));
    }
    public function riwayat()
    {
        $data_proses = DataProses::get();
        return view("pages.kmean.auto.riwayat", compact("data_proses"));
    }
    public function showByLiterasi(Request $request, $data_proses_id, $literasi)
    {

        $data_klusters = Kluster::where(["data_proses_id" => $data_proses_id, "literasi" => $literasi])->filter(request(["search", "c_min"]))->paginate(10);
        $data_chart = Kluster::where(["literasi" => $literasi, "data_proses_id" => $data_proses_id])->select(DB::raw('count(*) as total, c_min'))
            ->groupBy('c_min')->orderBy("c_min")->get();
        $centroids =  Centroid::where(["data_proses_id" => $data_proses_id, "literasi" => $literasi])->get();
        return view("pages.kmean.auto.hasil.literasi", compact("data_klusters", "data_chart", "centroids"));
    }













































    // public function prosesKmean(Request $request)
    // {
    //     $attr = $request->validate([
    //         "max_literasi" => "required|numeric",
    //         "jumlah_centroid" => "required|numeric"
    //     ]);
    //     try {
    //         $max_literasi = $attr["max_literasi"];
    //         $countCentroid = $attr["jumlah_centroid"];
    //         $datas = TransaksiDetail::get();
    //         // $max =  TransaksiDetail::count() - 1;
    //         $cluster = [];
    //         $dataProsesId = DataProses::create(["user_id" => auth()->user()->id ?? null])->id;
    //         for ($key_literasi = 1; $key_literasi <= $max_literasi; $key_literasi++) {
    //             $centroid_id = [];
    //             if ($key_literasi === 1) {
    //                 for ($i = 0; $i < $countCentroid; $i++) {
    //                     $produk = $datas[$i];
    //                     $centroid =  Centroid::create([
    //                         "data_proses_id" => $dataProsesId,
    //                         "nama" => "c" . $i + 1,
    //                         "c1" => $produk->nama_operator,
    //                         "c2" => $produk->metode_pembayaran,
    //                         "c3" => $produk->quantity,
    //                         "c4" => $produk->harga,
    //                         "c5" => $produk->subtotal,
    //                         "c6" => $produk->diskon,
    //                         "literasi" => 1
    //                     ]);
    //                     $centroid_id[] = $centroid->id;
    //                 }
    //                 foreach ($datas as $data_key => $data) {
    //                     $cluster[$data_key] = [
    //                         "data_proses_id" => $dataProsesId,
    //                         "transaksi_detail_id" => $data["id"],
    //                         "nama" => $data["produk"],
    //                     ];
    //                     foreach ($centroid_id as $ct_id) {
    //                         $data_centroid = Centroid::where("id", $ct_id)->first();
    //                         $cluster[$data_key]["centroid_id"] = $data_centroid->id;
    //                         $cluster[$data_key][$data_centroid->nama] = sqrt(
    //                             pow(($data["nama_operator"] - $data_centroid->c1), 2) +
    //                                 pow(($data["metode_pembayaran"] - $data_centroid->c2), 2) +
    //                                 pow(($data["quantity"] - $data_centroid->c3), 2) +
    //                                 pow(($data["harga"] - $data_centroid->c4), 2) +
    //                                 pow(($data["subtotal"] - $data_centroid->c5), 2) +
    //                                 pow(($data["diskon"] - $data_centroid->c6), 2)
    //                         );
    //                     }
    //                     $datacluster = $cluster[$data_key];
    //                     unset($datacluster["nama"]);
    //                     unset($datacluster["data_proses_id"]);
    //                     unset($datacluster["transaksi_detail_id"]);
    //                     unset($datacluster["centroid_id"]);
    //                     $cluster[$data_key]["literasi"] = $key_literasi;
    //                     if (count($datacluster) > 0) {
    //                         $array = $datacluster;
    //                         $maxIndex = array_search(min($array), $array);
    //                         $cluster[$data_key]["c_min"] = $maxIndex;
    //                         Kluster::create($cluster[$data_key]);
    //                     }
    //                 }
    //                 $jenis_kluster = Kluster::groupBy('c_min')->select(["c_min"])->where(["data_proses_id" => $dataProsesId, "literasi" => $key_literasi])->get()->pluck("c_min");
    //                 foreach ($jenis_kluster as $jk) {
    //                     $kluster = Kluster::where(["c_min" => $jk])->get();

    //                     $ct["c1"] =  $kluster->sum("transaksi_detail.nama_operator") / $kluster->count();
    //                     $ct["c2"] =  $kluster->sum("transaksi_detail.metode_pembayaran") / $kluster->count();
    //                     $ct["c3"] =  $kluster->sum("transaksi_detail.quantity") / $kluster->count();
    //                     $ct["c4"] =  $kluster->sum("transaksi_detail.harga") / $kluster->count();
    //                     $ct["c5"] =  $kluster->sum("transaksi_detail.subtotal") / $kluster->count();
    //                     $ct["c6"] =  $kluster->sum("transaksi_detail.diskon") / $kluster->count();
    //                     $ct["nama"] = $jk;
    //                     $ct["literasi"] = $key_literasi + 1;
    //                     $ct["data_proses_id"] = $dataProsesId;
    //                     Centroid::create($ct);
    //                 }
    //             } else {
    //                 $centroid_id = Centroid::where("literasi", $key_literasi)->get()->pluck("id");
    //                 foreach ($datas as $data_key => $data) {
    //                     $cluster[$data_key] = [
    //                         "data_proses_id" => $dataProsesId,
    //                         "transaksi_detail_id" => $data["id"],
    //                         "nama" => $data["produk"],
    //                     ];
    //                     foreach ($centroid_id as $ct_id) {
    //                         $data_centroid = Centroid::where("id", $ct_id)->first();
    //                         $cluster[$data_key]["centroid_id"] = $data_centroid->id;
    //                         $cluster[$data_key][$data_centroid->nama] = sqrt(
    //                             pow(($data["nama_operator"] - $data_centroid->c1), 2) +
    //                                 pow(($data["metode_pembayaran"] - $data_centroid->c2), 2) +
    //                                 pow(($data["quantity"] - $data_centroid->c3), 2) +
    //                                 pow(($data["harga"] - $data_centroid->c4), 2) +
    //                                 pow(($data["subtotal"] - $data_centroid->c5), 2) +
    //                                 pow(($data["diskon"] - $data_centroid->c6), 2)
    //                         );
    //                     }
    //                     $datacluster = $cluster[$data_key];
    //                     unset($datacluster["nama"]);
    //                     unset($datacluster["data_proses_id"]);
    //                     unset($datacluster["transaksi_detail_id"]);
    //                     unset($datacluster["centroid_id"]);
    //                     $cluster[$data_key]["literasi"] = $key_literasi;
    //                     if (count($datacluster) > 0) {
    //                         $array = $datacluster;
    //                         $maxIndex = array_search(min($array), $array);
    //                         $cluster[$data_key]["c_min"] = $maxIndex;
    //                         Kluster::create($cluster[$data_key]);
    //                     }

    //                     if ($key_literasi !=  2) {
    //                         $selesai = $this->calculate($dataProsesId, $key_literasi - 1, $key_literasi);
    //                         if ($selesai) {
    //                             return redirect()->route("hasil.kmean", [$dataProsesId])->with("success", "berhasil memproses");
    //                         }
    //                     }
    //                 }
    //                 $jenis_kluster = Kluster::groupBy('c_min')->select(["c_min"])->where(["data_proses_id" => $dataProsesId, "literasi" => $key_literasi])->get()->pluck("c_min");
    //                 foreach ($jenis_kluster as $jk) {
    //                     $kluster = Kluster::where(["c_min" => $jk])->get();

    //                     $ct["c1"] =  $kluster->sum("transaksi_detail.nama_operator") / $kluster->count();
    //                     $ct["c2"] =  $kluster->sum("transaksi_detail.metode_pembayaran") / $kluster->count();
    //                     $ct["c3"] =  $kluster->sum("transaksi_detail.quantity") / $kluster->count();
    //                     $ct["c4"] =  $kluster->sum("transaksi_detail.harga") / $kluster->count();
    //                     $ct["c5"] =  $kluster->sum("transaksi_detail.subtotal") / $kluster->count();
    //                     $ct["c6"] =  $kluster->sum("transaksi_detail.diskon") / $kluster->count();
    //                     $ct["nama"] = $jk;
    //                     $ct["literasi"] = $key_literasi + 1;
    //                     $ct["data_proses_id"] = $dataProsesId;
    //                     Centroid::create($ct);
    //                 }
    //             }
    //         }
    //         // return;
    //         return redirect()->route("hasil.kmean", [$dataProsesId])->with("success", "berhasil memproses");
    //     } catch (\Throwable $e) {
    //         return abort(404);
    //     }
    // }
}
