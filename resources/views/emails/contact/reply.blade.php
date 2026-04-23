<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>Odpowiedź na wiadomość kontaktową</title>
    </head>
    <body style="font-family: Arial, sans-serif; color: #0f172a;">
        <h2>Odpowiedź na Twoją wiadomość</h2>

        <p style="white-space: pre-line;">{{ $replyMessage }}</p>

        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #cbd5e1;">

        <p><strong>Odpowiada:</strong> {{ $responderName }}</p>
        <p><strong>Twoja wiadomość:</strong> {{ $contactMessage->subject }}</p>
        <p style="white-space: pre-line;">{{ $contactMessage->message }}</p>
    </body>
</html>
