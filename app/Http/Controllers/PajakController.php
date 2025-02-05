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

    public function ppn(Request $request): View
    {
        try {
            $from_date = $request->input('from');
            $to_date = $request->input('to');

            $pajak_ppn = DB::table('pajak_ppn')
                ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                    return $query->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
                })
                ->get();

            return view('pages.pajak.pajak_ppn', [
                'pajak_ppn' => $pajak_ppn
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

            return view('pages.pajak.pajak_pph', [
                'pajak_pph' => $pajak_pph
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

            return view('pages.pajak.pajak_ppnbm', [
                'pajak_ppnbm' => $pajak_ppnbm
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
