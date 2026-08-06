@php
    $panelUrl = $toAdmin ? route('admin.tickets.show', $ticket) : route('client.tickets.show', $ticket);
@endphp

<x-email-shell
    :title="$toAdmin ? 'Nowe zgłoszenie' : 'Potwierdzenie zgłoszenia'"
    :heading="$toAdmin ? 'Nowe zgłoszenie serwisowe' : 'Zgłoszenie zostało przyjęte'"
    :summary="$toAdmin ? 'Klient utworzył nowe zgłoszenie w panelu.' : 'Dziękujemy. Przyjęliśmy zgłoszenie i wrócimy z dalszymi informacjami.'"
    :action-url="$panelUrl"
>
    @if ($toAdmin)
        <p style="margin:0 0 16px 0;"><strong style="color:#facc15;">Klient:</strong> {{ $ticket->user?->name }} ({{ $ticket->user?->email }})</p>
    @endif

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse; margin:0 0 18px 0;">
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

    @if ($ticket->estimated_price_from)
        <p style="margin:0 0 16px 0;"><strong style="color:#facc15;">Szacunkowa cena od:</strong> {{ number_format($ticket->estimated_price_from, 2, ',', ' ') }} PLN</p>
    @endif

    <p style="margin:0 0 8px 0; color:#facc15; font-weight:700;">Opis problemu</p>
    <div style="white-space:pre-line; border:1px solid #273244; border-radius:10px; background:#090b10; padding:14px; color:#e5e7eb;">{{ $ticket->description }}</div>
</x-email-shell>
