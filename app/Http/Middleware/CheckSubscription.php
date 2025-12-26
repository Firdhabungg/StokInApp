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

        // Skip if no toko
        if (!$user || !$user->toko) {
            return $next($request);
        }

        $toko = $user->toko;

        // Check if subscription is expired
        if ($toko->isSubscriptionExpired()) {
            // Allow access to subscription routes
            if ($request->routeIs('subscription.*') || $request->routeIs('logout')) {
                return $next($request);
            }

            return redirect()->route('subscription.expired');
        }

        return $next($request);
    }
}
