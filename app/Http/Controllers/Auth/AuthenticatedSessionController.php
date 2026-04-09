<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login', [
            'returnTo' => request()->query('return'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $fallback = route('dashboard', absolute: false);
        $returnTo = (string) $request->input('return_to', '');
        $intended = (string) $request->session()->get('url.intended', '');

        if ($returnTo !== '' && str_starts_with($returnTo, '/') && ! str_starts_with($returnTo, '//')) {
            $fallback = $returnTo;
        }

        if (! $request->user()->hasVerifiedEmail()) {
            if ($intended !== '' && str_contains($intended, '/verify-email/')) {
                return redirect()->intended($fallback);
            }

            return redirect()
                ->route('verification.notice')
                ->with('status', 'Najpierw potwierdź adres e-mail.');
        }

        if ($request->user()->force_password_change) {
            return redirect()
                ->route('profile.edit')
                ->with('status', 'To pierwsze logowanie. Ustaw nowe hasło, aby kontynuować.');
        }

        return redirect()->intended($fallback);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

