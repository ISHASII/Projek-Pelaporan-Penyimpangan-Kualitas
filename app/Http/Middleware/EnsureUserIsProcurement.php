<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsProcurement
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $role = strtolower(preg_replace('/[\s_\-]/', '', $user->role ?? ''));
        if (! $user || (! str_contains($role, 'procure') && ! str_contains($role, 'procurement') && ! str_contains($role, 'purchasing') )) {
            abort(403);
        }
        return $next($request);
    }
}
