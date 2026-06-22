@extends('layouts.admin')

@section('title', $user->name)

@push('styles')
<style>
    .det-grid { display: grid; grid-template-columns: 320px 1fr; gap: 20px; align-items: start; }
    .det-id .av-lg { width: 64px; height: 64px; border-radius: 16px; background: var(--grad-brand-soft); border: 1px solid var(--line-3); display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-weight: 700; font-size: 22px; color: var(--brand-600); }
    .det-row { display: flex; justify-content: space-between; gap: 12px; padding: 11px 0; border-bottom: 1px solid var(--line-1); font-size: 14px; }
    .det-row:last-child { border-bottom: none; }
    .det-row .k { color: var(--fg-4); }
    .det-row .v { color: var(--fg-1); font-weight: 500; text-align: right; }
    .det-scores { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-top: 18px; }
    .det-score { text-align: center; padding: 14px 8px; background: var(--bg-1); border: 1px solid var(--line-1); border-radius: var(--r-md); }
    .det-score .pf { font-family: var(--font-mono); font-size: 12px; font-weight: 700; }
    .det-score .pc { font-family: var(--font-display); font-size: 22px; font-weight: 700; color: var(--fg-1); margin-top: 2px; }
    .det-score .pt { font-size: 11px; color: var(--fg-4); }
    @media (max-width: 860px) { .det-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="adm-page-head">
    <div>
        <span class="eyebrow"><a href="{{ route('admin.respondents') }}" style="color: var(--brand-600);">← Respondentes</a></span>
        <h1>{{ $user->name }}</h1>
        <p>{{ $user->email }}</p>
    </div>
</div>

<div class="det-grid">
    {{-- Coluna de identificação --}}
    <div class="panel">
        <div class="det-id" style="display: flex; align-items: center; gap: 14px; margin-bottom: 18px;">
            @php use Illuminate\Support\Str; $ini = Str::of($user->name)->explode(' ')->map(fn($p)=>Str::substr($p,0,1))->take(2)->implode(''); @endphp
            <div class="av-lg">{{ $ini ?: '?' }}</div>
            <div>
                @if ($profile)
                    <span class="disc-badge" style="background: {{ $profile['hex'] }}1A; color: {{ $profile['hex'] }};">
                        <span class="d" style="background: {{ $profile['hex'] }};">{{ $profile['letter'] }}</span>{{ $profile['archetype'] }}
                    </span>
                @else
                    <span class="pill" style="color: var(--fg-4);">Sem resultado ainda</span>
                @endif
            </div>
        </div>

        <div class="det-row"><span class="k">E-mail</span><span class="v">{{ $user->email }}</span></div>
        <div class="det-row"><span class="k">Telefone</span><span class="v">{{ $user->phone ?: '—' }}</span></div>
        <div class="det-row"><span class="k">Cadastro</span><span class="v">{{ $user->created_at->format('d/m/Y') }}</span></div>
        <div class="det-row"><span class="k">Testes</span><span class="v">{{ $tests->count() }}</span></div>
    </div>

    {{-- Coluna de resultado --}}
    <div class="panel">
        @if ($latest && $profile)
            <div class="panel-head">
                <div><h3>Resultado · {{ $profile['name'] }}</h3><div class="sub">Concluído em {{ $latest->completed_at?->format('d/m/Y H:i') }}</div></div>
            </div>

            <div style="width: 100%; height: 240px;"><canvas id="detChart"></canvas></div>

            <div class="det-scores">
                <div class="det-score"><div class="pf" style="color:#2F66A8;">D</div><div class="pc">{{ round($latest->percent_d) }}%</div><div class="pt">{{ $latest->score_d }} pts</div></div>
                <div class="det-score"><div class="pf" style="color:#18A878;">I</div><div class="pc">{{ round($latest->percent_i) }}%</div><div class="pt">{{ $latest->score_i }} pts</div></div>
                <div class="det-score"><div class="pf" style="color:#6E9BD0;">S</div><div class="pc">{{ round($latest->percent_s) }}%</div><div class="pt">{{ $latest->score_s }} pts</div></div>
                <div class="det-score"><div class="pf" style="color:#4FA3C4;">C</div><div class="pc">{{ round($latest->percent_c) }}%</div><div class="pt">{{ $latest->score_c }} pts</div></div>
            </div>

            <p style="color: var(--fg-3); font-size: 14px; margin-top: 18px; line-height: 1.6;">{{ $profile['tagline'] }}</p>
        @else
            <p style="color: var(--fg-4); text-align: center; padding: 60px 0;">Este respondente ainda não concluiu nenhum teste.</p>
        @endif
    </div>
</div>

{{-- Histórico de testes --}}
@if ($tests->isNotEmpty())
<div class="panel" style="margin-top: 20px;">
    <div class="panel-head"><div><h3>Histórico de testes</h3><div class="sub">Todas as tentativas deste respondente</div></div></div>
    <table class="tbl" style="margin-top: 8px;">
        <thead><tr><th>#</th><th>Status</th><th>Perfil</th><th>Iniciado</th><th>Concluído</th></tr></thead>
        <tbody>
            @foreach ($tests as $t)
                @php $tp = $t->dominant_profile ? config('disc_profiles.'.$t->dominant_profile) : null; @endphp
                <tr>
                    <td class="mono-cell">#{{ $t->id }}</td>
                    <td>
                        @if ($t->status === 'completed')
                            <span class="pill" style="color: var(--success);">Concluído</span>
                        @else
                            <span class="pill" style="color: var(--disc-i);">Pendente</span>
                        @endif
                    </td>
                    <td>
                        @if ($tp)
                            <span class="disc-badge" style="background: {{ $tp['hex'] }}1A; color: {{ $tp['hex'] }};"><span class="d" style="background: {{ $tp['hex'] }};">{{ $tp['letter'] }}</span>{{ $tp['name'] }}</span>
                        @else
                            <span style="color: var(--fg-4);">—</span>
                        @endif
                    </td>
                    <td class="mono-cell">{{ $t->started_at?->format('d/m/Y H:i') ?? '—' }}</td>
                    <td class="mono-cell">{{ $t->completed_at?->format('d/m/Y H:i') ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection

@push('scripts')
@if ($latest && $profile)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const ctx = document.getElementById('detChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['D', 'I', 'S', 'C'],
            datasets: [{
                data: [{{ $latest->percent_d }}, {{ $latest->percent_i }}, {{ $latest->percent_s }}, {{ $latest->percent_c }}],
                backgroundColor: ['#2F66A8', '#18A878', '#6E9BD0', '#4FA3C4'],
                borderRadius: 8,
                maxBarThickness: 56
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100, ticks: { color: '#5A6473', callback: (v) => v + '%' }, grid: { color: 'rgba(40,60,90,0.08)' } },
                x: { ticks: { color: '#3D4654', font: { size: 14, weight: '600' } }, grid: { display: false } }
            }
        }
    });
})();
</script>
@endif
@endpush
