<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modal;
use App\Models\Akun;
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

        $akun = Akun::paginate(10);
        // dd($akun);
        return view('pages.akun.index', compact('akun'));
    }

    public function getKategoriAkun()
    {
        $kategori_akun = Kategori_akun::all();
        return response()->json($kategori_akun);
    }


    public function getSubAkunByKategori(Request $request)
    {
        $subakun = Subakun::where('id_kategori_akun', $request->id_kategori)->get();
        return response()->json($subakun);
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

            Akun::create($request->all());

            Alert::success('Data Added!', 'Data Created Successfully');
            return redirect()->route('akun.index');
            // dd($data_akun);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Akun $akun)
    {
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
