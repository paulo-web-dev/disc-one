@extends('layouts.admin')

@section('title', 'Vendas')

@section('content')
<div class="adm-page-head">
    <div>
        <span class="eyebrow">Gestão</span>
        <h1>Vendas</h1>
        <p>Compras do relatório completo e status de pagamento.</p>
    </div>
</div>

{{-- KPIs de venda --}}
<div class="kpi-grid" style="margin-bottom: 16px;">
    <div class="kpi">
        <div class="top"><div class="ic" style="background: rgba(43,217,161,0.12); color: var(--disc-s);">R$</div></div>
        <div class="label">Receita</div>
        <div class="value">R$ {{ number_format($stats['revenue'], 2, ',', '.') }}</div>
        <div class="sub">total recebido</div>
    </div>
    <div class="kpi">
        <div class="top"><div class="ic" style="background: rgba(43,217,161,0.12); color: var(--disc-s);">✓</div></div>
        <div class="label">Pagas</div>
        <div class="value">{{ $stats['paid'] }}</div>
        <div class="sub">compras confirmadas</div>
    </div>
    <div class="kpi">
        <div class="top"><div class="ic" style="background: rgba(255,181,71,0.12); color: var(--disc-i);">⏳</div></div>
        <div class="label">Pendentes</div>
        <div class="value">{{ $stats['pending'] }}</div>
        <div class="sub">aguardando pagamento</div>
    </div>
</div>

<div class="tbl-wrap">
    <div class="tbl-toolbar">
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.purchases') }}" class="fchip {{ $status ? '' : 'on' }}">Todas</a>
            <a href="{{ route('admin.purchases', ['status' => 'paid']) }}" class="fchip {{ $status === 'paid' ? 'on' : '' }}">Pagas</a>
            <a href="{{ route('admin.purchases', ['status' => 'pending']) }}" class="fchip {{ $status === 'pending' ? 'on' : '' }}">Pendentes</a>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Teste</th>
                    <th>Valor</th>
                    <th>Método</th>
                    <th>Status</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchases as $p)
                    <tr>
                        <td>
                            <div class="cell-user">
                                <div>
                                    <div class="nm">{{ $p->user?->name ?? '—' }}</div>
                                    <div class="em">{{ $p->user?->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="mono-cell">{{ $p->test_id ? '#'.$p->test_id : '—' }}</td>
                        <td class="mono-cell">R$ {{ number_format($p->amount, 2, ',', '.') }}</td>
                        <td>{{ $p->payment_method ?: '—' }}</td>
                        <td>
                            @if ($p->status === 'paid')
                                <span class="pill" style="color: var(--success);">● Pago</span>
                            @else
                                <span class="pill" style="color: var(--disc-i);">○ Pendente</span>
                            @endif
                        </td>
                        <td class="mono-cell">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--fg-4);">
                            Nenhuma compra ainda. O checkout entra na Etapa 9.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="tbl-foot">
        <span>Mostrando {{ $purchases->count() }} de {{ $purchases->total() }} compras</span>
        <div class="pager">
            <button onclick="location.href='{{ $purchases->previousPageUrl() }}'" {{ $purchases->onFirstPage() ? 'disabled' : '' }}>‹</button>
            <button class="on">{{ $purchases->currentPage() }} / {{ max($purchases->lastPage(), 1) }}</button>
            <button onclick="location.href='{{ $purchases->nextPageUrl() }}'" {{ $purchases->hasMorePages() ? '' : 'disabled' }}>›</button>
        </div>
    </div>
</div>
@endsection
