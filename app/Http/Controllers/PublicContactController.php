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

        try {
            Mail::to((string) config('mail.contact_inbox'))->send(new ContactMessageReceived($contactMessage));
        } catch (\Throwable $e) {
            $apiFallbackSent = $this->sendViaSendGridApi($contactMessage);

            Log::warning('Contact notification email failed.', [
                'error' => $e->getMessage(),
                'sendgrid_api_fallback_sent' => $apiFallbackSent,
                'mail_default' => (string) config('mail.default', ''),
                'mail_host' => (string) config('mail.mailers.smtp.host', ''),
                'mail_port' => (string) config('mail.mailers.smtp.port', ''),
                'mail_username_set' => (string) config('mail.mailers.smtp.username', '') !== '',
                'mail_password_set' => (string) config('mail.mailers.smtp.password', '') !== '',
                'mail_contact_inbox' => (string) config('mail.contact_inbox', ''),
            ]);
        }

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

    private function sendViaSendGridApi(ContactMessage $contactMessage): bool
    {
        $apiKey = $this->resolveSendGridApiKey();
        $to = (string) config('mail.contact_inbox', '');
        $fromAddress = (string) config('mail.from.address', '');
        $fromName = (string) config('mail.from.name', 'Ogarnie Sie');

        if ($apiKey === '' || $to === '' || $fromAddress === '') {
            return false;
        }

        try {
            $response = Http::timeout(12)
                ->withToken($apiKey)
                ->post('https://api.sendgrid.com/v3/mail/send', [
                    'personalizations' => [[
                        'to' => [[
                            'email' => $to,
                        ]],
                        'subject' => '[Ogarnie się] Nowa wiadomość kontaktowa: '.$contactMessage->subject,
                    ]],
                    'from' => [
                        'email' => $fromAddress,
                        'name' => $fromName,
                    ],
                    'reply_to' => [
                        'email' => $contactMessage->email,
                        'name' => $contactMessage->name,
                    ],
                    'content' => [[
                        'type' => 'text/plain',
                        'value' => "Nowa wiadomość z formularza kontaktowego:\n\n"
                            ."Imię i nazwisko: {$contactMessage->name}\n"
                            ."Email: {$contactMessage->email}\n"
                            .'Telefon: '.($contactMessage->phone ?: '-')."\n"
                            ."Temat: {$contactMessage->subject}\n\n"
                            ."Treść:\n{$contactMessage->message}\n",
                    ]],
                ]);
        } catch (\Throwable $e) {
            Log::warning('SendGrid API fallback failed.', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }

        if ($response->status() >= 200 && $response->status() < 300) {
            return true;
        }

        Log::warning('SendGrid API fallback returned non-success status.', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return false;
    }

    private function resolveSendGridApiKey(): string
    {
        $explicit = (string) config('services.sendgrid.api_key', '');
        if ($explicit !== '') {
            return $explicit;
        }

        $smtpHost = (string) config('mail.mailers.smtp.host', '');
        $smtpUser = (string) config('mail.mailers.smtp.username', '');
        $smtpPass = (string) config('mail.mailers.smtp.password', '');

        if (str_contains(strtolower($smtpHost), 'sendgrid') && strtolower($smtpUser) === 'apikey' && $smtpPass !== '') {
            return $smtpPass;
        }

        return '';
    }
}
