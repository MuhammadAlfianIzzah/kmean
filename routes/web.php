<?php

use App\Http\Controllers\DataTransaksiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KmeanController;
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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get("/hitung-kmean", [KmeanController::class, "index"])->name("hitung.kmean");
    Route::get("/riwayat-kmean", [KmeanController::class, "riwayat"])->name("riwayat.kmean");
    Route::post("/proses-kmean", [KmeanController::class, "prosesKmean"])->name("proses-kmean");
    Route::get("/hasil-kmean/{data_proses:id}", [KmeanController::class, "hasil"])->name("hasil.kmean");
    Route::get("/calculate", [KmeanController::class, "calculate"]);
    Route::get("/check-progress/{id}", [KmeanController::class, "checkProgress"]);
    Route::get("/hasil-kmean/{data_proses_id}/literasi/{literasi}", [KmeanController::class, "showByLiterasi"])->name("show.hasil.kmean.literasi");
    Route::get("/data/import", [DataTransaksiController::class, "index"])->name("data.transaksi");
    Route::post("/data/import", [DataTransaksiController::class, "importData"])->name("data.transaksi.import");

    Route::delete('/data-reset', [DataTransaksiController::class, 'destroy'])->name('data.destroy');
});


require __DIR__ . '/auth.php';
