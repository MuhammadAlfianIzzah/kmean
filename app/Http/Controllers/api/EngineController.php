<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Engine;
use Illuminate\Http\Request;

class EngineController extends Controller
{
    public function update(Request $request, Engine $engine)
    {
        $attr = $request->validate([
            "elbow" => "required",
            "legent" => "required",
            "finish_at" => "nullable"
        ]);
        try {
            $engine->update($attr);
            return response()->json([], 200);
        } catch (\Throwable $th) {
            return response()->json([], 400);
        }
    }
}
