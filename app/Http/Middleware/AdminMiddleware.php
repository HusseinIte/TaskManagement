<?php

namespace App\Http\Middleware;

use App\Enums\RoleUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === RoleUser::Admin) {
            return $next($request);
        }
        $message = 'Unauthorized access. Admin privileges are required.';
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $message,
            ], 403); // 403 Forbidden status code
        }

    }
}
