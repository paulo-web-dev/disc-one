@extends('layouts.admin')

@section('title', 'Respondentes')

@section('content')
<div class="adm-page-head">
    <div>
        <span class="eyebrow">Gestão</span>
        <h1>Respondentes</h1>
        <p>{{ $respondents->total() }} respondente(s) cadastrado(s) · quem respondeu e quem não.</p>
    </div>
</div>

<div class="tbl-wrap">
    <div class="tbl-toolbar">
        <form method="GET" action="{{ route('admin.respondents') }}" class="tbl-search" style="flex: 1;">
            <input type="text" name="q" value="{{ $q }}" placeholder="Buscar nome ou e-mail… (Enter para filtrar)">
        </form>
        @if ($q !== '')
            <a href="{{ route('admin.respondents') }}" class="fchip">Limpar filtro</a>
        @endif
    </div>

    <div style="overflow-x: auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Respondente</th>
                    <th>Telefone</th>
                    <th>Última conclusão</th>
                    <th>Perfil</th>
                    <th>Situação</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($respondents as $r)
                    @php
                        use Illuminate\Support\Str;
                        $latest = $r->tests->first();
                        $responded = $r->tests->isNotEmpty();
                        $prof = $latest && $latest->dominant_profile ? config('disc_profiles.'.$latest->dominant_profile) : null;
                        $initials = Str::of($r->name)->explode(' ')->map(fn ($p) => Str::substr($p, 0, 1))->take(2)->implode('');
                    @endphp
                    <tr>
                        <td>
                            <div class="cell-user">
                                <div class="av">{{ $initials ?: '?' }}</div>
                                <div>
                                    <div class="nm">{{ $r->name }}</div>
                                    <div class="em">{{ $r->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="mono-cell">{{ $r->phone ?: '—' }}</td>
                        <td class="mono-cell">{{ $latest?->completed_at?->format('d/m/Y H:i') ?? '—' }}</td>
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
                            @if ($responded)
                                <span class="pill" style="color: var(--success);">● Respondeu</span>
                            @else
                                <span class="pill" style="color: var(--fg-4);">○ Não respondeu</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.respondent.show', $r) }}" class="btn btn-ghost" style="padding: 6px 14px; font-size: 13px;">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--fg-4);">
                            Nenhum respondente encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="tbl-foot">
        <span>Mostrando {{ $respondents->count() }} de {{ $respondents->total() }} respondentes</span>
        <div class="pager">
            <button onclick="location.href='{{ $respondents->previousPageUrl() }}'" {{ $respondents->onFirstPage() ? 'disabled' : '' }}>‹</button>
            <button class="on">{{ $respondents->currentPage() }} / {{ $respondents->lastPage() }}</button>
            <button onclick="location.href='{{ $respondents->nextPageUrl() }}'" {{ $respondents->hasMorePages() ? '' : 'disabled' }}>›</button>
        </div>
    </div>
</div>
@endsection
