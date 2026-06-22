@extends('layouts.admin')

@section('title', 'Consultores')

@section('content')
<div class="adm-page-head" style="display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap;">
    <div>
        <span class="eyebrow">Gestão</span>
        <h1>Consultores</h1>
        <p>{{ $consultants->total() }} consultor(es) · cada um com seu link de divulgação.</p>
    </div>
    <a href="{{ route('admin.consultants.create') }}" class="btn btn-primary">+ Novo consultor</a>
</div>

<div class="tbl-wrap">
    <div style="overflow-x: auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Consultor</th>
                    <th>Link de referral</th>
                    <th>Respondentes</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($consultants as $c)
                    @php
                        $parts = array_filter(explode(' ', trim((string) $c->name)));
                        $initials = strtoupper(mb_substr($parts[0] ?? '', 0, 1) . mb_substr($parts[1] ?? ($parts[0] ?? ''), 0, count($parts) > 1 ? 1 : 0));
                    @endphp
                    <tr>
                        <td>
                            <div class="cell-user">
                                <div class="av">{{ $initials ?: '?' }}</div>
                                <div>
                                    <div class="nm">{{ $c->name }}</div>
                                    <div class="em">{{ $c->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <code class="mono" style="font-size: 12px; color: var(--brand-700); background: var(--brand-50); padding: 5px 10px; border-radius: var(--r-sm); border: 1px solid var(--brand-100); max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $c->referralUrl() }}</code>
                                <button type="button" class="btn btn-ghost" style="padding: 6px 12px; font-size: 12px;"
                                        onclick="navigator.clipboard.writeText('{{ $c->referralUrl() }}'); this.textContent='Copiado!'; setTimeout(() => this.textContent='Copiar', 1500)">Copiar</button>
                            </div>
                        </td>
                        <td>
                            <span class="pill is-brand">{{ $c->referred_tests_count }}</span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a href="{{ route('admin.consultants.edit', $c) }}" class="btn btn-ghost" style="padding: 6px 14px; font-size: 13px;">Editar</a>
                            <form method="POST" action="{{ route('admin.consultants.destroy', $c) }}" style="display: inline;"
                                  onsubmit="return confirm('Remover o consultor {{ $c->name }}? Os respondentes dele continuam, mas sem vínculo.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-ghost" style="padding: 6px 14px; font-size: 13px; color: var(--danger);">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 40px; color: var(--fg-4);">
                            Nenhum consultor ainda. Clique em “+ Novo consultor”.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="tbl-foot">
        <span>Mostrando {{ $consultants->count() }} de {{ $consultants->total() }} consultores</span>
        <div class="pager">
            <button onclick="location.href='{{ $consultants->previousPageUrl() }}'" {{ $consultants->onFirstPage() ? 'disabled' : '' }}>‹</button>
            <button class="on">{{ $consultants->currentPage() }} / {{ $consultants->lastPage() }}</button>
            <button onclick="location.href='{{ $consultants->nextPageUrl() }}'" {{ $consultants->hasMorePages() ? '' : 'disabled' }}>›</button>
        </div>
    </div>
</div>
@endsection
