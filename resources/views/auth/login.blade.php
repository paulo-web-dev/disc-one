@extends('layouts.app')

@section('title', 'Entrar')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth-wrap">
    <div class="card auth-card animate-fadeUp">
        <div class="brand-lockup">
            <span class="brand-mark"><img src="{{ asset('assets/logo.png') }}" alt="DISC ONE"></span>
            <span class="brand-word">DISC<b>ONE</b></span>
        </div>

        <div class="auth-head">
            <h1>Entrar</h1>
            <p>Acesse sua conta DISC ONE</p>
        </div>

        @if (session('status'))
            <div class="auth-status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field">
                <label for="email">E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       required autofocus autocomplete="username" placeholder="voce@email.com">
                @error('email') <span class="err">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="password">Senha</label>
                <input id="password" type="password" name="password"
                       required autocomplete="current-password" placeholder="••••••••">
                @error('password') <span class="err">{{ $message }}</span> @enderror
            </div>

            <div class="auth-row">
                <label class="checkbox-row">
                    <input type="checkbox" name="remember"> Lembrar de mim
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">Entrar</button>
        </form>

        <p class="auth-foot">Não tem conta? <a href="{{ route('register') }}">Criar conta</a></p>
    </div>
</div>
@endsection
