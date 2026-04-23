<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>Nowa wiadomość kontaktowa</title>
    </head>
    <body style="font-family: Arial, sans-serif; color: #0f172a;">
        <h2>Nowa wiadomość z formularza kontaktowego</h2>

        <p><strong>Imię i nazwisko:</strong> {{ $name }}</p>
        <p><strong>Email:</strong> {{ $email }}</p>
        <p><strong>Telefon:</strong> {{ $phone !== '' ? $phone : 'Brak' }}</p>
        <p><strong>Temat:</strong> {{ $subjectLine }}</p>

        <p><strong>Treść:</strong></p>
        <p style="white-space: pre-line;">{{ $messageBody }}</p>
    </body>
</html>
