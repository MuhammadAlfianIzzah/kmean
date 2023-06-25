<?php

use App\Http\Controllers\api\EngineController;
use App\Http\Controllers\api\HistoryEngineController;
use App\Http\Controllers\api\TransaksiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/transaksi", [TransaksiController::class, "index"]);
Route::post("/history-engine", [HistoryEngineController::class, "store"]);
Route::post("/engine/{engine:id}/update", [EngineController::class, "update"]);
