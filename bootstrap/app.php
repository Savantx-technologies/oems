<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureAdminRole;
use App\Http\Middleware\EnsureAdminSectionAccess;
use App\Http\Middleware\LogAuthenticatedActivity;
use App\Http\Middleware\EnsureSuperAdminSectionAccess;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            LogAuthenticatedActivity::class,
        ]);

        $middleware->alias([
            'admin.role' => EnsureAdminRole::class,
            'admin.section' => EnsureAdminSectionAccess::class,
            'superadmin.section' => EnsureSuperAdminSectionAccess::class,
            
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
