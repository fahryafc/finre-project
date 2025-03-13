<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\Akun;
use App\Models\Pengeluaran;
use App\Models\Kasdanbank;
use App\Models\Arusuang;
use App\Models\Kontak;
use App\Models\Pajak;
use App\Models\Pajak_ppn;
use App\Models\Pajak_ppnbm;
use App\Models\Pajak_pph;
use App\Models\Jurnal;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Exception;
use App\Repositories\JurnalRepository;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
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
            // $pengeluaran = DB::table('pengeluaran')->get();
            $pengeluaran = Pengeluaran::join('kontak', 'pengeluaran.id_kontak', '=', 'kontak.id_kontak')
            ->join('akun', 'akun.kode_akun', '=', 'pengeluaran.akun_pembayaran', 'left')
                ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                    return $query->whereBetween('tanggal', [$from_date, $to_date]);
                })
                ->select('pengeluaran.*', 'kontak.nama_kontak','akun.nama_akun')
                ->paginate(5);
            $akun = DB::table('akun')->get();
            $satuan = DB::table('satuan')->get();
            $produk = DB::table('produk')->get();
            $kasdanbank = DB::table('kas_bank')->get();
            $kategori = DB::table('kategori')->get();
            $karyawanKontak = DB::table('kontak')->where('jenis_kontak', '=', 'karyawan')->get();
            $vendorKontak = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();

            $months = range(1, 12); // Buat array bulan 1-12
            $categoryList = [];
            $categoryValue = [];
            $totalBiayaKategori = 0;

            // KATEGORI PEMBAYARAN
            // Presentase pengeluaran = Total pemasukan kategori / total pemasukan semua kategori * 100%
            // Kolom biaya dari tabel pengeluaran
            $getKategoriPembayaran = DB::table('pengeluaran')
                ->whereYear('tanggal', date('Y'))
                ->select(
                    DB::raw('SUM(pengeluaran.biaya) as total'),
                    'pengeluaran.kategori'
                )
                ->groupBy('kategori')
                ->get(); // Ambil sebagai key-value (bulan => total_hutang)

            foreach ($getKategoriPembayaran as $key => $value) {
                $totalBiayaKategori += $value->total;
            }

            foreach ($getKategoriPembayaran as $key => $value) {
                array_push($categoryList, $value->kategori);
                // Cek apakah totalBiayaKategori tidak nol
                if ($totalBiayaKategori != 0) {
                    $count = ($value->total / $totalBiayaKategori) * 100;
                } else {
                    $count = 0;
                }
                array_push($categoryValue, round($count, 2));
            }

            // OVERVIEW PENGELUARAN
            // Total dari table pengeluaran, kolom biaya
            $pengeluaranChartGet = DB::table('pengeluaran')
                ->whereYear('created_at', date('Y'))
                ->select(
                    DB::raw('SUM(pengeluaran.biaya) as total'),
                    DB::raw('MONTH(created_at) as bulan')
                )
                ->groupBy('bulan')
                ->pluck('total', 'bulan') // Ambil sebagai key-value (bulan => total_hutang)
                ->toArray();

            // Mengisi bulan yang kosong dengan 0
            $pengeluaranChart = array_map(function ($month) use ($pengeluaranChartGet) {
                return $pengeluaranChartGet[$month] ?? 0;
            }, $months);

            return view('pages.pengeluaran.index', [
                'pengeluaran' => $pengeluaran,
                'pengeluaranChart' => $pengeluaranChart,
                'categoryList' => $categoryList,
                'categoryValue' => $categoryValue,
                'akun' => $akun,
                'satuan' => $satuan,
                'produk' => $produk,
                'kas_bank' => $kasdanbank,
                'kategori' => $kategori,
                'karyawanKontak' => $karyawanKontak,
                'vendorKontak' => $vendorKontak
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all datas pengeluaran failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function pengeluaran()
    {
        try {
            return view('pages.pengeluaran.pengeluaran');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all datas pengeluaran failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function create()
    {
        try {
            // $pengeluaran = DB::table('pengeluaran')->get();
            $pengeluaran = Pengeluaran::join('kontak', 'pengeluaran.id_kontak', '=', 'kontak.id_kontak')
                ->select('pengeluaran.*', 'kontak.nama_kontak')
                ->paginate(5);
            $akun = DB::table('akun')->get();
            $satuan = DB::table('satuan')->get();
            $produk = DB::table('produk')->get();
            $kasdanbank = DB::table('akun')->where('type', '=', 'Kas & Bank')->get();
            $kategori = DB::table('kategori')->get();
            $karyawanKontak = DB::table('kontak')->where('jenis_kontak', '=', 'karyawan')->get();
            $vendorKontak = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();
            $akun_pemasukan = DB::table('akun')->whereIn('id_kategori_akun', ['1', '2', '5'])->get();

            // dd($pengeluaran);

            return view('pages.pengeluaran.create', [
                'pengeluaran' => $pengeluaran,
                'akun' => $akun,
                'satuan' => $satuan,
                'produk' => $produk,
                'kas_bank' => $kasdanbank,
                'kategori' => $kategori,
                'karyawanKontak' => $karyawanKontak,
                'vendorKontak' => $vendorKontak,
                'akun_pemasukan' => $akun_pemasukan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all datas pengeluaran failed',
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
            DB::table('pajak_ppn')->where('kode_reff', $kodeReff)->exists() ||
            DB::table('pajak_pph')->where('kode_reff', $kodeReff)->exists()
        );

        return $kodeReff;
    }

    public function store(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            // generate kode reff untuk pajak
        if ($request->jns_pajak === 'ppnbm') {
            $kodeReff = Helper::generateKodeReff('PPNBM');
        } elseif ($request->jns_pajak === 'ppn') {
            $kodeReff = Helper::generateKodeReff('PPN');
        } elseif ($request->jns_pajak === 'pph') {
            $kodeReff = Helper::generateKodeReff('PPH');
        } else {
            $kodeReff = null;
        }

        // Simpan data pengeluaran ke dalam tabel pengeluaran
        $data_pengeluaran = Pengeluaran::create([
            'nm_pengeluaran'       => $request->nm_pengeluaran,
            'jenis_pengeluaran'    => $request->jenis_pengeluaran,
            'id_kontak'            => $request->id_kontak,
            'tanggal'              => Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d'),
            'kategori'             => $request->kategori,
            'biaya'                => parseRupiahToNumber($request->biaya),
            'pajak'                => $request->pajakButton ? 1 : 0, // Jika checked, isi dengan 1
            'kode_reff_pajak'      => $kodeReff,
            'jns_pajak'            => $request->jns_pajak,
            'pajak_persen'         => $request->pajak_persen,
            'pajak_dibayarkan'     => parseRupiahToNumber($request->pajak_dibayarkan),
            'hutang'               => $request->hutangButton ? 1 : 0, // Jika checked, isi dengan 1
            'nominal_hutang'       => parseRupiahToNumber($request->nominal_hutang),
            'akun_pembayaran'      => $request->akun_pembayaran,
            'akun_pemasukan'       => $request->akun_pemasukan,
            'tgl_jatuh_tempo'      => $request->hutangButton ? Carbon::createFromFormat('d-m-Y', $request->tgl_jatuh_tempo)->format('Y-m-d') : null, // Set to null if hutang is 0
            'user_id'              => 1, // Auth::user()->id,
        ]);

        // dd($data_pengeluaran->biaya);

        // Menambahkan data pajak jika ada
        $findKaryawan = Kontak::where('id_kontak', $data_pengeluaran->id_kontak)->first();
        if ($data_pengeluaran->pajak == 1) {
            if ($data_pengeluaran->jns_pajak == 'ppn') {
                DB::table('pajak_ppn')->insert([
                    'kode_reff'         => $data_pengeluaran->kode_reff_pajak,
                    'jenis_transaksi'   => 'penjualan',
                    'keterangan'        => $data_pengeluaran->produk,
                    'nilai_transaksi'   => parseRupiahToNumber($data_pengeluaran->harga) * $data_pengeluaran->kuantitas,
                    'persen_pajak'      => $data_pengeluaran->persen_pajak,
                    'jenis_pajak'       => 'Pajak Keluaran',
                    'saldo_pajak'       => parseRupiahToNumber($data_pengeluaran->total_pemasukan) * ($data_pengeluaran->persen_pajak / 100),
                ]);
            } elseif ($data_pengeluaran->jns_pajak == 'ppnbm') {
                DB::table('pajak_ppnbm')->insert([
                    'kode_reff'             => $data_pengeluaran->kode_reff_pajak,
                    'deskripsi_barang'      => $data_pengeluaran->produk,
                    'harga_barang'          => parseRupiahToNumber($data_pengeluaran->harga),
                    'tarif_ppnbm'           => $data_pengeluaran->persen_pajak,
                    'ppnbm_dikenakan'       => parseRupiahToNumber($data_pengeluaran->total_pemasukan) * ($data_pengeluaran->persen_pajak / 100),
                    'jenis_pajak'           => "Pajak Masukan",
                    'tgl_transaksi'         => $data_pengeluaran->tanggal,
                ]);
            } elseif ($data_pengeluaran->jns_pajak == 'pph') {
                DB::table('pajak_pph')->insert([
                    'id_pengeluaran'    => $data_pengeluaran->id_pengeluaran,
                    'kode_reff'         => $data_pengeluaran->kode_reff_pajak,
                    'nm_karyawan'       => $findKaryawan->nama_kontak,
                    'gaji_karyawan'     => $data_pengeluaran->biaya,
                    'pph_terutang'      => $data_pengeluaran->pajak_dibayarkan,
                    'bersih_diterima'   => parseRupiahToNumber($data_pengeluaran->biaya) - parseRupiahToNumber($data_pengeluaran->pajak_dibayarkan),
                ]);
            }
        }

        // Cek jika data pengeluaran berhasil disimpan
        if ($data_pengeluaran) {
            // Cari akun kas_bank berdasarkan kode_akun (akun_pembayaran)
            $kas_bank = Akun::where('type', 'Kas & Bank')->where('kode_akun', $data_pengeluaran->akun_pembayaran)->first();

            // Jika akun ditemukan, tambahkan data ke tabel arus_uang
            if ($kas_bank) {
                $arusuang = Arusuang::create([
                    'kode_akun'        => $data_pengeluaran->akun_pembayaran, // Diambil dari akun_pembayaran
                    'nominal'          => parseRupiahToNumber($data_pengeluaran->biaya), // Diambil dari biaya pengeluaran
                    'type'             => 'uang keluar', // Default type
                    'tanggal'          => $data_pengeluaran->tanggal, // Diambil dari tanggal pengeluaran
                ]);
            } else {
                // Jika akun tidak ditemukan, tampilkan pesan error (opsional)
                Alert::error('Error', 'Akun pembayaran tidak ditemukan di Kas & Bank');
                return redirect()->back();
            }
        }

        // Added data hutang jika ada
        if ($data_pengeluaran->hutang == 1) {
            // Cek apakah sudah ada row dengan id_kontak dan kategori yang sama di hutangpiutang
            $hutangPiutang = DB::table('hutangpiutang')
                ->where('id_kontak', $data_pengeluaran->id_kontak)
                ->where('kategori', $data_pengeluaran->kategori)
                ->where('tgl_jatuh_tempo', $data_pengeluaran->tgl_jatuh_tempo)
                ->where('jenis', 'hutang')
                ->first();

            if ($hutangPiutang) {
                // Jika ditemukan, tambahkan nominal ke saldo existing
                DB::table('hutangpiutang')
                    ->where('id_hutangpiutang', $hutangPiutang->id_hutangpiutang)
                    ->update([
                        'nominal' => parseRupiahToNumber($hutangPiutang->nominal) + parseRupiahToNumber($data_pengeluaran->nominal_hutang),
                    ]);
            } else {
                // Jika tidak ditemukan, insert row baru
                DB::table('hutangpiutang')->insert([
                    'id_kontak'          => $data_pengeluaran->id_kontak,
                    'kategori'           => $data_pengeluaran->kategori,
                    'jenis'              => 'hutang',
                    'nominal'            => parseRupiahToNumber($data_pengeluaran->nominal_hutang),
                    'status'             => 'Belum Lunas',
                    'tgl_jatuh_tempo'    => $data_pengeluaran->tgl_jatuh_tempo,
                ]);
            }
        }

        // Insert Jurnal
        $this->jurnalRepository->storePengeluaran($data_pengeluaran);

        // Tampilkan notifikasi sukses
        DB::commit();
        Alert::success('Data Added!', 'Data Created Successfully');
        return redirect()->route('pengeluaran.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Tambah Data Pengeluaran Gagal: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            // $pengeluaran = DB::table('pengeluaran')->get();
            $pengeluaran = Pengeluaran::join('kontak', 'pengeluaran.id_kontak', '=', 'kontak.id_kontak')
                ->select('pengeluaran.*', 'kontak.nama_kontak')
                ->where('pengeluaran.id_pengeluaran', $id)
                ->first();

            $akun = DB::table('akun')->get();
            $satuan = DB::table('satuan')->get();
            $produk = DB::table('produk')->get();
            $kasdanbank = DB::table('akun')->where('type', '=', 'Kas & Bank')->get();
            $kategori = DB::table('kategori')->get();
            $karyawanKontak = DB::table('kontak')->where('jenis_kontak', '=', 'karyawan')->get();
            $vendorKontak = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();

            // dd($pengeluaran);

            return view('pages.pengeluaran.edit', [
                'pengeluaran' => $pengeluaran,
                'akun' => $akun,
                'satuan' => $satuan,
                'produk' => $produk,
                'kas_bank' => $kasdanbank,
                'kategori' => $kategori,
                'karyawanKontak' => $karyawanKontak,
                'vendorKontak' => $vendorKontak
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all datas pengeluaran failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, string $id_pengeluaran): RedirectResponse
    {
        try {
            // Temukan data pengeluaran berdasarkan ID
            $findPengeluaran = Pengeluaran::find($id_pengeluaran);
            if (!$findPengeluaran) {
                return redirect()->back()->with('error', 'Data pengeluaran tidak ditemukan.');
            }

            // Ambil data lama untuk perbandingan
            $old_data = $findPengeluaran->toArray();

            // Update data pengeluaran
            $findPengeluaran->update([
                'nm_pengeluaran'       => $request->nm_pengeluaran,
                'jenis_pengeluaran'    => $request->jenis_pengeluaran,
                'id_kontak'            => $request->id_kontak,
                'tanggal'              => Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d'),
                'kategori'             => $request->kategori,
                'biaya'                => parseRupiahToNumber($request->biaya),
                'pajak'                => $request->pajakButton ? 1 : 0,
                'jns_pajak'            => $request->jns_pajak,
                'pajak_persen'         => $request->pajak_persen,
                'pajak_dibayarkan'     => parseRupiahToNumber($request->pajak_dibayarkan),
                'hutang'               => $request->hutangButton ? 1 : 0,
                'nominal_hutang'       => parseRupiahToNumber($request->nominal_hutang),
                'akun_pembayaran'      => $request->akun_pembayaran,
                'akun_pemasukan'       => $request->akun_pemasukan,
                'tgl_jatuh_tempo'      => $request->hutangButton ? Carbon::createFromFormat('d-m-Y', $request->tgl_jatuh_tempo)->format('Y-m-d') : null,
            ]);

            // Perbarui data pajak jika ada
            if ($findPengeluaran->pajakButton) {
                if ($findPengeluaran->jns_pajak === 'ppn') {
                    if ($findPengeluaran->pajak == 1) {
                        // Update data jika pajak bernilai 1
                        DB::table('pajak_ppn')->where('id_pengeluaran', $id_pengeluaran)->update([
                            'kode_reff'         => $findPengeluaran->kode_reff_pajak,
                            'jenis_transaksi'   => 'pengeluaran',
                            'keterangan'        => $findPengeluaran->nm_pengeluaran,
                            'nilai_transaksi'   => parseRupiahToNumber($findPengeluaran->biaya),
                            'persen_pajak'      => $findPengeluaran->pajak_persen,
                            'jenis_pajak'       => 'Pajak Masukan',
                            'saldo_pajak'       => parseRupiahToNumber($findPengeluaran->biaya) * ($findPengeluaran->pajak_persen / 100),
                        ]);
                    } else {
                        // Insert data baru jika pajak bernilai 0/null/kosong
                        DB::table('pajak_ppn')->insert([
                            'id_pengeluaran'    => $id_pengeluaran,
                            'kode_reff'         => $findPengeluaran->kode_reff_pajak,
                            'jenis_transaksi'   => 'pengeluaran',
                            'keterangan'        => $findPengeluaran->nm_pengeluaran,
                            'nilai_transaksi'   => parseRupiahToNumber($findPengeluaran->biaya),
                            'persen_pajak'      => $findPengeluaran->pajak_persen,
                            'jenis_pajak'       => 'Pajak Masukan',
                            'saldo_pajak'       => parseRupiahToNumber($findPengeluaran->biaya) * ($findPengeluaran->pajak_persen / 100),
                        ]);
                    }
                } elseif ($findPengeluaran->jns_pajak === 'ppnbm') {
                    if ($findPengeluaran->pajak == 1) {
                        // Update data jika pajak bernilai 1
                        DB::table('pajak_ppnbm')->where('id_pengeluaran', $id_pengeluaran)->update([
                            'deskripsi_barang'  => $findPengeluaran->nm_pengeluaran,
                            'harga_barang'      => parseRupiahToNumber($findPengeluaran->biaya),
                            'tarif_ppnbm'       => $findPengeluaran->pajak_persen,
                            'ppnbm_dikenakan'   => parseRupiahToNumber($findPengeluaran->biaya) * ($findPengeluaran->pajak_persen / 100),
                            'jenis_pajak'       => 'Pajak Masukan',
                            'tgl_transaksi'     => $findPengeluaran->tanggal,
                        ]);
                    } else {
                        // Insert data baru jika pajak bernilai 0/null/kosong
                        DB::table('pajak_ppnbm')->insert([
                            'id_pengeluaran'    => $id_pengeluaran,
                            'deskripsi_barang'  => $findPengeluaran->nm_pengeluaran,
                            'harga_barang'      => parseRupiahToNumber($findPengeluaran->biaya),
                            'tarif_ppnbm'       => $findPengeluaran->pajak_persen,
                            'ppnbm_dikenakan'   => parseRupiahToNumber($findPengeluaran->biaya) * ($findPengeluaran->pajak_persen / 100),
                            'jenis_pajak'       => 'Pajak Masukan',
                            'tgl_transaksi'     => $findPengeluaran->tanggal,
                        ]);
                    }
                } elseif ($findPengeluaran->jns_pajak === 'pph') {
                    $findKaryawan = Kontak::where('id_kontak', $findPengeluaran->id_kontak)->first();
                    if ($findPengeluaran->pajak == 1) {
                        // Update data jika pajak bernilai 1
                        DB::table('pajak_pph')->where('kode_reff', $findPengeluaran->kode_reff)->update([
                            'id_pengeluaran'    => $findPengeluaran->id_pengeluaran,
                            'kode_reff'         => $findPengeluaran->kode_reff,
                            'nm_karyawan'       => $findKaryawan->nama_kontak,
                            'gaji_karyawan'     => parseRupiahToNumber($findPengeluaran->biaya),
                            'pph_terutang'      => parseRupiahToNumber($findPengeluaran->pajak_dibayarkan),
                            'bersih_diterima'   => parseRupiahToNumber($findPengeluaran->biaya) - parseRupiahToNumber($findPengeluaran->pajak_dibayarkan),
                        ]);
                    } else {
                        // Insert data baru jika pajak bernilai 0/null/kosong
                        DB::table('pajak_pph')->insert([
                            'id_pengeluaran'    => $findPengeluaran->id_pengeluaran,
                            'kode_reff'         => $findPengeluaran->kode_reff,
                            'nm_karyawan'       => $findKaryawan->nama_kontak,
                            'gaji_karyawan'     => parseRupiahToNumber($findPengeluaran->biaya),
                            'pph_terutang'      => parseRupiahToNumber($findPengeluaran->pajak_dibayarkan),
                            'bersih_diterima'   => parseRupiahToNumber($findPengeluaran->biaya) - parseRupiahToNumber($findPengeluaran->pajak_dibayarkan),
                        ]);
                    }
                }
            }

            // Periksa perubahan pada akun pembayaran
            if ($old_data['akun_pembayaran'] !== $request->akun_pembayaran || $old_data['biaya'] != $request->biaya) {
                // Kurangi saldo pada akun pembayaran lama
                $kas_bank_old = Kasdanbank::where('kode_akun', $old_data['akun_pembayaran'])->first();
                if ($kas_bank_old) {
                    $kas_bank_old->decrement('uang_keluar', parseRupiahToNumber($old_data['biaya']));
                }

                // Tambahkan saldo pada akun pembayaran baru
                $kas_bank_new = Kasdanbank::where('kode_akun', $request->akun_pembayaran)->first();
                if ($kas_bank_new) {
                    $kas_bank_new->increment('uang_keluar', $request->biaya);
                } else {
                    Alert::error('Error', 'Akun pembayaran baru tidak ditemukan di Kas & Bank.');
                    return redirect()->back();
                }
            }

            // Perbarui data pada tabel hutangpiutang jika hutang
            if ($request->hutangButton) {
                $hutangPiutang = DB::table('hutangpiutang')
                    ->where('id_kontak', $findPengeluaran->id_kontak)
                    ->where('kategori', $findPengeluaran->kategori)
                    ->where('tgl_jatuh_tempo', $findPengeluaran->tgl_jatuh_tempo)
                    ->where('jenis', 'hutang')
                    ->first();

                if ($hutangPiutang) {
                    DB::table('hutangpiutang')
                        ->where('id_hutangpiutang', $hutangPiutang->id_hutangpiutang)
                        ->update([
                            'nominal' => parseRupiahToNumber($hutangPiutang->nominal) + parseRupiahToNumber($request->nominal_hutang) - parseRupiahToNumber($old_data['nominal_hutang']),
                        ]);
                } else {
                    DB::table('hutangpiutang')->insert([
                        'id_kontak'          => $findPengeluaran->id_kontak,
                        'kategori'           => $findPengeluaran->kategori,
                        'jenis'              => 'hutang',
                        'nominal'            => parseRupiahToNumber($request->nominal_hutang),
                        'status'             => 'Belum Lunas',
                        'tgl_jatuh_tempo'    => $findPengeluaran->tgl_jatuh_tempo,
                    ]);
                }
            }

            // Insert Jurnal
            $this->jurnalRepository->storePengeluaran($findPengeluaran);

            Alert::success('Data Edited!', 'Data Edited Successfully');
            return redirect()->route('pengeluaran.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating data: ' . $e->getMessage());
        }
    }

    public function destroy(string $id_pengeluaran)
    {
        try {
            $pengeluaran = Pengeluaran::find($id_pengeluaran);

            // Delete Jurnal
            $prefix = Pengeluaran::CODE_JURNAL;
            $jurnal = Jurnal::where('code', $prefix)->where('no_reff', $pengeluaran->id_pengeluaran)->first();
            if ($jurnal) {
                $this->jurnalRepository->delete($jurnal->id_jurnal);
            }

            $pengeluaran->delete();
            Alert::success('Data Deleted!', 'Data Deleted Successfully');
            return redirect()->route('pengeluaran.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting data: ' . $e->getMessage());
        }
    }
}
