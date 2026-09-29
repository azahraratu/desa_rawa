<?php

use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // Project ini tidak memakai Event Listener sama sekali, jadi auto-discovery
    // event bawaan Laravel dimatikan. Ini juga menghindari error
    // "Target class [files] does not exist" yang bisa muncul saat `package:discover`
    // pada beberapa environment Windows/PHP tertentu.
    ->withEvents(discover: false)
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        $middleware->append(SecurityHeaders::class);

        $middleware->redirectGuestsTo(function ($request) {
            return $request->is('admin/*') ? route('admin.login') : route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
