<?php

namespace App\Http\Middleware;

use App\Models\RiwayatRegistrasi;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAcademicRegisteredMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $belum_registrasi = RiwayatRegistrasi::where('username', Auth::user()->username)->where('status_registrasi', 'pending')->first();
        if ($belum_registrasi) {
            return redirect('/registrasi-akademik')->with('message', 'Untuk dapat menggunakan aplikasi segera lakukan registrasi ulang');
        }
        return $next($request);
    }
}
