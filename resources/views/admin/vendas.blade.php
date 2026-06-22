@extends('layouts.admin')

@section('title', 'Vendas')

@php
    $metodos = ['PIX' => 'PIX', 'BOLETO' => 'Boleto', 'CREDIT_CARD' => 'Cartão', 'DEBIT_CARD' => 'Cartão', 'UNDEFINED' => 'A definir'];
@endphp

@section('content')
<div class="adm-page-head">
    <div>
        <span class="eyebrow">Financeiro</span>
        <h1>Vendas</h1>
        <p>Relatório financeiro das compras do relatório premium.</p>
    </div>
</div>

{{-- KPIs financeiros --}}
<div class="kpi-grid" style="margin-bottom: 16px;">
    <div class="kpi">
        <div class="top"><div class="ic" style="background: rgba(24,168,120,0.12); color: var(--disc-s);">R$</div></div>
        <div class="label">Receita total</div>
        <div class="value">R$ {{ number_format($stats['revenue'], 2, ',', '.') }}</div>
        <div class="sub">tudo que já entrou</div>
    </div>
    <div class="kpi">
        <div class="top"><div class="ic" style="background: rgba(46,115,184,0.12); color: var(--brand-600);">📅</div></div>
        <div class="label">Receita no mês</div>
        <div class="value">R$ {{ number_format($stats['revenue_month'], 2, ',', '.') }}</div>
        <div class="sub">{{ ucfirst(now()->translatedFormat('F')) }}</div>
    </div>
    <div class="kpi">
        <div class="top"><div class="ic" style="background: rgba(110,155,208,0.18); color: var(--disc-c);">🎟️</div></div>
        <div class="label">Ticket médio</div>
        <div class="value">R$ {{ number_format($stats['avg_ticket'], 2, ',', '.') }}</div>
        <div class="sub">por compra paga</div>
    </div>
    <div class="kpi">
        <div class="top"><div class="ic" style="background: rgba(24,168,120,0.12); color: var(--disc-s);">✓</div></div>
        <div class="label">Pagas</div>
        <div class="value">{{ $stats['paid'] }}</div>
        <div class="sub">compras confirmadas</div>
    </div>
    <div class="kpi">
        <div class="top"><div class="ic" style="background: rgba(224,162,60,0.12); color: var(--disc-i);">⏳</div></div>
        <div class="label">Pendentes</div>
        <div class="value">{{ $stats['pending'] }}</div>
        <div class="sub">R$ {{ number_format($stats['pending_value'], 2, ',', '.') }} a receber</div>
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
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchases as $p)
                    @php
                        $cliNome = $p->test?->respondent_name ?: ($p->user?->name ?: 'Sem nome');
                        $cliEmail = $p->test?->respondent_email ?: $p->user?->email;
                    @endphp
                    <tr>
                        <td>
                            <div class="cell-user">
                                <div>
                                    <div class="nm">{{ $cliNome }}</div>
                                    <div class="em">{{ $cliEmail ?: '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="mono-cell">{{ $p->test_id ? '#'.$p->test_id : '—' }}</td>
                        <td class="mono-cell">R$ {{ number_format($p->amount, 2, ',', '.') }}</td>
                        <td>{{ $metodos[$p->payment_method] ?? ($p->payment_method ?: '—') }}</td>
                        <td>
                            @if ($p->status === 'paid')
                                <span class="pill" style="color: var(--success);">● Pago</span>
                            @else
                                <span class="pill" style="color: var(--disc-i);">○ Pendente</span>
                            @endif
                        </td>
                        <td class="mono-cell">{{ ($p->paid_at ?? $p->created_at)->format('d/m/Y H:i') }}</td>
                        <td style="text-align: right; white-space: nowrap;">
                            @if ($p->invoice_url)
                                <a href="{{ $p->invoice_url }}" target="_blank" rel="noopener" class="btn btn-ghost" style="padding: 6px 12px; font-size: 12px;">Fatura</a>
                            @endif
                            @if ($p->status === 'paid' && $p->test_id)
                                <a href="{{ route('disc.resultDocumentoPremium', ['id' => $p->test_id]) }}" target="_blank" class="btn btn-ghost" style="padding: 6px 12px; font-size: 12px;">Relatório</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--fg-4);">
                            Nenhuma compra ainda.
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
