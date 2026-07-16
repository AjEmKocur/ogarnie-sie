<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public string $cancelledByName,
        public bool $toAdmin
    ) {
    }

    public function envelope(): Envelope
    {
        $subject = $this->toAdmin
            ? "[Kocur Serwis Komputerowy] Klient anulował zgłoszenie #{$this->ticket->id}"
            : "[Kocur Serwis Komputerowy] Potwierdzenie anulowania zgłoszenia #{$this->ticket->id}";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tickets.cancelled',
        );
    }

    /**
     * @return array<int, string>
     */
    public function attachments(): array
    {
        return [];
    }
}
