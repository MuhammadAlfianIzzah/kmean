<?php

use App\Http\Controllers\DataTransaksiController;
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

Route::middleware('auth')->group(function () {
    // auto
    Route::get("kmean-auto/hitung-kmean", [KmeanController::class, "index"])->name("hitung.kmean");
    Route::get("kmean-auto/riwayat-kmean", [KmeanController::class, "riwayat"])->name("riwayat.kmean");
    Route::post("kmean-auto/proses-kmean", [KmeanController::class, "prosesKmean"])->name("proses-kmean");
    Route::get("kmean-auto/hasil-kmean/{data_proses:id}", [KmeanController::class, "hasil"])->name("hasil.kmean");
    Route::get("kmean-auto/calculate", [KmeanController::class, "calculate"]);
    Route::get("kmean-auto/check-progress/{id}", [KmeanController::class, "checkProgress"]);
    Route::get("kmean-auto/hasil-kmean/{data_proses_id}/literasi/{literasi}", [KmeanController::class, "showByLiterasi"])->name("show.hasil.kmean.literasi");
    //

    // manual
    Route::get("kmean/hitung-kmean", [KmeanManualController::class, "index"])->name("manual.hitung.kmean");
    Route::post("kmean/hitung-kmean/steps-1", [KmeanManualController::class, "steps1"])->name("proses-kmean-steps-1");
    Route::get("kmean/hitung-kmean/steps-2", [KmeanManualController::class, "steps2"])->name("proses-kmean-steps-2");
    Route::post("kmean/hitung-kmean/steps-2/tambah-centroid", [KmeanManualController::class, "tambahCentroid"])->name("proses-kmean-steps-2.tambah.centroid");
    Route::post("kmean/hitung-kmean/steps-3", [KmeanManualController::class, "prosesKmean"])->name("proses-kmean-steps-3");
    // manual




    Route::get("/data/import", [DataTransaksiController::class, "index"])->name("data.transaksi");
    Route::post("/data/import", [DataTransaksiController::class, "importData"])->name("data.transaksi.import");
    Route::delete('/data-reset', [DataTransaksiController::class, 'destroy'])->name('data.destroy');
});


require __DIR__ . '/auth.php';
