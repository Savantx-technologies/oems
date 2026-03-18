<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminSectionAccess
{
    public function handle(Request $request, Closure $next, string $section): Response
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            abort(403, 'Unauthorized.');
        }

        if ($admin->canAccessSidebarSection($section)) {
            return $next($request);
        }

        abort(403, 'You do not have permission to access this section.');
    }
}
