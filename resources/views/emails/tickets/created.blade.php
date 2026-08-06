<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>{{ $toAdmin ? 'Nowe zgłoszenie' : 'Potwierdzenie zgłoszenia' }}</title>
    </head>
    <body style="font-family: Arial, sans-serif; color: #0f172a;">
        @if ($toAdmin)
            <h2>Nowe zgłoszenie serwisowe.</h2>
            <p>Klient utworzył nowe zgłoszenie w panelu.</p>
            <p><strong>Klient:</strong> {{ $ticket->user?->name }} ({{ $ticket->user?->email }})</p>
        @else
            <h2>Dziękujemy. Zgłoszenie zostało przyjęte.</h2>
        @endif

        <p><strong>Numer zgłoszenia:</strong> #{{ $ticket->id }}</p>
        <p><strong>Temat:</strong> {{ $ticket->title }}</p>
        <p><strong>Status:</strong> {{ \App\Models\Ticket::statuses()[$ticket->status] ?? $ticket->status }}</p>

        @if ($ticket->estimated_price_from)
            <p><strong>Szacunkowa cena od:</strong> {{ number_format($ticket->estimated_price_from, 2, ',', ' ') }} PLN</p>
        @endif

        <p>Opis problemu:</p>
        <p style="white-space: pre-line;">{{ $ticket->description }}</p>

        @if (! $toAdmin)
            <p style="margin-top: 20px;">
                Możesz sprawdzić status po zalogowaniu w panelu klienta.
            </p>
        @endif
    </body>
</html>
