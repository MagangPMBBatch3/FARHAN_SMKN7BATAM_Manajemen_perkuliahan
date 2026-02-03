<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GraphiqlAdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            abort(404);
        }

        $role = strtolower((string) auth()->user()->role);

        if ($role !== 'Admin') {
            abort(404);
        }

        return $next($request);
    }
}
