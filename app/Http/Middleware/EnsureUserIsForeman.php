<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsForeman
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $role = strtolower(preg_replace('/[\s_\-]/', '', $user->role ?? ''));
        if (! $user || ! str_contains($role, 'foreman')) {
            abort(403, 'Unauthorized - hanya Foreman yang boleh mengakses.');
        }

        return $next($request);
    }
}