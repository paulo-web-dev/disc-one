@extends('layouts.admin')

@section('title', 'Perguntas')

@php
    $colors = ['D' => '#FF5470', 'I' => '#FFB547', 'S' => '#2BD9A1', 'C' => '#6E8BFF'];
    $labels = ['D' => 'Dominância', 'I' => 'Influência', 'S' => 'Estabilidade', 'C' => 'Conformidade'];
@endphp

@section('content')
<div class="adm-page-head">
    <div>
        <span class="eyebrow">Gestão</span>
        <h1>Perguntas</h1>
        <p>As {{ $questions->count() }} perguntas do teste e suas 4 frases (D · I · S · C).</p>
    </div>
</div>

<div style="display: flex; flex-direction: column; gap: 14px;">
    @foreach ($questions as $question)
        @php $byDim = $question->phrases->keyBy('dimension'); @endphp
        <div class="panel">
            <div class="panel-head">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span class="av" style="background: var(--bg-3);">{{ $question->number }}</span>
                    <div>
                        <h3 style="font-size: 16px;">Pergunta {{ $question->number }}</h3>
                        <div class="sub">
                            @if ($question->active)
                                <span style="color: var(--success);">● Ativa</span>
                            @else
                                <span style="color: var(--fg-4);">○ Inativa</span>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-ghost" style="padding: 7px 16px; font-size: 13px;">Editar</a>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 6px;">
                @foreach (['D', 'I', 'S', 'C'] as $dim)
                    <div style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; background: var(--bg-1); border: 1px solid var(--line-1); border-radius: var(--r-md);">
                        <span style="flex: none; width: 24px; height: 24px; border-radius: 7px; background: {{ $colors[$dim] }}1f; color: {{ $colors[$dim] }}; font-family: var(--font-mono); font-weight: 700; font-size: 12px; display: flex; align-items: center; justify-content: center;">{{ $dim }}</span>
                        <span style="font-size: 14px; color: var(--fg-1);">{{ $byDim[$dim]->phrase ?? '—' }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
