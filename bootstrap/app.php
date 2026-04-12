<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsMainAdmin;
use App\Http\Middleware\EnsureUserIsClient;
use App\Http\Middleware\EnsureUserHasAdminPermission;
use App\Http\Middleware\EnsurePasswordChangeIsCompleted;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Render działa za reverse proxy, więc musimy ufać nagłówkom X-Forwarded-*.
        $middleware->trustProxies(at: ['*'], headers: Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_HOST
            | Request::HEADER_X_FORWARDED_PORT
            | Request::HEADER_X_FORWARDED_PROTO
            | Request::HEADER_X_FORWARDED_AWS_ELB);

        // Tymczasowy hotfix pod deploy demo: wyłączamy CSRF tylko dla formularzy auth.
        // Po ustabilizowaniu sesji/cookies na Render należy to cofnąć.
        $middleware->validateCsrfTokens(except: [
            'login',
            'register',
            'forgot-password',
            'reset-password',
            'confirm-password',
            'email/verification-notification',
        ]);

        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'client' => EnsureUserIsClient::class,
            'main_admin' => EnsureUserIsMainAdmin::class,
            'admin.permission' => EnsureUserHasAdminPermission::class,
            'password.change.required' => EnsurePasswordChangeIsCompleted::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (TokenMismatchException $exception, Request $request): void {
            logger()->warning('CSRF token mismatch on request.', [
                'method' => $request->method(),
                'path' => $request->path(),
                'full_url' => $request->fullUrl(),
                'has_session_cookie' => $request->cookies->has(config('session.cookie')),
                'session_cookie_name' => config('session.cookie'),
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                'has_csrf_input' => $request->request->has('_token'),
                'has_x_csrf_header' => $request->headers->has('X-CSRF-TOKEN'),
                'has_x_xsrf_header' => $request->headers->has('X-XSRF-TOKEN'),
                'origin' => $request->headers->get('origin'),
                'referer' => $request->headers->get('referer'),
                'host' => $request->getHost(),
                'is_secure' => $request->isSecure(),
                'forwarded_proto' => $request->headers->get('x-forwarded-proto'),
            ]);
        });
    })->create();
