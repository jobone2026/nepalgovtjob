<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'page.cache' => \App\Http\Middleware\PageCache::class,
        ]);
        
        // Apply TejasFoodie middleware first (before any other processing)
        $middleware->web(prepend: [
            \App\Http\Middleware\TejasFoodieMiddleware::class,
            \App\Http\Middleware\DomainStateFilter::class,
            \App\Http\Middleware\RedirectMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
