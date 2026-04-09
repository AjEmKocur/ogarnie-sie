<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>Potwierdzenie zgłoszenia</title>
    </head>
    <body style="font-family: Arial, sans-serif; color: #0f172a;">
        <h2>Dziękujemy. Zgłoszenie zostało przyjęte.</h2>

        <p><strong>Numer zgłoszenia:</strong> #{{ $ticket->id }}</p>
        <p><strong>Temat:</strong> {{ $ticket->title }}</p>
        <p><strong>Status:</strong> {{ \App\Models\Ticket::statuses()[$ticket->status] ?? $ticket->status }}</p>

        @if (!empty($serviceNames))
            <p><strong>Wybrane usługi:</strong></p>
            <ul>
                @foreach ($serviceNames as $serviceName)
                    <li>{{ $serviceName }}</li>
                @endforeach
            </ul>
        @endif

        @if ($ticket->estimated_price_from)
            <p><strong>Szacunkowa cena od:</strong> {{ number_format($ticket->estimated_price_from, 2, ',', ' ') }} PLN</p>
        @endif

        <p>Opis problemu:</p>
        <p style="white-space: pre-line;">{{ $ticket->description }}</p>

        <p style="margin-top: 20px;">
            Możesz sprawdzić status po zalogowaniu w panelu klienta.
        </p>
    </body>
</html>

