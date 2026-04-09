<?php

namespace App\Http\Controllers;

use App\Mail\TicketMessageNotification;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class TicketMessageController extends Controller
{
    public function store(Request $request, Ticket $ticket): RedirectResponse
    {
        $user = $request->user();

        if (! $user->isAdmin() && $ticket->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'min:2', 'max:5000'],
        ]);

        $ticket->messages()->create([
            'user_id' => $user->id,
            'message' => $validated['message'],
        ]);

        $this->sendEmailNotification($ticket, $user->isAdmin(), $user->name, $validated['message']);

        return redirect()->back()->with('status', 'Wiadomość została dodana.');
    }

    private function sendEmailNotification(Ticket $ticket, bool $fromAdmin, string $senderName, string $messageText): void
    {
        try {
            if ($fromAdmin) {
                $clientEmail = $ticket->user?->email;
                if (! $clientEmail) {
                    return;
                }

                Mail::to($clientEmail)->send(
                    new TicketMessageNotification(
                        ticket: $ticket,
                        messageText: $messageText,
                        senderName: $senderName,
                        fromAdmin: true
                    )
                );

                return;
            }

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

            if (empty($adminEmails)) {
                return;
            }

            Mail::to($adminEmails)->send(
                new TicketMessageNotification(
                    ticket: $ticket,
                    messageText: $messageText,
                    senderName: $senderName,
                    fromAdmin: false
                )
            );
        } catch (Throwable $e) {
            report($e);
        }
    }
}
