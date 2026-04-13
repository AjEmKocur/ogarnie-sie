<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $routeId = (string) $request->route('id');
        $routeHash = (string) $request->route('hash');

        $user = $request->user();
        if (! $user) {
            $user = User::find($routeId);
        }

        if (! $user) {
            abort(403);
        }

        $expectedHash = sha1($user->getEmailForVerification());

        if ($routeId !== (string) $user->getKey() || ! hash_equals($expectedHash, $routeHash)) {
            abort(403);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()
                ->route('public.home')
                ->with('status', 'Adres e-mail jest już potwierdzony.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()
            ->route('public.home')
            ->with('status', 'Dziękujemy za potwierdzenie adresu e-mail.');
    }
}
