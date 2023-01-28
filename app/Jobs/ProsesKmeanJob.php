<?php

namespace App\Jobs;

use App\Models\Centroid;
use App\Models\TransaksiDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\DataProses;
use App\Models\Kluster;
use App\Models\User;
use Illuminate\Bus\Batchable;

class ProsesKmeanJob implements ShouldQueue
{
    public $attr;
    public $dataProsesId;
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($attr, $dataProsesId)
    {
        $this->attr = $attr;
        $this->dataProsesId = $dataProsesId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $max_literasi = $this->attr["max_literasi"] ?? 10;
        $countCentroid = $this->attr["jumlah_centroid"];
        $datas = TransaksiDetail::get();
        // $max =  TransaksiDetail::count() - 1;
        $cluster = [];
        for ($key_literasi = 1; $key_literasi <= $max_literasi; $key_literasi++) {
            $centroid_id = [];
            if ($key_literasi === 1) {
                for ($i = 0; $i < $countCentroid; $i++) {
                    $produk = $datas[$i];
                    $centroid =  Centroid::create([
                        "data_proses_id" => $this->dataProsesId,
                        "nama" => "c" . $i + 1,
                        "c1" => $produk->nama_operator,
                        "c2" => $produk->metode_pembayaran,
                        "c3" => $produk->quantity,
                        "c4" => $produk->harga,
                        "c5" => $produk->subtotal,
                        "c6" => $produk->diskon,
                        "literasi" => 1
                    ]);
                    $centroid_id[] = $centroid->id;
                }
                foreach ($datas as $data_key => $data) {
                    $cluster[$data_key] = [
                        "data_proses_id" => $this->dataProsesId,
                        "transaksi_detail_id" => $data["id"],
                        "nama" => $data["produk"],
                    ];
                    foreach ($centroid_id as $ct_id) {
                        $data_centroid = Centroid::where("id", $ct_id)->first();
                        $cluster[$data_key]["centroid_id"] = $data_centroid->id;
                        $cluster[$data_key][$data_centroid->nama] = sqrt(
                            pow(($data["nama_operator"] - $data_centroid->c1), 2) +
                                pow(($data["metode_pembayaran"] - $data_centroid->c2), 2) +
                                pow(($data["quantity"] - $data_centroid->c3), 2) +
                                pow(($data["harga"] - $data_centroid->c4), 2) +
                                pow(($data["subtotal"] - $data_centroid->c5), 2) +
                                pow(($data["diskon"] - $data_centroid->c6), 2)
                        );
                    }
                    $datacluster = $cluster[$data_key];
                    unset($datacluster["nama"]);
                    unset($datacluster["data_proses_id"]);
                    unset($datacluster["transaksi_detail_id"]);
                    unset($datacluster["centroid_id"]);
                    $cluster[$data_key]["literasi"] = $key_literasi;
                    if (count($datacluster) > 0) {
                        $array = $datacluster;
                        $maxIndex = array_search(min($array), $array);
                        $cluster[$data_key]["c_min"] = $maxIndex;
                        Kluster::create($cluster[$data_key]);
                    }
                }
                $jenis_kluster = Kluster::groupBy('c_min')->select(["c_min"])->where(["data_proses_id" => $this->dataProsesId, "literasi" => $key_literasi])->get()->pluck("c_min");
                foreach ($jenis_kluster as $jk) {
                    $kluster = Kluster::where(["c_min" => $jk])->get();

                    $ct["c1"] =  $kluster->sum("transaksi_detail.nama_operator") / $kluster->count();
                    $ct["c2"] =  $kluster->sum("transaksi_detail.metode_pembayaran") / $kluster->count();
                    $ct["c3"] =  $kluster->sum("transaksi_detail.quantity") / $kluster->count();
                    $ct["c4"] =  $kluster->sum("transaksi_detail.harga") / $kluster->count();
                    $ct["c5"] =  $kluster->sum("transaksi_detail.subtotal") / $kluster->count();
                    $ct["c6"] =  $kluster->sum("transaksi_detail.diskon") / $kluster->count();
                    $ct["nama"] = $jk;
                    $ct["literasi"] = $key_literasi + 1;
                    $ct["data_proses_id"] = $this->dataProsesId;
                    Centroid::create($ct);
                }
            } else {
                $centroid_id = Centroid::where("literasi", $key_literasi)->get()->pluck("id");
                foreach ($datas as $data_key => $data) {
                    $cluster[$data_key] = [
                        "data_proses_id" => $this->dataProsesId,
                        "transaksi_detail_id" => $data["id"],
                        "nama" => $data["produk"],
                    ];
                    foreach ($centroid_id as $ct_id) {
                        $data_centroid = Centroid::where("id", $ct_id)->first();
                        $cluster[$data_key]["centroid_id"] = $data_centroid->id;
                        $cluster[$data_key][$data_centroid->nama] = sqrt(
                            pow(($data["nama_operator"] - $data_centroid->c1), 2) +
                                pow(($data["metode_pembayaran"] - $data_centroid->c2), 2) +
                                pow(($data["quantity"] - $data_centroid->c3), 2) +
                                pow(($data["harga"] - $data_centroid->c4), 2) +
                                pow(($data["subtotal"] - $data_centroid->c5), 2) +
                                pow(($data["diskon"] - $data_centroid->c6), 2)
                        );
                    }
                    $datacluster = $cluster[$data_key];
                    unset($datacluster["nama"]);
                    unset($datacluster["data_proses_id"]);
                    unset($datacluster["transaksi_detail_id"]);
                    unset($datacluster["centroid_id"]);
                    $cluster[$data_key]["literasi"] = $key_literasi;
                    if (count($datacluster) > 0) {
                        $array = $datacluster;
                        $maxIndex = array_search(min($array), $array);
                        $cluster[$data_key]["c_min"] = $maxIndex;
                        Kluster::create($cluster[$data_key]);
                    }

                    if ($key_literasi !=  2) {
                        $selesai = $this->calculate($this->dataProsesId, $key_literasi - 1, $key_literasi);
                        if ($selesai) {
                            return redirect()->route("hasil.kmean", [$this->dataProsesId])->with("success", "berhasil memproses");
                        }
                    }
                }
                $jenis_kluster = Kluster::groupBy('c_min')->select(["c_min"])->where(["data_proses_id" => $this->dataProsesId, "literasi" => $key_literasi])->get()->pluck("c_min");
                foreach ($jenis_kluster as $jk) {
                    $kluster = Kluster::where(["c_min" => $jk])->get();

                    $ct["c1"] =  $kluster->sum("transaksi_detail.nama_operator") / $kluster->count();
                    $ct["c2"] =  $kluster->sum("transaksi_detail.metode_pembayaran") / $kluster->count();
                    $ct["c3"] =  $kluster->sum("transaksi_detail.quantity") / $kluster->count();
                    $ct["c4"] =  $kluster->sum("transaksi_detail.harga") / $kluster->count();
                    $ct["c5"] =  $kluster->sum("transaksi_detail.subtotal") / $kluster->count();
                    $ct["c6"] =  $kluster->sum("transaksi_detail.diskon") / $kluster->count();
                    $ct["nama"] = $jk;
                    $ct["literasi"] = $key_literasi + 1;
                    $ct["data_proses_id"] = $this->dataProsesId;
                    Centroid::create($ct);
                }
            }
        }
    }

    public function calculate($data_proses_id, $literasi_satu, $literasi_dua)
    {
        $kluster_min_satu = Kluster::select(["nama", "c_min"])->where(["literasi" => $literasi_satu, "data_proses_id" => $data_proses_id])->get();
        $kluster_akhir = [];
        foreach ($kluster_min_satu  as $kluster_min) {
            $data = Kluster::where(["nama" => $kluster_min->nama, "c_min" => $kluster_min->c_min, "data_proses_id" => $data_proses_id, "literasi" => $literasi_dua]);
            if ($data->exists()) {
                $kluster_akhir[] = $data->first();
            }
        }
        if ($kluster_min_satu->count() == count($kluster_akhir)) {
            return true;
        } else {
            return false;
        }
    }
}
