<tr>
<td class="header">
<a href="{{ config('app.url') }}" style="display: inline-block;">
@if (trim($slot) === 'Kocur Serwis Komputerowy' || trim($slot) === config('app.name'))
<img src="{{ asset('images/kocur-logo-amber.png') }}" class="logo" alt="Kocur Serwis Komputerowy">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
