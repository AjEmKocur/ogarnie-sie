<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class PublicContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];

        if ($this->turnstileEnabled()) {
            $rules['cf-turnstile-response'] = ['required', 'string'];
        }

        $validated = $request->validate($rules, [
            'cf-turnstile-response.required' => 'Potwierdź, że nie jesteś botem.',
        ]);

        $this->verifyTurnstile($request);
        unset($validated['cf-turnstile-response']);

        $contactMessage = ContactMessage::create($validated);

        Mail::to(config('mail.from.address'))->send(new ContactMessageReceived($contactMessage));

        return redirect()
            ->route('public.contact')
            ->with('status', 'Dziękujemy! Wiadomość została wysłana.');
    }

    private function turnstileEnabled(): bool
    {
        return (bool) config('services.turnstile.enabled', false);
    }

    private function verifyTurnstile(Request $request): void
    {
        if (! $this->turnstileEnabled()) {
            return;
        }

        $secret = (string) config('services.turnstile.secret_key', '');
        $token = (string) $request->input('cf-turnstile-response', '');
        $verifyUrl = (string) config('services.turnstile.verify_url', 'https://challenges.cloudflare.com/turnstile/v0/siteverify');
        $timeout = (float) config('services.turnstile.timeout_seconds', 5);

        if ($secret === '') {
            Log::warning('Turnstile enabled but TURNSTILE_SECRET_KEY is empty.');
            throw ValidationException::withMessages([
                'captcha' => 'Weryfikacja CAPTCHA jest chwilowo niedostępna. Spróbuj ponownie za chwilę.',
            ]);
        }

        try {
            $response = Http::asForm()
                ->timeout($timeout)
                ->post($verifyUrl, [
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);
        } catch (\Throwable $e) {
            Log::warning('Turnstile request error: '.$e->getMessage());
            throw ValidationException::withMessages([
                'captcha' => 'Nie udało się zweryfikować CAPTCHA. Spróbuj ponownie.',
            ]);
        }

        if (! $response->ok()) {
            Log::warning('Turnstile non-OK response', ['status' => $response->status(), 'body' => $response->body()]);
            throw ValidationException::withMessages([
                'captcha' => 'Nie udało się zweryfikować CAPTCHA. Spróbuj ponownie.',
            ]);
        }

        $success = (bool) data_get($response->json(), 'success', false);

        if (! $success) {
            throw ValidationException::withMessages([
                'captcha' => 'Weryfikacja CAPTCHA nie powiodła się. Spróbuj ponownie.',
            ]);
        }
    }
}
