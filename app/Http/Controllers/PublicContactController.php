<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageConfirmation;
use App\Mail\ContactMessageReceived;
use App\Mail\TicketCreated;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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

        $matchedClient = $this->findVerifiedClientByEmail((string) $validated['email']);

        if ($matchedClient) {
            $ticket = $this->createTicketForMatchedClient($matchedClient, $validated);

            try {
                Mail::to($matchedClient->email)->send(new TicketCreated($ticket));

                $adminEmails = $this->adminNotificationEmails();
                if (! empty($adminEmails)) {
                    Mail::to($adminEmails)->send(new TicketCreated(
                        ticket: $ticket->loadMissing('user'),
                        toAdmin: true
                    ));
                }
            } catch (\Throwable $e) {
                report($e);
            }

            return redirect()
                ->route('public.contact')
                ->with('status', 'Dziękujemy! Zgłoszenie zostało dodane i jest dostępne w panelu klienta.');
        }

        try {
            Mail::to(config('mail.from.address'))->send(new ContactMessageReceived(
                name: (string) $validated['name'],
                email: (string) $validated['email'],
                phone: (string) ($validated['phone'] ?? ''),
                subjectLine: (string) $validated['subject'],
                messageBody: (string) $validated['message'],
            ));
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'mail' => 'Nie udało się wysłać wiadomości. Spróbuj ponownie za chwilę albo napisz bezpośrednio na kontakt@kocurserwis.pl.',
                ]);
        }

        try {
            Mail::to((string) $validated['email'])->send(new ContactMessageConfirmation(
                name: (string) $validated['name'],
                subjectLine: (string) $validated['subject'],
            ));
        } catch (\Throwable $e) {
            Log::warning('Contact confirmation email failed: '.$e->getMessage());
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

    /**
     * @param array<string, mixed> $data
     */
    private function createTicketForMatchedClient(User $user, array $data): Ticket
    {
        $phone = trim((string) ($data['phone'] ?? ''));
        $description = trim((string) $data['message']);

        if ($phone !== '') {
            $description .= "\n\nTelefon kontaktowy: ".$phone;
        }

        $ticket = $user->tickets()->create([
            'title' => (string) $data['subject'],
            'description' => $description,
            'custom_request' => null,
            'estimated_price_from' => null,
            'status' => Ticket::STATUS_NEW,
        ]);

        $ticket->statusHistories()->create([
            'changed_by_user_id' => null,
            'status' => $ticket->status,
            'admin_note' => null,
        ]);

        return $ticket->loadMissing('user');
    }

    private function findVerifiedClientByEmail(string $email): ?User
    {
        return User::query()
            ->whereRaw('LOWER(email) = ?', [Str::lower(trim($email))])
            ->where('role', User::ROLE_CLIENT)
            ->where('is_active', true)
            ->whereNotNull('email_verified_at')
            ->first();
    }

    /**
     * @return array<int, string>
     */
    private function adminNotificationEmails(): array
    {
        $adminEmails = User::query()
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_OPERATOR])
            ->where('is_active', true)
            ->whereNotNull('email')
            ->pluck('email')
            ->filter()
            ->all();

        $serviceInbox = config('mail.from.address');
        if (! empty($serviceInbox)) {
            $adminEmails[] = $serviceInbox;
        }

        return collect($adminEmails)
            ->map(fn ($email) => strtolower(trim((string) $email)))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
