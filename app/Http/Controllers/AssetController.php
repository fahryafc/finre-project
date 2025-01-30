<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Assetpenyusutan;
use App\Models\Satuan;
use App\Models\Akun;
use App\Models\Produk;
use App\Models\Kontak;
use App\Models\PenjualanAsset;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Exception;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;


class AssetController extends Controller
{
     /* Fungsi untuk generate kode reff pajak unik */
    private function generateKodeReff(string $prefix): string
    {
        do {
            $kodeReff = $prefix . '-' . strtoupper(Str::random(6));
        } while (
            DB::table('pajak_ppnbm')->where('kode_reff', $kodeReff)->exists() || 
            DB::table('pajak_ppn')->where('kode_reff', $kodeReff)->exists()
        );

        return $kodeReff;
    }

    public function index()
    {
        // $title = 'Hapus Data!';
        // $text = "Apakah kamu yakin menghapus data ini ?";
        // confirmDelete($title, $text);

        try {
            // Mengambil data aset dan melakukan join dengan tabel asset_penyusutan
            $asset = Aset::leftJoin('asset_penyusutan', 'asset.id_aset', '=', 'asset_penyusutan.id_aset')
                ->select(
                    'asset.*',
                    'asset_penyusutan.nominal_masa_manfaat',
                    'asset_penyusutan.masa_manfaat',
                    'asset_penyusutan.nilai_tahun',
                    'asset_penyusutan.nominal_nilai_tahun',
                    'asset_penyusutan.tanggal_penyusutan',
                    DB::raw('asset.harga_beli * asset.kuantitas AS total_harga'), // Menghitung total harga
                    DB::raw('IFNULL(asset_penyusutan.nominal_masa_manfaat, 0) AS total_penyusutan'), // Total penyusutan
                    DB::raw('
                            CASE 
                                WHEN asset_penyusutan.tanggal_penyusutan IS NOT NULL AND asset_penyusutan.nominal_masa_manfaat IS NOT NULL THEN 
                                    (asset.harga_beli * asset.kuantitas) - 
                                    (TIMESTAMPDIFF(YEAR, asset_penyusutan.tanggal_penyusutan, CURDATE()) * asset_penyusutan.nominal_masa_manfaat) 
                                ELSE 
                                    asset.harga_beli * asset.kuantitas 
                            END AS harga_buku_masa_manfaat
                        '),
                    DB::raw('
                            CASE 
                                WHEN asset_penyusutan.tanggal_penyusutan IS NOT NULL AND asset_penyusutan.nominal_nilai_tahun IS NOT NULL THEN 
                                    (asset.harga_beli * asset.kuantitas) - 
                                    (TIMESTAMPDIFF(YEAR, asset_penyusutan.tanggal_penyusutan, CURDATE()) * asset_penyusutan.nominal_nilai_tahun) 
                                ELSE 
                                    asset.harga_beli * asset.kuantitas 
                            END AS harga_buku_nilai_tahun
                        '),
                    DB::raw('IF(asset_penyusutan.masa_manfaat IS NOT NULL, 100 / asset_penyusutan.masa_manfaat, 0) AS persentase_masa_manfaat') // Persentase masa manfaat per tahun
                )
                ->paginate(5);

            $assetTerjual = Aset::leftJoin('asset_penyusutan', 'asset.id_aset', '=', 'asset_penyusutan.id_aset')
                ->leftJoin('penjualan_asset', 'asset.id_aset', '=', 'penjualan_asset.id_aset') // Join dengan tabel penjualan_asset
                ->select(
                    'asset.*',
                    'asset_penyusutan.nominal_masa_manfaat',
                    'asset_penyusutan.masa_manfaat',
                    'asset_penyusutan.tanggal_penyusutan',
                    'penjualan_asset.kuantitas AS terjual',
                    DB::raw('asset.harga_beli * asset.kuantitas AS total_harga'), // Menghitung total harga
                    DB::raw('IFNULL(asset_penyusutan.nominal_masa_manfaat, 0) AS total_penyusutan'), // Total penyusutan
                    DB::raw('
                            CASE 
                                WHEN asset_penyusutan.tanggal_penyusutan IS NOT NULL AND asset_penyusutan.nominal_masa_manfaat IS NOT NULL THEN 
                                    (asset.harga_beli * asset.kuantitas) - 
                                    (TIMESTAMPDIFF(YEAR, asset_penyusutan.tanggal_penyusutan, CURDATE()) * asset_penyusutan.nominal_masa_manfaat) 
                                ELSE 
                                    asset.harga_beli * asset.kuantitas 
                            END AS harga_buku
                        '),
                    DB::raw('IF(asset_penyusutan.masa_manfaat IS NOT NULL, 100 / asset_penyusutan.masa_manfaat, 0) AS persentase_masa_manfaat'), // Persentase masa manfaat per tahun
                    'penjualan_asset.kuantitas AS kuantitas_terjual'
                )
                ->where('asset.asset_terjual', 1)
                ->paginate(5);

            // Ambil data tambahan lainnya
            $satuan = Satuan::get();
            $kategori = Kategori::get();
            $akun = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
            $akun_penyusutan = DB::table('akun')->where('kategori_akun', '=', 'Beban')->get();
            $akun_deposit = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
            $akun_kredit = DB::table('akun')
                ->whereIn('kategori_akun', ['Pendapatan', 'Beban'])
                ->get();
            $kasdanbank = DB::table('kas_bank')->get();
            $total_nilai_asset = DB::table('asset')
                ->selectRaw('SUM(harga_beli * kuantitas) as total_nilai_asset')
                ->value('total_nilai_asset');
            $totalTersedia = Aset::sum('kuantitas');
            $totalTerjual = PenjualanAsset::sum('kuantitas');
            $pemasoks = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();


            // dd($assetTerjual);
            // Kirim data ke view
            return view('pages.asset.index', [
                'asset' => $asset,
                'kategori' => $kategori,
                'assetTerjual' => $assetTerjual,
                'satuan' => $satuan,
                'akun' => $akun,
                'pemasoks' => $pemasoks,
                'kasdanbank' => $kasdanbank,
                'akun_penyusutan' => $akun_penyusutan,
                'total_nilai_asset' => $total_nilai_asset,
                'akun_kredit' => $akun_kredit,
                'akun_deposit' => $akun_deposit,
                'totalTersedia' => $totalTersedia,
                'totalTerjual' => $totalTerjual,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all datas asset failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function asset_tersedia()
    {
        // $title = 'Hapus Data!';
        // $text = "Apakah kamu yakin menghapus data ini ?";
        // confirmDelete($title, $text);

        try {
            // Mengambil data aset dan melakukan join dengan tabel asset_penyusutan
            $asset = Aset::leftJoin('asset_penyusutan', 'asset.id_aset', '=', 'asset_penyusutan.id_aset')
                ->select(
                    'asset.*',
                    'asset_penyusutan.nominal_masa_manfaat',
                    'asset_penyusutan.masa_manfaat',
                    'asset_penyusutan.nilai_tahun',
                    'asset_penyusutan.nominal_nilai_tahun',
                    'asset_penyusutan.tanggal_penyusutan',
                    DB::raw('asset.harga_beli * asset.kuantitas AS total_harga'), // Menghitung total harga
                    DB::raw('IFNULL(asset_penyusutan.nominal_masa_manfaat, 0) AS total_penyusutan'), // Total penyusutan
                    DB::raw('
                            CASE 
                                WHEN asset_penyusutan.tanggal_penyusutan IS NOT NULL AND asset_penyusutan.nominal_masa_manfaat IS NOT NULL THEN 
                                    (asset.harga_beli * asset.kuantitas) - 
                                    (TIMESTAMPDIFF(YEAR, asset_penyusutan.tanggal_penyusutan, CURDATE()) * asset_penyusutan.nominal_masa_manfaat) 
                                ELSE 
                                    asset.harga_beli * asset.kuantitas 
                            END AS harga_buku_masa_manfaat
                        '),
                    DB::raw('
                            CASE 
                                WHEN asset_penyusutan.tanggal_penyusutan IS NOT NULL AND asset_penyusutan.nominal_nilai_tahun IS NOT NULL THEN 
                                    (asset.harga_beli * asset.kuantitas) - 
                                    (TIMESTAMPDIFF(YEAR, asset_penyusutan.tanggal_penyusutan, CURDATE()) * asset_penyusutan.nominal_nilai_tahun) 
                                ELSE 
                                    asset.harga_beli * asset.kuantitas 
                            END AS harga_buku_nilai_tahun
                        '),
                    DB::raw('IF(asset_penyusutan.masa_manfaat IS NOT NULL, 100 / asset_penyusutan.masa_manfaat, 0) AS persentase_masa_manfaat') // Persentase masa manfaat per tahun
                )
                ->paginate(5);

            $assetTerjual = Aset::leftJoin('asset_penyusutan', 'asset.id_aset', '=', 'asset_penyusutan.id_aset')
                ->leftJoin('penjualan_asset', 'asset.id_aset', '=', 'penjualan_asset.id_aset') // Join dengan tabel penjualan_asset
                ->select(
                    'asset.*',
                    'asset_penyusutan.nominal_masa_manfaat',
                    'asset_penyusutan.masa_manfaat',
                    'asset_penyusutan.tanggal_penyusutan',
                    'penjualan_asset.kuantitas AS terjual',
                    DB::raw('asset.harga_beli * asset.kuantitas AS total_harga'), // Menghitung total harga
                    DB::raw('IFNULL(asset_penyusutan.nominal_masa_manfaat, 0) AS total_penyusutan'), // Total penyusutan
                    DB::raw('
                            CASE 
                                WHEN asset_penyusutan.tanggal_penyusutan IS NOT NULL AND asset_penyusutan.nominal_masa_manfaat IS NOT NULL THEN 
                                    (asset.harga_beli * asset.kuantitas) - 
                                    (TIMESTAMPDIFF(YEAR, asset_penyusutan.tanggal_penyusutan, CURDATE()) * asset_penyusutan.nominal_masa_manfaat) 
                                ELSE 
                                    asset.harga_beli * asset.kuantitas 
                            END AS harga_buku
                        '),
                    DB::raw('IF(asset_penyusutan.masa_manfaat IS NOT NULL, 100 / asset_penyusutan.masa_manfaat, 0) AS persentase_masa_manfaat'), // Persentase masa manfaat per tahun
                    'penjualan_asset.kuantitas AS kuantitas_terjual'
                )
                ->where('asset.asset_terjual', 1)
                ->paginate(5);

            // Ambil data tambahan lainnya
            $satuan = Satuan::get();
            $kategori = Kategori::get();
            $akun = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
            $akun_penyusutan = DB::table('akun')->where('kategori_akun', '=', 'Beban')->get();
            $akun_deposit = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
            $akun_kredit = DB::table('akun')
                ->whereIn('kategori_akun', ['Pendapatan', 'Beban'])
                ->get();
            $kasdanbank = DB::table('kas_bank')->get();
            $total_nilai_asset = DB::table('asset')
                ->selectRaw('SUM(harga_beli * kuantitas) as total_nilai_asset')
                ->value('total_nilai_asset');
            $totalTersedia = Aset::sum('kuantitas');
            $totalTerjual = PenjualanAsset::sum('kuantitas');
            $pemasoks = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();


            // dd($assetTerjual);
            // Kirim data ke view
            return view('pages.asset.asset_tersedia', [
                'asset' => $asset,
                'kategori' => $kategori,
                'assetTerjual' => $assetTerjual,
                'satuan' => $satuan,
                'akun' => $akun,
                'pemasoks' => $pemasoks,
                'kasdanbank' => $kasdanbank,
                'akun_penyusutan' => $akun_penyusutan,
                'total_nilai_asset' => $total_nilai_asset,
                'akun_kredit' => $akun_kredit,
                'akun_deposit' => $akun_deposit,
                'totalTersedia' => $totalTersedia,
                'totalTerjual' => $totalTerjual,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all datas asset failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function asset_terjual()
    {
        // $title = 'Hapus Data!';
        // $text = "Apakah kamu yakin menghapus data ini ?";
        // confirmDelete($title, $text);

        try {
            // Mengambil data aset dan melakukan join dengan tabel asset_penyusutan
            $asset = Aset::leftJoin('asset_penyusutan', 'asset.id_aset', '=', 'asset_penyusutan.id_aset')
                ->select(
                    'asset.*',
                    'asset_penyusutan.nominal_masa_manfaat',
                    'asset_penyusutan.masa_manfaat',
                    'asset_penyusutan.nilai_tahun',
                    'asset_penyusutan.nominal_nilai_tahun',
                    'asset_penyusutan.tanggal_penyusutan',
                    DB::raw('asset.harga_beli * asset.kuantitas AS total_harga'), // Menghitung total harga
                    DB::raw('IFNULL(asset_penyusutan.nominal_masa_manfaat, 0) AS total_penyusutan'), // Total penyusutan
                    DB::raw('
                            CASE 
                                WHEN asset_penyusutan.tanggal_penyusutan IS NOT NULL AND asset_penyusutan.nominal_masa_manfaat IS NOT NULL THEN 
                                    (asset.harga_beli * asset.kuantitas) - 
                                    (TIMESTAMPDIFF(YEAR, asset_penyusutan.tanggal_penyusutan, CURDATE()) * asset_penyusutan.nominal_masa_manfaat) 
                                ELSE 
                                    asset.harga_beli * asset.kuantitas 
                            END AS harga_buku_masa_manfaat
                        '),
                    DB::raw('
                            CASE 
                                WHEN asset_penyusutan.tanggal_penyusutan IS NOT NULL AND asset_penyusutan.nominal_nilai_tahun IS NOT NULL THEN 
                                    (asset.harga_beli * asset.kuantitas) - 
                                    (TIMESTAMPDIFF(YEAR, asset_penyusutan.tanggal_penyusutan, CURDATE()) * asset_penyusutan.nominal_nilai_tahun) 
                                ELSE 
                                    asset.harga_beli * asset.kuantitas 
                            END AS harga_buku_nilai_tahun
                        '),
                    DB::raw('IF(asset_penyusutan.masa_manfaat IS NOT NULL, 100 / asset_penyusutan.masa_manfaat, 0) AS persentase_masa_manfaat') // Persentase masa manfaat per tahun
                )
                ->paginate(5);

            $assetTerjual = Aset::leftJoin('asset_penyusutan', 'asset.id_aset', '=', 'asset_penyusutan.id_aset')
                ->leftJoin('penjualan_asset', 'asset.id_aset', '=', 'penjualan_asset.id_aset') // Join dengan tabel penjualan_asset
                ->select(
                    'asset.*',
                    'asset_penyusutan.nominal_masa_manfaat',
                    'asset_penyusutan.masa_manfaat',
                    'asset_penyusutan.tanggal_penyusutan',
                    'penjualan_asset.kuantitas AS terjual',
                    DB::raw('asset.harga_beli * asset.kuantitas AS total_harga'), // Menghitung total harga
                    DB::raw('IFNULL(asset_penyusutan.nominal_masa_manfaat, 0) AS total_penyusutan'), // Total penyusutan
                    DB::raw('
                            CASE 
                                WHEN asset_penyusutan.tanggal_penyusutan IS NOT NULL AND asset_penyusutan.nominal_masa_manfaat IS NOT NULL THEN 
                                    (asset.harga_beli * asset.kuantitas) - 
                                    (TIMESTAMPDIFF(YEAR, asset_penyusutan.tanggal_penyusutan, CURDATE()) * asset_penyusutan.nominal_masa_manfaat) 
                                ELSE 
                                    asset.harga_beli * asset.kuantitas 
                            END AS harga_buku
                        '),
                    DB::raw('IF(asset_penyusutan.masa_manfaat IS NOT NULL, 100 / asset_penyusutan.masa_manfaat, 0) AS persentase_masa_manfaat'), // Persentase masa manfaat per tahun
                    'penjualan_asset.kuantitas AS kuantitas_terjual'
                )
                ->where('asset.asset_terjual', 1)
                ->paginate(5);

            // Ambil data tambahan lainnya
            $satuan = Satuan::get();
            $kategori = Kategori::get();
            $akun = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
            $akun_penyusutan = DB::table('akun')->where('kategori_akun', '=', 'Beban')->get();
            $akun_deposit = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
            $akun_kredit = DB::table('akun')
                ->whereIn('kategori_akun', ['Pendapatan', 'Beban'])
                ->get();
            $kasdanbank = DB::table('kas_bank')->get();
            $total_nilai_asset = DB::table('asset')
                ->selectRaw('SUM(harga_beli * kuantitas) as total_nilai_asset')
                ->value('total_nilai_asset');
            $totalTersedia = Aset::sum('kuantitas');
            $totalTerjual = PenjualanAsset::sum('kuantitas');
            $pemasoks = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();


            // dd($assetTerjual);
            // Kirim data ke view
            return view('pages.asset.asset_terjual', [
                'asset' => $asset,
                'kategori' => $kategori,
                'assetTerjual' => $assetTerjual,
                'satuan' => $satuan,
                'akun' => $akun,
                'pemasoks' => $pemasoks,
                'kasdanbank' => $kasdanbank,
                'akun_penyusutan' => $akun_penyusutan,
                'total_nilai_asset' => $total_nilai_asset,
                'akun_kredit' => $akun_kredit,
                'akun_deposit' => $akun_deposit,
                'totalTersedia' => $totalTersedia,
                'totalTerjual' => $totalTerjual,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all datas asset failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function getAssetData(Request $request, $id)
    {
        // Ambil data asset berdasarkan id yang dikirim
        $asset = DB::table('asset')
            ->where('id_aset', $id)
            ->first(); // Mengambil satu data

        // Ambil data penyusutan terkait dengan asset yang dipilih
        $assetPenyusutan = DB::table('asset_penyusutan')
            ->where('id_aset', $id)
            ->get(); // Mengambil semua data penyusutan terkait

        $datas = Aset::leftJoin(
            'asset_penyusutan',
            'asset.id_aset',
            '=',
            'asset_penyusutan.id_aset'
        )
            ->select(
                'asset.*',
                'asset_penyusutan.nominal_masa_manfaat',
                'asset_penyusutan.masa_manfaat',
                'asset_penyusutan.nilai_tahun',
                'asset_penyusutan.nominal_nilai_tahun',
                'asset_penyusutan.tanggal_penyusutan',
                DB::raw('asset.harga_beli * asset.kuantitas AS total_harga'), // Menghitung total harga
                DB::raw('IFNULL(asset_penyusutan.nominal_masa_manfaat, 0) AS total_penyusutan'), // Total penyusutan
                DB::raw('
                            CASE 
                                WHEN asset_penyusutan.tanggal_penyusutan IS NOT NULL AND asset_penyusutan.nominal_masa_manfaat IS NOT NULL THEN 
                                    (asset.harga_beli * asset.kuantitas) - 
                                    (TIMESTAMPDIFF(YEAR, asset_penyusutan.tanggal_penyusutan, CURDATE()) * asset_penyusutan.nominal_masa_manfaat) 
                                ELSE 
                                    asset.harga_beli * asset.kuantitas 
                            END AS harga_buku
                        '),
                DB::raw('IF(asset_penyusutan.masa_manfaat IS NOT NULL, 100 / asset_penyusutan.masa_manfaat, 0) AS persentase_masa_manfaat') // Persentase masa manfaat per tahun
            )
            ->where('asset.id_aset', $id)
            ->get();

        // Gabungkan data ke dalam array untuk dikembalikan ke view
        $data = [
            'asset' => $asset,
            'asset_penyusutan' => $assetPenyusutan,
            'datas' => $datas,
        ];

        // Kembalikan data dalam bentuk JSON atau ke view
        return response()->json($data);
    }

    public function getAssetDetail($id)
    {
        $asset = Aset::find($id);
        $penyusutan = Assetpenyusutan::where('id_aset', $id)->first();

        // Hitung persentase depresiasi jika ada penyusutan
        $persentaseDepresiasi = null;
        if ($asset->penyusutan == 1 && $penyusutan) {
            $tanggalPenyusutan = Carbon::parse($penyusutan->tanggal_penyusutan);
            $tahunBerjalan = Carbon::now()->diffInYears($tanggalPenyusutan);

            if ($penyusutan->masa_manfaat > 0) {
                $persentaseDepresiasiTahunan = 100 / $penyusutan->masa_manfaat;
                $persentaseDepresiasi = $tahunBerjalan * $persentaseDepresiasiTahunan;
                $persentaseDepresiasi = min($persentaseDepresiasi, 100);
            }
        }

        return response()->json([
            'asset' => $asset,
            'penyusutan' => $penyusutan,
            'persentase_depresiasi' => $persentaseDepresiasi
        ]);
    }

    public function create(){
        // Ambil data tambahan lainnya
        $satuan = Satuan::get();
        $kategori = Kategori::get();
        $akun = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
        $akun_penyusutan = DB::table('akun')->where('kategori_akun', '=', 'Beban')->get();
        $akun_deposit = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
        $akun_kredit = DB::table('akun')
            ->whereIn('kategori_akun', ['Pendapatan', 'Beban'])
            ->get();
        $kasdanbank = DB::table('kas_bank')->get();
        $total_nilai_asset = DB::table('asset')
            ->selectRaw('SUM(harga_beli * kuantitas) as total_nilai_asset')
            ->value('total_nilai_asset');
        $totalTersedia = Aset::sum('kuantitas');
        $totalTerjual = PenjualanAsset::sum('kuantitas');
        $pemasoks = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();

        return view('pages.asset.tambah_assets', [
                'kategori' => $kategori,
                'satuan' => $satuan,
                'akun' => $akun,
                'pemasoks' => $pemasoks,
                'kasdanbank' => $kasdanbank,
                'akun_penyusutan' => $akun_penyusutan,
                'total_nilai_asset' => $total_nilai_asset,
                'akun_kredit' => $akun_kredit,
                'akun_deposit' => $akun_deposit,
                'totalTersedia' => $totalTersedia,
                'totalTerjual' => $totalTerjual,
            ]);
    }

    public function store(Request $request)
    {
        // Memulai transaksi agar jika terjadi kesalahan, semua perubahan akan di-rollback
        DB::beginTransaction();

        // Inisialisasi nilai awal untuk variabel
        $persentaseMasaManfaat = null;
        $kodeReff = $request->jns_pajak === 'ppnbm' 
            ? $this->generateKodeReff('PPNBM') 
            : $this->generateKodeReff('PPN');

        try {
            // Simpan data ke tabel aset
            $aset = Aset::create([
                'pemasok'           => $request->pemasok,
                'tanggal'           => $request->tanggal,
                'nm_aset'           => $request->nm_aset,
                'kategori'          => $request->kategori,
                'satuan'            => $request->satuan,
                'pajak'             => $request->pajakButton ? 1 : 0, // Jika checked, isi dengan 1
                'kode_reff_pajak'   => $kodeReff,
                'kuantitas'         => $request->kuantitas,
                'jns_pajak'         => $request->jns_pajak,
                'persen_pajak'      => $request->persen_pajak,
                'pajak_dibayarkan'  => $request->pajak_dibayarkan,
                'kode_sku'          => $request->kode_sku,
                'harga_beli'        => $request->harga_beli,
                'akun_pembayaran'   => $request->akun_pembayaran,
                'akun_aset'         => $request->akun_aset,
                'penyusutan'        => $request->penyusutan ? 1 : 0 // Jika checked, isi dengan 1
            ]);

            // Jika penyusutan diisi dengan 1, masukkan data ke tabel asset_penyusutan
            $total_harga = $aset->harga_beli * $aset->kuantitas;
            if ($request->penyusutan) {
                // Hitung nominal berdasarkan masa manfaat atau nilai tahun
                $nominalMasaManfaat = $request->masa_manfaat ? $total_harga / $request->masa_manfaat : null;
                $nominalNilaiTahun = $request->nilai_tahun ? ($request->nilai_tahun / 100) * $total_harga : null;

                // Tentukan nilai akumulasi berdasarkan inputan
                $akumulasiAkun = null;

                if ($nominalMasaManfaat !== null) {
                    // Akumulasi berdasarkan masa manfaat
                    $akumulasiAkun = $nominalMasaManfaat * $request->masa_manfaat;
                } elseif ($nominalNilaiTahun !== null) {
                    // Akumulasi berdasarkan nilai tahun
                    $akumulasiAkun = $nominalNilaiTahun * $request->tahun;
                }

                // Simpan data penyusutan ke tabel asset_penyusutan
                AssetPenyusutan::create([
                    'id_aset'               => $aset->id_aset, // Menggunakan id_aset sebagai foreign key untuk asset_penyusutan
                    'tanggal_penyusutan'    => $request->tanggal_penyusutan,
                    'masa_manfaat'          => $request->masa_manfaat ?: null, // Jika ada input masa manfaat, isi, jika tidak null
                    'nilai_tahun'           => $request->nilai_tahun ?: null, // Jika ada input nilai tahun, isi, jika tidak null
                    'nominal_masa_manfaat'  => $nominalMasaManfaat, // Nominal dari harga_beli dibagi masa manfaat
                    'nominal_nilai_tahun'   => $nominalNilaiTahun, // Nominal dari persen dikali harga beli
                    'akun_penyusutan'       => $request->akun_penyusutan,
                    'akumulasi_akun'        => $akumulasiAkun
                ]);

                // dd($request->tanggal_penyusutan);

                // Update saldo pada tabel akun untuk akun_penyusutan
                $akun_penyusutan = Akun::where('kode_akun', $request->akun_penyusutan)->first();
                if ($akun_penyusutan) {
                    // Update saldo tanpa mass assignment
                    $akun_penyusutan->saldo += $nominalMasaManfaat ?? $nominalNilaiTahun;
                    $akun_penyusutan->save(); // Simpan perubahan saldo
                } else {
                    Alert::error('Error', 'Akun penyusutan tidak ditemukan');
                    return redirect()->back();
                }

                // Update saldo pada tabel akun untuk akumulasi_akun
                $akun_akumulasi = Akun::where('kode_akun', $request->akumulasi_akun)->first();
                if ($akun_akumulasi && $akumulasiAkun !== null) {
                    // Update saldo tanpa mass assignment
                    $akun_akumulasi->saldo += $akumulasiAkun;
                    $akun_akumulasi->save(); // Simpan perubahan saldo
                } else {
                    Alert::error('Error', 'Akun akumulasi tidak ditemukan');
                    return redirect()->back();
                }
            }

            if ($aset->pajak == 1) {
                if ($aset->jns_pajak == 'ppn11') {
                    DB::table('pajak_ppn')->insert([
                        'kode_reff'         => $aset->kode_reff_pajak,
                        'jenis_transaksi'   => 'Assets',
                        'keterangan'        => $aset->nm_aset,
                        'nilai_transaksi'   => $aset->harga_beli * $aset->kuantitas,
                        'persen_pajak'      => $aset->persen_pajak,
                        'jenis_pajak'       => 'Pajak Keluaran',
                        'saldo_pajak'       => $aset->pajak_dibayarkan,
                    ]);
                } elseif ($aset->jns_pajak == 'ppn12') {
                    DB::table('pajak_ppn')->insert([
                        'kode_reff'         => $aset->kode_reff_pajak,
                        'jenis_transaksi'   => 'Assets',
                        'keterangan'        => $aset->nm_aset,
                        'nilai_transaksi'   => $aset->harga_beli * $aset->kuantitas,
                        'persen_pajak'      => $aset->persen_pajak,
                        'jenis_pajak'       => 'Pajak Keluaran',
                        'saldo_pajak'       => $aset->pajak_dibayarkan,
                    ]);
                } elseif ($aset->jns_pajak == 'ppnbm') {
                    DB::table('pajak_ppnbm')->insert([
                        'kode_reff'             => $aset->kode_reff_pajak,
                        'deskripsi_barang'      => $aset->produk,
                        'harga_barang'          => $aset->harga_beli,
                        'tarif_ppnbm'           => $aset->persen_pajak,
                        'ppnbm_dikenakan'       => $aset->pajak_dibayarkan,
                        'jenis_pajak'           => "Pajak Masukan",
                        'tgl_transaksi'         => $aset->tanggal,
                    ]);
                }
            }
            
            // Commit transaksi jika tidak ada kesalahan
            DB::commit();
            Alert::success('Data Added!', 'Data Created Successfully');
            return redirect()->route('asset.asset_tersedia');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::error('Error!', 'Failed to create data: ' . $e->getMessage());
        }

        return redirect()->route('asset.asset_tersedia');
    }

    public function jual($id){
        // Ambil data tambahan lainnya
        $asset = DB::table('asset')->where('id_aset', '=', $id)->first();
        $satuan = Satuan::get();
        $kategori = Kategori::get();
        $akun = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
        $akun_penyusutan = DB::table('akun')->where('kategori_akun', '=', 'Beban')->get();
        $akun_deposit = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
        $akun_kredit = DB::table('akun')
            ->whereIn('kategori_akun', ['Pendapatan', 'Beban'])
            ->get();
        $kasdanbank = DB::table('kas_bank')->get();
        $total_nilai_asset = DB::table('asset')
            ->selectRaw('SUM(harga_beli * kuantitas) as total_nilai_asset')
            ->value('total_nilai_asset');
        $totalTersedia = Aset::sum('kuantitas');
        $totalTerjual = PenjualanAsset::sum('kuantitas');
        $pemasoks = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();
        $pelanggan = DB::table('kontak')->where('jenis_kontak', '=', 'pelanggan')->get();

        return view('pages.asset.jual_assets', [
                'asset' => $asset,
                'kategori' => $kategori,
                'satuan' => $satuan,
                'akun' => $akun,
                'pemasoks' => $pemasoks,
                'pelanggan' => $pelanggan,
                'kasdanbank' => $kasdanbank,
                'akun_penyusutan' => $akun_penyusutan,
                'total_nilai_asset' => $total_nilai_asset,
                'akun_kredit' => $akun_kredit,
                'akun_deposit' => $akun_deposit,
                'totalTersedia' => $totalTersedia,
                'totalTerjual' => $totalTerjual,
            ]);
    }

    public function store_penjualan(Request $request)
    {
        try {

            // Membuat data baru untuk penjualan asset
            $PenjualanAsset = PenjualanAsset::create([
                'id_aset'                       => $request->id_aset,
                'nm_pelanggan'                  => $request->nm_pelanggan,
                'nm_perusahaan'                 => $request->nm_perusahaan,
                'no_hp'                         => $request->no_hp,
                'gender'                        => $request->gender,
                'email'                         => $request->email,
                'alamat'                        => $request->alamat,
                'kuantitas'                     => $request->kuantitas,
                'tgl_penjualan'                 => $request->tgl_penjualan,
                'harga_pelepasan'               => $request->harga_pelepasan,
                'nilai_penyusutan_terakhir'     => $request->nilai_penyusutan_terakhir,
                'nilai_buku'                    => $request->nilai_buku,
                'akun_deposit'                  => $request->akun_deposit,
                'nominal_deposit'               => $request->nominal_deposit,
                'akun_keuntungan_kerugian'      => $request->akun_keuntungan_kerugian,
                'nominal_keuntungan_kerugian'   => $request->nominal_keuntungan_kerugian
            ]);

            // dd($PenjualanAsset);

            // Update field asset_terjual pada tabel asset
            // Asumsi bahwa $request->id_asset berisi ID dari asset yang terjual
            $asset = Aset::find($request->id_aset);
            if ($asset) {
                $asset->asset_terjual = 1; // Menandakan bahwa asset telah terjual
                $asset->save();
            }

            // Kurangi Kuantitas pada assets sesuai request
            $kuantitas = $request->kuantitas;
            if ($asset) {
                // Pastikan kuantitas produk mencukupi
                if ($asset->kuantitas >= $kuantitas) {
                    $asset->kuantitas -= $kuantitas; // Kurangi kuantitas produk
                    $asset->save();
                } else {
                    // Jika kuantitas tidak cukup, berikan pesan kesalahan
                    Alert::error('Error', 'Kuantitas produk tidak mencukupi!');
                    return redirect()->route('penjualan.index');
                }
            } else {
                // Jika produk tidak ditemukan, berikan pesan kesalahan
                Alert::error('Error', 'Produk tidak ditemukan!');
                return redirect()->route('penjualan.index');
            }

            if ($PenjualanAsset->pajak == 1) {
                if ($PenjualanAsset->jns_pajak == 'ppn11') {
                    DB::table('pajak_ppn')->insert([
                        'kode_reff'         => $PenjualanAsset->kode_reff_pajak,
                        'jenis_transaksi'   => 'Assets',
                        'keterangan'        => $PenjualanAsset->nm_aset,
                        'nilai_transaksi'   => $PenjualanAsset->harga_beli * $PenjualanAsset->kuantitas,
                        'persen_pajak'      => $PenjualanAsset->persen_pajak,
                        'jenis_pajak'       => 'Pajak Keluaran',
                        'saldo_pajak'       => $PenjualanAsset->pajak_dibayarkan,
                    ]);
                } elseif ($PenjualanAsset->jns_pajak == 'ppn12') {
                    DB::table('pajak_ppn')->insert([
                        'kode_reff'         => $PenjualanAsset->kode_reff_pajak,
                        'jenis_transaksi'   => 'Assets',
                        'keterangan'        => $PenjualanAsset->nm_aset,
                        'nilai_transaksi'   => $PenjualanAsset->harga_beli * $PenjualanAsset->kuantitas,
                        'persen_pajak'      => $PenjualanAsset->persen_pajak,
                        'jenis_pajak'       => 'Pajak Keluaran',
                        'saldo_pajak'       => $PenjualanAsset->pajak_dibayarkan,
                    ]);
                } elseif ($PenjualanAsset->jns_pajak == 'ppnbm') {
                    DB::table('pajak_ppnbm')->insert([
                        'kode_reff'             => $aset->kode_reff_pajak,
                        'deskripsi_barang'      => $aset->produk,
                        'harga_barang'          => $aset->harga_beli,
                        'tarif_ppnbm'           => $aset->persen_pajak,
                        'ppnbm_dikenakan'       => $aset->pajak_dibayarkan,
                        'jenis_pajak'           => "Pajak Masukan",
                        'tgl_transaksi'         => $aset->tanggal,
                    ]);
                }
            }

            // Redirect atau response jika penyimpanan berhasil
            Alert::success('Data Added!', 'Data Created Successfully');
            return redirect()->route('asset.asset_terjual');
        } catch (\Exception $e) {
            Log::error('Error saving penjualan asset: ' . $e->getMessage());
            // Menangani kesalahan jika terjadi exception
            Alert::error('Error', 'Failed Created Penjualan');
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id){
        // Ambil data tambahan lainnya
        $asset = DB::table('asset')->where('id_aset', '=', $id)->first();
        $satuan = Satuan::get();
        $kategori = Kategori::get();
        $akun = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
        $akun_penyusutan = DB::table('akun')->where('kategori_akun', '=', 'Beban')->get();
        $akun_deposit = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
        $akun_kredit = DB::table('akun')
            ->whereIn('kategori_akun', ['Pendapatan', 'Beban'])
            ->get();
        $kasdanbank = DB::table('kas_bank')->get();
        $total_nilai_asset = DB::table('asset')
            ->selectRaw('SUM(harga_beli * kuantitas) as total_nilai_asset')
            ->value('total_nilai_asset');
        $totalTersedia = Aset::sum('kuantitas');
        $totalTerjual = PenjualanAsset::sum('kuantitas');
        $pemasoks = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();

        return view('pages.asset.edit_assets', [
                'asset' => $asset,
                'kategori' => $kategori,
                'satuan' => $satuan,
                'akun' => $akun,
                'pemasoks' => $pemasoks,
                'kasdanbank' => $kasdanbank,
                'akun_penyusutan' => $akun_penyusutan,
                'total_nilai_asset' => $total_nilai_asset,
                'akun_kredit' => $akun_kredit,
                'akun_deposit' => $akun_deposit,
                'totalTersedia' => $totalTersedia,
                'totalTerjual' => $totalTerjual,
            ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $asset = Aset::findOrFail($id);

            // Update data asset
            $asset->update([
                'pemasok'           => $request->pemasok,
                'no_hp'             => $request->no_hp,
                'nm_perusahaan'     => $request->nm_perusahaan,
                'email'             => $request->email,
                'alamat'            => $request->alamat,
                'tanggal'           => $request->tanggal,
                'nm_aset'           => $request->nm_aset,
                'satuan'            => $request->satuan,
                'pajak'             => $request->pajakButton ? 1 : 0, // Jika checked, isi dengan 1
                'kuantitas'         => $request->kuantitas,
                'jns_pajak'         => $request->jns_pajak,
                'persen_pajak'      => $request->pajak,
                'pajak_dibayarkan'  => $request->pajak_dibayarkan,
                'kode_sku'          => $request->kode_sku,
                'harga_beli'        => $request->harga_beli,
                'akun_pembayaran'   => $request->akun_pembayaran,
                'akun_aset'         => $request->akun_aset,
                'penyusutan'        => $request->penyusutan ? 1 : 0 // Jika checked, isi dengan 1
            ]);

            // Jika penyusutan diisi dengan 1, update atau create data di tabel asset_penyusutan
            $total_harga = $asset->harga_beli * $asset->kuantitas;
            if ($request->penyusutan) {
                // Hitung nominal berdasarkan masa manfaat atau nilai tahun
                $nominalMasaManfaat = $request->masa_manfaat ? $total_harga / $request->masa_manfaat : null;
                $nominalNilaiTahun = $request->nilai_tahun ? ($request->nilai_tahun / 100) * $total_harga : null;

                // Tentukan nilai akumulasi berdasarkan inputan
                $akumulasiAkun = null;
                if ($nominalMasaManfaat !== null) {
                    $akumulasiAkun = $nominalMasaManfaat * $request->masa_manfaat;
                } elseif ($nominalNilaiTahun !== null) {
                    $akumulasiAkun = $nominalNilaiTahun * 1; // Akumulasi per tahun
                }

                // Cek apakah data penyusutan sudah ada untuk asset ini
                $assetPenyusutan = AssetPenyusutan::where('id_aset', $asset->id_aset)->first();

                if ($assetPenyusutan) {
                    // Update data penyusutan yang sudah ada
                    $assetPenyusutan->update([
                        'masa_manfaat' => $request->masa_manfaat ?: null,
                        'nilai_tahun' => $request->nilai_tahun ?: null,
                        'nominal_masa_manfaat' => $nominalMasaManfaat,
                        'nominal_nilai_tahun' => $nominalNilaiTahun,
                        'akun_penyusutan' => $request->akun_penyusutan,
                        'akumulasi_akun' => $akumulasiAkun
                    ]);
                } else {
                    // Create new data penyusutan
                    AssetPenyusutan::create([
                        'id_aset' => $asset->id_aset,
                        'masa_manfaat' => $request->masa_manfaat ?: null,
                        'nilai_tahun' => $request->nilai_tahun ?: null,
                        'nominal_masa_manfaat' => $nominalMasaManfaat,
                        'nominal_nilai_tahun' => $nominalNilaiTahun,
                        'akun_penyusutan' => $request->akun_penyusutan,
                        'akumulasi_akun' => $akumulasiAkun
                    ]);
                }

                // Update saldo pada tabel akun untuk akun_penyusutan
                $akun_penyusutan = Akun::where('kode_akun', $request->akun_penyusutan)->first();
                if ($akun_penyusutan) {
                    // Update saldo tanpa mass assignment
                    $akun_penyusutan->saldo += $nominalMasaManfaat ?? $nominalNilaiTahun;
                    $akun_penyusutan->save(); // Simpan perubahan saldo
                } else {
                    Alert::error('Error', 'Akun penyusutan tidak ditemukan');
                    return redirect()->back();
                }

                // Update saldo pada tabel akun untuk akumulasi_akun
                $akun_akumulasi = Akun::where('kode_akun', $request->akumulasi_akun)->first();
                if ($akun_akumulasi && $akumulasiAkun !== null) {
                    // Update saldo tanpa mass assignment
                    $akun_akumulasi->saldo += $akumulasiAkun;
                    $akun_akumulasi->save(); // Simpan perubahan saldo
                } else {
                    Alert::error('Error', 'Akun akumulasi tidak ditemukan');
                    return redirect()->back();
                }
            }

            Alert::success('Data Updated!', 'Data Updated Successfully');
            return redirect()->route('asset.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Update data gagal: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $asset = aset::findOrFail($id);
            AssetPenyusutan::where('id_aset', $id)->delete();
            $asset->delete();
            Alert::success('Data Deleted!', 'Data Deleted Successfully');
            return redirect()->route('asset.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }
}
