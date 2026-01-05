<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        $user = $request->user();

        // Skip for super_admin
        if ($user && $user->role === 'super_admin') {
            return $next($request);
        }

        // Skip if no user or no toko
        if (!$user || !$user->toko) {
            return $next($request);
        }

        $toko = $user->toko;
        
        // Allow access to subscription routes, logout, and profil
        $allowedRoutes = ['subscription.*', 'logout', 'profil.*'];
        foreach ($allowedRoutes as $pattern) {
            if ($request->routeIs($pattern)) {
                return $next($request);
            }
        }

        // Check if user has NO subscription at all (new registration)
        $hasAnySubscription = $toko->subscriptions()->exists();
        
        if (!$hasAnySubscription) {
            return redirect()->route('subscription.index')
                ->with('info', 'Silakan pilih paket langganan untuk memulai menggunakan StokIn.');
        }

        // Check if subscription is expired
        if ($toko->isSubscriptionExpired()) {
            return redirect()->route('subscription.expired');
        }

        return $next($request);
    }
}
