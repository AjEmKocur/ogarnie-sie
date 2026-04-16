<?php

namespace App\Http\Controllers;

use App\Mail\TicketUpdated;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class AdminTicketController extends Controller
{
    public function index(Request $request): View
    {
        $statuses = Ticket::statuses();
        $statusFilter = (string) $request->query('status', 'all');

        $query = Ticket::query()
            ->with(['user'])
            ->withCount(['attachments', 'messages'])
            ->latest();

        if ($statusFilter === Ticket::STATUS_CANCELLED) {
            $statusFilter = Ticket::STATUS_CLOSED;
            $query->whereIn('status', [Ticket::STATUS_CLOSED, Ticket::STATUS_CANCELLED]);
        } elseif ($statusFilter === 'all') {
            // W "Wszystkie aktywne" pokazujemy zgłoszenia bez zamkniętych i anulowanych.
            $query->whereNotIn('status', [Ticket::STATUS_CLOSED, Ticket::STATUS_CANCELLED]);
        } elseif ($statusFilter === Ticket::STATUS_CLOSED) {
            // Zakładka "Zamknięte" zbiera także anulowane.
            $query->whereIn('status', [Ticket::STATUS_CLOSED, Ticket::STATUS_CANCELLED]);
        } elseif (array_key_exists($statusFilter, $statuses)) {
            $query->where('status', $statusFilter);
        } else {
            $statusFilter = 'all';
            $query->whereNotIn('status', [Ticket::STATUS_CLOSED, Ticket::STATUS_CANCELLED]);
        }

        $tickets = $query->paginate(20)->withQueryString();

        return view('admin.tickets.index', [
            'tickets' => $tickets,
            'statuses' => $statuses,
            'paymentStatuses' => Ticket::paymentStatuses(),
            'statusFilter' => $statusFilter,
        ]);
    }

    public function show(Ticket $ticket): View
    {
        $ticket->load([
            'user',
            'services',
            'attachments',
            'messages.user',
            'statusHistories.changedByUser',
        ]);

        return view('admin.tickets.show', [
            'ticket' => $ticket,
            'statuses' => Ticket::statuses(),
            'paymentStatuses' => Ticket::paymentStatuses(),
        ]);
    }

    public function update(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(Ticket::statuses()))],
            'admin_note' => ['nullable', 'string', 'max:5000'],
            'payment_amount' => ['nullable', 'numeric', 'min:0.01'],
            'payment_mark_paid' => ['nullable', 'boolean'],
        ]);

        $oldStatus = $ticket->status;
        $oldAdminNote = $ticket->admin_note;
        $oldPayment = [
            'payment_mode' => $ticket->payment_mode,
            'payment_status' => $ticket->payment_status,
            'payment_amount' => $ticket->payment_amount,
            'payment_note' => $ticket->payment_note,
            'payment_requested_at' => optional($ticket->payment_requested_at)?->format('Y-m-d H:i:s'),
            'paid_at' => optional($ticket->paid_at)?->format('Y-m-d H:i:s'),
        ];

        $paymentAmount = array_key_exists('payment_amount', $validated) && $validated['payment_amount'] !== null
            ? (float) $validated['payment_amount']
            : null;
        $paymentMarkedPaid = $request->boolean('payment_mark_paid');

        if ($validated['status'] === Ticket::STATUS_CANCELLED) {
            // Anulowane zgłoszenie nie powinno oczekiwać na płatność.
            $paymentMode = Ticket::PAYMENT_MODE_NONE;
            $paymentStatus = Ticket::PAYMENT_STATUS_NOT_REQUIRED;
            $paymentAmount = null;
            $paymentRequestedAt = null;
            $paidAt = null;
        } elseif ($paymentAmount === null) {
            $paymentMode = Ticket::PAYMENT_MODE_NONE;
            $paymentStatus = Ticket::PAYMENT_STATUS_NOT_REQUIRED;
            $paymentRequestedAt = null;
            $paidAt = null;
        } else {
            $paymentMode = Ticket::PAYMENT_MODE_ON_PICKUP;
            $paymentStatus = $paymentMarkedPaid ? Ticket::PAYMENT_STATUS_PAID : Ticket::PAYMENT_STATUS_PENDING;
            $paymentRequestedAt = $ticket->payment_requested_at ?: now();
            $paidAt = $paymentStatus === Ticket::PAYMENT_STATUS_PAID
                ? ($ticket->paid_at ?: now())
                : null;
        }

        $ticket->update([
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? null,
            'payment_mode' => $paymentMode,
            'payment_status' => $paymentStatus,
            'payment_amount' => $paymentAmount,
            'payment_note' => null,
            'payment_requested_at' => $paymentRequestedAt,
            'paid_at' => $paidAt,
        ]);

        $statusChanged = $oldStatus !== $ticket->status;
        $noteChanged = (string) $oldAdminNote !== (string) $ticket->admin_note;
        $paymentChanged = $oldPayment !== [
            'payment_mode' => $ticket->payment_mode,
            'payment_status' => $ticket->payment_status,
            'payment_amount' => $ticket->payment_amount,
            'payment_note' => $ticket->payment_note,
            'payment_requested_at' => optional($ticket->payment_requested_at)?->format('Y-m-d H:i:s'),
            'paid_at' => optional($ticket->paid_at)?->format('Y-m-d H:i:s'),
        ];

        if ($statusChanged || $noteChanged) {
            $ticket->statusHistories()->create([
                'changed_by_user_id' => $request->user()->id,
                'status' => $ticket->status,
                'admin_note' => $ticket->admin_note,
            ]);
        }

        if (($statusChanged || $noteChanged || $paymentChanged) && $ticket->user?->email) {
            try {
                $statusLabels = Ticket::statuses();

                Mail::to($ticket->user->email)->send(
                    new TicketUpdated(
                        ticket: $ticket,
                        oldStatusLabel: $statusLabels[$oldStatus] ?? $oldStatus,
                        newStatusLabel: $statusLabels[$ticket->status] ?? $ticket->status
                    )
                );
            } catch (Throwable $e) {
                report($e);
            }
        }

        return redirect()
            ->route('admin.tickets.show', $ticket)
            ->with('status', 'Zgłoszenie zostało zaktualizowane.');
    }
}
