<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\Kasdanbank;
use App\Models\Arusuang;
use App\Models\Kontak;
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

    /**
     * Store a newly created resource in storage.
     */
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
        }

        // Tampilkan notifikasi sukses
        Alert::success('Data Added!', 'Data Created Successfully');
        return redirect()->route('pengeluaran.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
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
