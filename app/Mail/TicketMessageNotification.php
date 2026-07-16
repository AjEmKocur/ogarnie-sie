<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketMessageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public string $messageText,
        public string $senderName,
        public bool $fromAdmin
    ) {
    }

    public function envelope(): Envelope
    {
        $prefix = '[Kocur Serwis Komputerowy]';
        $subject = $this->fromAdmin
            ? "Nowa odpowiedź serwisu w zgłoszeniu #{$this->ticket->id}"
            : "Nowa wiadomość klienta w zgłoszeniu #{$this->ticket->id}";

        return new Envelope(subject: "{$prefix} {$subject}");
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tickets.message',
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
