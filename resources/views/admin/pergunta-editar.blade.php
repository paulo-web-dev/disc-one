@extends('layouts.admin')

@section('title', 'Editar pergunta '.$question->number)

@php
    $dims = [
        'D' => ['nome' => 'Dominância', 'cor' => '#2F66A8'],
        'I' => ['nome' => 'Influência', 'cor' => '#18A878'],
        'S' => ['nome' => 'Estabilidade', 'cor' => '#6E9BD0'],
        'C' => ['nome' => 'Conformidade', 'cor' => '#4FA3C4'],
    ];
@endphp

@section('content')
<div class="adm-page-head">
    <div>
        <span class="eyebrow"><a href="{{ route('admin.questions') }}" style="color: var(--brand-600);">← Perguntas</a></span>
        <h1>Editar pergunta {{ $question->number }}</h1>
        <p>Ajuste o enunciado e as 4 frases. Cada frase pertence a uma dimensão DISC.</p>
    </div>
</div>

<div class="panel" style="max-width: 760px;">
    <form method="POST" action="{{ route('admin.questions.update', $question) }}">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 22px; padding-bottom: 20px; border-bottom: 1px solid var(--line-2);">
            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--fg-2); margin-bottom: 7px;">Enunciado da pergunta</label>
            <input type="text" name="title" value="{{ old('title', $question->title) }}" maxlength="255" required
                   placeholder="Ex.: Quando estou diante de uma situação nova:"
                   style="width: 100%; padding: 12px 14px; font-size: 15px; font-family: inherit; background: var(--bg-1); border: 1px solid var(--line-2); border-radius: var(--r-md); color: var(--fg-1); outline: none;">
            <div style="font-size: 12px; color: var(--fg-4); margin-top: 6px;">É o texto que aparece no topo da pergunta, acima das 4 frases.</div>
            @error('title')
                <div style="color: var(--danger); font-size: 13px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        @foreach ($dims as $letra => $info)
            @php $atual = old('phrases.'.$letra, $phrases[$letra]->phrase ?? ''); @endphp
            <div style="margin-bottom: 16px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: var(--fg-2); margin-bottom: 7px;">
                    <span style="width: 24px; height: 24px; border-radius: 6px; background: {{ $info['cor'] }}; color: #fff; font-weight: 700; font-size: 12px; display: flex; align-items: center; justify-content: center;">{{ $letra }}</span>
                    {{ $info['nome'] }}
                </label>
                <input type="text" name="phrases[{{ $letra }}]" value="{{ $atual }}" maxlength="500" required
                       style="width: 100%; padding: 12px 14px; font-size: 15px; font-family: inherit; background: var(--bg-1); border: 1px solid var(--line-2); border-radius: var(--r-md); color: var(--fg-1); outline: none;">
                @error('phrases.'.$letra)
                    <div style="color: var(--danger); font-size: 13px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        @endforeach

        <label style="display: flex; align-items: center; gap: 9px; margin: 6px 0 18px; cursor: pointer; font-size: 14px; color: var(--fg-2);">
            <input type="checkbox" name="active" value="1" {{ old('active', $question->active ?? true) ? 'checked' : '' }}>
            Pergunta ativa (aparece no teste)
        </label>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">Salvar alterações</button>
            <a href="{{ route('admin.questions') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </form>
</div>
@endsection
