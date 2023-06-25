<?php

namespace App\Jobs;

use App\Models\Engine;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EngineJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $engineId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($engineId)
    {
        $this->engineId = $engineId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Running command");
        $engineId = $this->engineId;
        $engine = Engine::where([
            "id" => $engineId
        ])->first();

        $command = escapeshellcmd('python.exe E:\code\kmeans\app\python\engine.py ' . $engine->id . " " . $engine->jumlah_cluster . " " . $engine->max_literasi);

        $output = shell_exec($command);
        Log::info($output);
    }
}
