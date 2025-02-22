<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modal;
use App\Models\Akun;
use App\Models\Kasdanbank;
use App\Models\Subakun;
use App\Models\Kategori_akun;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Exception;

class AkunController extends Controller
{
    public function index()
    {
        $title = 'Hapus Data!';
        $text = "Apakah kamu yakin menghapus data ini ?";
        confirmDelete($title, $text);

        $akun = Akun::orderBy('kategori_akun', 'asc')->get();
        return view('pages.akun.index', compact('akun'));
    }

    public function getKategoriAkun()
    {
        $kategori_akun = Kategori_akun::all();
        return response()->json($kategori_akun);
    }

    public function getSubAkunByKategori(Request $request)
    {
        $kategori = $request->input('kategori_akun'); // Ambil kategori dari request
        $subkategori = Akun::where('kategori_akun', $kategori)
            ->groupBy('subakun')
            ->get();

        return response()->json($subkategori);
    }

    public function store(Request $request)
    {

        try {
            $data_akun = Akun::where('id_kategori_akun', $request->kategori_akun)->first();
            $akun = Akun::create([
                'id_kategori_akun'      => $request->kategori_akun,
                'type'                  => "Akun",
                'nama_akun'             => $request->nama_akun,
                'kode_akun'             => $request->kode_akun,
                'kategori_akun'         => $data_akun->kategori_akun,
                'subakun'               => $data_akun->subakun,
            ]);

            Alert::success('Data Added!', 'Data Created Successfully');
            return redirect()->route('akun.index');
            // dd($data_akun);
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
        ]);
    }

    public function update(Request $request, Akun $akun)
    {
        // dd($akun);
        try {
            $request->validate([
                'nama_akun' => 'string',
                'kode_akun' => 'string',
                'kategori_akun' => 'string',
                'subakun' => 'string',
            ]);

            $akun->update($request->all());

            Alert::success('Data Edited!', 'Data Edited Successfully');
            return redirect()->route('akun.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Akun $akun)
    {
        try {
            $akun->delete();
            Alert::success('Data Deleted!', 'Data Deleted Successfully');
            return redirect()->route('akun.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
