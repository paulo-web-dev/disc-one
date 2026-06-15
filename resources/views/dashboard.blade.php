@extends('layouts.app')

@section('title', 'Painel')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth-wrap">
    <div class="card auth-card animate-fadeUp" style="max-width: 520px; text-align: center;">
        <div class="brand-lockup">
            <span class="brand-mark"><img src="{{ asset('assets/logo.png') }}" alt="DISC ONE"></span>
            <span class="brand-word">DISC<b>ONE</b></span>
        </div>

        <span class="eyebrow" style="margin-top: var(--s-6);">Painel</span>
        <h1 style="margin: var(--s-4) 0 var(--s-3);">Olá, {{ auth()->user()->name }} 👋</h1>
        <p style="color: var(--fg-3);">
            Seu papel: <span class="pill">{{ auth()->user()->role }}</span>
        </p>

        <div style="margin: var(--s-7) 0; display: flex; gap: var(--s-3); justify-content: center; flex-wrap: wrap;">
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Painel administrativo</a>
            @else
                <a href="{{ route('test.intro') }}" class="btn btn-primary">Iniciar questionário</a>
            @endif
        </div>

        <form method="POST" action="{{ route('logout') }}" style="margin-top: var(--s-6);">
            @csrf
            <button type="submit" class="btn btn-ghost">Sair</button>
        </form>
    </div>
</div>
@endsection
