<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kasdanbank;
use App\Models\Akun;
use App\Models\Subakun;
use App\Models\Kategori_akun;
use App\Models\Arusuang;
use App\Models\Penjualan;
use RealRashid\SweetAlert\Facades\Alert;
use Exception;

class KasdanbankController extends Controller
{
    public function index()
    {
        $title = 'Hapus Data!';
        $text = "Apakah kamu yakin menghapus data ini ?";
        confirmDelete($title, $text);
        try {
            // Mengambil data dari tabel kas_bank dengan join ke tabel penjualan
            $kasdanbank = DB::table('kas_bank')
                ->leftJoin('penjualan', 'kas_bank.kode_akun', '=', 'penjualan.pembayaran')
                ->select(
                    'kas_bank.*', // ambil semua field dari kas_bank
                    'penjualan.total_pemasukan' // ambil total_pemasukan
                )
                ->groupBy('kas_bank.id_kas_bank') // Pastikan untuk group by berdasarkan primary key tabel kas_bank
                ->paginate(5);

            $kategoriAkun = DB::table('kategori_akun')->get();
            $subakunKategori = DB::table('subakun_kategori')->get();

            // Ambil total_pemasukan berdasarkan kode_akun
            // $totalPemasukan = DB::table('penjualan')
            //     ->join('kas_bank', 'penjualan.pembayaran', '=', 'kas_bank.kode_akun')
            //     ->select('kas_bank.kode_akun', 'total_pemasukan')
            //     ->groupBy('kas_bank.kode_akun')
            //     ->get();

            // Total saldo akhir / total semua saldo akhir * 100%
            $chart['uang_masuk'] = DB::table('kas_bank')
                ->whereYear('kas_bank.created_at', date('Y'))
                ->sum('uang_masuk');

            $chart['uang_keluar'] = DB::table('kas_bank')
                ->whereYear('kas_bank.created_at', date('Y'))
                ->sum('uang_keluar');

            return view('pages.kasdanbank.index', [
                'kas_bank' => $kasdanbank,
                'chart' => $chart,
                'kategoriAkun' => $kategoriAkun,
                'subakunKategori' => $subakunKategori,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all datas failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function getKategoriAkun()
    {
        $kategori_akun = Kategori_akun::all();
        return response()->json($kategori_akun);
    }

    public function getSubkategori(Request $request)
    {
        $kategori = $request->input('kategori'); // Ambil kategori dari request
        $subkategori = Akun::where('kategori_akun', $kategori)
            ->groupBy('subakun')
            ->get();

        return response()->json($subkategori); // Kembalikan sebagai JSON
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_akun' => 'string',
                'kode_akun' => 'string',
                'kategori_akun' => 'string',
                'subakun' => 'string',
            ]);

            Kasdanbank::create($request->all());

            Alert::success('Data Added!', 'Data Created Successfully');
            return redirect()->route('kasdanbank.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = Kasdanbank::find($id);

        // Jika data tidak ditemukan
        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    public function update(Request $request, kasdanbank $kasdanbank)
    {
        try {
            $request->validate([
                'nama_akun' => 'string',
                'kode_akun' => 'string',
                'kategori_akun' => 'string',
                'subakun' => 'string',
            ]);

            $kasdanbank->update($request->all());

            Alert::success('Data Edited!', 'Data Edited Successfully');
            return redirect()->route('kasdanbank.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(kasdanbank $kasdanbank)
    {
        try {
            $kasdanbank->delete();
            Alert::success('Data Deleted!', 'Data Deleted Successfully');
            return redirect()->route('kasdanbank.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
