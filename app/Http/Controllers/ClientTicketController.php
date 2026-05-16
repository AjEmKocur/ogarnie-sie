<?php

namespace App\Http\Controllers;

use App\Mail\TicketCancelled;
use App\Mail\TicketCreated;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class ClientTicketController extends Controller
{
    public function index(Request $request): View
    {
        $this->abortIfAdmin($request);

        $tickets = $request->user()
            ->tickets()
            ->with(['testimonial'])
            ->latest()
            ->get();

        return view('client.tickets.index', [
            'tickets' => $tickets,
            'statuses' => Ticket::statuses(),
        ]);
    }

    public function create(): View
    {
        abort_if(auth()->user()?->isAdmin(), 403);

        return view('client.tickets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->abortIfAdmin($request);

        $validated = $request->validate(
            [
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:5000'],
                'custom_request' => ['nullable', 'string', 'max:5000'],
                'attachments' => ['nullable', 'array', 'max:5'],
                'attachments.*' => ['file', 'image', 'max:10240', 'mimes:jpg,jpeg,png,webp'],
            ],
            [
                'attachments.max' => 'Możesz dodać maksymalnie 5 zdjęć.',
                'attachments.*.image' => 'Każdy załącznik musi być obrazem.',
                'attachments.*.mimes' => 'Dozwolone formaty zdjęć: jpg, jpeg, png, webp.',
                'attachments.*.max' => 'Maksymalny rozmiar jednego zdjęcia to 10 MB.',
            ]
        );

        $customRequest = trim((string) ($validated['custom_request'] ?? ''));

        $ticket = $request->user()->tickets()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'custom_request' => $customRequest !== '' ? $customRequest : null,
            'estimated_price_from' => null,
            'status' => Ticket::STATUS_NEW,
        ]);

        $ticket->statusHistories()->create([
            'changed_by_user_id' => $request->user()->id,
            'status' => $ticket->status,
            'admin_note' => null,
        ]);

        /** @var UploadedFile $file */
        foreach ($request->file('attachments', []) as $file) {
            $disk = 'local';
            $path = $file->store("ticket-attachments/{$ticket->id}", $disk);

            $ticket->attachments()->create([
                'user_id' => $request->user()->id,
                'disk' => $disk,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        try {
            Mail::to($request->user()->email)->send(
                new TicketCreated(
                    ticket: $ticket
                )
            );
        } catch (Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('client.tickets.index')
            ->with('status', 'Zgłoszenie zostało dodane.');
    }

    public function show(Request $request, Ticket $ticket): View
    {
        $this->abortIfAdmin($request);

        abort_unless($ticket->user_id === $request->user()->id, 403);

        $ticket->forceFill([
            'client_last_seen_at' => now(),
        ])->saveQuietly();

        $ticket->load(['attachments', 'statusHistories.changedByUser', 'messages.user']);

        return view('client.tickets.show', [
            'ticket' => $ticket,
            'statuses' => Ticket::statuses(),
        ]);
    }

    public function pay(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->abortIfAdmin($request);

        abort_unless($ticket->user_id === $request->user()->id, 403);

        if ($ticket->payment_mode === Ticket::PAYMENT_MODE_NONE) {
            return back()->with('status', 'Płatność nie jest wymagana dla tego zgłoszenia.');
        }

        if ($ticket->payment_status === Ticket::PAYMENT_STATUS_PAID) {
            return back()->with('status', 'To zgłoszenie jest już opłacone.');
        }

        // Symulacja płatności online dla projektu.
        $ticket->update([
            'payment_status' => Ticket::PAYMENT_STATUS_PAID,
            'paid_at' => now(),
        ]);

        return back()->with('status', 'Płatność została zaksięgowana.');
    }

    public function cancel(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->abortIfAdmin($request);

        abort_unless($ticket->user_id === $request->user()->id, 403);

        if (in_array($ticket->status, [Ticket::STATUS_CLOSED, Ticket::STATUS_CANCELLED], true)) {
            return back()->with('status', 'Tego zgłoszenia nie można już anulować.');
        }

        $ticket->update([
            'status' => Ticket::STATUS_CANCELLED,
            'payment_mode' => Ticket::PAYMENT_MODE_NONE,
            'payment_status' => Ticket::PAYMENT_STATUS_NOT_REQUIRED,
            'payment_amount' => null,
            'payment_requested_at' => null,
            'paid_at' => null,
        ]);

        $ticket->statusHistories()->create([
            'changed_by_user_id' => $request->user()->id,
            'status' => Ticket::STATUS_CANCELLED,
            'admin_note' => 'Zgłoszenie anulowane przez klienta.',
        ]);

        $this->sendCancellationNotification($ticket, (string) $request->user()->name);

        return back()->with('status', 'Zgłoszenie zostało anulowane.');
    }

    private function sendCancellationNotification(Ticket $ticket, string $cancelledByName): void
    {
        try {
            $adminEmails = User::query()
                ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_OPERATOR])
                ->where('is_active', true)
                ->whereNotNull('email')
                ->pluck('email')
                ->filter()
                ->unique()
                ->values()
                ->all();

            if (empty($adminEmails)) {
                $fallback = config('mail.from.address');
                if (! empty($fallback)) {
                    $adminEmails = [$fallback];
                }
            }

            if (! empty($adminEmails)) {
                Mail::to($adminEmails)->send(
                    new TicketCancelled(
                        ticket: $ticket->loadMissing('user'),
                        cancelledByName: $cancelledByName,
                        toAdmin: true
                    )
                );
            }

            if (! empty($ticket->user?->email)) {
                Mail::to($ticket->user->email)->send(
                    new TicketCancelled(
                        ticket: $ticket,
                        cancelledByName: $cancelledByName,
                        toAdmin: false
                    )
                );
            }
        } catch (Throwable $e) {
            report($e);
        }
    }

    private function abortIfAdmin(Request $request): void
    {
        abort_if($request->user()?->isAdmin(), 403);
    }
}
