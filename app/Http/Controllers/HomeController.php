<?php

namespace App\Http\Controllers;

use App\Models\Centroid;
use App\Models\DataProses;
use App\Models\Engine;
use App\Models\Kluster;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class HomeController extends Controller
{
    public function checkProgress($id)
    {
        $progress = Bus::findBatch($id);
        if ($progress == null) {
            return response([
                "code" => 400,
                "message" => "data tidak ditemukan",
                "data" => $progress
            ], 404);
        }
        $engineProses = Engine::where(["job_id" => $progress->id])->first();
        if ($engineProses) {
            if ($progress->finishedAt != null) {
            }
        }
        return response([
            "code" => 200,
            "message" => "data berhasil ditemukan",
            "data" => $progress
        ], 200);
    }
}
