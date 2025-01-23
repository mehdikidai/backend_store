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
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {

        $userRoles = $request->user()->roles()->pluck('name');

        if (!$userRoles->intersect($roles)->count()) {
            return response()->json(['message' => 'Access denied: insufficient permissions'], 403);
        }

        return $next($request);

    }
}
