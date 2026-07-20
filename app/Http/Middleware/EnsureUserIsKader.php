<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsKader
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'kader') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Kader.');
        }

        return $next($request);
    }
}
