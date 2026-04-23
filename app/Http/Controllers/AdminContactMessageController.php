<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReply;
use App\Models\ContactMessage;
use App\Models\ContactMessageEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class AdminContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = (string) $request->query('status', 'all');
        $statuses = ContactMessage::statuses();

        $query = ContactMessage::query()
            ->select([
                'id',
                'name',
                'email',
                'phone',
                'subject',
                'status',
                'created_at',
            ])
            ->selectRaw('substr(message, 1, 220) as message_preview')
            ->latest();

        if ($statusFilter !== 'all' && array_key_exists($statusFilter, $statuses)) {
            $query->where('status', $statusFilter);
        } else {
            $statusFilter = 'all';
        }

        return view('admin.contact.index', [
            'messages' => $query->paginate(20)->withQueryString(),
            'statuses' => $statuses,
            'statusFilter' => $statusFilter,
            'badgeClasses' => ContactMessage::badgeClasses(),
        ]);
    }

    public function show(ContactMessage $contactMessage): View
    {
        $contactMessage->load([
            'repliedByUser',
            'entries.user',
        ]);

        return view('admin.contact.show', [
            'message' => $contactMessage,
            'statuses' => ContactMessage::statuses(),
            'badgeClasses' => ContactMessage::badgeClasses(),
        ]);
    }

    public function update(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', array_keys(ContactMessage::statuses()))],
        ]);

        $contactMessage->update($validated);

        return redirect()
            ->back()
            ->with('status', 'Status wiadomości został zaktualizowany.');
    }

    public function reply(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $validated = $request->validate([
            'reply_subject' => ['required', 'string', 'max:255'],
            'reply_message' => ['required', 'string', 'max:5000'],
        ]);

        try {
            Mail::to($contactMessage->email)->send(new ContactMessageReply(
                contactMessage: $contactMessage,
                replySubject: $validated['reply_subject'],
                replyMessage: $validated['reply_message'],
                responderName: (string) ($request->user()?->name ?? config('app.name')),
            ));
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->route('admin.contact.show', $contactMessage)
                ->with('error', 'Nie udało się wysłać odpowiedzi. Spróbuj ponownie.');
        }

        $contactMessage->entries()->create([
            'user_id' => $request->user()?->id,
            'sender_type' => ContactMessageEntry::SENDER_ADMIN,
            'message' => $validated['reply_message'],
        ]);

        $contactMessage->update([
            'status' => ContactMessage::STATUS_REPLIED,
            'reply_subject' => $validated['reply_subject'],
            'reply_message' => $validated['reply_message'],
            'replied_at' => now(),
            'replied_by_user_id' => $request->user()?->id,
        ]);

        return redirect()
            ->route('admin.contact.show', $contactMessage)
            ->with('status', 'Odpowiedź została wysłana.');
    }
}
