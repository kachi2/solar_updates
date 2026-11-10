<?php

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
        // Guests hitting /admin/* go to the admin login; customers to the shop login.
        $middleware->redirectGuestsTo(fn (Request $request) => $request->is('admin', 'admin/*')
            ? route('admin-login')
            : route('login'));

        // Already-logged-in visitors of a login page go to their home page.
        $middleware->redirectUsersTo(fn (Request $request) => $request->is('admin', 'admin/*')
            ? route('admin.index')
            : route('index'));

        // Site-wide per-IP page limit ("web" limiter, AppServiceProvider).
        $middleware->appendToGroup('web', 'throttle:web');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
