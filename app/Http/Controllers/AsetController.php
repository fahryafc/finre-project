<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\aset;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Exception;

class AsetController extends Controller
{
    public function index()
    {
        $title = 'Hapus Data!';
        $text = "Apakah kamu yakin menghapus data ini ?";
        confirmDelete($title, $text);

        try {
            $produk = DB::table('produk')->get();
            return view('pages.produkdaninventori.index', ['produk' => $produk]);
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
        try {
            Produk::create([
                'pemasok'           => $request->pemasok,
                'no_hp'             => $request->no_hp,
                'nm_perusahaan'     => $request->nm_perusahaan,
                'email'             => $request->email,
                'alamat'            => $request->alamat,
                'nama_produk'       => $request->nama_produk,
                'satuan'            => $request->satuan,
                'kategori'          => $request->kategori,
                'kuantitas'         => $request->kuantitas,
                'kode_sku'          => $request->kode_sku,
                'tanggal'           => $request->tanggal,
                'harga_beli'        => $request->harga_beli,
                'harga_jual'        => $request->harga_jual,
                'akun_pembayaran'   => $request->akun_pembayaran,
                'masuk_akun'        => $request->masuk_akun,
            ]);
            Alert::success('Data Added!', 'Data Created Successfully');
            return redirect()->route('produkdaninventori.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    public function update(request $request, string $id_produk)
    {
        $findProduk = Produk::find($id_produk);

        try {
            $findProduk->update($request->all());
            Alert::success('Data Edited!', 'Data Edited Successfully');
            return redirect()->route('produkdaninventori.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    public function destroy(string $id_produk)
    {
        try {
            $produk = Produk::find($id_produk);
            $produk->delete();
            Alert::success('Data Deleted!', 'Data Deleted Successfully');
            return redirect()->route('produkdaninventori.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }
}
