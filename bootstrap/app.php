<?php

use App\Http\Middleware\CheckBoutiqueActive;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\EnsureUserIsGerant;
use App\Http\Middleware\EnsureUserIsSuperAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'gerant' => EnsureUserIsGerant::class,
            'super_admin' => EnsureUserIsSuperAdmin::class,
            'role' => EnsureUserHasRole::class,
        ]);

        $middleware->appendToGroup('web', CheckBoutiqueActive::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();