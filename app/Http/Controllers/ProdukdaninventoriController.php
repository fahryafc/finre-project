<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
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
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Repositories\JurnalRepository;
use Illuminate\Support\Facades\Auth;

class ProdukdaninventoriController extends Controller
{
    protected $jurnalRepository;

    public function __construct(JurnalRepository $jurnalRepository)
    {
        $this->jurnalRepository = $jurnalRepository;
    }

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

    public function check_hampir_habis(Request $request)
    {
        $produkHampirHabis = DB::table('produk')->where('kuantitas', '<=', $request->hampirHabis)->count();

        return response()->json([
            'status' => 'success',
            'message' => 'Get all data produk success',
            'data' => $produkHampirHabis,
        ]);
    }

    public function create()
    {
        try {
            // if ($request->jns_pajak === 'ppnbm') {
            //     $kodeReff = Helper::generateKodeReff('PPNBM');
            // } elseif ($request->jns_pajak === 'ppn') {
            //     $kodeReff = Helper::generateKodeReff('PPN');
            // } elseif ($request->jns_pajak === 'pph') {
            //     $kodeReff = Helper::generateKodeReff('PPH');
            // } else {
            //     $kodeReff = null;
            // }

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
            return view('pages.produkdaninventori.create', [
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
        if ($request->jns_pajak === 'ppnbm') {
            $kodeReff = Helper::generateKodeReff('PPNBM');
        } elseif ($request->jns_pajak === 'ppn') {
            $kodeReff = Helper::generateKodeReff('PPN');
        } elseif ($request->jns_pajak === 'pph') {
            $kodeReff = Helper::generateKodeReff('PPH');
        } else {
            $kodeReff = null;
        }
        $pemasok = Kontak::where('id_kontak', $request->pemasok)->first();
        
        db::beginTransaction();
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
                'jns_pajak'         => $request->jns_pajak,
                'persen_pajak'        => $request->persen_pajak,
                'nominal_pajak'     => $request->nominal_pajak,
                'total_transaksi'   => $request->total_transaksi,
            ]);

            if ($data->jns_pajak == 'ppn') {
                DB::table('pajak_ppn')->insert([
                    'kode_reff'         => $data->kode_reff_pajak,
                    'jenis_transaksi'   => 'penjualan',
                    'keterangan'        => $data->produk,
                    'nilai_transaksi'   => $data->harga * $data->kuantitas,
                    'persen_pajak'      => $data->persen_pajak,
                    'jenis_pajak'       => 'Pajak Keluaran',
                    'saldo_pajak'       => $data->total_pemasukan * ($data->persen_pajak / 100),
                ]);
            } elseif ($data->jns_pajak == 'ppnbm') {
                DB::table('pajak_ppnbm')->insert([
                    'kode_reff'             => $data->kode_reff_pajak,
                    'deskripsi_barang'      => $data->produk,
                    'harga_barang'          => $data->harga,
                    'tarif_ppnbm'           => $data->persen_pajak,
                    'ppnbm_dikenakan'       => $data->total_pemasukan * ($data->persen_pajak / 100),
                    'jenis_pajak'           => "Pajak Masukan",
                    'tgl_transaksi'         => $data->tanggal,
                ]);
            }

            // Insert Jurnal
            $this->jurnalRepository->storeProduk($data);

            DB::commit();
            Alert::success('Data Added!', 'Data Created Successfully');
            return redirect()->route('produkdaninventori.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            // Ambil data untuk ditampilkan di tabel produk
            $produk = DB::table('produk')->where('id_produk', '=', $id)->first();
            $produk->tanggal = Carbon::parse($produk->tanggal)->format('d-m-Y');
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
            return view('pages.produkdaninventori.edit', [
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

    public function update(request $request, string $id_produk)
    {
        // Ambil pemasok berdasarkan id_kontak
        $pemasok = Kontak::where('id_kontak', $request->id_kontak)->first();

        db::beginTransaction();
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

            if ($data->nominal_pajak != NULL || $data->nominal_pajak != '') {
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

            // Insert Jurnal
            $this->jurnalRepository->storeProduk($produk);

            // Redirect dengan pesan sukses
            DB::commit();
            Alert::success('Data Edited!', 'Data Edited Successfully');
            return redirect()->route('produkdaninventori.index');
        } catch (\Exception $e) {
            DB::rollBack();
            // Redirect dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(string $id_produk)
    {
        try {
            $produk = Produk::find($id_produk);

            // Delete Jurnal
            $prefix = Produk::CODE_JURNAL;
            $jurnal = Jurnal::where('code',$prefix)->where('no_reff', $produk->id_produk)->first();
            if ($jurnal) {
                $this->jurnalRepository->delete($jurnal->id_jurnal);
            }
            
            $produk->delete();
            Alert::success('Data Deleted!', 'Data Deleted Successfully');
            return redirect()->route('produkdaninventori.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }
}
