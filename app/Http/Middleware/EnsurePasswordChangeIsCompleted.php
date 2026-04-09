<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChangeIsCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->force_password_change) {
            return $next($request);
        }

        if (
            $request->routeIs('profile.edit')
            || $request->routeIs('profile.update')
            || $request->routeIs('password.update')
            || $request->routeIs('logout')
        ) {
            return $next($request);
        }

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Zmień hasło startowe, aby kontynuować.');
    }
}

