<?php

namespace App\Http\Middleware;

use App\Support\SecurityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogAuthenticatedActivity
{
    private const EXCLUDED_ROUTE_NAMES = [
        'superadmin.notifications.unreadCount',
        'admin.notifications.unreadCount',
        'student.notifications.unreadCount',
        'superadmin.exams.monitor.data',
        'superadmin.stream.poll',
        'superadmin.stream.signal',
        'admin.exams.monitor.data',
        'admin.stream.poll',
        'admin.stream.signal',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$this->shouldLog($request, $response->getStatusCode())) {
            return $response;
        }

        [$guard, $user] = $this->resolveActor();

        if (!$guard || !$user) {
            return $response;
        }

        $route = $request->route();
        $routeName = $route?->getName() ?? 'unknown';
        $event = $request->isMethod('GET') ? 'page_visited' : 'action_performed';

        SecurityLogger::log(
            $guard,
            $user->id,
            $event,
            sprintf('%s %s', $request->method(), $routeName),
            [
                'method' => $request->method(),
                'path' => $request->path(),
                'route_name' => $routeName,
                'role' => $user->role ?? ($guard === 'student' ? 'student' : $guard),
                'status_code' => $response->getStatusCode(),
            ]
        );

        return $response;
    }

    private function shouldLog(Request $request, int $statusCode): bool
    {
        $route = $request->route();

        if (!$route || in_array($route->getName(), self::EXCLUDED_ROUTE_NAMES, true)) {
            return false;
        }

        if ($statusCode >= 400) {
            return false;
        }

        if (!$request->isMethod('GET') && !$request->isMethod('POST') && !$request->isMethod('PUT') && !$request->isMethod('PATCH') && !$request->isMethod('DELETE')) {
            return false;
        }

        return true;
    }

    private function resolveActor(): array
    {
        if (Auth::guard('superadmin')->check()) {
            return ['superadmin', Auth::guard('superadmin')->user()];
        }

        if (Auth::guard('admin')->check()) {
            return ['admin', Auth::guard('admin')->user()];
        }

        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            if (($user->role ?? null) === 'student') {
                return ['student', $user];
            }

            return ['web', $user];
        }

        return [null, null];
    }
}
