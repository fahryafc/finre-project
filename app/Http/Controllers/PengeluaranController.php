<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\Kasdanbank;
use App\Models\Arusuang;
use App\Models\Kontak;
use App\Models\Pajak;
use App\Models\Pajak_ppn;
use App\Models\Pajak_ppnbm;
use App\Models\Pajak_pph;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Exception;

class PengeluaranController extends Controller
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
            // $pengeluaran = DB::table('pengeluaran')->get();
            $pengeluaran = Pengeluaran::join('kontak', 'pengeluaran.id_kontak', '=', 'kontak.id_kontak')
                ->select('pengeluaran.*', 'kontak.nama_kontak')
                ->paginate(5);
            $akun = DB::table('akun')->get();
            $satuan = DB::table('satuan')->get();
            $produk = DB::table('produk')->get();
            $kasdanbank = DB::table('kas_bank')->get();
            $kategori = DB::table('kategori')->get();
            $karyawanKontak = DB::table('kontak')->where('jenis_kontak', '=', 'karyawan')->get();
            $vendorKontak = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();

            // dd($pengeluaran);

            return view('pages.pengeluaran.index', [
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
            $kasdanbank = DB::table('kas_bank')->get();
            $kategori = DB::table('kategori')->get();
            $karyawanKontak = DB::table('kontak')->where('jenis_kontak', '=', 'karyawan')->get();
            $vendorKontak = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();

            // dd($pengeluaran);

            return view('pages.pengeluaran.create', [
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
        // generate kode reff untuk pajak
        if ($request->jns_pajak === 'ppnbm') {
            $kodeReff = $this->generateKodeReff('PPNBM');
        } elseif ($request->jns_pajak === 'ppn') {
            $kodeReff = $this->generateKodeReff('PPN');
        } elseif ($request->jns_pajak === 'pph') {
            $kodeReff = $this->generateKodeReff('PPH');
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
            'biaya'                => $request->biaya,
            'pajak'                => $request->pajakButton ? 1 : 0, // Jika checked, isi dengan 1
            'kode_reff_pajak'      => $kodeReff,
            'jns_pajak'            => $request->jns_pajak,
            'pajak_persen'         => $request->pajak_persen,
            'pajak_dibayarkan'     => $request->pajak_dibayarkan,
            'hutang'               => $request->hutangButton ? 1 : 0, // Jika checked, isi dengan 1
            'nominal_hutang'       => $request->nominal_hutang,
            'akun_pembayaran'      => $request->akun_pembayaran,
            'akun_pemasukan'       => $request->akun_pemasukan,
            'tgl_jatuh_tempo'      => $request->hutangButton ? Carbon::createFromFormat('d-m-Y', $request->tgl_jatuh_tempo)->format('Y-m-d') : null, // Set to null if hutang is 0
        ]);

        // Menambahkan data pajak jika ada
        $findKaryawan = Kontak::where('id_kontak', $data_pengeluaran->id_kontak)->first();
        if ($data_pengeluaran->pajak == 1) {
            if ($data_pengeluaran->jns_pajak == 'ppn') {
                DB::table('pajak_ppn')->insert([
                    'kode_reff'         => $data_pengeluaran->kode_reff_pajak,
                    'jenis_transaksi'   => 'penjualan',
                    'keterangan'        => $data_pengeluaran->produk,
                    'nilai_transaksi'   => $data_pengeluaran->harga * $data_pengeluaran->kuantitas,
                    'persen_pajak'      => $data_pengeluaran->persen_pajak,
                    'jenis_pajak'       => 'Pajak Keluaran',
                    'saldo_pajak'       => $data_pengeluaran->total_pemasukan * ($data_pengeluaran->persen_pajak / 100),
                ]);
            } elseif ($data_pengeluaran->jns_pajak == 'ppnbm') {
                DB::table('pajak_ppnbm')->insert([
                    'kode_reff'             => $data_pengeluaran->kode_reff_pajak,
                    'deskripsi_barang'      => $data_pengeluaran->produk,
                    'harga_barang'          => $data_pengeluaran->harga,
                    'tarif_ppnbm'           => $data_pengeluaran->persen_pajak,
                    'ppnbm_dikenakan'       => $data_pengeluaran->total_pemasukan * ($data_pengeluaran->persen_pajak / 100),
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
                    'bersih_diterima'   => $data_pengeluaran->biaya - $data_pengeluaran->pajak_dibayarkan,
                ]);
            }
        }

        // Cek jika data pengeluaran berhasil disimpan
        if ($data_pengeluaran) {
            // Cari akun kas_bank berdasarkan kode_akun (akun_pembayaran)
            $kas_bank = Kasdanbank::where('kode_akun', $data_pengeluaran->akun_pembayaran)->first();

            // Jika akun ditemukan, tambahkan data ke tabel arus_uang
            if ($kas_bank) {
                $arusuang = Arusuang::create([
                    'kode_akun'        => $data_pengeluaran->akun_pembayaran, // Diambil dari akun_pembayaran
                    'nominal'          => $data_pengeluaran->biaya, // Diambil dari biaya pengeluaran
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
                        'nominal' => $hutangPiutang->nominal + $data_pengeluaran->nominal_hutang,
                    ]);
            } else {
                // Jika tidak ditemukan, insert row baru
                DB::table('hutangpiutang')->insert([
                    'id_kontak'          => $data_pengeluaran->id_kontak,
                    'kategori'           => $data_pengeluaran->kategori,
                    'jenis'              => 'hutang',
                    'nominal'            => $data_pengeluaran->nominal_hutang,
                    'status'             => 'Belum Lunas',
                    'tgl_jatuh_tempo'    => $data_pengeluaran->tgl_jatuh_tempo,
                ]);
            }
        }

        // Tampilkan notifikasi sukses
        Alert::success('Data Added!', 'Data Created Successfully');
        return redirect()->route('pengeluaran.index');
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
            $kasdanbank = DB::table('kas_bank')->get();
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
                'biaya'                => $request->biaya,
                'pajak'                => $request->pajakButton ? 1 : 0,
                'jns_pajak'            => $request->jns_pajak,
                'pajak_persen'         => $request->pajak_persen,
                'pajak_dibayarkan'     => $request->pajak_dibayarkan,
                'hutang'               => $request->hutangButton ? 1 : 0,
                'nominal_hutang'       => $request->nominal_hutang,
                'akun_pembayaran'      => $request->akun_pembayaran,
                'akun_pemasukan'       => $request->akun_pemasukan,
                'tgl_jatuh_tempo'      => $request->hutangButton ? Carbon::createFromFormat('d-m-Y', $request->tgl_jatuh_tempo)->format('Y-m-d') : null,
            ]);

            // Periksa perubahan pada akun pembayaran
            if ($old_data['akun_pembayaran'] !== $request->akun_pembayaran || $old_data['biaya'] != $request->biaya) {
                // Kurangi saldo pada akun pembayaran lama
                $kas_bank_old = Kasdanbank::where('kode_akun', $old_data['akun_pembayaran'])->first();
                if ($kas_bank_old) {
                    $kas_bank_old->decrement('uang_keluar', $old_data['biaya']);
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
                            'nominal' => $hutangPiutang->nominal + $request->nominal_hutang - $old_data['nominal_hutang'],
                        ]);
                } else {
                    DB::table('hutangpiutang')->insert([
                        'id_kontak'          => $findPengeluaran->id_kontak,
                        'kategori'           => $findPengeluaran->kategori,
                        'jenis'              => 'hutang',
                        'nominal'            => $request->nominal_hutang,
                        'status'             => 'Belum Lunas',
                        'tgl_jatuh_tempo'    => $findPengeluaran->tgl_jatuh_tempo,
                    ]);
                }
            }

            // Perbarui data pajak jika ada
            if ($request->pajakButton) {
                if ($request->jns_pajak === 'ppn') {
                    DB::table('pajak_ppn')->updateOrInsert([
                        'id_pengeluaran'    => $id_pengeluaran,
                    ], [
                        'kode_reff'         => $findPengeluaran->kode_reff_pajak,
                        'jenis_transaksi'   => 'pengeluaran',
                        'keterangan'        => $findPengeluaran->nm_pengeluaran,
                        'nilai_transaksi'   => $request->biaya,
                        'persen_pajak'      => $request->pajak_persen,
                        'jenis_pajak'       => 'Pajak Masukan',
                        'saldo_pajak'       => $request->biaya * ($request->pajak_persen / 100),
                    ]);
                } elseif ($request->jns_pajak === 'ppnbm') {
                    DB::table('pajak_ppnbm')->updateOrInsert([
                        'id_pengeluaran'    => $id_pengeluaran,
                    ], [
                        'deskripsi_barang'  => $findPengeluaran->nm_pengeluaran,
                        'harga_barang'      => $request->biaya,
                        'tarif_ppnbm'       => $request->pajak_persen,
                        'ppnbm_dikenakan'   => $request->biaya * ($request->pajak_persen / 100),
                        'jenis_pajak'       => 'Pajak Masukan',
                        'tgl_transaksi'     => $findPengeluaran->tanggal,
                    ]);
                } elseif ($request->jns_pajak === 'pph') {
                    $findKaryawan = Kontak::where('id_kontak', $request->id_kontak)->first();
                    DB::table('pajak_pph')->updateOrInsert([
                        'id_pengeluaran'    => $id_pengeluaran,
                    ], [
                        'nm_karyawan'       => $findKaryawan->nama_kontak,
                        'gaji_karyawan'     => $request->biaya,
                        'pph_terutang'      => $request->pajak_dibayarkan,
                        'bersih_diterima'   => $request->biaya - $request->pajak_dibayarkan,
                    ]);
                }
            }

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
            $pengeluaran->delete();
            Alert::success('Data Deleted!', 'Data Deleted Successfully');
            return redirect()->route('pengeluaran.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting data: ' . $e->getMessage());
        }
    }
}