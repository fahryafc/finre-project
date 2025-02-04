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
            $filter_date = $request->input('date');

            $pajak_ppn = DB::table('pajak_ppn')
                ->when($filter_date, function ($query, $filter_date) {
                    return $query->whereDate('created_at', $filter_date);
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

    public function pph(): View
    {
        try {
            $pajak_pph = DB::table('pajak_pph')->get();

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

    public function ppnbm(): view
    {

        try {
            $pajak_ppnbm = DB::table('pajak_ppnbm')->paginate(5);

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
