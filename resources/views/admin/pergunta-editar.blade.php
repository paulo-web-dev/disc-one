@extends('layouts.admin')

@section('title', "Editar pergunta {$question->number}")

@php
    $colors = ['D' => '#2F66A8', 'I' => '#18A878', 'S' => '#6E9BD0', 'C' => '#4FA3C4'];
    $labels = ['D' => 'Dominância', 'I' => 'Influência', 'S' => 'Estabilidade', 'C' => 'Conformidade'];
@endphp

@push('styles')
<style>
    .qedit-field { margin-bottom: 16px; }
    .qedit-field label { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: var(--fg-2); margin-bottom: 8px; }
    .qedit-field .tag { width: 24px; height: 24px; border-radius: 7px; font-family: var(--font-mono); font-weight: 700; font-size: 12px; display: flex; align-items: center; justify-content: center; }
    .qedit-field input { width: 100%; padding: 12px 14px; font-size: 15px; font-family: inherit; background: var(--bg-1); border: 1px solid var(--line-2); border-radius: var(--r-md); color: var(--fg-1); outline: none; }
    .qedit-field input:focus { border-color: var(--brand-500); box-shadow: 0 0 0 3px rgba(46,115,184,0.16); }
    .qedit-err { color: var(--danger); font-size: 13px; margin-top: 6px; }
</style>
@endpush

@section('content')
<div class="adm-page-head">
    <div>
        <span class="eyebrow"><a href="{{ route('admin.questions') }}" style="color: var(--brand-600);">← Perguntas</a></span>
        <h1>Editar pergunta {{ $question->number }}</h1>
        <p>Ajuste o texto de cada frase. Cada uma pertence a uma dimensão fixa (D · I · S · C).</p>
    </div>
</div>

<div class="panel" style="max-width: 720px;">
    <form method="POST" action="{{ route('admin.questions.update', $question) }}">
        @csrf
        @method('PUT')

        @foreach (['D', 'I', 'S', 'C'] as $dim)
            <div class="qedit-field">
                <label>
                    <span class="tag" style="background: {{ $colors[$dim] }}1f; color: {{ $colors[$dim] }};">{{ $dim }}</span>
                    {{ $labels[$dim] }}
                </label>
                <input type="text" name="phrases[{{ $dim }}]"
                       value="{{ old('phrases.'.$dim, $phrases[$dim]->phrase ?? '') }}" maxlength="500" required>
                @error('phrases.'.$dim) <div class="qedit-err">{{ $message }}</div> @enderror
            </div>
        @endforeach

        <label class="checkbox-row" style="display: inline-flex; align-items: center; gap: 8px; color: var(--fg-2); font-size: 14px; margin: 8px 0 20px; cursor: pointer;">
            <input type="checkbox" name="active" value="1" {{ $question->active ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--brand-500);">
            Pergunta ativa
        </label>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">Salvar alterações</button>
            <a href="{{ route('admin.questions') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </form>
</div>
@endsection
