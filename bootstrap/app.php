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
    ->withMiddleware(function (Middleware $middleware) {
        // 引数に直接URLを指定するか、クロージャを使ってリダイレクト先を制御します
        $middleware->redirectTo(function ($request) {
            // 未認証ユーザーがアクセスしてきたら /login に飛ばす（FN011-1）
            return route('login', [], false) ?: '/login';
        });
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
