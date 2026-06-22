@extends('layouts.app')

@section('title', 'Iniciar o teste')

@push('styles')
<style>
    .intro-wrap {
        min-height: 100vh; display: flex; align-items: center; justify-content: center;
        padding: var(--s-7); background: var(--grad-vignette), var(--bg-0);
    }
    .intro-card { width: 100%; max-width: 600px; padding: var(--s-9); }
    .intro-card h1 { font-size: 30px; margin: var(--s-4) 0 var(--s-3); }
    .intro-card .lead { color: var(--fg-3); font-size: 15px; margin-bottom: var(--s-6); }
    .intro-steps { list-style: none; padding: 0; margin: 0 0 var(--s-7); display: flex; flex-direction: column; gap: var(--s-4); }
    .intro-steps li { display: flex; gap: var(--s-4); align-items: flex-start; }
    .intro-steps .num {
        flex: none; width: 30px; height: 30px; border-radius: 50%;
        background: var(--grad-brand-soft); border: 1px solid var(--line-3);
        color: var(--brand-200); font-family: var(--font-mono); font-weight: 600; font-size: 13px;
        display: flex; align-items: center; justify-content: center;
    }
    .intro-steps .body { color: var(--fg-2); font-size: 14px; line-height: 1.5; }
    .intro-steps .body b { color: var(--fg-1); }
    .intro-resume {
        display: flex; align-items: center; gap: 10px; margin-bottom: var(--s-6);
        padding: 12px 16px; background: var(--disc-i-soft); border: 1px solid rgba(255,181,71,0.3);
        border-radius: var(--r-md); font-size: 14px; color: var(--fg-2);
    }
    .intro-fields { display: flex; flex-direction: column; gap: var(--s-4); margin-bottom: var(--s-6); text-align: left; }
    .intro-fields .field label { display: block; margin-bottom: var(--s-2); font-family: var(--font-display); font-weight: 600; color: var(--fg-2); font-size: 13px; }
    .intro-error { color: var(--danger); font-size: 13px; margin-top: 6px; }
</style>
@endpush

@section('content')
<div class="intro-wrap">
    <div class="card intro-card animate-fadeUp">
        <div class="brand-lockup">
            <span class="brand-mark"><img src="{{ asset('assets/logo.png') }}" alt="DISC ONE"></span>
            <span class="brand-word">DISC<b>ONE</b></span>
        </div>

        <span class="eyebrow" style="margin-top: var(--s-6);">Avaliação comportamental</span>
        <h1>Antes de começar</h1>
        <p class="lead">São 24 perguntas. Em cada uma, ordene as 4 frases conforme o quanto elas descrevem você.</p>

        <ul class="intro-steps">
            <li>
                <span class="num">1</span>
                <span class="body">Ordene as alternativas de <b>1 a 4</b>: digite <b>1</b> para a que <b>MAIS</b> te descreve e <b>4</b> para a que <b>MENOS</b> te descreve.</span>
            </li>
            <li>
                <span class="num">2</span>
                <span class="body"><b>Não repita números</b> — cada frase recebe uma posição diferente (1, 2, 3 e 4).</span>
            </li>
            <li>
                <span class="num">3</span>
                <span class="body">Responda de forma <b>intuitiva</b>, sem interromper. Leva cerca de <b>15 minutos</b>.</span>
            </li>
        </ul>

        <form method="POST" action="{{ route('test.start') }}">
            @csrf
            <div class="intro-fields">
                <div class="field">
                    <label for="respondent_name">Seu nome</label>
                    <input type="text" id="respondent_name" name="respondent_name"
                           value="{{ old('respondent_name') }}" placeholder="Como podemos te chamar?" required>
                    @error('respondent_name') <div class="intro-error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label for="respondent_email">Seu e-mail</label>
                    <input type="email" id="respondent_email" name="respondent_email"
                           value="{{ old('respondent_email') }}" placeholder="email@exemplo.com" required>
                    @error('respondent_email') <div class="intro-error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label for="respondent_phone">Celular / WhatsApp</label>
                    <input type="tel" id="respondent_phone" name="respondent_phone"
                           value="{{ old('respondent_phone') }}" placeholder="(11) 90000-0000" required>
                    @error('respondent_phone') <div class="intro-error">{{ $message }}</div> @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block">Iniciar questionário</button>
        </form>
    </div>
</div>
@endsection
