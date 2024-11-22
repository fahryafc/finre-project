<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modal;
use App\Models\Kontak;
use App\Models\Kasdanbank;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Exception;

class ModalController extends Controller
{
    public function index()
    {
        $title = 'Hapus Data!';
        $text = "Apakah kamu yakin menghapus data ini ?";
        confirmDelete($title, $text);

        $modal = Modal::paginate(5);
        $kasdanbank = DB::table('kas_bank')->get();
        $pemodal = DB::table('kontak')->where('jenis_kontak', '=', 'investor')->get();
        return view('pages.modal.index', [
            'modal' => $modal,
            'kas_bank' => $kasdanbank,
            'pemodal' => $pemodal,
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Ambil nilai jenis transaksi dan nominal
            $jnsTransaksi = $request->input('jns_transaksi');
            $nominal = $request->input('nominal');

            // Tentukan kode akun berdasarkan jenis transaksi
            $kodeAkun = $jnsTransaksi === 'Penyetoran Modal' ? $request->input('masuk_akun') : $request->input('credit_akun');

            // Validasi bahwa kode akun harus ada di tabel kas_bank
            $akun = Kasdanbank::where('kode_akun', $kodeAkun)->first();
            if (!$akun) {
                return redirect()->back()->with('error', 'Kode Akun tidak valid!');
            }

            // Cek jika jenis transaksi adalah 'Penarikan Dividen', pastikan nominal tidak melebihi saldo yang ada
            if ($jnsTransaksi === 'Penarikan Dividen' && $nominal > $akun->saldo) {
                Alert::warning('Penarikan Gagal!', 'Saldo Tidak Mencukupi');
                return redirect()->back()->with('error', 'Nominal penarikan dividen melebihi saldo yang tersedia!');
            }

            // dd($jnsTransaksi);

            // Jika validasi lolos, buat record pada tabel modal
            Modal::create([
                'tanggal' => $request->input('tanggal'),
                'jns_transaksi' => $jnsTransaksi,
                'nama_badan' => $request->input('nama_badan'),
                'nominal' => $nominal,
                'masuk_akun' => $jnsTransaksi === 'Penyetoran Modal' ? $request->input('masuk_akun') : null,
                'credit_akun' => $jnsTransaksi === 'Penarikan Dividen' ? $request->input('credit_akun') : null,
                'keterangan' => $request->input('keterangan'),
            ]);

            // Update saldo atau uang_keluar di tabel kas_bank berdasarkan jenis transaksi
            if ($jnsTransaksi === 'Penyetoran Modal') {
                $akun->saldo += $nominal;  // Tambahkan nominal ke saldo jika penyetoran modal
            } elseif ($jnsTransaksi === 'Penarikan Dividen') {
                // $akun->saldo -= $nominal;  // Kurangi saldo jika penarikan dividen
                $akun->uang_keluar += $nominal;  // Tambahkan nominal ke uang_keluar
            }
            $akun->save();

            // Tampilkan pesan sukses
            Alert::success('Data Added!', 'Data Created Successfully');
            return redirect()->route('modal.index');
        } catch (\Exception $e) {
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
            $modal->delete();
            Alert::success('Data Deleted!', 'Data Deleted Successfully');
            return redirect()->route('modal.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
