<?php

// Load config/config.php and populate environment variables (replaces .env)
(static function (): void {
    $configFile = dirname(__DIR__) . '/config/config.php';
    if (file_exists($configFile)) {
        foreach (require $configFile as $key => $value) {
            if (! isset($_ENV[$key]) && ! isset($_SERVER[$key])) {
                putenv("$key=$value");
                $_ENV[$key]    = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
})();

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'two-factor' => \App\Http\Middleware\TwoFactorMiddleware::class,
            'api.token' => \App\Http\Middleware\ApiTokenMiddleware::class,
        ]);
        
        $middleware->web(\App\Http\Middleware\VisitorTracking::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
