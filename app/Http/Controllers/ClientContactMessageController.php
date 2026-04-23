<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\ContactMessageEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $messages = ContactMessage::query()
            ->where('user_id', $request->user()->id)
            ->select(['id', 'subject', 'status', 'created_at'])
            ->selectRaw('substr(message, 1, 220) as message_preview')
            ->latest()
            ->paginate(20);

        return view('client.contact.index', [
            'messages' => $messages,
            'statuses' => ContactMessage::statuses(),
            'badgeClasses' => ContactMessage::badgeClasses(),
        ]);
    }

    public function show(Request $request, ContactMessage $contactMessage): View
    {
        abort_unless($contactMessage->user_id === $request->user()->id, 403);

        $contactMessage->load(['entries.user']);

        return view('client.contact.show', [
            'message' => $contactMessage,
            'statuses' => ContactMessage::statuses(),
            'badgeClasses' => ContactMessage::badgeClasses(),
        ]);
    }

    public function storeEntry(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        abort_unless($contactMessage->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $contactMessage->entries()->create([
            'user_id' => $request->user()->id,
            'sender_type' => ContactMessageEntry::SENDER_CLIENT,
            'message' => $validated['message'],
        ]);

        $contactMessage->update([
            'status' => ContactMessage::STATUS_NEW,
            'message' => $validated['message'],
        ]);

        return redirect()
            ->route('client.contact.show', $contactMessage)
            ->with('status', 'Dopisano Twoją wiadomość do wątku.');
    }
}
