@extends('layouts.consultant')

@section('title', 'Meus respondentes')

@section('content')
<div class="adm-page-head">
    <div>
        <span class="eyebrow">Minha área</span>
        <h1>Olá, {{ $consultant->name }}</h1>
        <p>Compartilhe seu link e acompanhe quem respondeu o DISC por ele.</p>
    </div>
</div>

{{-- Link de divulgação --}}
<div class="panel" style="margin-bottom: 16px;">
    <div class="panel-head">
        <div><h3 style="font-size: 16px;">Seu link de divulgação</h3><div class="sub">Envie este link — quem responder por ele aparece aqui.</div></div>
    </div>
    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-top: 6px;">
        <code class="mono" style="flex: 1; min-width: 240px; font-size: 13px; color: var(--brand-700); background: var(--brand-50); padding: 12px 14px; border-radius: var(--r-md); border: 1px solid var(--brand-100); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $consultant->referralUrl() }}</code>
        <button type="button" class="btn btn-primary"
                onclick="navigator.clipboard.writeText('{{ $consultant->referralUrl() }}'); this.textContent='Copiado!'; setTimeout(() => this.textContent='Copiar link', 1500)">Copiar link</button>
    </div>
</div>

{{-- KPIs --}}
<div class="kpi-grid" style="grid-template-columns: repeat(2, 1fr);">
    <div class="kpi">
        <div class="top"><div class="ic" style="background: rgba(46,115,184,0.12); color: var(--brand-600);">👥</div></div>
        <div class="label">Respondentes</div>
        <div class="value">{{ $stats['total'] }}</div>
        <div class="sub">vieram pelo seu link</div>
    </div>
    <div class="kpi">
        <div class="top"><div class="ic" style="background: rgba(24,168,120,0.12); color: var(--disc-s);">✓</div></div>
        <div class="label">Concluídos</div>
        <div class="value">{{ $stats['completed'] }}</div>
        <div class="sub">testes finalizados</div>
    </div>
</div>

{{-- Tabela --}}
<div class="tbl-wrap" style="margin-top: 16px;">
    <div style="overflow-x: auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Respondente</th>
                    <th>Telefone</th>
                    <th>Perfil</th>
                    <th>Situação</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tests as $r)
                    @php
                        $prof = $r->status === 'completed' && $r->dominant_profile
                            ? config('disc_profiles.'.$r->dominant_profile) : null;
                        $isPremium = ($r->paid_purchases_count ?? 0) > 0;
                        $parts = array_filter(explode(' ', trim((string) $r->respondent_name)));
                        $initials = strtoupper(mb_substr($parts[0] ?? '', 0, 1) . mb_substr($parts[1] ?? ($parts[0] ?? ''), 0, count($parts) > 1 ? 1 : 0));
                    @endphp
                    <tr>
                        <td>
                            <div class="cell-user">
                                <div class="av">{{ $initials ?: '?' }}</div>
                                <div>
                                    <div class="nm">{{ $r->respondent_name ?: 'Sem nome' }}</div>
                                    <div class="em">{{ $r->respondent_email ?: '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="mono-cell">{{ $r->respondent_phone ?: '—' }}</td>
                        <td>
                            @if ($prof)
                                <span class="disc-badge" style="background: {{ $prof['hex'] }}1A; color: {{ $prof['hex'] }};">
                                    <span class="d" style="background: {{ $prof['hex'] }};">{{ $prof['letter'] }}</span>{{ $prof['name'] }}
                                </span>
                            @else
                                <span style="color: var(--fg-4);">—</span>
                            @endif
                        </td>
                        <td>
                            @if ($r->status === 'completed')
                                <span class="pill" style="color: var(--success); margin-right: 6px;">● Concluído</span>
                                @if ($isPremium)
                                    <span class="pill is-brand">Premium</span>
                                @else
                                    <span class="pill">Básico</span>
                                @endif
                            @else
                                <span class="pill" style="color: var(--warning);">○ Em andamento</span>
                            @endif
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            @if ($r->status === 'completed')
                                <a href="{{ route('disc.resultDocumento', ['id' => $r->id]) }}" target="_blank" class="btn btn-ghost" style="padding: 6px 12px; font-size: 13px;">Ver básico</a>
                                @if ($isPremium)
                                    <a href="{{ route('disc.resultDocumentoPremium', ['id' => $r->id]) }}" target="_blank" class="btn btn-ghost" style="padding: 6px 12px; font-size: 13px;">Ver premium</a>
                                @endif
                            @else
                                <span style="color: var(--fg-4); font-size: 13px;">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--fg-4);">
                            Ninguém respondeu pelo seu link ainda. Compartilhe-o para começar!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="tbl-foot">
        <span>Mostrando {{ $tests->count() }} de {{ $tests->total() }} respondentes</span>
        <div class="pager">
            <button onclick="location.href='{{ $tests->previousPageUrl() }}'" {{ $tests->onFirstPage() ? 'disabled' : '' }}>‹</button>
            <button class="on">{{ $tests->currentPage() }} / {{ $tests->lastPage() }}</button>
            <button onclick="location.href='{{ $tests->nextPageUrl() }}'" {{ $tests->hasMorePages() ? '' : 'disabled' }}>›</button>
        </div>
    </div>
</div>
@endsection
