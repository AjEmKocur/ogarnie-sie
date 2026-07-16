# Kocur Serwis Komputerowy

Portal internetowy dla lokalnego serwisu komputerowego.

## Zakres aplikacji

- publiczna strona informacyjna z oferta, cennikiem, realizacjami, opiniami i formularzem kontaktowym,
- panel klienta do zakladania i obslugi zgloszen serwisowych,
- panel administratora do zarzadzania zgloszeniami, uslugami, realizacjami, galeria oraz opiniami,
- system wiadomosci i zalacznikow przy zgloszeniach,
- obsluga statusow, platnosci na miejscu oraz historii zmian,
- zabezpieczenie formularza kontaktowego Cloudflare Turnstile,
- wysylka powiadomien e-mail przez SMTP,
- integracja z modulem moderacji opinii opartym o FastAPI.

## Technologie

- Laravel 12,
- Blade,
- Tailwind CSS,
- PostgreSQL,
- FastAPI,
- Cloudflare Turnstile,
- zewnetrzny storage zgodny z S3 dla plikow i obrazow.
