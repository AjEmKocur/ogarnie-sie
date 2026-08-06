<x-email-shell
    title="Nowa wiadomość kontaktowa"
    heading="Nowa wiadomość z formularza"
    summary="Ktoś wysłał wiadomość ze strony kontaktowej."
    :action-url="route('public.contact')"
    action-text="Otwórz stronę"
>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse; margin:0 0 18px 0;">
        <tr>
            <td style="padding:10px 0; border-bottom:1px solid #273244; color:#94a3b8;">Imię i nazwisko</td>
            <td align="right" style="padding:10px 0; border-bottom:1px solid #273244; color:#ffffff; font-weight:700;">{{ $name }}</td>
        </tr>
        <tr>
            <td style="padding:10px 0; border-bottom:1px solid #273244; color:#94a3b8;">Email</td>
            <td align="right" style="padding:10px 0; border-bottom:1px solid #273244; color:#ffffff; font-weight:700;">{{ $email }}</td>
        </tr>
        <tr>
            <td style="padding:10px 0; border-bottom:1px solid #273244; color:#94a3b8;">Telefon</td>
            <td align="right" style="padding:10px 0; border-bottom:1px solid #273244; color:#ffffff; font-weight:700;">{{ $phone !== '' ? $phone : 'Brak' }}</td>
        </tr>
        <tr>
            <td style="padding:10px 0; border-bottom:1px solid #273244; color:#94a3b8;">Temat</td>
            <td align="right" style="padding:10px 0; border-bottom:1px solid #273244; color:#ffffff; font-weight:700;">{{ $subjectLine }}</td>
        </tr>
    </table>

    <p style="margin:0 0 8px 0; color:#facc15; font-weight:700;">Treść</p>
    <div style="white-space:pre-line; border:1px solid #273244; border-radius:10px; background:#090b10; padding:14px; color:#e5e7eb;">{{ $messageBody }}</div>
</x-email-shell>
