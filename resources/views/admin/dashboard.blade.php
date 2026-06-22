@extends('layouts.admin')

@section('title', 'Dashboard')

@php
    $totalDist = array_sum($distribution);
@endphp

@section('content')
<div class="adm-page-head">
    <div>
        <span class="eyebrow">Visão geral</span>
        <h1>Dashboard</h1>
        <p>Acompanhe os testes, respondentes e a distribuição de perfis.</p>
    </div>
</div>

{{-- KPIs --}}
<div class="kpi-grid">
    <div class="kpi">
        <div class="top"><div class="ic" style="background: rgba(46,115,184,0.12); color: var(--brand-600);">📋</div></div>
        <div class="label">Total de testes</div>
        <div class="value">{{ $totalTests }}</div>
        <div class="sub">desde o início</div>
    </div>
    <div class="kpi">
        <div class="top"><div class="ic" style="background: rgba(24,168,120,0.12); color: var(--disc-s);">✓</div></div>
        <div class="label">Respondidos</div>
        <div class="value">{{ $completed }}</div>
        <div class="sub">testes concluídos</div>
    </div>
    <div class="kpi">
        <div class="top"><div class="ic" style="background: rgba(224,162,60,0.12); color: var(--disc-i);">⏳</div></div>
        <div class="label">Pendentes</div>
        <div class="value">{{ $pending }}</div>
        <div class="sub">em andamento</div>
    </div>
    <div class="kpi">
        <div class="top"><div class="ic" style="background: rgba(79,163,196,0.12); color: var(--disc-c);">👥</div></div>
        <div class="label">Respondentes</div>
        <div class="value">{{ $respondents }}</div>
        <div class="sub">responderam o teste</div>
    </div>
    <div class="kpi">
        <div class="top"><div class="ic" style="background: rgba(224,85,106,0.12); color: var(--disc-d);">R$</div></div>
        <div class="label">Receita</div>
        <div class="value">R$ {{ number_format($revenue, 2, ',', '.') }}</div>
        <div class="sub">relatórios pagos</div>
    </div>
</div>

{{-- Distribuição de perfis --}}
<div class="adm-grid g-2" style="margin-top: 16px;">
    <div class="panel">
        <div class="panel-head">
            <div><h3>Mix de perfis</h3><div class="sub">Distribuição DISC (testes concluídos)</div></div>
        </div>

        @if ($totalDist > 0)
            <div style="display: flex; justify-content: center; padding: 12px 0;">
                <div style="width: 260px; height: 260px;"><canvas id="distChart"></canvas></div>
            </div>
            <div class="legend" style="justify-content: center; gap: 18px; margin-top: 8px;">
                <span><span class="sw" style="background: #2F66A8;"></span>D · {{ $distribution['D'] }}</span>
                <span><span class="sw" style="background: #18A878;"></span>I · {{ $distribution['I'] }}</span>
                <span><span class="sw" style="background: #6E9BD0;"></span>S · {{ $distribution['S'] }}</span>
                <span><span class="sw" style="background: #4FA3C4;"></span>C · {{ $distribution['C'] }}</span>
            </div>
        @else
            <p style="color: var(--fg-4); text-align: center; padding: 48px 0;">Nenhum teste concluído ainda.</p>
        @endif
    </div>

    <div class="panel">
        <div class="panel-head">
            <div><h3>Respondidos × Pendentes</h3><div class="sub">Status dos testes</div></div>
        </div>
        @if ($totalTests > 0)
            <div style="padding: 16px 0;">
                @php $pctDone = (int) round($completed / max($totalTests, 1) * 100); @endphp
                <div style="display: flex; justify-content: space-between; font-size: 14px; color: var(--fg-2); margin-bottom: 8px;">
                    <span>Concluídos</span><span><b style="color:var(--fg-1);">{{ $completed }}</b> / {{ $totalTests }}</span>
                </div>
                <div class="asmt-progress" style="height: 10px;"><i style="width: {{ $pctDone }}%;"></i></div>
                <p style="color: var(--fg-4); font-size: 13px; margin-top: 12px;">{{ $pctDone }}% dos testes iniciados foram concluídos · {{ $pending }} ainda pendente(s).</p>
            </div>
        @else
            <p style="color: var(--fg-4); text-align: center; padding: 48px 0;">Nenhum teste iniciado ainda.</p>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@if ($totalDist > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const ctx = document.getElementById('distChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['D', 'I', 'S', 'C'],
            datasets: [{
                data: [{{ $distribution['D'] }}, {{ $distribution['I'] }}, {{ $distribution['S'] }}, {{ $distribution['C'] }}],
                backgroundColor: ['#2F66A8', '#18A878', '#6E9BD0', '#4FA3C4'],
                borderColor: 'transparent',
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '64%',
            plugins: { legend: { display: false } }
        }
    });
})();
</script>
@endif
@endpush
