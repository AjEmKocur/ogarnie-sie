<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>Anulowanie zgłoszenia</title>
    </head>
    <body style="font-family: Arial, sans-serif; color: #0f172a;">
        @if ($toAdmin)
            <h2>Klient anulował zgłoszenie</h2>
            <p>Użytkownik <strong>{{ $cancelledByName }}</strong> anulował zgłoszenie serwisowe.</p>
        @else
            <h2>Potwierdzenie anulowania zgłoszenia</h2>
            <p>Twoje zgłoszenie zostało anulowane.</p>
        @endif

        <p><strong>Numer zgłoszenia:</strong> #{{ $ticket->id }}</p>
        <p><strong>Temat:</strong> {{ $ticket->title }}</p>
        <p><strong>Status:</strong> {{ \App\Models\Ticket::statuses()[$ticket->status] ?? $ticket->status }}</p>

        <p style="margin-top: 20px;">
            Szczegóły są dostępne po zalogowaniu do panelu.
        </p>
    </body>
</html>
