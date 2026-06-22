@extends('layouts.admin')

@php $editing = $consultant->exists; @endphp

@section('title', $editing ? 'Editar consultor' : 'Novo consultor')

@push('styles')
<style>
    .cform-field { margin-bottom: 16px; }
    .cform-field label { display: block; font-size: 13px; font-weight: 600; color: var(--fg-2); margin-bottom: 8px; }
    .cform-field input { width: 100%; padding: 12px 14px; font-size: 15px; font-family: inherit; background: var(--bg-1); border: 1px solid var(--line-2); border-radius: var(--r-md); color: var(--fg-1); outline: none; }
    .cform-field input:focus { border-color: var(--brand-500); box-shadow: 0 0 0 3px rgba(46,115,184,0.16); }
    .cform-field .hint { font-size: 12px; color: var(--fg-4); margin-top: 6px; }
    .cform-err { color: var(--danger); font-size: 13px; margin-top: 6px; }
</style>
@endpush

@section('content')
<div class="adm-page-head">
    <div>
        <span class="eyebrow"><a href="{{ route('admin.consultants') }}" style="color: var(--brand-600);">← Consultores</a></span>
        <h1>{{ $editing ? 'Editar consultor' : 'Novo consultor' }}</h1>
        <p>{{ $editing ? 'Atualize os dados de acesso e o código do link.' : 'Crie o acesso do consultor e o link de divulgação dele.' }}</p>
    </div>
</div>

<div class="panel" style="max-width: 640px;">
    <form method="POST" action="{{ $editing ? route('admin.consultants.update', $consultant) : route('admin.consultants.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="cform-field">
            <label for="name">Nome</label>
            <input type="text" id="name" name="name" value="{{ old('name', $consultant->name) }}" required>
            @error('name') <div class="cform-err">{{ $message }}</div> @enderror
        </div>

        <div class="cform-field">
            <label for="email">E-mail (login do consultor)</label>
            <input type="email" id="email" name="email" value="{{ old('email', $consultant->email) }}" required>
            @error('email') <div class="cform-err">{{ $message }}</div> @enderror
        </div>

        <div class="cform-field">
            <label for="password">Senha {{ $editing ? '(deixe em branco para manter a atual)' : '' }}</label>
            <input type="text" id="password" name="password" value="" autocomplete="new-password" {{ $editing ? '' : 'required' }}>
            <div class="hint">O consultor usa este e-mail e senha para entrar em <span class="mono">/login</span>.</div>
            @error('password') <div class="cform-err">{{ $message }}</div> @enderror
        </div>

        <div class="cform-field">
            <label for="referral_code">Código do link {{ $editing ? '' : '(opcional — gerado automático se ficar vazio)' }}</label>
            <input type="text" id="referral_code" name="referral_code" value="{{ old('referral_code', $consultant->referral_code) }}" placeholder="ex.: joaosilva" {{ $editing ? 'required' : '' }}>
            <div class="hint">Vira o link <span class="mono">{{ url('/r') }}/SEU-CODIGO</span> · só letras, números, hífen e underline.</div>
            @error('referral_code') <div class="cform-err">{{ $message }}</div> @enderror
        </div>

        <div style="display: flex; gap: 12px; margin-top: 8px;">
            <button type="submit" class="btn btn-primary">{{ $editing ? 'Salvar alterações' : 'Criar consultor' }}</button>
            <a href="{{ route('admin.consultants') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </form>
</div>
@endsection
