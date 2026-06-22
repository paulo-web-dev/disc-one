@extends('layouts.app')

@section('title', 'Seu resultado')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/result.css') }}">
<style>
    .res-breakdown { display: flex; gap: 18px; flex-wrap: wrap; justify-content: center; margin-top: 20px; padding-top: 18px; border-top: 1px solid var(--line-1); width: 100%; }
    .res-breakdown .it { display: inline-flex; align-items: center; gap: 8px; font-size: 14px; color: var(--fg-2); }
    .res-breakdown .it b { font-family: var(--font-mono); color: var(--fg-1); }
    .chart-card h3 { font-family: var(--font-display); font-size: 16px; color: var(--fg-2); align-self: flex-start; margin-bottom: 14px; }
    .trait .ck { font-weight: 800; font-size: 13px; }
    .offer-note { text-align: center; color: var(--fg-4); font-size: 12px; margin-top: 10px; }
</style>
@endpush

@section('content')
<div class="res">
    <div class="res-bar">
        <div class="res-bar-inner">
            <div class="brand-lockup">
                <span class="brand-mark"><img src="{{ asset('assets/logo.png') }}" alt="DISC ONE"></span>
                <span class="brand-word">DISC<b>ONE</b></span>
            </div>
            <span class="spacer"></span>
            <a href="{{ route('dashboard') }}" class="btn btn-ghost">Painel</a>
        </div>
    </div>

    <div class="res-wrap">
        {{-- Revelação do perfil dominante --}}
        <div class="reveal animate-fadeUp">
            <span class="eyebrow">Seu perfil natural</span>
            <div class="big-letter" style="background: {{ $profile['hex'] }}1f; color: {{ $profile['hex'] }};">
                {{ $profile['letter'] }}
            </div>
            <h1>{{ $profile['name'] }}</h1>
            <div class="arche">{{ $profile['archetype'] }}</div>
            <p class="tagline">{{ $profile['tagline'] }}</p>
        </div>

        {{-- Gráfico + características --}}
        <div class="res-overview">
            <div class="chart-card">
                <h3>Natural</h3>
                <div style="width: 100%;">
                    <canvas id="discChart" height="240"></canvas>
                </div>
                <div class="res-breakdown">
                    <span class="it"><i class="disc-dot dot-d"></i> D <b>{{ round($test->percent_d) }}%</b></span>
                    <span class="it"><i class="disc-dot dot-i"></i> I <b>{{ round($test->percent_i) }}%</b></span>
                    <span class="it"><i class="disc-dot dot-s"></i> S <b>{{ round($test->percent_s) }}%</b></span>
                    <span class="it"><i class="disc-dot dot-c"></i> C <b>{{ round($test->percent_c) }}%</b></span>
                </div>
            </div>

            <div class="traits-card">
                <h3>Suas características</h3>
                <p class="sub">Traços marcantes do perfil {{ $profile['name'] }}.</p>
                <div class="trait-list">
                    @foreach ($profile['traits'] as $trait)
                        <div class="trait">
                            <span class="ck" style="background: {{ $profile['hex'] }}1f; color: {{ $profile['hex'] }};">✓</span>
                            <span>{{ $trait }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Oferta do relatório completo (checkout + PDF chegam nas Etapas 8/9) --}}
        <div class="offer">
            <div class="offer-grid">
                <div class="offer-left">
                    <span class="eyebrow">Relatório completo</span>
                    <h2>Vá fundo no seu perfil</h2>
                    <div class="offer-incl">
                        <div class="li"><span class="ck">✓</span> Análise completa do perfil dominante</div>
                        <div class="li"><span class="ck">✓</span> Pontos fortes e pontos de melhoria</div>
                        <div class="li"><span class="ck">✓</span> Estilo de liderança e de comunicação</div>
                        <div class="li"><span class="ck">✓</span> Ambiente ideal e motivadores</div>
                        <div class="li"><span class="ck">✓</span> Perfil comercial e de equipe</div>
                    </div>
                </div>
                <div class="offer-right">
                    <span class="price-old">R$ 97</span>
                    <div class="price">R$ 49</div>
                    <div class="price-note">pagamento único · PDF para download</div>
                    <a href="{{ route('disc.checkout', ['test' => $test->id]) }}" class="btn btn-primary btn-lg btn-block">
                        Liberar relatório completo
                    </a>
                    <p class="offer-note">O teste e este resultado são gratuitos. O pagamento libera só o PDF.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const ctx = document.getElementById('discChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['D', 'I', 'S', 'C'],
            datasets: [{
                data: [
                    {{ $test->percent_d }},
                    {{ $test->percent_i }},
                    {{ $test->percent_s }},
                    {{ $test->percent_c }}
                ],
                backgroundColor: ['#2F66A8', '#18A878', '#6E9BD0', '#4FA3C4'],
                borderRadius: 8,
                maxBarThickness: 64
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { color: '#5A6473', callback: (v) => v + '%' },
                    grid: { color: 'rgba(40,60,90,0.08)' }
                },
                x: {
                    ticks: { color: '#3D4654', font: { size: 14, weight: '600' } },
                    grid: { display: false }
                }
            }
        }
    });
})();
</script>
@endpush
