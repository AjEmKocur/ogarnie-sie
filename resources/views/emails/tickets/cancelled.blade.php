@php
    $panelUrl = $toAdmin ? route('admin.tickets.show', $ticket) : route('client.tickets.show', $ticket);
@endphp

<x-email-shell
    title="Anulowanie zgłoszenia"
    :heading="$toAdmin ? 'Klient anulował zgłoszenie' : 'Zgłoszenie zostało anulowane'"
    :summary="$toAdmin ? 'Użytkownik '.$cancelledByName.' anulował zgłoszenie serwisowe.' : 'Potwierdzamy anulowanie zgłoszenia w panelu klienta.'"
    :action-url="$panelUrl"
    action-text="Otwórz zgłoszenie"
>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
        <tr>
            <td style="padding:10px 0; border-bottom:1px solid #273244; color:#94a3b8;">Numer zgłoszenia</td>
            <td align="right" style="padding:10px 0; border-bottom:1px solid #273244; color:#ffffff; font-weight:700;">#{{ $ticket->id }}</td>
        </tr>
        <tr>
            <td style="padding:10px 0; border-bottom:1px solid #273244; color:#94a3b8;">Temat</td>
            <td align="right" style="padding:10px 0; border-bottom:1px solid #273244; color:#ffffff; font-weight:700;">{{ $ticket->title }}</td>
        </tr>
        <tr>
            <td style="padding:10px 0; border-bottom:1px solid #273244; color:#94a3b8;">Status</td>
            <td align="right" style="padding:10px 0; border-bottom:1px solid #273244; color:#ffffff; font-weight:700;">{{ \App\Models\Ticket::statuses()[$ticket->status] ?? $ticket->status }}</td>
        </tr>
    </table>
</x-email-shell>
