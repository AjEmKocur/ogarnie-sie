<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminContactMessageController extends Controller
{
    public function index(): View
    {
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
}
