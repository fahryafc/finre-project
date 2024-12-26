<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Akun;
use App\Models\Satuan;
use App\Models\Kasdanbank;
use App\Models\Produk;
use App\Models\Pajakppn;
use App\Models\Pajakppnbm;
use App\Models\Arusuang;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Hapus Data!';
        $text = "Apakah kamu yakin menghapus data ini ?";
        confirmDelete($title, $text);

        try {
            $penjualan = Penjualan::leftJoin('hutangpiutang', 'penjualan.id_kontak', '=', 'hutangpiutang.id_kontak')
                ->select('penjualan.*', 'hutangpiutang.nominal as nominal_piutang', 'hutangpiutang.jenis')
                ->groupBy('penjualan.id_penjualan')
                ->paginate(5);

            $akun = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
            $kasdanbank = DB::table('kas_bank')->get();
            $satuan = DB::table('satuan')->get();
            $produk = DB::table('produk')->get();
            $kategori = DB::table('kategori')->get();
            $karyawanKontak = DB::table('kontak')->where('jenis_kontak', '=', 'karyawan')->get();
            $vendorKontak = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();
            $pelanggan = DB::table('kontak')->where('jenis_kontak', '=', 'pelanggan')->get();

            // $data_penjualan = response()->json($penjualan);
            // dd($akun);

            return view('pages.penjualan.index', [
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

    public function create(){
        try {
            $penjualan = Penjualan::leftJoin('hutangpiutang', 'penjualan.id_kontak', '=', 'hutangpiutang.id_kontak')
                ->select('penjualan.*', 'hutangpiutang.nominal as nominal_piutang', 'hutangpiutang.jenis')
                ->groupBy('penjualan.id_penjualan')
                ->paginate(5);

            $akun = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
            $kasdanbank = DB::table('kas_bank')->get();
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
        // generate kode reff untuk pajak
        $kodeReff = $request->jns_pajak === 'ppnbm' 
            ? $this->generateKodeReff('PPNBM') 
            : $this->generateKodeReff('PPN');

        // print_r($kodeReff);
        // exit;

        DB::beginTransaction();

        try {
            $kategori_produk = Produk::where('nama_produk', $request->produk)->first();

            if (!$kategori_produk) {
                throw new \Exception('Produk tidak ditemukan!');
            }

            if($request->jns_pajak == 'ppn'){
                $persen_pajak = '11';
            }else{
                $persen_pajak = $request->persen_pajak;
            }

            $data = Penjualan::create([
                'id_kontak'         => $request->id_kontak,
                'tanggal'           => Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d'),
                'produk'            => $request->produk,
                'kategori_produk'   => $kategori_produk->kategori,
                'satuan'            => $request->satuan,
                'harga'             => $request->harga,
                'kuantitas'         => $request->kuantitas,
                'diskon'            => $request->diskon,
                'pajak'             => '1',
                'kode_reff_pajak'   => $kodeReff,
                'jns_pajak'         => $request->jns_pajak,
                'persen_pajak'      => $persen_pajak,
                'nominal_pajak'     => $request->total_pemasukan * ($persen_pajak / 100),
                'piutang'           => $request->piutangSwitch ? 1 : 0,
                'ongkir'            => $request->ongkir,
                'pembayaran'        => $request->pembayaran,
                'total_pemasukan'   => $request->total_pemasukan,
                'tgl_jatuh_tempo'   => $request->tgl_jatuh_tempo,
            ]);

            // Mengurangi kuantitas produk di tabel produk
            $produk = Produk::where('nama_produk', $data->produk)->first();
            if ($produk->kuantitas >= $data->kuantitas) {
                $produk->kuantitas -= $data->kuantitas;
                $produk->save();
            } else {
                throw new \Exception('Kuantitas produk tidak mencukupi!');
            }

            // Mengupdate saldo akun kas & bank
            $akun = Kasdanbank::where('kode_akun', $request->pembayaran)->first();
            if ($akun) {
                $akun->saldo += $data->total_pemasukan;
                $akun->save();
            } else {
                throw new \Exception('Akun pembayaran tidak ditemukan!');
            }

            // Menambahkan data piutang jika ada
            if ($data->piutang == 1) {
                DB::table('hutangpiutang')->insert([
                    'id_kontak'     => $data->id_kontak,
                    'kategori'      => $data->kategori_produk,
                    'jenis'         => 'piutang',
                    'nominal'       => $request->piutang,
                    'status'        => 'Belum Lunas',
                ]);
            }

            // Menambahkan data pajak jika ada
            if ($data->pajak == 1) {
                if ($data->jns_pajak == 'ppn11') {
                    DB::table('pajak_ppn')->insert([
                        'kode_reff'         => $data->kode_reff_pajak,
                        'jenis_transaksi'   => 'penjualan',
                        'keterangan'        => $data->produk,
                        'nilai_transaksi'   => $data->harga * $data->kuantitas,
                        'persen_pajak'      => $data->persen_pajak,
                        'jenis_pajak'       => 'Pajak Keluaran',
                        'saldo_pajak'       => $data->total_pemasukan * ($data->persen_pajak / 100),
                    ]);
                } else if ($data->jns_pajak == 'ppn12') {
                    DB::table('pajak_ppn')->insert([
                        'kode_reff'         => $data->kode_reff_pajak,
                        'jenis_transaksi'   => 'penjualan',
                        'keterangan'        => $data->produk,
                        'nilai_transaksi'   => $data->harga * $data->kuantitas,
                        'persen_pajak'      => $data->persen_pajak,
                        'jenis_pajak'       => 'Pajak Keluaran',
                        'saldo_pajak'       => $data->total_pemasukan * ($data->persen_pajak / 100),
                    ]);
                } elseif ($data->jns_pajak == 'ppnbm') {
                    DB::table('pajak_ppnbm')->insert([
                        'kode_reff'             => $data->kode_reff_pajak,
                        'deskripsi_barang'      => $data->produk,
                        'harga_barang'          => $data->harga,
                        'tarif_ppnbm'           => $data->persen_pajak,
                        'ppnbm_dikenakan'       => $data->total_pemasukan * ($data->persen_pajak / 100),
                        'jenis_pajak'           => "Pajak Masukan",
                        'tgl_transaksi'         => $data->tanggal,
                    ]);
                }
            }

            DB::commit();
            Alert::success('Data Added!', 'Tambah Data Penjualan Berhasil');
            return redirect()->route('penjualan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Tambah Data Penjualan Gagal: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    private function parseRupiahToNumber($rupiah)
    {
        // Hapus karakter selain angka dan koma/titik, serta awalan "Rp" jika ada
        $cleaned = str_replace(['Rp', '.', ' '], '', $rupiah); // Hapus "Rp", titik pemisah ribuan, dan spasi
        $cleaned = str_replace(',', '.', $cleaned); // Ganti koma menjadi titik untuk memastikan desimal benar

        return floatval($cleaned) ?: 0;
    }

    public function edit($id){
        try {

            $penjualan = Penjualan::leftJoin('hutangpiutang', 'penjualan.id_kontak', '=', 'hutangpiutang.id_kontak')
                ->leftJoin('pajak_ppn', function ($join) {
                    $join->on('penjualan.kode_reff_pajak', '=', 'pajak_ppn.kode_reff')
                        ->where('penjualan.jns_pajak', '=', 'ppn');
                })
                ->leftJoin('pajak_ppnbm', function ($join) {
                    $join->on('penjualan.kode_reff_pajak', '=', 'pajak_ppnbm.kode_reff')
                        ->where('penjualan.jns_pajak', '=', 'ppnbm');
                })
                ->select(
                    'penjualan.*', 
                    'penjualan.persen_pajak as pajak_persen', 
                    'hutangpiutang.nominal as nominal_piutang', 
                    'hutangpiutang.jenis', 
                    'pajak_ppn.*', 
                    'pajak_ppnbm.*'
                )
                ->where('penjualan.id_penjualan', $id)
                ->first();
            $penjualan->tanggal = Carbon::parse($penjualan->tanggal)->format('d-m-Y');
            $akun = DB::table('akun')->where('kategori_akun', '=', 'Aset/Harta')->get();
            $kasdanbank = DB::table('kas_bank')->get();
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

    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $penjualan = Penjualan::findOrFail($id);
            // Update data penjualan
            $penjualan->update([
                'nm_pelanggan'      => $request->nm_pelanggan,
                'no_hp'             => $request->no_hp,
                'nm_perusahaan'     => $request->nm_perusahaan,
                'email'             => $request->email,
                'alamat'            => $request->alamat,
                'tanggal'           => $request->tanggal,
                'produk'            => $request->produk,
                'satuan'            => $request->satuan,
                'harga'             => $this->parseRupiahToNumber($request->harga),
                'kuantitas'         => $request->kuantitas,
                'diskon'            => $request->diskon,
                'pajak'             => $request->pajak,
                'piutang'           => $request->piutangSwitch ? 1 : 0, // Jika checked, isi dengan 1,
                'pembayaran'        => $request->pembayaran,
                'tgl_jatuh_tempo'   => $request->tgl_jatuh_tempo,
                'total_pemasukan'   => $this->parseRupiahToNumber($request->total_pemasukan), // Simpan total_pemasukan ke database
            ]);

            // update saldo akun kas & bank
            $akun = Kasdanbank::where('kode_akun', $request->pembayaran)->first();
            if ($akun) {
                // Contoh: Menambah nilai ke saldo yang ada
                $akun->saldo += $penjualan->total_pemasukan;
                $akun->save();
            }

            // Mengupdate kuantitas produk
            $produk = Produk::where('nama_produk', $penjualan->produk)->first();
            if ($produk) {
                // Pastikan kuantitas produk mencukupi
                if ($produk->kuantitas >= $request->kuantitas) {
                    // Jika kuantitas berubah, update kuantitas produk
                    $produk->kuantitas += $penjualan->kuantitas - $request->kuantitas; // Sesuaikan kuantitas
                    $produk->save();
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

            // added data piutang jika ada
            if($penjualan->piutang == 1){
                // Masukkan data ke tabel hutangpiutang
                DB::table('hutangpiutang')->insert([
                    'id_kontak'     => $penjualan->id_kontak, // Contoh field, sesuaikan dengan struktur tabel Anda
                    'kategori'      => $penjualan->kategori_produk,
                    'jenis'         => 'piutang',
                    'nominal'       => $request->piutang,
                    'status'        => 'Belum Lunas',
                ]);
            }

            // Menambahkan data pajak jika ada
            if ($data->pajak == 1) {
                if ($data->jns_pajak == 'ppn') {
                    DB::table('pajak_ppn')->insert([
                        'kode_reff'         => $data->kode_reff_pajak,
                        'jenis_transaksi'   => 'penjualan',
                        'keterangan'        => $data->produk,
                        'nilai_transaksi'   => $data->harga * $data->kuantitas,
                        'persen_pajak'      => $data->persen_pajak,
                        'jenis_pajak'       => 'Pajak Keluaran',
                        'saldo_pajak'       => $data->total_pemasukan * ($data->persen_pajak / 100),
                    ]);
                } elseif ($data->jns_pajak == 'ppnbm') {
                    DB::table('pajak_ppnbm')->insert([
                        'kode_reff'             => $data->kode_reff_pajak,
                        'deskripsi_barang'      => $data->produk,
                        'harga_barang'          => $data->harga,
                        'tarif_ppnbm'           => $data->persen_pajak,
                        'ppnbm_dikenakan'       => $data->total_pemasukan * ($data->persen_pajak / 100),
                        'jenis_pajak'           => "Pajak Keluaran",
                        'tgl_transaksi'         => $data->tanggal,
                    ]);
                }
            }

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

            $penjualan->delete();

            Alert::success('Data Deleted!', 'Data Deleted Successfully');
            return redirect()->route('penjualan.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error : ' . $e->getMessage());
        }
    }
}
