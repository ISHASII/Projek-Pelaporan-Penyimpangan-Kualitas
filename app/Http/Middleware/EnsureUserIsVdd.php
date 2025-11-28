<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsVdd
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $role = strtolower(preg_replace('/[\s_\-]/', '', $user->role ?? ''));
        if (! $user || (! str_contains($role, 'vdd') && ! str_contains($role, 'vddhead') )) {
            abort(403);
        }
        return $next($request);
    }
}