<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\Kasdanbank;
use App\Models\Arusuang;
use App\Models\Kontak;
use App\Models\Pajak;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
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

    public function store(Request $request): RedirectResponse
    {
        // Simpan data pengeluaran ke dalam tabel pengeluaran
        $data_pengeluaran = Pengeluaran::create([
            'nm_pengeluaran'       => $request->nm_pengeluaran,
            'jenis_pengeluaran'    => $request->jenis_pengeluaran,
            'id_kontak'            => $request->id_kontak,
            'tanggal'              => Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d'),
            'kategori'             => $request->kategori,
            'biaya'                => $request->biaya,
            'pajak'                => $request->pajakButton ? 1 : 0, // Jika checked, isi dengan 1
            'jns_pajak'            => $request->jns_pajak,
            'pajak_persen'         => $request->pajak_persen,
            'pajak_dibayarkan'     => $request->pajak_dibayarkan,
            'hutang'               => $request->hutangButton ? 1 : 0, // Jika checked, isi dengan 1
            'nominal_hutang'       => $request->nominal_hutang,
            'akun_pembayaran'      => $request->akun_pembayaran,
            'akun_pemasukan'       => $request->akun_pemasukan,
            'tgl_jatuh_tempo'      => $request->hutangButton ? Carbon::createFromFormat('d-m-Y', $request->tgl_jatuh_tempo)->format('Y-m-d') : null, // Set to null if hutang is 0
        ]);

        // dd($data_pengeluaran);

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

            // dd($hutangPiutang);

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
                } elseif ($data_pengeluaran->jns_pajak == 'ppn') {
                    DB::table('pajak_pph')->insert([
                        'id_pengeluaran'    => $data_pengeluaran->id_pengeluaran,
                        'nm_karyawan'       => $findKaryawan->nama_kontak,
                        'gaji_karyawan'     => $data_pengeluaran->biaya,
                        'pph_terutang'      => "",
                        'potongan'          => "",
                        'persen_pajak'      => "",
                    ]);
                }
            }
        }

        // added data pajak jika ada
        if($data_pengeluaran->pajak != NULL || $data->pajak != ''){
            // Masukkan data ke tabel pajak
            DB::table('pajak_ppn')->insert([
                'jenis_transaksi'   => 'Pembelian',
                'keterangan'        => $data_pengeluaran->nm_pengeluaran,
                'nilai_transaksi'   => $data_pengeluaran->biaya,
                'persen_pajak'      => $data_pengeluaran->pajak_persen,
                'jenis_pajak'       => 'Pajak Masukan',
                'saldo_pajak'       => $data_pengeluaran->pajak_dibayarkan,
            ]);
        }

        // Tampilkan notifikasi sukses
        Alert::success('Data Added!', 'Data Created Successfully');
        return redirect()->route('pengeluaran.index');
    }

    /**
     * Display the specified resource.
     */
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

            // Ambil data lama pembayaran dan akun_pembayaran untuk membandingkan
            $old_pembayaran = $findPengeluaran->pembayaran;
            $old_akun_pembayaran = $findPengeluaran->akun_pembayaran;

            // Update data pengeluaran dengan data baru
            $findPengeluaran->update($request->all());
            // Cek jika ada perubahan pada 'pembayaran' atau 'akun_pembayaran'
            if ($old_pembayaran !== $request->pembayaran || $old_akun_pembayaran !== $request->akun_pembayaran) {
                // Cari akun kas_bank berdasarkan kode_akun (akun_pembayaran) lama
                $kas_bank_old = Kasdanbank::where('kode_akun', $old_akun_pembayaran)->first();

                // Jika pembayaran atau akun pembayaran lama ditemukan, kurangi uang_keluar
                if ($kas_bank_old) {
                    $kas_bank_old->update([
                        'uang_keluar' => $kas_bank_old->uang_keluar - $old_pembayaran,
                    ]);
                }

                // Cari akun kas_bank berdasarkan kode_akun (akun_pembayaran) baru
                $kas_bank_new = Kasdanbank::where('kode_akun', $request->akun_pembayaran)->first();

                // Jika akun pembayaran baru ditemukan, tambahkan uang_keluar dengan pembayaran baru
                if ($kas_bank_new) {
                    $kas_bank_new->update([
                        'uang_keluar' => $kas_bank_new->uang_keluar + $request->pembayaran,
                    ]);
                } else {
                    Alert::error('Error', 'Akun pembayaran baru tidak ditemukan di kas_bank');
                    return redirect()->back();
                }
            }

            // added data hutang jika ada
            if ($data_pengeluaran->hutang == 1) {
                // Cek apakah sudah ada row dengan id_kontak dan kategori yang sama di hutangpiutang
                $hutangPiutang = DB::table('hutangpiutang')
                    ->where('id_kontak', $data_pengeluaran->id_kontak)
                    ->where('kategori', $data_pengeluaran->kategori)
                    ->where('tgl_jatuh_tempo', $data_pengeluaran->tgl_jatuh_tempo)
                    ->where('jenis', 'hutang')
                    ->first();

                // dd($hutangPiutang);

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

            Alert::success('Data Edited!', 'Data Edited Successfully');
            return redirect()->route('pengeluaran.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
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
