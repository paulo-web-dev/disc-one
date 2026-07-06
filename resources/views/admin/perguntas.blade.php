@extends('layouts.admin')

@section('title', 'Perguntas')

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
        <span class="eyebrow">Conteúdo</span>
        <h1>Perguntas</h1>
        <p>As {{ $questions->count() }} perguntas do teste. Cada uma tem um enunciado e 4 frases — uma por dimensão DISC.</p>
    </div>
</div>

@forelse ($questions as $q)
    @php $byDim = $q->phrases->keyBy('dimension'); @endphp
    <div class="panel" style="margin-bottom: 14px;">
        <div class="panel-head" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                    <span style="font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--fg-4);">Pergunta {{ $q->number }}</span>
                    @if ($q->active ?? true)
                        <span class="pill" style="color: var(--success);">● Ativa</span>
                    @else
                        <span class="pill" style="color: var(--fg-4);">○ Inativa</span>
                    @endif
                </div>
                <div style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 16px; color: var(--fg-1);">
                    {{ $q->title ?: '(sem enunciado — clique em Editar)' }}
                </div>
            </div>
            <a href="{{ route('admin.questions.edit', $q) }}" class="btn btn-primary" style="padding: 8px 18px; font-size: 13px; white-space: nowrap;">Editar</a>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 8px;">
            @foreach ($dims as $letra => $info)
                @php $frase = $byDim[$letra]->phrase ?? '—'; @endphp
                <div style="display: flex; gap: 10px; align-items: flex-start; padding: 10px 12px; background: var(--bg-1); border: 1px solid var(--line-2); border-radius: var(--r-md);">
                    <span style="flex: 0 0 auto; width: 26px; height: 26px; border-radius: 7px; background: {{ $info['cor'] }}; color: #fff; font-weight: 700; font-size: 13px; display: flex; align-items: center; justify-content: center;">{{ $letra }}</span>
                    <div>
                        <div style="font-size: 11px; font-weight: 600; color: {{ $info['cor'] }}; text-transform: uppercase; letter-spacing: .04em;">{{ $info['nome'] }}</div>
                        <div style="font-size: 14px; color: var(--fg-1);">{{ $frase }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@empty
    <div class="panel" style="text-align: center; color: var(--fg-4); padding: 40px;">
        Nenhuma pergunta cadastrada. Rode o seeder das perguntas.
    </div>
@endforelse
@endsection
