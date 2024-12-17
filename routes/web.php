<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\RoutingController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\ProdukdaninventoriController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KasdanbankController;
use App\Http\Controllers\HutangpiutangController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\PajakController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

require __DIR__ . '/auth.php';

// Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
//     Route::get('', [RoutingController::class, 'index'])->name('root');
//     Route::get('/home', fn() => view('index'))->name('home');
//     Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
//     Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
//     Route::get('{any}', [RoutingController::class, 'root'])->name('any');
// });

Route::get('/', function () {
    return view('pages.dashboard.index');
});

Route::resource('/dashboard', \App\Http\Controllers\DashboardController::class);

Route::get('/penjualan/edit{id}', [PenjualanController::class, 'edit'])->name('penjualan.edit');
Route::resource('/penjualan', PenjualanController::class);

Route::get('/pengeluaran/edit{id}', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
Route::resource('/pengeluaran', PengeluaranController::class);

// Route::post('/hutangpiutang/storePembayaranHutang', [HutangPiutangController::class, 'storePembayaranHutang'])->name('hutangpiutang.store');
Route::get('/hutangpiutang/detail/{idKontak}', [HutangpiutangController::class, 'getHutangDetail']);
Route::resource('/hutangpiutang', HutangpiutangController::class);

Route::resource('/kasdanbank', KasdanbankController::class);
Route::get('/get-subkategori', [KasdanbankController::class, 'getSubkategori']);

Route::get('/pajak/ppn', [PajakController::class, 'ppn'])->name('pajak.ppn');
Route::get('/pajak/pph', [PajakController::class, 'pph'])->name('pajak.pph');
Route::get('/pajak/ppnbm', [PajakController::class, 'ppnbm'])->name('pajak.ppnbm');
Route::resource('/pajak', PajakController::class);

// Route::get('/produkdaninventori/edit{id}', [ProdukdaninventoriController::class, 'ppn'])->name('produkdaninventori.edit');
Route::resource('/produkdaninventori', ProdukdaninventoriController::class);

Route::resource('/asset', AssetController::class);
Route::get('/get-asset-data/{id}', [AssetController::class, 'getAssetData']);
Route::post('/penjualan-asset/store', [AssetController::class, 'store_penjualan'])->name('penjualan-asset.store');
Route::get('/get-asset-detail/{id}', [AssetController::class, 'getAssetDetail']);


Route::resource('/kategori', KategoriController::class);

// Route modal
Route::get('/modal', [ModalController::class, 'index'])->name('modal.index');
Route::post('/modal', [ModalController::class, 'store'])->name('modal.store');
Route::put('/modal/{modal}', [ModalController::class, 'update'])->name('modal.update');
Route::delete('/modal/{modal}', [ModalController::class, 'destroy'])->name('modal.destroy');

// Route akun
Route::get('/akun', [AkunController::class, 'index'])->name('akun.index');
Route::post('/akun', [AkunController::class, 'store'])->name('akun.store');
Route::put('/akun/{akun}', [AkunController::class, 'update'])->name('akun.update');
Route::delete('/akun/{akun}', [AkunController::class, 'destroy'])->name('akun.destroy');
Route::get('/get-kategori-akun', [AkunController::class, 'getKategoriAkun'])->name('get-kategori-akun');
Route::get('/get-subakun-by-kategori', [AkunController::class, 'getSubAkunByKategori'])->name('get-subakun-by-kategori');

// route kontak
Route::get('/kontak/export-pdf', [KontakController::class, 'exportKontakToPDF'])->name('export.kontak.pdf');
Route::get('/kontak/export-kontak', [KontakController::class, 'exportKontakToExcel'])->name('export.kontak');
Route::resource('/kontak', KontakController::class);
