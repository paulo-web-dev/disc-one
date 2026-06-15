<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Avaliação comportamental') · {{ config('app.name', 'DISC ONE') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">

    {{-- As fontes são importadas dentro do foundation.css; o preconnect só acelera o carregamento --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- CSS base/tokens — vale para todas as telas --}}
    <link rel="stylesheet" href="{{ asset('css/foundation.css') }}">

    {{-- CSS específico de cada tela entra aqui (ex.: @push('styles') ... @endpush na view) --}}
    @stack('styles')
</head>
<body>
    @yield('content')

    {{-- Scripts específicos de cada tela entram aqui (ex.: Chart.js na tela de resultado) --}}
    @stack('scripts')
</body>
</html>
