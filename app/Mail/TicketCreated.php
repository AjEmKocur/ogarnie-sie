<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Ticket $ticket
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Ogarnie się] Potwierdzenie przyjęcia zgłoszenia #'.$this->ticket->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tickets.created',
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

