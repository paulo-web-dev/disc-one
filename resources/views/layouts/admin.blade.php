<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Painel') · {{ config('app.name', 'DISC ONE') }} Admin</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/foundation.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>
@php
    use Illuminate\Support\Str;
    $u = auth()->user();
    $initials = Str::of($u->name)->explode(' ')->map(fn ($p) => Str::substr($p, 0, 1))->take(2)->implode('');
@endphp
<div class="adm">
    <aside class="adm-side">
        <div class="brand-lockup">
            <div class="brand-mark"><img src="{{ asset('assets/logo.png') }}" alt=""></div>
            <div>
                <div class="brand-word" style="font-size: 16px;">DISC<b>ONE</b></div>
                <div style="font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase; color: var(--fg-4); font-weight: 600; margin-top: 1px;">Admin</div>
            </div>
        </div>

        <div class="adm-nav-label">Gestão</div>

        <a href="{{ route('admin.dashboard') }}" class="adm-nav {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg class="ico" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.respondents') }}" class="adm-nav {{ request()->routeIs('admin.respondents') ? 'active' : '' }}">
            <svg class="ico" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Respondentes</span>
        </a>

        <a href="{{ route('admin.consultants') }}" class="adm-nav {{ request()->routeIs('admin.consultants*') ? 'active' : '' }}">
            <svg class="ico" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            <span>Consultores</span>
        </a>

        <a href="{{ route('admin.purchases') }}" class="adm-nav {{ request()->routeIs('admin.purchases') ? 'active' : '' }}">
            <svg class="ico" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            <span>Vendas</span>
        </a>

        <a href="{{ route('admin.questions') }}" class="adm-nav {{ request()->routeIs('admin.questions') || request()->routeIs('admin.questions.edit') ? 'active' : '' }}">
            <svg class="ico" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            <span>Perguntas</span>
        </a>

        <div class="spacer"></div>

        <div class="adm-upgrade">
            <div class="h">Ver a plataforma</div>
            <div class="p">Abra o funil do usuário para testar a jornada de ponta a ponta.</div>
            <a href="{{ url('/') }}" class="btn btn-secondary btn-block" style="font-size: 13px; padding: 9px 14px;">Abrir funil</a>
        </div>

        <div class="side-foot">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="adm-nav" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left;">
                    <svg class="ico" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    <span>Sair</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="adm-main">
        <header class="adm-top">
            <div class="search">
                <input placeholder="Buscar respondentes, e-mails…">
            </div>
            <div class="right">
                <div class="user-chip">
                    <div class="av">{{ $initials }}</div>
                    <div>
                        <div class="nm">{{ $u->name }}</div>
                        <div class="rl">Administrador</div>
                    </div>
                </div>
            </div>
        </header>

        <div class="adm-scroll">
            @if (session('success'))
                <div style="margin-bottom: 20px; padding: 12px 16px; background: rgba(24,168,120,0.1); border: 1px solid rgba(24,168,120,0.3); border-radius: var(--r-md); color: var(--success); font-size: 14px;">{{ session('success') }}</div>
            @endif
            @yield('content')
        </div>
    </main>
</div>

@stack('scripts')
</body>
</html>
