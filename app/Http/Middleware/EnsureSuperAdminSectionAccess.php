<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdminSectionAccess
{
    public function handle(Request $request, Closure $next, string ...$sections): Response
    {
        $superAdmin = Auth::guard('superadmin')->user();

        if (!$superAdmin) {
            abort(403, 'Unauthorized.');
        }

        foreach ($sections as $section) {
            if ($superAdmin->canAccessSection($section)) {
                return $next($request);
            }
        }

        abort(403, 'You do not have permission to access this section.');
    }
}
