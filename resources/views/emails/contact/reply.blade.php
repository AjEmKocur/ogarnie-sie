<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>Odpowiedź na wiadomość kontaktową</title>
    </head>
    <body style="font-family: Arial, sans-serif; color: #0f172a;">
        <h2>Odpowiedź na Twoją wiadomość</h2>

        <p>Dzień dobry {{ $contactMessage->name }},</p>

        <p style="white-space: pre-line;">{{ $messageBody }}</p>

        <p style="margin-top: 20px;">
            Pozdrawiamy,<br>
            {{ $responderName !== '' ? $responderName : 'Zespół Ogarnie się' }}
        </p>

        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #e2e8f0;">

        <p style="font-size: 13px; color: #475569;">
            Oryginalny temat: {{ $contactMessage->subject }}
        </p>
    </body>
</html>
