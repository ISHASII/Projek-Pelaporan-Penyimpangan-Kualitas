<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsQc
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $role = strtolower(preg_replace('/[\s_\-]/', '', $user->role ?? ''));
        if (! $user || ! str_contains($role, 'qc')) {
            abort(403, 'Unauthorized - hanya QC yang boleh mengakses.');
        }

        return $next($request);
    }
}