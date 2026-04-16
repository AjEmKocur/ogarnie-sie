<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>Aktualizacja zgłoszenia</title>
    </head>
    <body style="font-family: Arial, sans-serif; color: #0f172a;">
        <h2>Twoje zgłoszenie zostało zaktualizowane</h2>

        <p><strong>Numer zgłoszenia:</strong> #{{ $ticket->id }}</p>
        <p><strong>Temat:</strong> {{ $ticket->title }}</p>
        <p><strong>Status:</strong> {{ $oldStatusLabel }} -> {{ $newStatusLabel }}</p>
        <p>
            <strong>Płatność:</strong>
            {{ \App\Models\Ticket::paymentStatuses()[$ticket->payment_status] ?? $ticket->payment_status }}
            @if ($ticket->payment_amount !== null)
                ({{ number_format((float) $ticket->payment_amount, 2, ',', ' ') }} PLN)
            @endif
        </p>

        @if ($ticket->payment_note)
            <p><strong>Notatka dotycząca płatności:</strong></p>
            <p style="white-space: pre-line;">{{ $ticket->payment_note }}</p>
        @endif

        <p style="margin-top: 20px;">
            Szczegóły zgłoszenia są dostępne po zalogowaniu w panelu klienta.
        </p>
    </body>
</html>
