@php
    $panelUrl = $fromAdmin ? route('client.tickets.show', $ticket) : route('admin.tickets.show', $ticket);
@endphp

<x-email-shell
    title="Nowa wiadomość w zgłoszeniu"
    :heading="'Nowa wiadomość w zgłoszeniu #'.$ticket->id"
    :summary="$fromAdmin ? 'Serwis odpowiedział w Twoim zgłoszeniu.' : 'Klient dodał wiadomość do zgłoszenia.'"
    :action-url="$panelUrl"
    action-text="Otwórz zgłoszenie"
>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse; margin:0 0 18px 0;">
        <tr>
            <td style="padding:10px 0; border-bottom:1px solid #273244; color:#94a3b8;">Temat</td>
            <td align="right" style="padding:10px 0; border-bottom:1px solid #273244; color:#ffffff; font-weight:700;">{{ $ticket->title }}</td>
        </tr>
        <tr>
            <td style="padding:10px 0; border-bottom:1px solid #273244; color:#94a3b8;">Nadawca</td>
            <td align="right" style="padding:10px 0; border-bottom:1px solid #273244; color:#ffffff; font-weight:700;">{{ $senderName }}</td>
        </tr>
        <tr>
            <td style="padding:10px 0; border-bottom:1px solid #273244; color:#94a3b8;">Typ wiadomości</td>
            <td align="right" style="padding:10px 0; border-bottom:1px solid #273244; color:#ffffff; font-weight:700;">{{ $fromAdmin ? 'Odpowiedź serwisu' : 'Wiadomość klienta' }}</td>
        </tr>
    </table>

    <p style="margin:0 0 8px 0; color:#facc15; font-weight:700;">Treść</p>
    <div style="white-space:pre-line; border:1px solid #273244; border-radius:10px; background:#090b10; padding:14px; color:#e5e7eb;">{{ $messageText }}</div>
</x-email-shell>
