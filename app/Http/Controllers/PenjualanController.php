<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Akun;
use App\Models\Satuan;
use App\Models\Kasdanbank;
use App\Models\Produk;
use App\Models\ProdukPenjualan;
use App\Models\Pajakppn;
use App\Models\Pajakppnbm;
use App\Models\Arusuang;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use App\Repositories\JurnalRepository;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    protected $jurnalRepository;

    public function __construct(JurnalRepository $jurnalRepository)
    {
        $this->jurnalRepository = $jurnalRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_id = 1; // Auth::user()->id;
        $title = 'Hapus Data!';
        $text = "Apakah kamu yakin menghapus data ini ?";
        confirmDelete($title, $text);

        $from_date = $request->input('from');
        $to_date = $request->input('to');
        try {
            $penjualan = DB::table('penjualan')
                ->join('kontak', 'kontak.id_kontak', '=', 'penjualan.id_kontak')
                ->join('produk_penjualan', 'produk_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
                ->join('produk', 'produk.id_produk', '=', 'produk_penjualan.id_produk')
                ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                    return $query->whereBetween('penjualan.tanggal', [$from_date, $to_date]);
                })
                ->select(
                    'kontak.nama_kontak',
                    'penjualan.*',
                    DB::raw('SUM(produk.harga_jual * produk.kuantitas) AS total_harga')
                )
                ->groupBy(
                    'penjualan.id_penjualan'
                )
                ->where('user_id', $user_id)
                ->get();

            $akun = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
            $kasdanbank = DB::table('kas_bank')->get();
            $satuan = DB::table('satuan')->get();
            $produk = DB::table('produk')->get();
            $kategori = DB::table('kategori')->get();
            $karyawanKontak = DB::table('kontak')->where('jenis_kontak', '=', 'karyawan')->get();
            $vendorKontak = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();
            $pelanggan = DB::table('kontak')->where('jenis_kontak', '=', 'pelanggan')->get();

            $months = range(1, 12);
            $produkList = [];
            $produkListValue = [];
            $totalPemasukan = 0;

            $getDataPenjualan = DB::table('penjualan')
                ->whereYear('tanggal', date('Y'))
                ->select(
                    DB::raw('SUM(penjualan.total_pemasukan) as total_penjualan'),
                    DB::raw('MONTH(penjualan.created_at) as bulan')
                )
                ->groupBy('bulan')
                ->pluck('total_penjualan', 'bulan')
                ->toArray();

            $chart['pemasukan'] = array_map(function ($month) use ($getDataPenjualan) {
                return $getDataPenjualan[$month] ?? 0;
            }, $months);

            // Pemasukan produk = harga(harga_jual) * kuantitas - diskon(nominal_diskon)
            // Presentase produk = total pemasukan produk / total pemasukan semua produk * 100%
            // Total_Pemasukkan dari tabel penjualan
            $getDataProdukPenjualan = DB::table('produk_penjualan')
                ->join('produk', 'produk_penjualan.id_produk', '=', 'produk.id_produk')
                ->whereYear('produk_penjualan.created_at', date('Y'))
                ->select(
                    // DB::raw('SUM(penjualan.total_pemasukan) as total_pemasukan'),
                    'produk_penjualan.id_produk_penjualan as id_produk_penjualan',
                    'produk.nama_produk as nama_produk',
                    'produk.harga_jual as harga_jual',
                    'produk.kuantitas as kuantitas',
                    'produk_penjualan.nominal_diskon as nominal_diskon',
                    DB::raw('(produk.harga_jual * produk.kuantitas) - produk_penjualan.nominal_diskon as pemasukan_produk')
                )
                ->get();

            foreach ($getDataProdukPenjualan as $item) {
                $totalPemasukan += $item->pemasukan_produk;
            }

            foreach ($getDataProdukPenjualan as $item) {
                $persentase = ($item->pemasukan_produk / $totalPemasukan) * 100;
                array_push($produkList, $item->nama_produk);
                array_push($produkListValue, round($persentase, 2));
            }

            return view('pages.penjualan.index', [
                'penjualan' => $penjualan,
                'chart' => $chart,
                'produkList' => $produkList,
                'produkListValue' => $produkListValue,
                'akun' => $akun,
                'kas_bank' => $kasdanbank,
                'satuan' => $satuan,
                'produk' => $produk,
                'kategori' => $kategori,
                'karyawanKontak' => $karyawanKontak,
                'vendorKontak' => $vendorKontak,
                'pelanggan' => $pelanggan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all datas penjualan failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function create()
    {
        try {
            $penjualan = Penjualan::leftJoin('hutangpiutang', 'penjualan.id_kontak', '=', 'hutangpiutang.id_kontak')
                ->select('penjualan.*', 'hutangpiutang.nominal as nominal_piutang', 'hutangpiutang.jenis')
                ->groupBy('penjualan.id_penjualan')
                ->paginate(5);

            $akun = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
            $kasdanbank = DB::table('akun')->where('type', '=', 'Kas & Bank')->get();
            $satuan = DB::table('satuan')->get();
            $produk = DB::table('produk')->get();
            $kategori = DB::table('kategori')->get();
            $karyawanKontak = DB::table('kontak')->where('jenis_kontak', '=', 'karyawan')->get();
            $vendorKontak = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();
            $pelanggan = DB::table('kontak')->where('jenis_kontak', '=', 'pelanggan')->get();

            // $data_penjualan = response()->json($penjualan);
            // dd($akun);

            return view('pages.penjualan.create', [
                'penjualan' => $penjualan,
                'akun' => $akun,
                'kas_bank' => $kasdanbank,
                'satuan' => $satuan,
                'produk' => $produk,
                'kategori' => $kategori,
                'karyawanKontak' => $karyawanKontak,
                'vendorKontak' => $vendorKontak,
                'pelanggan' => $pelanggan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all datas penjualan failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

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

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_penjualan' => ['required', 'unique:penjualan,id_penjualan'],
            'id_kontak' => ['required'],
            'pembayaran' => ['required'],
        ]);
        db::beginTransaction();
        try {
            // Menyimpan data penjualan utama
            $data = Penjualan::create([
                'id_penjualan'      => $request->id_penjualan,
                'id_kontak'         => $request->id_kontak,
                'tanggal'           => Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d'),
                'piutang'           => $request->piutangSwitch ? 1 : 0,
                'ongkir'            => $this->parseRupiahToNumber($request->ongkir),
                'pembayaran'        => $request->pembayaran,
                'total_pajak'       => $this->parseRupiahToNumber($request->nominal_pajak),
                'total_diskon'      => $this->parseRupiahToNumber($request->diskon_output),
                'total_pemasukan'   => $this->parseRupiahToNumber($request->total_pemasukan),
                'tgl_jatuh_tempo'   => $request->tgl_jatuh_tempo,
                'user_id'           => 1, // Auth::user()->id,
            ]);

            // dd($request->produk);

            // Menyimpan detail penjualan dan pajak
            foreach ($request->produk as $key => $nm_produk) {
                // Generate kode reff untuk pajak
                $kodeReff = $request->jns_pajak[$key] === 'ppnbm'
                    ? $this->generateKodeReff('PPNBM')
                    : $this->generateKodeReff('PPN');

                // Simpan detail produk penjualan
                $harga = $this->parseRupiahToNumber($request->harga[$key]);
                $produkPenjualan = ProdukPenjualan::create([
                    'id_penjualan'      => $data->id_penjualan,
                    'id_produk'         => $request->produk[$key],
                    'harga'             => $harga,
                    'kuantitas'         => $request->kuantitas[$key],
                    'kode_reff_pajak'   => $kodeReff,
                    'jns_pajak'         => $request->jns_pajak[$key],
                    'persen_pajak'      => $request->persen_pajak[$key] ?? 0,
                    'nominal_pajak' => ((float) $harga * (float) $request->kuantitas[$key] * ((float) $request->persen_pajak[$key] / 100)) ?? 0,
                    'persen_diskon'     => $request->diskon[$key],
                    'nominal_diskon' => ((float) $harga * (float) $request->kuantitas[$key] * ((float) $request->diskon[$key] / 100)) ?? 0,
                ]);

                // Menambahkan pajak jika ada
                if ($produkPenjualan->jns_pajak) {
                    if ($produkPenjualan->jns_pajak == 'ppn11' || $produkPenjualan->jns_pajak == 'ppn12') {
                        // Insert pajak untuk PPN
                        DB::table('pajak_ppn')->insert([
                            'kode_reff'       => $produkPenjualan->kode_reff_pajak,
                            'jenis_transaksi' => 'penjualan',
                            'keterangan'      => $produkPenjualan->produk,
                            'nilai_transaksi' => (float) $produkPenjualan->harga * (float) $produkPenjualan->kuantitas,
                            'persen_pajak'    => $produkPenjualan->persen_pajak,
                            'jenis_pajak'     => 'Pajak Keluaran',
                            'saldo_pajak'     => $produkPenjualan->harga * $produkPenjualan->kuantitas * ($produkPenjualan->persen_pajak / 100),
                        ]);
                    } elseif ($produkPenjualan->jns_pajak == 'ppnbm') {
                        // Insert pajak untuk PPNBM
                        DB::table('pajak_ppnbm')->insert([
                            'kode_reff'       => $produkPenjualan->kode_reff_pajak,
                            'deskripsi_barang' => $produkPenjualan->produk,
                            'harga_barang'    => $produkPenjualan->harga,
                            'tarif_ppnbm'     => $produkPenjualan->persen_pajak,
                            'ppnbm_dikenakan' => $produkPenjualan->harga * $produkPenjualan->kuantitas * ($produkPenjualan->persen_pajak / 100),
                            'jenis_pajak'     => 'Pajak Masukan',
                            'tgl_transaksi'   => $data->tanggal,
                        ]);
                    }
                }
            }

            // Mengurangi kuantitas produk di tabel produk
            foreach ($request->produk as $key => $nm_produk) {
                $produk = Produk::where('id_produk', $request->produk[$key])->first();
                if ($produk->kuantitas >= $request->kuantitas[$key]) {
                    $produk->kuantitas -= $request->kuantitas[$key];
                    $produk->save();
                } else {
                    throw new \Exception('Kuantitas produk tidak mencukupi!');
                }
            }

            // Mengupdate saldo akun kas & bank
            $akun = Akun::where('type', 'Kas & Bank')->where('kode_akun', $request->pembayaran)->first();
            if ($akun) {
                $akun->saldo += $this->parseRupiahToNumber($request->total_pemasukan);
                $akun->save();
            } else {
                throw new \Exception('Akun pembayaran tidak ditemukan!');
            }

            // Menambahkan data piutang jika ada
            $piutang = 0;
            if ($data->piutang == 1) {
                $piutang = $request->piutang;
                DB::table('hutangpiutang')->insert([
                    'id_kontak'     => $data->id_kontak,
                    'kategori'      => 'Penjualan', //$data->kategori_produk,
                    'jenis'         => 'piutang',
                    'nominal'       => $piutang,
                    'status'        => 'Belum Lunas',
                    'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo,
                ]);
            }

            // Insert Jurnal
            $this->jurnalRepository->storePenjualan($data, $piutang);

            DB::commit();
            Alert::success('Data Added!', 'Tambah Data Penjualan Berhasil');
            return redirect()->route('penjualan.index');
        } catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            Alert::error('Error', 'Tambah Data Penjualan Gagal: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function detail($id)
    {
        try {
            $detailPenjualan = DB::table('produk_penjualan')
                ->join('penjualan', 'penjualan.id_penjualan', '=', 'produk_penjualan.id_penjualan')
                ->join('kontak', 'kontak.id_kontak', '=', 'penjualan.id_kontak')
                ->select('produk_penjualan.*', 'penjualan.*', 'kontak.nama_kontak')
                ->where('produk_penjualan.id_penjualan', $id)
                ->get();

            return response()->json([
                'status' => 'success',
                'detailPenjualan' => $detailPenjualan,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil detail penjualan',
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function parseRupiahToNumber($rupiah)
    {
        // Hapus karakter selain angka dan koma/titik, serta awalan "Rp" jika ada
        $cleaned = str_replace(['Rp', '.', ' '], '', $rupiah); // Hapus "Rp", titik pemisah ribuan, dan spasi
        $cleaned = str_replace(',', '.', $cleaned); // Ganti koma menjadi titik untuk memastikan desimal benar

        return floatval($cleaned) ?: 0;
    }

    public function edit($id)
    {
        try {
            $penjualan = Penjualan::with('produkPenjualan.produk')
                ->where('id_penjualan', $id)
                ->first();

            $penjualan->tanggal = Carbon::parse($penjualan->tanggal)->format('d-m-Y');
            $akun = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
            $kasdanbank = DB::table('akun')->where('type', '=', 'Kas & Bank')->get();
            $satuan = DB::table('satuan')->get();
            $produk = DB::table('produk')->get();
            $kategori = DB::table('kategori')->get();
            $karyawanKontak = DB::table('kontak')->where('jenis_kontak', '=', 'karyawan')->get();
            $vendorKontak = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();
            $pelanggan = DB::table('kontak')->where('jenis_kontak', '=', 'pelanggan')->get();

            // $data_penjualan = response()->json($penjualan);
            // dd($akun);

            return view('pages.penjualan.edit', [
                'penjualan' => $penjualan,
                'akun' => $akun,
                'kas_bank' => $kasdanbank,
                'satuan' => $satuan,
                'produk' => $produk,
                'kategori' => $kategori,
                'karyawanKontak' => $karyawanKontak,
                'vendorKontak' => $vendorKontak,
                'pelanggan' => $pelanggan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all datas penjualan failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function checkProdukPenjualanExists($id_penjualan, $id_produk)
    {
        return ProdukPenjualan::where('id_penjualan', $id_penjualan)
            ->where('id_produk', $id_produk)
            ->exists();
    }

    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $penjualan = Penjualan::findOrFail($id);
            // Update data penjualan
            $penjualan->update([
                'id_penjualan'      => $request->id_penjualan,
                'id_kontak'         => $request->id_kontak,
                'tanggal'           => Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d'),
                'piutang'           => $request->piutangSwitch ? 1 : 0,
                'ongkir'            => $this->parseRupiahToNumber($request->ongkir),
                'pembayaran'        => $request->pembayaran,
                'total_pajak'       => $this->parseRupiahToNumber($request->nominal_pajak),
                'total_diskon'      => $this->parseRupiahToNumber($request->diskon_output),
                'total_pemasukan'   => $this->parseRupiahToNumber($request->total_pemasukan),
                'tgl_jatuh_tempo'   => $request->tgl_jatuh_tempo,
            ]);

            // Ambil semua ID produk dari UI
            $id_produk_ui = array_map(function ($produk) {
                return explode("-", $produk)[0]; // Ambil ID produk sebelum "-"
            }, $request->produk);

            // Ambil semua ID produk yang sudah ada di database untuk transaksi ini
            $id_produk_db = DB::table('produk_penjualan')
                ->where('id_penjualan', $penjualan->id_penjualan)
                ->pluck('id_produk')
                ->toArray();

            // Cari ID produk yang harus dihapus (ada di DB tapi tidak ada di UI)
            $id_produk_dihapus = array_diff($id_produk_db, $id_produk_ui);

            // Hapus produk yang tidak ada di UI dari database
            if (!empty($id_produk_dihapus)) {
                $kode_ref = DB::table('produk_penjualan')
                    ->where('id_penjualan', $penjualan->id_penjualan)
                    ->whereIn('id_produk', $id_produk_dihapus)
                    ->get();

                // dd($kode_ref->toArray());

                foreach ($kode_ref as $key => $value) {
                    // Substring di jns_pajak
                    $parts = preg_split('/(?<=\D)(?=\d)/', $value->jns_pajak);
                    $pajak = $parts[0]; // "ppn"
                    // dd($pajak);

                    if ($pajak === 'ppn') {
                        DB::table('pajak_ppn')->where('kode_reff', 'LIKE', '%' . $value->kode_reff_pajak . '%')
                            ->delete();
                    } else if ($pajak === 'ppnbm') {
                        DB::table('pajak_ppnbm')->where('kode_reff', 'LIKE', '%' . $value->kode_reff_pajak . '%')
                            ->delete();
                    }
                }

                DB::table('produk_penjualan')
                    ->where('id_penjualan', $penjualan->id_penjualan)
                    ->whereIn('id_produk', $id_produk_dihapus)
                    ->delete();
            }

            // Menyimpan detail penjualan dan pajak
            foreach ($request->produk as $key => $nm_produk) {
                // dd($request->kuantitas[$key]);
                $id_produk = explode("-", $request->produk[$key])[0];
                $nm_produk = explode("-", $request->produk[$key])[1];

                // Generate kode reff untuk pajak
                $kodeReff = $request->jns_pajak[$key] === 'ppnbm'
                    ? $this->generateKodeReff('PPNBM')
                    : $this->generateKodeReff('PPN');

                if ($this->checkProdukPenjualanExists($penjualan->id_penjualan, $id_produk)) {
                    // Simpan detail produk penjualan
                    DB::table('produk_penjualan')->where('id_penjualan', $penjualan->id_penjualan)
                        ->where('id_produk', $id_produk)
                        ->update([
                            'id_produk'         => $id_produk,
                            'id_penjualan'      => $penjualan->id_penjualan,
                            // 'produk'            => $request->produk[$key],
                            // 'kategori_produk'   => $kategori_produk->kategori,
                            // 'satuan'            => $request->satuan[$key],
                            'harga'             => $this->parseRupiahToNumber($request->harga[$key]),
                            'kuantitas'         => $request->kuantitas[$key],
                            'kode_reff_pajak'   => $kodeReff,
                            'jns_pajak'         => $request->jns_pajak[$key],
                            'persen_pajak'      => (int)$request->persen_pajak[$key] ?? 0,
                            'nominal_pajak'     => $this->parseRupiahToNumber($request->harga[$key]) * $request->kuantitas[$key] * ((int)$request->persen_pajak[$key] / 100) ?? 0,
                            'persen_diskon'     => $request->diskon[$key],
                            'nominal_diskon'    => $this->parseRupiahToNumber($request->harga[$key]) * $request->kuantitas[$key] * ($request->diskon[$key] / 100) ?? 0,
                        ]);
                } else {
                    // Simpan detail produk penjualan
                    ProdukPenjualan::create([
                        'id_penjualan'      => $penjualan->id_penjualan,
                        'id_produk'         => $request->produk[$key],
                        'harga'             => $this->parseRupiahToNumber($request->harga[$key]),
                        'kuantitas'         => $request->kuantitas[$key],
                        'kode_reff_pajak'   => $kodeReff,
                        'jns_pajak'         => $request->jns_pajak[$key],
                        'persen_pajak'      => (int)$request->persen_pajak[$key] ?? 0,
                        'nominal_pajak'     => $this->parseRupiahToNumber($request->harga[$key]) * $request->kuantitas[$key] * ((int)$request->persen_pajak[$key] / 100) ?? 0,
                        'persen_diskon'     => $request->diskon[$key],
                        'nominal_diskon'    => $this->parseRupiahToNumber($request->harga[$key]) * $request->kuantitas[$key] * ($request->diskon[$key] / 100) ?? 0,
                    ]);
                }

                // Menambahkan pajak jika ada
                if ($request->jns_pajak[$key]) {
                    if ($request->jns_pajak[$key] == 'ppn11' || $request->jns_pajak[$key] == 'ppn12') {
                        // Insert pajak untuk PPN
                        DB::table('pajak_ppn')->insert([
                            'kode_reff'       => $kodeReff,
                            'jenis_transaksi' => 'penjualan',
                            'keterangan'      => $nm_produk,
                            'nilai_transaksi' => $this->parseRupiahToNumber($request->harga[$key]) * $request->kuantitas[$key],
                            'persen_pajak'    => (int)$request->persen_pajak[$key],
                            'jenis_pajak'     => 'Pajak Keluaran',
                            'saldo_pajak'     => $this->parseRupiahToNumber($request->harga[$key]) * $request->kuantitas[$key] * ((int)$request->persen_pajak[$key] / 100),
                        ]);
                    } else if ($request->jns_pajak[$key] == 'ppnbm') {
                        // Insert pajak untuk PPNBM
                        DB::table('pajak_ppnbm')->insert([
                            'kode_reff'       => $kodeReff,
                            'deskripsi_barang' => $nm_produk,
                            'harga_barang'    => $this->parseRupiahToNumber($request->harga[$key]),
                            'tarif_ppnbm'     => (int)$request->persen_pajak[$key],
                            'ppnbm_dikenakan' => $this->parseRupiahToNumber($request->harga[$key]) * $request->kuantitas[$key] * ((int)$request->persen_pajak[$key] / 100),
                            'jenis_pajak'     => 'Pajak Masukan',
                            'tgl_transaksi'   => $request->tanggal,
                        ]);
                    }
                }
            }

            // update saldo akun kas & bank
            $akun = Akun::where('type', 'Kas & Bank')->where('kode_akun', $request->pembayaran)->first();
            if ($akun) {
                // Contoh: Menambah nilai ke saldo yang ada
                $akun->saldo += $this->parseRupiahToNumber($request->total_pemasukan);
                $akun->save();
            }

            // Mengupdate kuantitas produk
            $produk = Produk::where('nama_produk', 'LIKE', '%' . $penjualan->produk . '%')->first();
            if ($produk) {
                foreach ($request->kuantitas as $kuantitas) {
                    // Pastikan kuantitas produk mencukupi
                    if ($produk->kuantitas >= $kuantitas) {
                        // Jika kuantitas berubah, update kuantitas produk
                        $produk->kuantitas += $penjualan->kuantitas - $kuantitas; // Sesuaikan kuantitas
                        $produk->save();
                    } else {
                        // Jika kuantitas tidak cukup, berikan pesan kesalahan
                        Alert::error('Error', 'Kuantitas produk tidak mencukupi!');
                        return redirect()->route('penjualan.index');
                    }
                }
            } else {
                // Jika produk tidak ditemukan, berikan pesan kesalahan
                Alert::error('Error', 'Produk tidak ditemukan!');
                return redirect()->route('penjualan.index');
            }

            $piutang = 0;
            if ($penjualan->piutang == 1) {
                $piutang = $request->piutang;
                // Masukkan data ke tabel hutangpiutang
                DB::table('hutangpiutang')->insert([
                    'id_kontak'     => $penjualan->id_kontak, // Contoh field, sesuaikan dengan struktur tabel Anda
                    'kategori'      => 'Penjualan',
                    'jenis'         => 'piutang',
                    'nominal'       => $piutang,
                    'status'        => 'Belum Lunas',
                    'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo,
                ]);
            }

            // Insert Jurnal
            $this->jurnalRepository->storePenjualan($penjualan, $piutang);

            Alert::success('Data Updated!', 'Data Updated Successfully');
            return redirect()->route('penjualan.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Update data gagal: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy(string $id_penjualan)
    {
        try {
            $penjualan = Penjualan::findOrFail($id_penjualan);

            Pajakppn::where('kode_reff', $penjualan->kode_reff_pajak)->delete();
            Pajakppnbm::where('kode_reff', $penjualan->kode_reff_pajak)->delete();

            // Delete Jurnal
            $prefix = Penjualan::CODE_JURNAL;
            $jurnal = Jurnal::where('code', $prefix)->where('no_reff', $penjualan->id_penjualan)->first();
            if ($jurnal) {
                $this->jurnalRepository->delete($jurnal->id_jurnal);
            }

            $penjualan->delete();

            Alert::success('Data Deleted!', 'Data Deleted Successfully');
            return redirect()->route('penjualan.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error : ' . $e->getMessage());
        }
    }
}
