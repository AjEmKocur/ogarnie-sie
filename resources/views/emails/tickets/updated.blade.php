<x-email-shell
    title="Aktualizacja zgłoszenia"
    heading="Twoje zgłoszenie zostało zaktualizowane"
    summary="W panelu klienta znajdziesz pełne szczegóły realizacji."
    :action-url="route('client.tickets.show', $ticket)"
>
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
            <td align="right" style="padding:10px 0; border-bottom:1px solid #273244; color:#ffffff; font-weight:700;">{{ $oldStatusLabel }} → {{ $newStatusLabel }}</td>
        </tr>
        <tr>
            <td style="padding:10px 0; border-bottom:1px solid #273244; color:#94a3b8;">Płatność</td>
            <td align="right" style="padding:10px 0; border-bottom:1px solid #273244; color:#ffffff; font-weight:700;">
                {{ \App\Models\Ticket::paymentStatuses()[$ticket->payment_status] ?? $ticket->payment_status }}
                @if ($ticket->payment_amount !== null)
                    ({{ number_format((float) $ticket->payment_amount, 2, ',', ' ') }} PLN)
                @endif
            </td>
        </tr>
    </table>

    @if ($ticket->payment_note)
        <p style="margin:0 0 8px 0; color:#facc15; font-weight:700;">Notatka dotycząca płatności</p>
        <div style="white-space:pre-line; border:1px solid #273244; border-radius:10px; background:#090b10; padding:14px; color:#e5e7eb;">{{ $ticket->payment_note }}</div>
    @endif
</x-email-shell>
