@extends('layouts.app')

@section('title', "Pergunta {$n} de {$total}")

@push('styles')
<link rel="stylesheet" href="{{ asset('css/assessment.css') }}">
<link rel="stylesheet" href="{{ asset('css/assessment-rank.css') }}">
@endpush

@section('content')
@php $pct = (int) round(($answeredCount / $total) * 100); @endphp
<div class="asmt">
    <div class="asmt-top">
        <div class="asmt-top-inner">
            <span class="step-label">Pergunta <b>{{ $n }}</b> de {{ $total }}</span>
            <div class="asmt-progress"><i style="width: {{ $pct }}%"></i></div>
            <span class="asmt-pct">{{ $pct }}%</span>
        </div>
    </div>

    <div class="asmt-body">
        <div class="asmt-stage">
            <div class="q-head">
                <span class="eyebrow">DISC ONE</span>
                <h2>Como você se descreve?</h2>
                <p>Ordene de <b>1</b> (mais te descreve) a <b>4</b> (menos te descreve). Sem repetir números.</p>
            </div>

            @error('order')
                <div class="auth-status" style="background: var(--disc-d-soft); color: var(--danger); border-color: rgba(224,85,106,0.3);">
                    {{ $message }}
                </div>
            @enderror

            <form method="POST" action="{{ route('test.answer', [$test, $n]) }}" id="qform">
                @csrf

                <div class="stmt-list">
                    @foreach ($phrases as $p)
                        <div class="stmt" data-phrase="{{ $p->id }}">
                            <span class="txt">{{ $p->phrase }}</span>
                            <div class="rank-btns">
                                @for ($r = 1; $r <= 4; $r++)
                                    <button type="button" class="rank-btn"
                                            data-phrase="{{ $p->id }}" data-rank="{{ $r }}">{{ $r }}</button>
                                @endfor
                            </div>
                            <input type="hidden" name="order[{{ $p->id }}]" value="{{ $existing[$p->id] ?? '' }}">
                        </div>
                    @endforeach
                </div>

                <div class="asmt-nav">
                    <div class="asmt-nav-inner">
                        @if ($n > 1)
                            <a href="{{ route('test.question', [$test, $n - 1]) }}" class="btn btn-ghost">Anterior</a>
                        @else
                            <span></span>
                        @endif

                        <span class="hint" id="q-hint">Ordene as 4 frases.</span>

                        <button type="submit" class="btn btn-primary" id="q-submit" disabled>
                            {{ $n < $total ? 'Próxima' : 'Finalizar' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const form = document.getElementById('qform');
    const stmts = Array.from(form.querySelectorAll('.stmt'));
    const submitBtn = document.getElementById('q-submit');
    const hint = document.getElementById('q-hint');
    const total = stmts.length; // 4

    function inputFor(pid) {
        return form.querySelector('input[name="order[' + pid + ']"]');
    }

    function setRank(pid, rank) {
        // Se outra frase já tem esse número, libera ela (mantém os números únicos).
        stmts.forEach(function (s) {
            const other = s.dataset.phrase;
            if (other !== pid && parseInt(inputFor(other).value, 10) === rank) {
                inputFor(other).value = '';
            }
        });
        const input = inputFor(pid);
        const current = parseInt(input.value, 10);
        input.value = (current === rank) ? '' : rank; // clicar de novo desmarca
        render();
    }

    function render() {
        let chosen = 0;
        stmts.forEach(function (s) {
            const pid = s.dataset.phrase;
            const rank = parseInt(inputFor(pid).value, 10) || null;
            if (rank) chosen++;
            s.classList.toggle('answered', !!rank);
            s.querySelectorAll('.rank-btn').forEach(function (b) {
                b.classList.toggle('on', parseInt(b.dataset.rank, 10) === rank);
            });
        });
        const ok = chosen === total;
        submitBtn.disabled = !ok;
        hint.textContent = ok ? 'Pronto — pode avançar.' : ('Falta ordenar ' + (total - chosen) + ' frase(s).');
        hint.classList.toggle('ok', ok);
    }

    form.querySelectorAll('.rank-btn').forEach(function (b) {
        b.addEventListener('click', function () {
            setRank(b.dataset.phrase, parseInt(b.dataset.rank, 10));
        });
    });

    render();
})();
</script>
@endpush
