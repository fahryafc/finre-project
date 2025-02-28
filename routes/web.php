<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvitesController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\PajakController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\KasdanbankController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\HutangpiutangController;
use App\Http\Controllers\ProdukdaninventoriController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\SubscriptionController;
use App\Models\Invites;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    if (Auth::check()) {
        // Jika role user owner
        if (Auth::user()->hasRole('owner')) {
            // return redirect('/dashboard-owner');
            echo "Berhasil Login Sebagai Owner";
        }

        // Jika role user inviter
        if (Auth::user()->hasRole('inviter')) {
            // return redirect('/dashboard');
            echo "Berhasil Login Sebagai Inviter";

        }

        $user = Auth::user();
        // Jika status member accepted
        $invite_status = Invites::where('email', $user->email)->where('status', 'accepted')->first();

        // Jika user terinvite
        if ($invite_status) {
            // JIka user memiliki permission
            if (count($user->permissions) > 0) {
                return redirect()->intended('/' . $user->permissions->toArray()[0]['name']);
            } else {
                return redirect()->intended('/waiting-permission');
            }
        } else {
            return redirect('/daftar-paket');
        }
    }
    return redirect()->route('login');
});

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [PagesController::class, 'login'])->name('login');
    Route::get('/register', [PagesController::class, 'register']);
    Route::get('/forget-password', [PagesController::class, 'forget_password']);
    Route::get('/reset-password/{slug}', [PagesController::class, 'reset_password']);

    // ---------------------------------------------------------------------------------------------

    Route::post('/forget-password-process', [AuthController::class, 'forget_password']);
    Route::post('/reset-password-process', [AuthController::class, 'reset_password']);
    Route::post('/register-process', [AuthController::class, 'register']);
    Route::post('/login-process', [AuthController::class, 'login']);
});

Route::get('/join', [PagesController::class, 'join_from_afiliate']);

// Route::get('/', function () {
//     return view('pages.dashboard.index');
// });

Route::prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/pendapatan', [DashboardController::class, 'pendapatan']);
    Route::get('/pengeluaran', [DashboardController::class, 'pengeluaran']);
});

Route::get('/penjualan/edit{id}', [PenjualanController::class, 'edit'])->name('penjualan.edit');
Route::get('/penjualan/detail/{id}', [PenjualanController::class, 'detail'])->name('penjualan.detail');
Route::resource('/penjualan', PenjualanController::class);

Route::get('/pengeluaran/edit{id}', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
Route::get('/pengeluaran/pengeluaran', [PengeluaranController::class, 'pengeluaran'])->name('pengeluaran.pengeluaran');
Route::resource('/pengeluaran', PengeluaranController::class);

Route::post('/hutangpiutang/storePembayaranHutang', [HutangPiutangController::class, 'storePembayaranHutang'])->name('hutangpiutang.store');
Route::get('/hutangpiutang/detail/{idKontak}', [HutangpiutangController::class, 'getHutangDetail']);
Route::resource('/hutangpiutang', HutangpiutangController::class);

Route::resource('/kasdanbank', KasdanbankController::class);
Route::get('/get-subkategori', [KasdanbankController::class, 'getSubkategori']);

Route::get('/pajak/ppn', [PajakController::class, 'ppn'])->name('pajak.ppn');
Route::get('/pajak/pph', [PajakController::class, 'pph'])->name('pajak.pph');
Route::get('/pajak/ppnbm', [PajakController::class, 'ppnbm'])->name('pajak.ppnbm');
Route::resource('/pajak', PajakController::class);

Route::get('/produkdaninventori/edit{id}', [ProdukdaninventoriController::class, 'ppn'])->name('produkdaninventori.edit');
Route::resource('/produkdaninventori', ProdukdaninventoriController::class);
Route::post('/check-hampir-habis', [ProdukdaninventoriController::class, 'check_hampir_habis']);

Route::get('/asset/asset_tersedia', [AssetController::class, 'asset_tersedia'])->name('asset.asset_tersedia');
Route::get('/asset/asset_terjual', [AssetController::class, 'asset_terjual'])->name('asset.asset_terjual');
Route::get('/get-asset-data/{id}', [AssetController::class, 'getAssetData']);
Route::post('/penjualan-asset/store', [AssetController::class, 'store_penjualan'])->name('penjualan-asset.store');
Route::get('/get-asset-detail/{id}', [AssetController::class, 'getAssetDetail']);
Route::get('/asset/tambah_asset', [AssetController::class, 'create'])->name('asset.tambah_asset');
Route::get('/asset/jual_asset/{id}', [AssetController::class, 'jual'])->name('asset.jual_asset');
Route::get('/asset/edit_asset/{id}', [AssetController::class, 'edit'])->name('asset.edit_asset');
Route::get('/asset/detail_asset/{id}', [AssetController::class, 'detail'])->name('asset.detail_asset');
Route::resource('/asset', AssetController::class);

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
Route::get('/akun/{id}', [AkunController::class, 'show']);
Route::get('/get-kategori-akun', [AkunController::class, 'getKategoriAkun'])->name('get-kategori-akun');
Route::get('/get-subakun-by-kategori', [AkunController::class, 'getSubAkunByKategori'])->name('get-subakun-by-kategori');

// route kontak
Route::get('/kontak/export-pdf', [KontakController::class, 'exportKontakToPDF'])->name('export.kontak.pdf');
Route::get('/kontak/export-kontak', [KontakController::class, 'exportKontakToExcel'])->name('export.kontak');
Route::resource('/kontak', KontakController::class);

// route jurnal
Route::get('/jurnal', [JurnalController::class, 'index'])->name('jurnal.index');
Route::get('/aruskas', [JurnalController::class, 'aruskas'])->name('aruskas.index');
Route::get('/neraca', [JurnalController::class, 'neraca'])->name('neraca.index');
Route::get('/labarugi', [JurnalController::class, 'labarugi'])->name('labarugi.index');
Route::get('/jurnal/detail/{id}/{code}', [JurnalController::class, 'detail'])->name('jurnal.detail');
Route::get('/jurnal/export-pdf', [JurnalController::class, 'exportToPDF'])->name('jurnal.export.pdf');
Route::get('/jurnal/export-excel', [JurnalController::class, 'exportToExcel'])->name('jurnal.export.excel');