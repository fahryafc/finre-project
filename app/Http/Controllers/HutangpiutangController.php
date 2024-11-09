<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pengeluaran;
use App\Models\Hutangpiutang;
use App\Models\RiwayatPembayaranHutangPiutang;
use App\Models\Kontak;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Exception;


class HutangpiutangController extends Controller
{

    public function index(Request $request)
    {
        try {
            // Ambil semua data hutang
            $hutang = DB::table('hutangpiutang')
                ->join('kontak', 'hutangpiutang.id_kontak', '=', 'kontak.id_kontak')
                ->where('hutangpiutang.jenis', '=', 'hutang')
                ->select(
                    'hutangpiutang.id_hutangpiutang',
                    'hutangpiutang.id_kontak',
                    'hutangpiutang.jenis', 
                    'hutangpiutang.kategori', 
                    'hutangpiutang.tgl_jatuh_tempo',
                    'hutangpiutang.status',
                    'kontak.nama_kontak',
                    'kontak.nm_perusahaan',
                    DB::raw('SUM(hutangpiutang.nominal) as total_hutang')
                )
                ->groupBy('hutangpiutang.id_kontak', 'hutangpiutang.kategori', 'kontak.nama_kontak', 'kontak.nm_perusahaan', 'hutangpiutang.tgl_jatuh_tempo')
                ->get();

            // Ambil semua data piutang
            $piutang = DB::table('hutangpiutang')
                ->join('kontak', 'hutangpiutang.id_kontak', '=', 'kontak.id_kontak')
                ->where('hutangpiutang.jenis', '=', 'piutang')
                ->select(
                    'hutangpiutang.id_hutangpiutang',
                    'hutangpiutang.id_kontak',
                    'hutangpiutang.jenis', 
                    'hutangpiutang.kategori', 
                    'hutangpiutang.tgl_jatuh_tempo',
                    'hutangpiutang.status',
                    'kontak.nama_kontak',
                    'kontak.nm_perusahaan',
                    DB::raw('SUM(hutangpiutang.nominal) as total_hutang')
                )
                ->groupBy('hutangpiutang.id_kontak', 'hutangpiutang.kategori', 'kontak.nama_kontak', 'kontak.nm_perusahaan', 'hutangpiutang.tgl_jatuh_tempo')
                ->get();
            
            $kategoriHutang = DB::table('hutangpiutang')
                ->select('kategori')
                ->where('jenis', '=', 'hutang')
                ->groupBy('kategori')
                ->get();

            $kasdanbank = DB::table('kas_bank')->get();
                
            return view('pages.hutangdanpiutang.index', [
                'piutang' => $piutang, 
                'hutang' => $hutang,
                'kategoriHutang' => $kategoriHutang,
                'kas_bank' => $kasdanbank
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all data Hutang Piutang failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function getHutangDetail($id_hutangpiutang)
    {
        $riwayatPembayaran = DB::table('riwayat_pembayaran_hutangpiutang')
                ->select('tanggal_pembayaran', 'dibayarkan', 'sisa_pembayaran', 'masuk_akun', 'catatan')
                ->where('id_hutangpiutang', '=', $id_hutangpiutang)
                ->get();

        return response()->json($riwayatPembayaran);
    }

    public function getPiutangDetail($idKontak)
    {
        $detail = DB::table('penjualan')
        ->join('kontak', 'penjualan.id_kontak', '=', 'kontak.id_kontak')
        ->where('penjualan.id_kontak', $idKontak)
        ->select(
            'penjualan.*',
            'kontak.nama_kontak',
            'kontak.nm_perusahaan',
            'kontak.email',
            'kontak.no_hp',
            'kontak.alamat'
        )
        ->get();

        return response()->json($detail);
        // dd($detail);
    }

    public function show()
    {
        try {
            // get data hutang
            // Get data hutang
        $hutang = DB::table('pengeluaran')
            ->join('kontak', 'pengeluaran.id_kontak', '=', 'kontak.id_kontak')
            ->select(
                'pengeluaran.id_pengeluaran', 
                'kontak.nama_kontak as nm_pelanggan', 
                'kontak.nm_perusahaan', 
                DB::raw('SUM(pengeluaran.hutang) as total_hutang')
            )
            ->groupBy('kontak.id_kontak', 'kontak.nama_kontak', 'kontak.nm_perusahaan')
            ->get();

        // Get data piutang
        $piutang = DB::table('penjualan')
            ->join('kontak', 'penjualan.id_kontak', '=', 'kontak.id_kontak')
            ->select(
                'penjualan.id_penjualan', 
                'kontak.nama_kontak as nm_pelanggan', 
                'kontak.nm_perusahaan', 
                DB::raw('SUM(penjualan.piutang) as total_piutang')
            )
            ->groupBy('kontak.id_kontak', 'kontak.nama_kontak', 'kontak.nm_perusahaan')
            ->get();

            return view('pages.hutangpiutang.index', ['piutang' => $piutang, 'hutang' => $hutang]);
        } catch (\Exception $errors) {
            return $this->Response($errors->getMessage(), 'pages.hutangpiutang.index', 'error');
        }
    }

    private function parseRupiahToNumber($rupiah)
    {
        // Hapus karakter selain angka dan koma/titik, serta awalan "Rp" jika ada
        $cleaned = str_replace(['Rp', '.', ' '], '', $rupiah); // Hapus "Rp", titik pemisah ribuan, dan spasi
        $cleaned = str_replace(',', '.', $cleaned); // Ganti koma menjadi titik untuk memastikan desimal benar

        return floatval($cleaned) ?: 0;
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pembayaran' => 'required',
            'dibayarkan' => 'required',
            'masuk_akun' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // Ambil data hutang
            $hutangPiutang = HutangPiutang::findOrFail($request->id_hutangpiutang);
            // Ambil sisa pembayaran terakhir dari riwayat pembayaran, jika ada
            $lastPayment = RiwayatPembayaranHutangPiutang::where('id_hutangpiutang', $hutangPiutang->id_hutangpiutang)
                            ->orderBy('created_at', 'desc')
                            ->first();
            // Hitung sisa pembayaran baru
            $sisaPembayaran = $lastPayment ? $lastPayment->sisa_pembayaran - $this->parseRupiahToNumber($request->dibayarkan) : $hutangPiutang->nominal - $this->parseRupiahToNumber($request->dibayarkan);

            // Insert data pembayaran hutang / piutang
            $pembayaran = new RiwayatPembayaranHutangPiutang();
            $pembayaran->id_hutangpiutang = $request->id_hutangpiutang;
            $pembayaran->jenis_riwayat = $hutangPiutang->jenis;
            $pembayaran->tanggal_pembayaran = $request->tanggal_pembayaran;
            $pembayaran->dibayarkan = $this->parseRupiahToNumber($request->dibayarkan);
            $pembayaran->sisa_pembayaran = $sisaPembayaran;
            $pembayaran->masuk_akun = $request->masuk_akun;
            $pembayaran->catatan = $request->catatan;
            $pembayaran->save();

            // Update status hutang menjadi lunas jika sisa pembayaran adalah 0
            if ($sisaPembayaran <= 0) {
                $hutangPiutang->status = 'Lunas';
                $hutangPiutang->save();
            }

            DB::commit();

            Alert::success('Data Added!', 'Pembayaran Sukses');
            return redirect()->route('hutangpiutang.index');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error!', 'Pembayaran Gagal');
            return redirect()->route('hutangpiutang.index'. $e->getMessage());
        }
    }
}
