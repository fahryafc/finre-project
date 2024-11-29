<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\Akun;
use App\Models\Kontak;
use App\Models\Pajak;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Log;

class ProdukdaninventoriController extends Controller
{
    public function index()
    {
        $title = 'Hapus Data!';
        $text = "Apakah kamu yakin menghapus data ini ?";
        confirmDelete($title, $text);

        try {
            // Ambil data untuk ditampilkan di tabel produk
            $produk = DB::table('produk')->paginate(5);
            $satuan = DB::table('satuan')->get();
            $kategori = DB::table('kategori')->get();
            $akun = DB::table('akun')->get();
            $pemasoks = DB::table('kontak')->where('jenis_kontak', '=', 'vendor')->get();

            // Hitung jumlah produk tersedia, hampir habis, habis, dan total produk
            $produkTersedia = DB::table('produk')->where('kuantitas', '>', 0)->count();
            $produkHampirHabis = DB::table('produk')->where('kuantitas', '<=', 3)->count();
            $produkHabis = DB::table('produk')->where('kuantitas', '=', 0)->count();
            $totalProduk = DB::table('produk')->sum('kuantitas');

            // Kirimkan data ke view
            return view('pages.produkdaninventori.index', [
                'produk' => $produk,
                'satuan' => $satuan,
                'kategori' => $kategori,
                'akun' => $akun,
                'pemasoks' => $pemasoks,
                'produkTersedia' => $produkTersedia,
                'produkHampirHabis' => $produkHampirHabis,
                'produkHabis' => $produkHabis,
                'totalProduk' => $totalProduk,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all data produk failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $pemasok = Kontak::where('id_kontak', $request->pemasok)->first();
        try {
            $data = Produk::create([
                'id_kontak'         => $request->pemasok,
                'pemasok'           => $pemasok->nama_kontak,
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
                'jns_pajak'         => 'PPN',
                'persen_pajak'	    => '11',
                'nominal_pajak'     => $request->nominal_pajak,
                'total_transaksi'   => $request->total_transaksi,
            ]);
            
            // var_dump($data);
            // die;
            if($request->nominal_pajak != NULL || $request->nominal_pajak != ''){
            // Masukkan data ke tabel pajak
                DB::table('pajak_ppn')->insert([
                    'jenis_transaksi'   => 'penjualan',
                    'keterangan'        => $data->nama_produk,
                    'nilai_transaksi'   => $data->harga_beli * $data->kuantitas,
                    'persen_pajak'      => $data->persen_pajak,
                    'jenis_pajak'       => 'Pajak Masukan',
                    'saldo_pajak'       => $data->nominal_pajak,
                ]);
            }
            Alert::success('Data Added!', 'Data Created Successfully');
            return redirect()->route('produkdaninventori.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    public function update(request $request, string $id_produk)
    {
        // Ambil pemasok berdasarkan id_kontak
        $pemasok = Kontak::where('id_kontak', $request->id_kontak)->first();

        try {
            // Cari produk berdasarkan ID dan update datanya
            $produk = Produk::findOrFail($id_produk);
            $produk->update([
                'pemasok'           => $pemasok->nama_kontak,
                'id_kontak'         => $request->id_kontak,
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
                'jns_pajak'         => 'PPN',
                'persen_pajak'      => 11,
                'nominal_pajak'     => $request->nominal_pajak, // Hitung nominal pajak
                'total_transaksi'     => $request->total_transaksi,
            ]);

            if($data->nominal_pajak != NULL || $data->nominal_pajak != ''){
            // Masukkan data ke tabel pajak
                DB::table('pajak_ppn')->insert([
                    'jenis_transaksi'   => 'penjualan',
                    'keterangan'        => $data->nama_produk,
                    'nilai_transaksi'   => $data->harga_beli * $data->kuantitas,
                    'persen_pajak'      => $data->persen_pajak,
                    'jenis_pajak'       => 'Pajak Masukan',
                    'saldo_pajak'       => $data->nominal_pajak,
                ]);
            }

            // Redirect dengan pesan sukses
            Alert::success('Data Edited!', 'Data Edited Successfully');
            return redirect()->route('produkdaninventori.index');
        } catch (\Exception $e) {
            // Redirect dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
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
