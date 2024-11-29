<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Akun;
use App\Models\Satuan;
use App\Models\Kasdanbank;
use App\Models\Produk;
use App\Models\Pajak;
use App\Models\Arusuang;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
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

    public function store(Request $request): RedirectResponse
    {
        // find kategori produk
        $kategori_produk = Produk::where('nama_produk', $request->produk)->first();
        try{
            $data = Penjualan::create([
            'id_kontak'         => $request->id_kontak,
            'tanggal'           => Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d'),
            'produk'            => $request->produk,
            'kategori_produk'   => $kategori_produk->kategori,
            'satuan'            => $request->satuan,
            'harga'             => $request->harga,
            'kuantitas'         => $request->kuantitas,
            'diskon'            => $request->diskon,
            'pajak'             => $request->pajak,
            'piutang'           => $request->piutangSwitch ? 1 : 0, // Jika checked, isi dengan 1,
            'pembayaran'        => $request->pembayaran,
            'total_pemasukan'   => $request->total_pemasukan,
            'tgl_jatuh_tempo'   => $request->tgl_jatuh_tempo,
        ]);

        // Mengurangi kuantitas produk di tabel produk
        $produk = Produk::where('nama_produk', $data->produk)->first();
        if ($produk) {
            // Pastikan kuantitas produk mencukupi
            if ($produk->kuantitas >= $data->kuantitas) {
                $produk->kuantitas -= $data->kuantitas; // Kurangi kuantitas produk
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

        // update saldo akun kas & bank
        $akun = Kasdanbank::where('kode_akun', $request->pembayaran)->first();
        if ($akun) {
            // Contoh: Menambah nilai ke saldo yang ada
            $akun->saldo += $request->total_pemasukan;
            $akun->save();
        }

        // added data piutang jika ada
        if($data->piutang == 1){
            // Masukkan data ke tabel hutangpiutang
            DB::table('hutangpiutang')->insert([
                'id_kontak'     => $data->id_kontak,
                'kategori'      => $data->kategori_produk,
                'jenis'         => 'piutang',
                'nominal'       => $request->piutang,
                'status'        => 'Belum Lunas',
            ]);
        }

        // added data pajak jika ada
        if($data->pajak != NULL || $data->pajak != ''){
            // Masukkan data ke tabel pajak
            DB::table('pajak_ppn')->insert([
                'jenis_transaksi'   => 'penjualan',
                'keterangan'        => $data->produk,
                'nilai_transaksi'   => $data->harga * $data->kuantitas,
                'persen_pajak'      => $data->pajak,
                'jenis_pajak'       => 'Pajak Keluaran',
                'saldo_pajak'       => $data->total_pemasukan * ($data->pajak / 100),
            ]);
        }

        if($data->pajak != NULL || $data->pajak != ''){
            // Masukkan data ke tabel pajak
            DB::table('pajak_ppnbm')->insert([
                'deskripsi_barang'      => $data,
                'harga_barang'          => $data,
                'tarif_ppnbm'           => $data,
                'ppnbm_dikenakan'       => $data,
                'tgl_transaksi'         => $data,
                'keterangan'            => $data
            ]);
        }

        Alert::success('Data Added!', 'Data Created Successfully');
        return redirect()->route('penjualan.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Tambah Data Penjualan Gagal: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function show(string $id)
    {
        //
    }

    private function parseRupiahToNumber($rupiah)
    {
        // Hapus karakter selain angka dan koma/titik, serta awalan "Rp" jika ada
        $cleaned = str_replace(['Rp', '.', ' '], '', $rupiah); // Hapus "Rp", titik pemisah ribuan, dan spasi
        $cleaned = str_replace(',', '.', $cleaned); // Ganti koma menjadi titik untuk memastikan desimal benar

        return floatval($cleaned) ?: 0;
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
            $penjualan = Penjualan::find($id_penjualan);
            $penjualan->delete();
            Alert::success('Data Deleted!', 'Data Deleted Successfully');
            return redirect()->route('penjualan.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

        public function getSalesData()
    {
        $penjualan = Penjualan::select(
            'id_penjualan',
            'produk',
            'harga',
            'kuantitas',
            'diskon',
            'pajak',
            'piutang',
            'pembayaran',
            'tgl_jatuh_tempo',
        )->get();

        $formatdata = [];
        $formatdata = $penjualan->map(function ($penjualan, $index) {
            $total_harga = $penjualan->harga * $penjualan->kuantitas;
            $diskon = ((int) $penjualan->diskon / (int) 100) * $total_harga;
            $harga_diskon = $total_harga - $diskon;
            $pajak = ((int) $penjualan->pajak / (int) 100) * $harga_diskon;
            $total_pemasukan = (int)$harga_diskon - (int)$pajak - (int)$penjualan->piutang;
            return [
                'No' => sprintf('%02d', $index + 1),
                'Penjualan' => $penjualan->produk,
                'Kuantitas' => $penjualan->kuantitas,
                'Harga' => 'Rp. ' . number_format($penjualan->harga, 0, ',', '.'),
                'Total_harga' => 'Rp. ' . number_format($total_harga, 0, ',', '.'),
                'Diskon' => $penjualan->diskon . '%',
                'Pajak' => $penjualan->pajak . '%',
                'Piutang' => 'Rp. ' . number_format($penjualan->piutang, 0, ',', '.'),
                'Total_pemasukan' => 'Rp. ' . number_format($total_pemasukan, 0, ',', '.'),
                'id_penjualan' => $penjualan->id_penjualan
            ];
        });

        return response()->json($formatdata);
    }
}
