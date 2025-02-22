<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Kontak;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class PajakController extends Controller
{
    public function index(): View
    {
        try {
            $pajak = DB::table('pajak')->get();

            return view('pages.pajak.index', [
                'pajak' => $pajak
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all datas pajak failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function ppn(Request $request)
    {
        try {
            $from_date = $request->input('from');
            $to_date = $request->input('to');

            $pajak_ppn = DB::table('pajak_ppn')
                ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                    return $query->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
                })
                ->get();

            $chart['masukan'] = DB::table('pajak_ppn')
                ->whereYear('created_at', date('Y'))
                ->where('jenis_pajak', 'Pajak Masukan')
                ->sum('saldo_pajak');

            $chart['keluaran'] = DB::table('pajak_ppn')
                ->whereYear('created_at', date('Y'))
                ->where('jenis_pajak', 'Pajak Keluaran')
                ->sum('saldo_pajak');

            return view('pages.pajak.pajak_ppn', [
                'pajak_ppn' => $pajak_ppn,
                'chart' => $chart
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all datas pajak failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function pph(Request $request): View
    {
        try {
            $from_date = $request->input('from');
            $to_date = $request->input('to');

            $pajak_pph = DB::table('pajak_pph')
                ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                    return $query->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
                })
                ->get();

            $months = range(1, 12);

            $getData = DB::table('pajak_pph')
                ->whereYear('created_at', date('Y'))
                ->select(
                    DB::raw('SUM(pajak_pph.pph_terutang) as bersih_diterima'),
                    DB::raw('MONTH(pajak_pph.created_at) as bulan')
                )
                ->groupBy('bulan')
                ->pluck('bersih_diterima', 'bulan') // Ambil sebagai key-value (bulan => total_hutang)
                ->toArray();

            $chart = array_map(function ($month) use ($getData) {
                return $getData[$month] ?? 0;
            }, $months);

            return view('pages.pajak.pajak_pph', [
                'pajak_pph' => $pajak_pph,
                'chart' => $chart
            ]);
        } catch (\Exception $e) {
            return view('pages.pajak.pajak_pph', [
                'message' => 'Get all data pajak failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function ppnbm(Request $request): view
    {
        try {
            $from_date = $request->input('from');
            $to_date = $request->input('to');

            $pajak_ppnbm = DB::table('pajak_ppnbm')
                ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                    return $query->whereBetween('tgl_transaksi', [$from_date, $to_date]);
                })
                ->paginate(5);

            // Chart
            // Diambil dari table ppnbm kolom ppnbm_dikenakan dan jenis_pajak
            $jenisPajakList = [];
            $jenisPajakValue = [];

            $getData = DB::table('pajak_ppnbm')
                ->whereYear('created_at', date('Y'))
                ->select(
                    DB::raw('SUM(pajak_ppnbm.ppnbm_dikenakan) as ppnbm_dikenakan'),
                    DB::raw('jenis_pajak')
                )
                ->groupBy('jenis_pajak')
                ->get();

            foreach ($getData as $data) {
                array_push($jenisPajakList, $data->jenis_pajak);
                array_push($jenisPajakValue, $data->ppnbm_dikenakan);
            }

            return view('pages.pajak.pajak_ppnbm', [
                'pajak_ppnbm' => $pajak_ppnbm,
                'jenisPajakList' => $jenisPajakList,
                'jenisPajakValue' => $jenisPajakValue
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Get all datas pajak failed',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
