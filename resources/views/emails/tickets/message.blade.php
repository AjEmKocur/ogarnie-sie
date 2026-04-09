<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>Nowa wiadomość w zgłoszeniu</title>
    </head>
    <body style="font-family: Arial, sans-serif; color: #0f172a;">
        <h2>Nowa wiadomość w zgłoszeniu #{{ $ticket->id }}</h2>

        <p><strong>Temat:</strong> {{ $ticket->title }}</p>
        <p><strong>Nadawca:</strong> {{ $senderName }}</p>
        <p>
            <strong>Typ wiadomości:</strong>
            {{ $fromAdmin ? 'Odpowiedź serwisu' : 'Wiadomość klienta' }}
        </p>

        <p style="margin-top: 14px;"><strong>Treść:</strong></p>
        <p style="white-space: pre-line;">{{ $messageText }}</p>

        <p style="margin-top: 20px;">
            Zaloguj się do panelu, aby odpowiedzieć:
            <a href="{{ url('/dashboard') }}">{{ url('/dashboard') }}</a>
        </p>
    </body>
</html>

