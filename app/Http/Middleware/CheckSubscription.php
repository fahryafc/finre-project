<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user->subscription) {
            if ($user->subscription->ends_at && Carbon::now()->gt($user->subscription->ends_at)) {
                // Langganan sudah kadaluarsa
                return redirect('/daftar-paket');
            }

            if ($user->subscription->status !== 'active') {
                // Langganan tidak aktif
                return redirect('/daftar-paket');
            }
        } else {
            // Tidak ada langganan
            return redirect('/daftar-paket');
        }

        return $next($request);
    }
}
