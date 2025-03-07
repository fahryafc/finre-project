<?php

namespace App\Http\Middleware;

use App\Models\Invites;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveMember
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
            // Jika user terinvite statusnya bukan accepted, maka kembalikan ke halaman daftar paket
            $check_active_member = Invites::where('email', $user->email)->where('status', '!=', 'accepted')->exists();

            if ($check_active_member) {
                return redirect('/daftar-paket');
            }
        }

        return $next($request);
    }
}
