<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="0;url={{ route('public.home') }}">
    <title>{{ config('app.name', 'Ogarnie się') }}</title>
</head>
<body>
    <a href="{{ route('public.home') }}">Przejdź do strony głównej</a>
</body>
</html>
