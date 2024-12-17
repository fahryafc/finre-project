<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Helper
{
    public static function generateKodeReff(string $prefix): string
    {
        do {
            $kodeReff = $prefix . '-' . strtoupper(Str::random(6));
        } while (
            DB::table('pajak_ppnbm')->where('kode_reff', $kodeReff)->exists() ||
            DB::table('pajak_ppn')->where('kode_reff', $kodeReff)->exists() ||
            DB::table('pajak_pph')->where('kode_reff', $kodeReff)->exists()
        );

        return $kodeReff;
    }
}
