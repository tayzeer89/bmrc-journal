<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\EnsureReviewerApproved;

return Application::configure(
    basePath: dirname(__DIR__)
)
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([

            /*
            |--------------------------------------------------------------------------
            | Spatie Role & Permission Middleware
            |--------------------------------------------------------------------------
            */

            'role' =>
                \Spatie\Permission\Middleware\RoleMiddleware::class,

            'permission' =>
                \Spatie\Permission\Middleware\PermissionMiddleware::class,

            'role_or_permission' =>
                \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,


            /*
            |--------------------------------------------------------------------------
            | Reviewer Middleware
            |--------------------------------------------------------------------------
            */

            'reviewer.approved' =>
                EnsureReviewerApproved::class,

        ]);

    })

    ->withExceptions(function (Exceptions $exceptions): void {

        //

    })
    ->create();