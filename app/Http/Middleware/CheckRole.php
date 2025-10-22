<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return $request->expectsJson()
                ? response()->json(['error' => 'Unauthenticated'], 401)
                : redirect()->route('login');
        }

        if (!auth()->user()->hasRole($role)) {
            return $request->expectsJson()
                ? response()->json(['error' => 'Unauthorized'], 403)
                : abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
