<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modal;
use App\Models\Kontak;
use App\Models\Kasdanbank;
use App\Models\Jurnal;
use App\Models\Akun;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Repositories\JurnalRepository;
use Illuminate\Support\Facades\Auth;

class ModalController extends Controller
{
    protected $jurnalRepository;

    public function __construct(JurnalRepository $jurnalRepository)
    {
        $this->jurnalRepository = $jurnalRepository;
    }

    public function index(Request $request)
    {
        $user_id = 1; // Auth::user()->id;
        $title = 'Hapus Data!';
        $text = "Apakah kamu yakin menghapus data ini ?";
        confirmDelete($title, $text);

        $filter = $request->input('filter');

        $modal = Modal::when($filter, function ($query) use ($filter) {
            return $query->where('jns_transaksi', '=', $filter);
        })
        ->where('user_id', $user_id)
        ->paginate(5);
        $kasdanbank = DB::table('akun')->where('type', '=', 'Kas & Bank')->get();
        $pemodal = DB::table('kontak')->where('jenis_kontak', '=', 'investor')->get();
        $jml_modal_disetor = Modal::where('jns_transaksi', '=', 'Penyetoran Modal')->sum('nominal');
        $jml_penarikan_deviden = Modal::where('jns_transaksi', '=', 'Penarikan Dividen')->sum('nominal');
        // dd($jml_modal_disetor);
        return view('pages.modal.index', [
            'modal' => $modal,
            'kas_bank' => $kasdanbank,
            'pemodal' => $pemodal,
            'jml_modal_disetor' => $jml_modal_disetor,
            'jml_penarikan_deviden' => $jml_penarikan_deviden,
        ]);
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
        db::beginTransaction();
        try {
            // Ambil nilai jenis transaksi dan nominal
            $jnsTransaksi = $request->input('jns_transaksi');
            $nominal = $this->parseRupiahToNumber($request->input('nominal'));

            // Tentukan kode akun berdasarkan jenis transaksi
            $kodeAkun = $jnsTransaksi === 'Penyetoran Modal' ? $request->input('masuk_akun') : $request->input('credit_akun');

            // Validasi bahwa kode akun harus ada di tabel kas_bank
            $akun = Akun::where('type','Kas & Bank')->where('kode_akun', $kodeAkun)->first();
            if (!$akun) {
                return redirect()->back()->with('error', 'Kode Akun tidak valid!');
            }

            // Cek jika jenis transaksi adalah 'Penarikan Dividen', pastikan nominal tidak melebihi saldo yang ada
            if ($jnsTransaksi === 'Penarikan Dividen' && $nominal > $akun->saldo) {
                Alert::warning('Penarikan Gagal!', 'Saldo Tidak Mencukupi');
                return redirect()->back()->with('error', 'Nominal penarikan dividen melebihi saldo yang tersedia!');
            }

            // dd($request->all());
            // dd($jnsTransaksi);

            // Jika validasi lolos, buat record pada tabel modal
            $modal = Modal::create([
                'tanggal' => Carbon::parse($request->input('tanggal')),
                'jns_transaksi' => $jnsTransaksi,
                'nama_badan' => $request->input('nama_badan'),
                'nominal' => $nominal,
                'masuk_akun' => $jnsTransaksi === 'Penyetoran Modal' ? $request->input('masuk_akun') : 0,
                'credit_akun' => $jnsTransaksi === 'Penarikan Dividen' ? $request->input('credit_akun') : 0,
                'keterangan' => $request->input('keterangan'),
                'user_id' => 1, // Auth::user()->id,
            ]);
// dd($request->all());
            // Update saldo atau uang_keluar di tabel kas_bank berdasarkan jenis transaksi
            if ($jnsTransaksi === 'Penyetoran Modal') {
                $akun->saldo += $nominal;  // Tambahkan nominal ke saldo jika penyetoran modal
            } elseif ($jnsTransaksi === 'Penarikan Dividen') {
                // $akun->saldo -= $nominal;  // Kurangi saldo jika penarikan dividen
                $akun->uang_keluar += $nominal;  // Tambahkan nominal ke uang_keluar
            }
            $akun->save();

            // Insert Jurnal
            $this->jurnalRepository->storeModal($modal);

            // Tampilkan pesan sukses
            DB::commit();
            Alert::success('Data Added!', 'Data Created Successfully');
            return redirect()->route('modal.index');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            // Jika terjadi kesalahan, kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Modal $modal)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'jns_transaksi' => 'required|string',
                'nama_badan' => 'required|string',
                'nominal' => 'required|numeric',
                'keterangan' => 'nullable|string',
            ]);

            $modal->update($request->all());

            Alert::success('Data Added!', 'Data Edited Successfully');
            return redirect()->route('modal.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Modal $modal)
    {
        try {
            // Delete Jurnal
            $prefix = Modal::CODE_JURNAL;
            $jurnal = Jurnal::where('code',$prefix)->where('no_reff', $modal->id_modal)->first();
            if ($jurnal) {
                $this->jurnalRepository->delete($jurnal->id_jurnal);
            }
            
            $modal->delete();
            Alert::success('Data Deleted!', 'Data Deleted Successfully');
            return redirect()->route('modal.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
