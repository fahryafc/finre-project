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
    public function index(Request $request)
    {
        $title = 'Hapus Data!';
        $text = "Apakah kamu yakin menghapus data ini ?";
        confirmDelete($title, $text);

        $filter_date = $request->input('date');
        try {
            $penjualan = DB::table('penjualan')
                ->join('kontak', 'kontak.id_kontak', '=', 'penjualan.id_kontak')
                ->join('produk_penjualan', 'produk_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
                ->when($filter_date, function ($query, $filter_date) {
                    return $query->whereDate('penjualan.tanggal', $filter_date);
                })
                ->select(
                    'kontak.nama_kontak',
                    'penjualan.*',
                    DB::raw('SUM(produk_penjualan.harga * produk_penjualan.kuantitas) AS total_harga')
                )
                ->groupBy(
                    'penjualan.id_penjualan'
                )
                ->get();

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

    public function create()
    {
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
        db::beginTransaction();
        try {
            // Menyimpan data penjualan utama
            $data = Penjualan::create([
                'id_penjualan'      => $request->id_penjualan,
                'id_kontak'         => $request->id_kontak,
                'tanggal'           => Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d'),
                'piutang'           => $request->piutangSwitch ? 1 : 0,
                'ongkir'            => $request->ongkir,
                'pembayaran'        => $request->pembayaran,
                'total_pajak'       => $request->nominal_pajak,
                'total_diskon'      => $request->diskon_output,
                'total_pemasukan'   => $request->total_pemasukan,
                'tgl_jatuh_tempo'   => $request->tgl_jatuh_tempo,
            ]);

            // Menyimpan detail penjualan dan pajak
            foreach ($request->produk as $key => $nm_produk) {
                // Cari kategori produk
                $kategori_produk = Produk::where('nama_produk', $request->produk[$key])->first();
                if (!$kategori_produk) {
                    throw new \Exception('Produk tidak ditemukan!');
                }

                // Generate kode reff untuk pajak
                $kodeReff = $request->jns_pajak[$key] === 'ppnbm'
                    ? $this->generateKodeReff('PPNBM')
                    : $this->generateKodeReff('PPN');

                // Simpan detail produk penjualan
                $produkPenjualan = ProdukPenjualan::create([
                    'id_penjualan'      => $data->id_penjualan,
                    'produk'            => $request->produk[$key],
                    'kategori_produk'   => $kategori_produk->kategori,
                    'satuan'            => $request->satuan[$key],
                    'harga'             => $request->harga[$key],
                    'kuantitas'         => $request->kuantitas[$key],
                    'kode_reff_pajak'   => $kodeReff,
                    'jns_pajak'         => $request->jns_pajak[$key],
                    'persen_pajak'      => $request->persen_pajak[$key] ?? 0,
                    'nominal_pajak'     => $request->harga[$key] * $request->kuantitas[$key] * ($request->persen_pajak[$key] / 100) ?? 0,
                    'persen_diskon'     => $request->diskon[$key],
                    'nominal_diskon'    => $request->harga[$key] * $request->kuantitas[$key] * ($request->diskon[$key] / 100) ?? 0,
                ]);

                // Menambahkan pajak jika ada
                if ($produkPenjualan->jns_pajak) {
                    if ($produkPenjualan->jns_pajak == 'ppn11' || $produkPenjualan->jns_pajak == 'ppn12') {
                        // Insert pajak untuk PPN
                        DB::table('pajak_ppn')->insert([
                            'kode_reff'       => $produkPenjualan->kode_reff_pajak,
                            'jenis_transaksi' => 'penjualan',
                            'keterangan'      => $produkPenjualan->produk,
                            'nilai_transaksi' => $produkPenjualan->harga * $produkPenjualan->kuantitas,
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
                $produk = Produk::where('nama_produk', $request->produk[$key])->first();
                if ($produk->kuantitas >= $request->kuantitas[$key]) {
                    $produk->kuantitas -= $request->kuantitas[$key];
                    $produk->save();
                } else {
                    throw new \Exception('Kuantitas produk tidak mencukupi!');
                }
            }

            // Mengupdate saldo akun kas & bank
            $akun = Kasdanbank::where('kode_akun', $request->pembayaran)->first();
            if ($akun) {
                $akun->saldo += $request->total_pemasukan;
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

            DB::commit();
            Alert::success('Data Added!', 'Tambah Data Penjualan Berhasil');
            return redirect()->route('penjualan.index');
        } catch (\Exception $e) {
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
            $penjualan = Penjualan::with('produkPenjualan')
                ->where('id_penjualan', $id)
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
                'id_penjualan'      => $request->id_penjualan,
                'id_kontak'         => $request->id_kontak,
                'tanggal'           => Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d'),
                'piutang'           => $request->piutangSwitch ? 1 : 0,
                'ongkir'            => $request->ongkir,
                'pembayaran'        => $request->pembayaran,
                'total_pajak'       => $request->nominal_pajak,
                'total_diskon'      => $request->diskon_output,
                'total_pemasukan'   => $request->total_pemasukan,
                'tgl_jatuh_tempo'   => $request->tgl_jatuh_tempo,
            ]);

            // Menyimpan detail penjualan dan pajak
            foreach ($request->produk as $key => $nm_produk) {
                // Cari kategori produk
                $kategori_produk = Produk::where('nama_produk', $request->produk[$key])->first();
                if (!$kategori_produk) {
                    throw new \Exception('Produk tidak ditemukan!');
                }

                // Generate kode reff untuk pajak
                $kodeReff = $request->jns_pajak[$key] === 'ppnbm'
                    ? $this->generateKodeReff('PPNBM')
                    : $this->generateKodeReff('PPN');

                // Simpan detail produk penjualan
                $produkPenjualan =  DB::table('produk_penjualan')->update([
                    'id_penjualan'      => $penjualan->id_penjualan,
                    'produk'            => $request->produk[$key],
                    'kategori_produk'   => $kategori_produk->kategori,
                    'satuan'            => $request->satuan[$key],
                    'harga'             => $request->harga[$key],
                    'kuantitas'         => $request->kuantitas[$key],
                    'kode_reff_pajak'   => $kodeReff,
                    'jns_pajak'         => $request->jns_pajak[$key],
                    'persen_pajak'      => $request->persen_pajak[$key] ?? 0,
                    'nominal_pajak'     => $request->harga[$key] * $request->kuantitas[$key] * ($request->persen_pajak[$key] / 100) ?? 0,
                    'persen_diskon'     => $request->diskon[$key],
                    'nominal_diskon'    => $request->harga[$key] * $request->kuantitas[$key] * ($request->diskon[$key] / 100) ?? 0,
                ]);

                dd($request->all());

                // Menambahkan pajak jika ada
                if ($produkPenjualan->jns_pajak) {
                    if ($produkPenjualan->jns_pajak == 'ppn11' || $produkPenjualan->jns_pajak == 'ppn12') {
                        // Insert pajak untuk PPN
                        DB::table('pajak_ppn')->insert([
                            'kode_reff'       => $produkPenjualan->kode_reff_pajak,
                            'jenis_transaksi' => 'penjualan',
                            'keterangan'      => $produkPenjualan->produk,
                            'nilai_transaksi' => $produkPenjualan->harga * $produkPenjualan->kuantitas,
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
            if ($penjualan->piutang == 1) {
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
