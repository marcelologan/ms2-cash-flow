<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Relatório de Transações</title>
    <style>
        @page {
            margin: 15mm;
            size: A4 portrait;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
        }

        /* Classes para ícones */
        .icon-summary {
            width: 20px;
            height: 20px;
            vertical-align: middle;
            margin-right: 5px;
        }

        .icon-table {
            width: 16px;
            height: 16px;
            vertical-align: middle;
        }

        .icon-filter {
            width: 18px;
            height: 18px;
            vertical-align: middle;
            margin-right: 5px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 15px;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .logo {
            display: table-cell;
            vertical-align: middle;
            width: 80px;
            text-align: left;
            padding-right: 15px;
        }

        .logo img {
            width: 80px;
            height: auto;
        }

        .title-section {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .header h1 {
            color: #1f2937;
            margin: 0;
            font-size: 18px;
        }

        .header p {
            color: #6b7280;
            margin: 3px 0;
            font-size: 11px;
        }

        /* Resumo em linha horizontal */
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        .summary-item {
            display: table-cell;
            text-align: center;
            width: 25%;
            vertical-align: top;
        }

        .summary-item h3 {
            margin: 0 0 5px 0;
            color: #374151;
            font-size: 11px;
            font-weight: bold;
        }

        .summary-item p {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
        }

        .summary-item.entradas p {
            color: #059669;
        }

        .summary-item.saidas p {
            color: #dc2626;
        }

        .summary-item.saldo p.positive {
            color: #059669;
        }

        .summary-item.saldo p.negative {
            color: #dc2626;
        }

        .filters {
            margin-bottom: 15px;
            padding: 12px;
            background: #f3f4f6;
            border-radius: 4px;
            border: 1px solid #d1d5db;
        }

        .filters h3 {
            margin: 0 0 8px 0;
            color: #374151;
            font-size: 12px;
        }

        .filters p {
            margin: 2px 0;
            font-size: 10px;
            color: #6b7280;
        }

        /* Tabela otimizada para PDF */
        .table-container {
            width: 100%;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 6px 4px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
        }

        th {
            background-color: #f9fafb;
            font-weight: bold;
            color: #374151;
            font-size: 9px;
        }

        /* Larguras específicas das colunas */
        .col-tipo { width: 8%; }
        .col-nome { width: 27%; }
        .col-categoria { width: 12%; }
        .col-valor { width: 12%; }
        .col-vencimento { width: 12%; }
        .col-situacao { width: 12%; }
        .col-pagamento { width: 12%; }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .tipo-entrada {
            color: #059669;
            font-weight: bold;
        }

        .tipo-saida {
            color: #dc2626;
            font-weight: bold;
        }

        .transaction-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .transaction-desc {
            font-size: 8px;
            color: #6b7280;
            font-style: italic;
        }

        /* Situação com fundo colorido para PDF */
        .situacao {
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
            min-width: 50px;
        }

        .situacao.pago {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #059669;
        }

        .situacao.pendente {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
        }

        .situacao.vencido {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #dc2626;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #6b7280;
        }

        /* Quebra de página */
        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <!-- Cabeçalho com Logo -->
    <div class="header">
        <div class="header-content">
            <div class="logo">
                <img src="{{ public_path('images/MS2 Cash_Flow_Logo.png') }}" alt="Logo">
            </div>
            <div class="title-section">
                <h1>Relatório de Transações</h1>
                <p>Usuário: {{ $user->name }}</p>
                <p>Gerado em: {{ $generatedAt }}</p>
            </div>
        </div>
    </div>

    <!-- Resumo em linha horizontal -->
    <div class="summary">
        <div class="summary-item entradas">
            <h3>
                <img src="{{ public_path('images/icons/entrada.png') }}" alt="" class="icon-summary">Entradas
            </h3>
            <p>R$ {{ number_format($totalEntradas, 2, ',', '.') }}</p>
        </div>
        <div class="summary-item saidas">
            <h3>
                <img src="{{ public_path('images/icons/saida.png') }}" alt="" class="icon-summary">Saídas
            </h3>
            <p>R$ {{ number_format($totalSaidas, 2, ',', '.') }}</p>
        </div>
        <div class="summary-item saldo">
            <h3>
                <img src="{{ public_path('images/icons/' . ($saldo >= 0 ? 'saldo_positivo' : 'saldo_negativo') . '.png') }}" alt="" class="icon-summary">Saldo
            </h3>
            <p class="{{ $saldo >= 0 ? 'positive' : 'negative' }}">
                R$ {{ number_format($saldo, 2, ',', '.') }}
            </p>
        </div>
        <div class="summary-item">
            <h3>
                <img src="{{ public_path('images/icons/total.png') }}" alt="" class="icon-summary">Total
            </h3>
            <p>{{ $totalTransactions }} transações</p>
        </div>
    </div>

    <!-- Filtros Aplicados -->
    @if (!empty(array_filter($filters)))
        <div class="filters">
            <h3>
                <img src="{{ public_path('images/icons/filtro.png') }}" alt="" class="icon-filter">Filtros Aplicados:
            </h3>
            @if (!empty($filters['search']))
                <p><strong>Busca:</strong> {{ $filters['search'] }}</p>
            @endif
            @if (!empty($filters['type']))
                <p><strong>Tipo:</strong> {{ $filters['type'] === 'entrada' ? 'Entrada' : 'Saída' }}</p>
            @endif
            @if (!empty($filters['situation']))
                <p><strong>Situação:</strong> {{ ucfirst($filters['situation']) }}</p>
            @endif
            @if (!empty($filters['data_inicio']))
                <p><strong>Data Início:</strong> {{ \Carbon\Carbon::parse($filters['data_inicio'])->format('d/m/Y') }}</p>
            @endif
            @if (!empty($filters['data_fim']))
                <p><strong>Data Fim:</strong> {{ \Carbon\Carbon::parse($filters['data_fim'])->format('d/m/Y') }}</p>
            @endif
        </div>
    @endif

    <!-- Tabela de Transações -->
    @if ($transactions->count() > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="col-tipo">Tipo</th>
                        <th class="col-nome">Nome</th>
                        <th class="col-categoria">Categoria</th>
                        <th class="col-valor">Valor</th>
                        <th class="col-vencimento">Vencimento</th>
                        <th class="col-situacao">Situação</th>
                        <th class="col-pagamento">Pagamento</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="col-tipo">
                                <img src="{{ public_path('images/icons/' . ($transaction->type->value === 'entrada' ? 'entrada_tabela' : 'saida_tabela') . '.png') }}" alt="" class="icon-table">
                            </td>
                            <td class="col-nome">
                                <div class="transaction-name">{{ $transaction->name }}</div>
                                @if ($transaction->description)
                                    <div class="transaction-desc">{{ $transaction->description }}</div>
                                @endif
                            </td>
                            <td class="col-categoria">{{ $transaction->category->name }}</td>
                            <td class="col-valor {{ $transaction->type->value === 'entrada' ? 'tipo-entrada' : 'tipo-saida' }}">
                                R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                            </td>
                            <td class="col-vencimento">{{ $transaction->due_date->format('d/m/Y') }}</td>
                            <td class="col-situacao">
                                @php
                                    $situationClass = match ($transaction->situation->value) {
                                        'PAGO' => 'pago',
                                        'A_PAGAR' => 'pendente',
                                        'VENCIDO' => 'vencido',
                                        default => 'pendente',
                                    };
                                @endphp
                                <span class="situacao {{ $situationClass }}">
                                    {{ $transaction->situation->label() }}
                                </span>
                            </td>
                            <td class="col-pagamento">
                                {{ $transaction->payment_date ? $transaction->payment_date->format('d/m/Y') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="no-data">
            <h3>Nenhuma transação encontrada</h3>
            <p>Tente ajustar os filtros para ver os resultados.</p>
        </div>
    @endif

    <!-- Rodapé -->
    <div class="footer">
        <p>Relatório gerado pelo Sistema de Controle Financeiro</p>
        <p>{{ config('app.name') }} - {{ now()->year }}</p>
    </div>
</body>
</html>