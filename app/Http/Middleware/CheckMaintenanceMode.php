<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Setting::get('maintenance_mode') !== '1') {
            return $next($request);
        }

        // Always allow: admin panel, auth routes, API
        if (
            $request->routeIs('admin.*') ||
            $request->routeIs('login') ||
            $request->routeIs('register') ||
            $request->routeIs('password.*') ||
            $request->is('api/*')
        ) {
            return $next($request);
        }

        // Allow logged-in admins through
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        abort(503);
    }
}
