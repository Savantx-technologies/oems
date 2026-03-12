<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            abort(403, 'Unauthorized.');
        }

        if (empty($roles) || $admin->hasAnyRole($roles)) {
            return $next($request);
        }

        abort(403, 'You do not have permission to access this section.');
    }
}
