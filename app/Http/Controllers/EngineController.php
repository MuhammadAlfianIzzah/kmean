<?php

namespace App\Http\Controllers;

use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use App\Jobs\EngineJob;
use App\Models\Engine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class EngineController extends Controller
{
    public function index()
    {
        $engines = Engine::paginate();
        return view("pages.admin.engine.index", compact("engines"));
    }
    public function show(Request $request, Engine $engine)
    {
        $klusters = $engine->historyEngine()->orderBy("cluster")->distinct()->pluck("cluster");
        return view("pages.admin.engine.show", compact("engine", "klusters"));
    }

    public function store(Request $request)
    {
        $attr = $request->validate([
            "nama" => "required",
            "jumlah_cluster" => "required",
            "max_literasi" => "required",
        ]);
        $attr["user_id"] = Auth::user()->id;
        $engine = Engine::create($attr);
        return back()->with("success", "berhasil menyimpan data engine");
    }
    public function destroy(Request $request, Engine $engine)
    {
        $engine->delete();
        return back()->with("success", "berhasil menghapus data engine");
    }
    public function runningAnalisis(Request $request, Engine $engine)
    {
        $engine->historyEngine()->delete();
        try {
            $batch = Bus::batch([
                new EngineJob($engine->id)
            ])->then(function (Batch $batch) {
                // All jobs completed successfully...
            })->catch(function (Batch $batch, Throwable $e) {
                // First batch job failure detected...
            })->finally(function (Batch $batch) {
                // The batch has finished executing...
            })->dispatch();

            $engine->update([
                "job_id" => $batch->id
            ]);
            return redirect("engine/" . $engine->id . "?job_id=" . $batch->id);;
        } catch (\Throwable $e) {
            return back()->with(400, "Telah terjadi sesuatu");
        }
    }
}
