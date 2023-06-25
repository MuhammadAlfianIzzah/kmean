<?php

use App\Http\Controllers\DataTransaksiController;
use App\Http\Controllers\EngineController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KmeanController;
use App\Http\Controllers\KmeanManualController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route("login");
    // return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/check-progress/{id}', [HomeController::class, "checkProgress"])->name('check-progress');

Route::middleware('auth')->group(function () {
    // auto
    Route::prefix('kmean-auto')->group(function () {
        Route::get("/hitung-kmean", [KmeanController::class, "index"])->name("hitung.kmean");

        Route::post("/proses-kmean", [KmeanController::class, "prosesKmean"])->name("proses-kmean");
        Route::get("kmean/hasil-kmean/{data_proses:id}", [KmeanController::class, "hasil"])->name("hasil.kmean");
        Route::get("/calculate", [KmeanController::class, "calculate"]);
        Route::get("/check-progress/{id}", [KmeanController::class, "checkProgress"]);
    });

    Route::get("kmean/hasil-kmean/{data_proses_id}/literasi/{literasi}", [KmeanController::class, "showByLiterasi"])->name("show.hasil.kmean.literasi");
    //
    Route::prefix('kmean')->group(function () {
        Route::get("/riwayat-kmean", [KmeanController::class, "riwayat"])->name("riwayat.kmean");
    });
    // manual

    // manual
    Route::prefix('kmean-manual')->group(function () {
        Route::get("/hitung-kmean", [KmeanManualController::class, "index"])->name("manual.hitung.kmean");
        Route::post("/hitung-kmean/steps-1", [KmeanManualController::class, "steps1"])->name("proses-kmean-steps-1");
        Route::get("/hitung-kmean/steps-2", [KmeanManualController::class, "steps2"])->name("proses-kmean-steps-2");
        Route::post("/hitung-kmean/steps-2/tambah-centroid", [KmeanManualController::class, "tambahCentroid"])->name("proses-kmean-steps-2.tambah.centroid");
        Route::post("/hitung-kmean/steps-3", [KmeanManualController::class, "prosesKmean"])->name("proses-kmean-steps-3");
    });



    Route::get("/data/import", [DataTransaksiController::class, "index"])->name("data.transaksi");
    Route::post("/data/import", [DataTransaksiController::class, "importData"])->name("data.transaksi.import");
    Route::delete('/data-reset', [DataTransaksiController::class, 'destroy'])->name('data.destroy');



    Route::get('/engine', [EngineController::class, "index"])->name("engine.index");
    Route::get('/engine/{engine:id}', [EngineController::class, "show"])->name("engine.show");
    Route::post('/engine', [EngineController::class, "store"])->name("engine.store");
    Route::delete('/engine/{engine:id}', [EngineController::class, "destroy"])->name("engine.destroy");
    Route::post('/engine/{engine:id}/running', [EngineController::class, "runningAnalisis"])->name("engine.run");
});


require __DIR__ . '/auth.php';
