<?php

namespace App\Http\Middleware;

use App\Enum\Roles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $isAdmin = $request->user()->roles()->pluck('name')->contains(Roles::Admin->value);

        if (!$isAdmin) {
            return response()->json(['message'=>'is not admin'],403);
        }

        return $next($request);
    }
}
