<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFreeTrial
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            // Pastikan middleware tidak berjalan pada halaman daftar paket
            if ($request->is('daftar-paket')) {
                return $next($request);
            }

            // Cek apakah user memiliki paket Free Trial yang sudah berakhir
            $check_free_trial = Subscription::where('user_id', $user->id)
                ->where('nama_paket', 'Free Trial')
                ->orderBy('ends_at', 'desc')
                ->first();

            // Jika user memiliki Free Trial tapi sudah habis, redirect ke daftar paket
            if ($check_free_trial && $check_free_trial->ends_at < Carbon::now()) {
                return redirect('/daftar-paket');
            }
        }

        return $next($request);
    }
}
