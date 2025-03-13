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
use App\Models\Modal;
use App\Models\Jurnal;
use RealRashid\SweetAlert\Facades\Alert;
use Exception;

class KasdanbankController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Hapus Data!';
        $text = "Apakah kamu yakin menghapus data ini ?";
        confirmDelete($title, $text);

        try {
            $kasdanbank = Akun::where('type', 'Kas & Bank')
                ->leftJoinSub(
                    Modal::select(
                        DB::raw('jurnal_detail.id_akun as akun_id'),
                        DB::raw('SUM(jurnal_detail.debit) as saldo_awal')
                    )
                        ->leftJoin('jurnal', 'jurnal.no_reff', 'modal.id_modal')
                        ->leftJoin('jurnal_detail', 'jurnal_detail.id_jurnal', 'jurnal.id_jurnal')
                        ->where('modal.jns_transaksi', 'Penyetoran Modal')
                        ->where('jurnal.code', Modal::CODE_JURNAL)
                        ->groupBy('jurnal_detail.id_akun'),
                    'md',
                    function ($join) {
                        $join->on('akun.id_akun', '=', 'md.akun_id');
                    }
                )
                ->leftJoinSub(
                    Jurnal::select(
                        DB::raw('jurnal_detail.id_akun AS akun_id'),
                        DB::raw('SUM(jurnal_detail.debit) AS debit'),
                        DB::raw('SUM(jurnal_detail.kredit) AS kredit')
                    )
                        ->leftJoin('jurnal_detail', 'jurnal_detail.id_jurnal', 'jurnal.id_jurnal')
                        ->groupBy('jurnal_detail.id_akun'),
                    'jl',
                    function ($join) {
                        $join->on('akun.id_akun', '=', 'jl.akun_id');
                    }
                )
                ->orderBy('kategori_akun', 'asc')
                ->paginate(5);

            $uang_masuk = Jurnal::join('jurnal_detail', 'jurnal_detail.id_jurnal', 'jurnal.id_jurnal')
                ->join('akun', 'jurnal_detail.id_akun', 'akun.id_akun')
                ->where('akun.type', 'Kas & Bank')
                ->sum('jurnal_detail.debit');

            $uang_keluar = Jurnal::join('jurnal_detail', 'jurnal_detail.id_jurnal', 'jurnal.id_jurnal')
                ->join('akun', 'jurnal_detail.id_akun', 'akun.id_akun')
                ->where('akun.type', 'Kas & Bank')
                ->sum('jurnal_detail.kredit');

            $kategoriAkun = DB::table('kategori_akun')->get();
            $subakunKategori = DB::table('subakun_kategori')->get();

            // Total saldo akhir / total semua saldo akhir * 100%
            $chart['uang_masuk'] = $uang_masuk;
            $chart['uang_keluar'] = $uang_keluar;

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
        $kategori = Kategori_akun::where('nama_kategori', $request->kategori)
            ->first();
        $subkategori = Akun::where('kategori_akun', $kategori->nama_kategori)
            ->groupBy('subakun')
            ->get();

        return response()->json($subkategori); // Kembalikan sebagai JSON
    }

    public function store(Request $request)
    {
        try {
            $data_akun = Akun::where('kategori_akun', $request->kategori_akun)->first();
            $akun = Akun::create([
                'id_kategori_akun'      => $data_akun->id_kategori_akun,
                'type'                  => "Kas & Bank",
                'nama_akun'             => $request->nama_akun,
                'kode_akun'             => $request->kode_akun,
                'kategori_akun'         => $request->kategori_akun,
                'subakun'               => $data_akun->subakun,
            ]);
            // dd($akun);

            Alert::success('Data Added!', 'Data Created Successfully');
            return redirect()->route('kasdanbank.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = Akun::find($id);

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

    public function update(Request $request, Akun $kasdanbank)
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

    public function destroy(Akun $kasdanbank)
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
