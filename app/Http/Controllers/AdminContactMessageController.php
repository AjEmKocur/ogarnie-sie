<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReply;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AdminContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        if ($user && $user->isAdmin()) {
            $user->forceFill([
                'contact_messages_last_seen_at' => now(),
            ])->saveQuietly();
        }

        return view('admin.contact.index', [
            'messages' => ContactMessage::latest()->get(),
            'statuses' => ContactMessage::statuses(),
        ]);
    }

    public function update(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', array_keys(ContactMessage::statuses()))],
        ]);

        $contactMessage->update($validated);

        return redirect()
            ->route('admin.contact.index')
            ->with('status', 'Status wiadomości został zaktualizowany.');
    }

    public function reply(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $validated = $request->validate([
            'reply_subject' => ['required', 'string', 'max:255'],
            'reply_message' => ['required', 'string', 'max:5000'],
        ]);

        Mail::to($contactMessage->email)->send(
            new ContactMessageReply(
                contactMessage: $contactMessage,
                subject: $validated['reply_subject'],
                messageBody: $validated['reply_message'],
                responderName: (string) $request->user()?->name
            )
        );

        $contactMessage->update([
            'status' => ContactMessage::STATUS_REPLIED,
        ]);

        return redirect()
            ->route('admin.contact.index')
            ->with('status', 'Odpowiedź została wysłana.');
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return redirect()
            ->route('admin.contact.index')
            ->with('status', 'Wiadomość została usunięta.');
    }
}
