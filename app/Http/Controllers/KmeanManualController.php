<?php

namespace App\Http\Controllers;

use App\Jobs\ProsesKmeanJob;
use App\Jobs\ProsesKmeanManual;
use App\Models\Centroid;
use App\Models\DataProses;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Throwable;

class KmeanManualController extends Controller
{
    public function index()
    {
        return view("pages.kmean.manual.hitung_kmean");
    }
    public function steps1(Request $request)
    {
        $data = $request->validate([
            "max_literasi" => "required|numeric",
            "jumlah_centroid" => "required|numeric|max:6"
        ]);
        $attr["user_id"] = Auth::user()->id;
        $dataProses = DataProses::create($attr);
        return redirect()->route("proses-kmean-steps-2", [
            "max_literasi" => $data["max_literasi"],
            "jumlah_centroid" => $data["jumlah_centroid"],
            "data_proses_id" => $dataProses->id
        ]);
    }
    public function steps2(Request $request)
    {
        $centroids = Centroid::where(["data_proses_id" => $request->data_proses_id])->get();
        return view("pages.kmean.manual.steps2", compact("centroids"));
    }
    public function tambahCentroid(Request $request)
    {
        $attr = $request->validate([
            "data_proses_id" => "required",
            "nama" => "required",
            "c1" => "required|numeric",
            "c2" => "required|numeric",
            "c3" => "required|numeric",
            "c4" => "required|numeric",
            "literasi" => "required",
        ]);
        Centroid::create($attr);
        return back()->with("success", "berhasil menyimpan data");
    }

    public function prosesKmean(Request $request)
    {

        $attr = $request->validate([
            "max_literasi" => "required|numeric",
            "jumlah_centroid" => "required|numeric|max:6",
            "data_proses_id" => "required"
        ]);

        try {
            $dataProsesId = $attr["data_proses_id"];
            $batch = Bus::batch([
                new ProsesKmeanManual($attr, $dataProsesId),
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
}
