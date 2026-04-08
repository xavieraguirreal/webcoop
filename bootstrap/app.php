<?php

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
        // Detecta URLs heredadas del Joomla viejo y emite 301 al equivalente
        // Laravel. Se registra como middleware GLOBAL (no dentro del grupo web)
        // porque el patrón de URL viejo no coincide con ninguna ruta definida
        // — si lo registráramos solo en "web", el router daría 404 antes de
        // ejecutar el middleware.
        $middleware->prepend(\App\Http\Middleware\RedirectFromJoomla::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
