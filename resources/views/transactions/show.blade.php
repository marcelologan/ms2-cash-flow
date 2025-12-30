<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="page-title">Detalhes da Transação</h2>
            <div class="flex space-x-2">
                <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Editar
                </a>
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="content-wrapper">
        <div class="max-w-4xl mx-auto">
            <!-- Card Principal -->
            <div class="card mb-6">
                <div class="card-header">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="card-title">{{ $transaction->name }}</h3>
                            <p class="text-sm text-gray-600">ID: #{{ $transaction->id }}</p>
                        </div>
                        <div class="flex flex-col items-end space-y-2">
                            <!-- Tipo -->
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
          {{ $transaction->type->value === 'entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                @if ($transaction->type->value === 'entrada')
                                    💰 Entrada
                                @else
                                    💸 Saída
                                @endif
                            </span>

                            @php
                                $situationEnum = $transaction->situation;
                            @endphp
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $situationEnum->badge() }}">
                                {{ $situationEnum->icon() }} {{ $situationEnum->label() }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Informações Principais -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Valor -->
                        <div class="info-group">
                            <label class="info-label">Valor</label>
                            <div
                                class="info-value {{ $transaction->type === 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                                <span class="text-3xl font-bold">
                                    R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Categoria -->
                        <div class="info-group">
                            <label class="info-label">Categoria</label>
                            <div class="info-value">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    {{ $transaction->category->name }}
                                </span>
                            </div>
                        </div>

                        <!-- Data de Vencimento -->
                        <div class="info-group">
                            <label class="info-label">Data de Vencimento</label>
                            <div class="info-value">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span
                                        class="text-lg">{{ \Carbon\Carbon::parse($transaction->due_date)->format('d/m/Y') }}</span>
                                    <span class="text-sm text-gray-500">
                                        ({{ \Carbon\Carbon::parse($transaction->due_date)->diffForHumans() }})
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Data de Pagamento -->
                        @if ($transaction->payment_date)
                            <div class="info-group">
                                <label class="info-label">Data de
                                    {{ $transaction->type === 'entrada' ? 'Recebimento' : 'Pagamento' }}</label>
                                <div class="info-value">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span
                                            class="text-lg">{{ \Carbon\Carbon::parse($transaction->payment_date)->format('d/m/Y') }}</span>
                                        <span class="text-sm text-gray-500">
                                            ({{ \Carbon\Carbon::parse($transaction->payment_date)->diffForHumans() }})
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="info-group">
                                <label class="info-label">Status de
                                    {{ $transaction->type === 'entrada' ? 'Recebimento' : 'Pagamento' }}</label>
                                <div class="info-value">
                                    <span class="text-gray-500 italic">
                                        @if ($situationEnum->isPending())
                                            Aguardando
                                            {{ $transaction->type === 'entrada' ? 'recebimento' : 'pagamento' }}
                                        @elseif($situationEnum->isOverdue())
                                            {{ $transaction->type === 'entrada' ? 'Recebimento' : 'Pagamento' }} em
                                            atraso
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Descrição -->
                    @if ($transaction->description)
                        <div class="info-group mb-6">
                            <label class="info-label">Descrição</label>
                            <div class="info-value">
                                <p class="text-gray-700 leading-relaxed">{{ $transaction->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Observações -->
                    @if ($transaction->notes)
                        <div class="info-group mb-6">
                            <label class="info-label">Observações</label>
                            <div class="info-value">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <p class="text-gray-700 leading-relaxed">{{ $transaction->notes }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Ações Rápidas -->
                    @if (!$situationEnum->isPaid())
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Ações Rápidas</h4>
                            <div class="flex flex-wrap gap-3">
                                <form action="{{ route('transactions.mark-as-paid', $transaction) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Confirma marcar como {{ $transaction->type === 'entrada' ? 'recebido' : 'pago' }}?')">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Marcar como {{ $transaction->type === 'entrada' ? 'Recebido' : 'Pago' }}
                                    </button>
                                </form>

                                <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-primary">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Editar Transação
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card de Informações do Sistema -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informações do Sistema</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="info-group">
                            <label class="info-label">Criado em</label>
                            <div class="info-value text-sm">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
                                </div>
                                <span class="text-gray-500 text-xs">
                                    ({{ $transaction->created_at->diffForHumans() }})
                                </span>
                            </div>
                        </div>

                        <div class="info-group">
                            <label class="info-label">Última Atualização</label>
                            <div class="info-value text-sm">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    <span>{{ $transaction->updated_at->format('d/m/Y H:i:s') }}</span>
                                </div>
                                <span class="text-gray-500 text-xs">
                                    ({{ $transaction->updated_at->diffForHumans() }})
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botões de Ação Inferior -->
            <div class="flex justify-between items-center mt-8">
                <div class="flex space-x-3">
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar à Lista
                    </a>
                    <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Editar
                    </a>
                </div>

                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Tem certeza que deseja excluir esta transação? Esta ação não pode ser desfeita.')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        Excluir Transação
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
