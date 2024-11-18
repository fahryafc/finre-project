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

    public function ppn(): View
    {
        try {
            $pajak_ppn = DB::table('pajak_ppn')->get();

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
            $pajak = DB::table('pajak')->get();

            return view('pages.pajak.pajak_pph', [
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

    public function ppnbm(): View
    {
        try {
            $pajak = DB::table('pajak')->get();

            return view('pages.pajak.pajak_ppnbm', [
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
}
