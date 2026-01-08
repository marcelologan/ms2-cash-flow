<x-app-layout>
    @push('head')
        <meta name="export-pdf-url" content="{{ route('transactions.export-pdf') }}">
    @endpush
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="page-title">Transações</h2>
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nova Transação
            </a>
        </div>
    </x-slot>

    <div class="content-wrapper">
        <!-- Estatísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stat-card">
                <div class="stat-icon bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Total de Transações</div>
                    <div class="stat-value">{{ number_format($totalTransactions) }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Entradas</div>
                    <div class="stat-value text-green-600">{{ number_format($totalEntradas) }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Saídas</div>
                    <div class="stat-value text-red-600">{{ number_format($totalSaidas) }}</div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-6">
            <div class="card-header">
                <div class="flex justify-between items-center">
                    <h3 class="card-title">Filtros</h3>

                </div>
            </div>
            <div class="card-body" x-data="transactionFilters()" x-init="initFilters()">
                <form method="GET" action="{{ route('transactions.index') }}" class="space-y-4">
                    <!-- Primeira linha de filtros (sempre visível) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Busca -->
                        <div class="form-group">
                            <label for="search" class="form-label">Buscar</label>
                            <input type="text" name="search" id="search" class="form-input"
                                value="{{ request('search') }}" placeholder="Nome, descrição ou categoria...">
                        </div>

                        <!-- Tipo -->
                        <div class="form-group">
                            <label for="type" class="form-label">Tipo</label>
                            <select name="type" id="type" class="form-select" x-model="selectedType"
                                x-on:change="onTypeChange()">
                                <option value="">Todos os tipos</option>
                                @foreach (\App\Enums\TransactionType::cases() as $type)
                                    <option value="{{ $type->value }}"
                                        {{ request('type') === $type->value ? 'selected' : '' }}>
                                        @if ($type->value === 'entrada')
                                        💰 @else💸
                                        @endif {{ $type->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Situação -->
                        <div class="form-group">
                            <label for="situation" class="form-label">Situação</label>
                            <select name="situation" id="situation" class="form-select" x-model="selectedSituation"
                                :disabled="isLoadingSituations">
                                <option value="">Todas as situações</option>
                                <template x-for="situationOption in availableSituations" :key="situationOption.value">
                                    <option :value="situationOption.value"
                                        :selected="situationOption.value === '{{ request('situation') }}'"
                                        x-text="situationOption.label"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <!-- Segunda linha de filtros (expansível) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Categoria -->
                        <div class="form-group">
                            <label for="category_id" class="form-label">Categoria</label>
                            <select name="category_id" id="category_id" class="form-select" x-model="selectedCategory"
                                :disabled="isLoadingCategories">
                                <option value="">Todas as categorias</option>
                                <template x-for="category in availableCategories" :key="category.id">
                                    <option :value="category.id"
                                        :selected="category.id == '{{ request('category_id') }}'"
                                        x-text="category.name"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Data Início -->
                        <div class="form-group">
                            <label for="data_inicio" class="form-label">Data Início</label>
                            <input type="date" name="data_inicio" id="data_inicio" class="form-input"
                                value="{{ request('data_inicio') }}">
                        </div>

                        <!-- Data Fim -->
                        <div class="form-group">
                            <label for="data_fim" class="form-label">Data Fim</label>
                            <input type="date" name="data_fim" id="data_fim" class="form-input"
                                value="{{ request('data_fim') }}">
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex justify-between items-center pt-4">
                        <div class="flex space-x-2">
                            <button type="submit" class="btn btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filtrar
                            </button>
                            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Limpar
                            </a>
                            <a href="{{ route('transactions.export-pdf', request()->query()) }}"
                                class="btn btn-success">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Exportar PDF
                            </a>

                            <div class="text-sm text-gray-600">
                                {{ $transactions->total() }} transação(ões) encontrada(s)
                            </div>
                        </div>
                </form>
            </div>
        </div>

        <!-- Lista de Transações -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de Transações</h3>
            </div>
            <div class="card-body p-0">
                @if ($transactions->count() > 0)
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="table-header">Tipo</th>
                                    <th class="table-header">Nome</th>
                                    <th class="table-header">Categoria</th>
                                    <th class="table-header">Valor</th>
                                    <th class="table-header">Vencimento</th>
                                    <th class="table-header">Situação</th>
                                    <th class="table-header">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($transactions as $transaction)
                                    <tr class="hover:bg-gray-50">
                                        <!-- Tipo -->
                                        <td class="table-cell">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
          {{ $transaction->type->value === 'entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                @if ($transaction->type->value === 'entrada')
                                                    💰 Entrada
                                                @else
                                                    💸 Saída
                                                @endif
                                            </span>
                                        </td>

                                        <!-- Nome -->
                                        <td class="table-cell">
                                            <div class="font-medium text-gray-900">{{ $transaction->name }}</div>
                                            @if ($transaction->description)
                                                <div class="text-sm text-gray-500 truncate max-w-xs">
                                                    {{ $transaction->description }}</div>
                                            @endif
                                        </td>

                                        <!-- Categoria -->
                                        <td class="table-cell">
                                            <span
                                                class="text-sm text-gray-900">{{ $transaction->category->name }}</span>
                                        </td>

                                        <!-- Valor -->
                                        <td class="table-cell">
                                            <span
                                                class="font-medium {{ $transaction->type === 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                                                R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                            </span>
                                        </td>

                                        <!-- Vencimento -->
                                        <td class="table-cell">
                                            <div class="text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($transaction->due_date)->format('d/m/Y') }}
                                            </div>
                                            @if ($transaction->payment_date)
                                                <div class="text-xs text-gray-500">
                                                    Pago em:
                                                    {{ \Carbon\Carbon::parse($transaction->payment_date)->format('d/m/Y') }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Situação -->
                                        <td class="table-cell">

                                            @php
                                                $situationEnum = $transaction->situation;
                                            @endphp

                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $situationEnum->badge() }}">
                                                {{ $situationEnum->icon() }} {{ $situationEnum->label() }}
                                            </span>
                                        </td>

                                        <!-- Ações -->
                                        <td class="table-cell">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('transactions.show', $transaction) }}"
                                                    class="text-blue-600 hover:text-blue-900" title="Visualizar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('transactions.edit', $transaction) }}"
                                                    class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>

                                                @if (!in_array($transaction->situation, ['pago', 'recebido']))
                                                    <form
                                                        action="{{ route('transactions.mark-as-paid', $transaction) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="text-green-600 hover:text-green-900"
                                                            title="Marcar como {{ $transaction->type === 'entrada' ? 'recebido' : 'pago' }}"
                                                            onclick="return confirm('Confirma marcar como {{ $transaction->type === 'entrada' ? 'recebido' : 'pago' }}?')">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('transactions.destroy', $transaction) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        title="Excluir"
                                                        onclick="return confirm('Tem certeza que deseja excluir esta transação?')">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden space-y-4 p-4">
                        @foreach ($transactions as $transaction)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $transaction->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $transaction->category->name }}</p>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $transaction->type === 'entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        @if ($transaction->type === 'entrada')
                                        💰 @else💸
                                        @endif
                                    </span>
                                </div>

                                <div class="flex justify-between items-center mb-3">
                                    <span
                                        class="font-medium {{ $transaction->type === 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                                        R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                    </span>
                                    @php
                                        $situationEnum = $transaction->situation;
                                    @endphp
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $situationEnum->badge() }}">
                                        {{ $situationEnum->icon() }} {{ $situationEnum->label() }}
                                    </span>
                                </div>

                                <div class="text-sm text-gray-600 mb-3">
                                    Vencimento: {{ \Carbon\Carbon::parse($transaction->due_date)->format('d/m/Y') }}
                                    @if ($transaction->payment_date)
                                        <br>Pago em:
                                        {{ \Carbon\Carbon::parse($transaction->payment_date)->format('d/m/Y') }}
                                    @endif
                                </div>

                                <div class="flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('transactions.show', $transaction) }}"
                                            class="text-blue-600 text-sm">Ver</a>
                                        <a href="{{ route('transactions.edit', $transaction) }}"
                                            class="text-yellow-600 text-sm">Editar</a>
                                    </div>

                                    @if (!in_array($transaction->situation, ['pago', 'recebido']))
                                        <form action="{{ route('transactions.mark-as-paid', $transaction) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 text-sm"
                                                onclick="return confirm('Confirma marcar como {{ $transaction->type === 'entrada' ? 'recebido' : 'pago' }}?')">
                                                Marcar como
                                                {{ $transaction->type === 'entrada' ? 'Recebido' : 'Pago' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Paginação -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $transactions->links() }}
                    </div>
                @else
                    <!-- Estado Vazio -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma transação encontrada</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if (request()->hasAny(['search', 'type', 'situation', 'category_id', 'data_inicio', 'data_fim']))
                                Tente ajustar os filtros ou criar uma nova transação.
                            @else
                                Comece criando sua primeira transação.
                            @endif
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Nova Transação
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
