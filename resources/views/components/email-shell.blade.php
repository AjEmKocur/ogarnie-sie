@props([
    'title',
    'eyebrow' => 'Kocur Serwis Komputerowy',
    'heading',
    'summary' => null,
    'actionUrl' => null,
    'actionText' => 'Przejdź do panelu',
])

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }}</title>
    </head>
    <body style="margin:0; padding:0; background:#090909; color:#f8fafc; font-family:Arial, Helvetica, sans-serif;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#090909; margin:0; padding:0;">
            <tr>
                <td align="center" style="padding:32px 16px;">
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px; border-collapse:collapse;">
                        <tr>
                            <td style="padding:0 0 18px 0;">
                                <img src="{{ asset('images/kocur-logo-amber.png') }}" alt="Kocur Serwis Komputerowy" width="170" style="display:block; width:170px; max-width:70%; height:auto; border:0;">
                            </td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #3a2a08; border-radius:16px; background:#0f1117; overflow:hidden;">
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
                                    <tr>
                                        <td style="background:#15110a; border-bottom:1px solid #3a2a08; padding:24px 28px;">
                                            <p style="margin:0 0 8px 0; color:#facc15; font-size:12px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase;">{{ $eyebrow }}</p>
                                            <h1 style="margin:0; color:#ffffff; font-size:24px; line-height:1.25; font-weight:800;">{{ $heading }}</h1>
                                            @if ($summary)
                                                <p style="margin:12px 0 0 0; color:#cbd5e1; font-size:15px; line-height:1.6;">{{ $summary }}</p>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:26px 28px; color:#e5e7eb; font-size:15px; line-height:1.65;">
                                            {{ $slot }}

                                            @if ($actionUrl)
                                                <table role="presentation" cellspacing="0" cellpadding="0" style="margin-top:24px;">
                                                    <tr>
                                                        <td style="border-radius:8px; background:#facc15;">
                                                            <a href="{{ $actionUrl }}" style="display:inline-block; padding:12px 18px; color:#111827; font-size:13px; font-weight:800; letter-spacing:0.08em; text-transform:uppercase; text-decoration:none;">
                                                                {{ $actionText }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:18px 4px 0 4px; color:#94a3b8; font-size:12px; line-height:1.6;">
                                <p style="margin:0;">Kocur Serwis Komputerowy • Jarosław i okolice</p>
                                <p style="margin:4px 0 0 0;">Ta wiadomość została wysłana automatycznie. Nie musisz na nią odpowiadać, jeśli sprawa jest już jasna.</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
