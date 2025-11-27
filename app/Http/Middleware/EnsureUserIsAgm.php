<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAgm
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $role = strtolower(preg_replace('/[\s_\-]/', '', $user->role ?? ''));
        if (! $user || (! str_contains($role, 'agm') && ! str_contains($role, 'assistantgeneralmanager') )) {
            abort(403);
        }
        return $next($request);
    }
}
