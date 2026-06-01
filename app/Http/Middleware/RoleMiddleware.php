<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized: Lu belum login bre!'], 401);
        }

        // 2. Cek apakah role user ada di daftar role yang diizinkan (admin/petugas)
        // Kita pakai ...$roles supaya bisa nerima banyak role sekaligus
        if (!in_array($request->user()->role, $roles)) {
            return response()->json(['message' => 'Access denied: Lu gak punya izin masuk sini!'], 403);
        }

        return $next($request);
    }
}
