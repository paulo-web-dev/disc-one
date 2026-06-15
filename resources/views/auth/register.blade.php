@extends('layouts.app')

@section('title', 'Criar conta')

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
            <h1>Criar conta</h1>
            <p>Comece sua avaliação DISC ONE</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="field">
                <label for="name">Nome</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                       required autofocus autocomplete="name" placeholder="Seu nome">
                @error('name') <span class="err">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="email">E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       required autocomplete="username" placeholder="voce@email.com">
                @error('email') <span class="err">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="password">Senha</label>
                <input id="password" type="password" name="password"
                       required autocomplete="new-password" placeholder="••••••••">
                @error('password') <span class="err">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Confirmar senha</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       required autocomplete="new-password" placeholder="••••••••">
                @error('password_confirmation') <span class="err">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">Criar conta</button>
        </form>

        <p class="auth-foot">Já tem conta? <a href="{{ route('login') }}">Entrar</a></p>
    </div>
</div>
@endsection
