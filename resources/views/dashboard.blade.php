<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="page-title">Dashboard Financeiro</h2>
            <div class="flex space-x-2">
                <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nova Transação
                </a>
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    Ver Todas
                </a>
            </div>
        </div>
    </x-slot>

    <div class="content-wrapper">
        <!-- Cards de Resumo Principal -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Saldo Total -->
            <div class="stat-card">
                <div
                    class="stat-icon {{ $saldoTotal >= 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                        </path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Saldo Total</div>
                    <div class="stat-value {{ $saldoTotal >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        R$ {{ number_format($saldoTotal, 2, ',', '.') }}
                    </div>
                    <div class="stat-subtitle">
                        Realizado: R$ {{ number_format($saldoRealizado, 2, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Total de Entradas -->
            <div class="stat-card">
                <div class="stat-icon bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Total Entradas</div>
                    <div class="stat-value text-green-600">R$ {{ number_format($totalEntradas, 2, ',', '.') }}</div>
                    <div class="stat-subtitle">
                        Recebido: R$ {{ number_format($entradasRecebidas, 2, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Total de Saídas -->
            <div class="stat-card">
                <div class="stat-icon bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Total Saídas</div>
                    <div class="stat-value text-red-600">R$ {{ number_format($totalSaidas, 2, ',', '.') }}</div>
                    <div class="stat-subtitle">
                        Pago: R$ {{ number_format($saidasPagas, 2, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Total de Transações -->
            <div class="stat-card">
                <div class="stat-icon bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Total Transações</div>
                    <div class="stat-value text-blue-600">{{ number_format($totalTransactions) }}</div>
                    <div class="stat-subtitle">
                        Este mês: {{ number_format($transacoesMesAtual) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda Linha de Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Pendências -->
            <div class="stat-card">
                <div class="stat-icon bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Saldo Pendente</div>
                    <div class="stat-value {{ $saldoPendente >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        R$ {{ number_format($saldoPendente, 2, ',', '.') }}
                    </div>
                    <div class="stat-subtitle text-xs">
                        A receber: R$ {{ number_format($entradasPendentes, 2, ',', '.') }}<br>
                        A pagar: R$ {{ number_format($saidasPendentes, 2, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Mês Atual - Entradas -->
            <div class="stat-card">
                <div class="stat-icon bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Entradas - {{ now()->format('M/Y') }}</div>
                    <div class="stat-value text-green-600">R$ {{ number_format($entradasMesAtual, 2, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Mês Atual - Saídas -->
            <div class="stat-card">
                <div class="stat-icon bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Saídas - {{ now()->format('M/Y') }}</div>
                    <div class="stat-value text-red-600">R$ {{ number_format($saidasMesAtual, 2, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Seção de Alertas e Listas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Transações Vencidas -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-red-600">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                        Transações Vencidas ({{ $transacoesVencidas->count() }})
                    </h3>
                </div>
                <div class="card-body p-0">
                    @if ($transacoesVencidas->count() > 0)
                        <div class="space-y-0">
                            @foreach ($transacoesVencidas as $transacao)
                                <div
                                    class="flex justify-between items-center p-4 border-b border-gray-100 last:border-b-0 hover:bg-red-50">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">{{ $transacao->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $transacao->category->name }}</div>
                                        <div class="text-xs text-red-600">
                                            Venceu em
                                            {{ \Carbon\Carbon::parse($transacao->due_date)->format('d/m/Y') }}
                                            ({{ \Carbon\Carbon::parse($transacao->due_date)->diffForHumans() }})
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div
                                            class="font-medium {{ $transacao->type === 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                                            R$ {{ number_format($transacao->amount, 2, ',', '.') }}
                                        </div>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $transacao->situation->badge() }}">
                                            {{ $transacao->situation->icon() }} {{ $transacao->situation->label() }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="p-4 bg-gray-50 text-center">
                            <a href="{{ route('transactions.index', ['situation' => 'vencido']) }}"
                                class="text-red-600 text-sm hover:text-red-800">
                                Ver todas as transações vencidas →
                            </a>
                        </div>
                    @else
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <p>Nenhuma transação vencida! 🎉</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Próximas Transações -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-blue-600">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Próximas Transações (7 dias)
                    </h3>
                </div>
                <div class="card-body p-0">
                    @if ($proximasTransacoes->count() > 0)
                        <div class="space-y-0">
                            @foreach ($proximasTransacoes as $transacao)
                                <div
                                    class="flex justify-between items-center p-4 border-b border-gray-100 last:border-b-0 hover:bg-blue-50">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">{{ $transacao->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $transacao->category->name }}</div>
                                        <div class="text-xs text-blue-600">
                                            Vence em {{ \Carbon\Carbon::parse($transacao->due_date)->format('d/m/Y') }}
                                            ({{ \Carbon\Carbon::parse($transacao->due_date)->diffForHumans() }})
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div
                                            class="font-medium {{ $transacao->type === 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                                            R$ {{ number_format($transacao->amount, 2, ',', '.') }}
                                        </div>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $transacao->situation->badge() }}">
                                            {{ $transacao->situation->icon() }} {{ $transacao->situation->label() }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="p-4 bg-gray-50 text-center">
                            <a href="{{ route('transactions.index') }}"
                                class="text-blue-600 text-sm hover:text-blue-800">
                                Ver todas as transações →
                            </a>
                        </div>
                    @else
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p>Nenhuma transação nos próximos 7 dias</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Gráficos e Estatísticas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Distribuição por Situação -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Distribuição por Situação</h3>
                </div>
                <div class="card-body">
                    @if ($transacoesPorSituacao->count() > 0)
                        <div class="space-y-4">
                            @foreach ($transacoesPorSituacao as $situacao => $total)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">{{ $situacao }}</span>
                                    <span class="text-sm text-gray-900 font-semibold">{{ $total }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Nenhuma transação encontrada</p>
                    @endif
                </div>
            </div>

            <!-- Categorias Mais Usadas -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Categorias Mais Usadas</h3>
                </div>
                <div class="card-body">
                    @if ($categoriasMaisUsadas->count() > 0)
                        <div class="space-y-4">
                            @foreach ($categoriasMaisUsadas as $categoria)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-700">
                                            {{ $categoria->category->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $categoria->total }} transações</div>
                                    </div>
                                    <div class="text-sm text-gray-900 font-semibold">
                                        R$ {{ number_format($categoria->valor_total, 2, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Nenhuma categoria encontrada</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>