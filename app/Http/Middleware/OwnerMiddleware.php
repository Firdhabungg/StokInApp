<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OwnerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Akses ditolak. Hanya owner yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}