<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN memiliki peran 'admin'
        if (Auth::check() && Auth::user()->isAdmin()) {
            // Jika ya, izinkan akses ke halaman berikutnya
            return $next($request);
        }

        // Jika tidak, kembalikan ke halaman dashboard default atau tampilkan error 403
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman ini.');
        // atau abort(403, 'UNAUTHORIZED ACTION.');
    }
}