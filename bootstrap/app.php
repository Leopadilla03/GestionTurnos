<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))

    /*
    |--------------------------------------------------------------------------
    | Configuración de rutas
    |--------------------------------------------------------------------------
    |
    | Aquí se definen los archivos de rutas principales del proyecto:
    | - web.php → Rutas normales del sistema (login, paneles, etc.)
    | - console.php → Comandos Artisan personalizados.
    | 
    | El parámetro 'health' define un endpoint simple para comprobar que
    | el sistema está corriendo (por defecto: /up)
    |
    */
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    /*
    |--------------------------------------------------------------------------
    | Configuración de middleware
    |--------------------------------------------------------------------------
    |
    | En Laravel 12 ya no se usa el archivo app/Http/Kernel.php.
    | Todo el registro de middleware globales o alias personalizados
    | se realiza aquí dentro del método "withMiddleware".
    |
    */
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            // middleware que se ejecuta para todas las rutas web
        ]);

        $middleware->alias([
            // aquí van los alias de middleware personalizados
            'rol' => \App\Http\Middleware\RolMiddleware::class, // ✅ Middleware de roles
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
