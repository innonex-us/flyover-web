<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-API-TOKEN') ?? $request->query('token');

        if (!$token || $token !== config('app.api_token')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Please provide a valid API token.'
            ], 401);
        }

        return $next($request);
    }
}
