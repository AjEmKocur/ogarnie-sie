<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TicketAttachmentController extends Controller
{
    public function store(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->abortIfUnauthorized($request, $ticket);

        $validated = $request->validate([
            'attachment' => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,txt,doc,docx'],
        ]);

        $file = $validated['attachment'];
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

        return redirect()
            ->back()
            ->with('status', 'Plik został dodany do zgłoszenia.');
    }

    public function download(Request $request, TicketAttachment $attachment): StreamedResponse
    {
        $ticket = $attachment->ticket;
        $this->abortIfUnauthorized($request, $ticket);

        return Storage::disk($attachment->disk)->download(
            $attachment->path,
            $attachment->original_name
        );
    }

    public function destroy(Request $request, TicketAttachment $attachment): RedirectResponse
    {
        $ticket = $attachment->ticket;
        $this->abortIfUnauthorized($request, $ticket);

        $disk = Storage::disk($attachment->disk);
        if ($disk->exists($attachment->path)) {
            $disk->delete($attachment->path);
        }

        $attachment->delete();

        return redirect()
            ->back()
            ->with('status', 'Plik został usunięty.');
    }

    private function abortIfUnauthorized(Request $request, Ticket $ticket): void
    {
        $user = $request->user();

        if (! $user->isAdmin() && $ticket->user_id !== $user->id) {
            abort(403);
        }
    }
}

