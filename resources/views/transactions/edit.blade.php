<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="page-title">Editar Transação</h2>
            <div class="flex space-x-2">
                <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    Visualizar
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Informações da Transação</h3>
                    <p class="text-sm text-gray-600">ID: #{{ $transaction->id }}</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('transactions.update', $transaction) }}" method="POST"
                        x-data="transactionEditForm()" x-init="initWithData()">
                        @csrf
                        @method('PUT')

                        <!-- Dados da transação para JavaScript -->
                        <input type="hidden" x-ref="transactionData" data-type="{{ $transaction->type }}"
                            data-category="{{ $transaction->category_id }}"
                            data-situation="{{ $transaction->situation }}">

                        <div class="form-grid cols-2 mb-6">
                            <!-- Tipo -->
                            <div class="form-group">
                                <label for="type" class="form-label required">Tipo</label>
                                <select name="type" id="type" class="form-select @error('type') error @enderror"
                                    x-model="selectedType" x-on:change="onTypeChange()" x-ref="typeSelect" required>
                                    <option value="">Selecione o tipo</option>
                                    @foreach (\App\Enums\TransactionType::cases() as $type)
                                        <option value="{{ $type->value }}"
                                            {{ old('type', $transaction->type) === $type->value ? 'selected' : '' }}>
                                            @if ($type->value === 'entrada')
                                            💰 @else💸
                                            @endif {{ $type->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Categoria -->
                            <div class="form-group">
                                <label for="category_id" class="form-label required">Categoria</label>
                                <select name="category_id" id="category_id"
                                    class="form-select @error('category_id') error @enderror" x-model="selectedCategory"
                                    x-ref="categorySelect" :disabled="!selectedType || isLoadingCategories" required>
                                    <option value="">Selecione a categoria</option>
                                    <template x-for="category in categories" :key="category.id">
                                        <option :value="category.id" x-text="category.name"></option>
                                    </template>
                                </select>
                                @error('category_id')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-grid cols-2 mb-6">
                            <!-- Nome -->
                            <div class="form-group">
                                <label for="name" class="form-label required">Nome</label>
                                <input type="text" name="name" id="name"
                                    class="form-input @error('name') error @enderror"
                                    value="{{ old('name', $transaction->name) }}"
                                    placeholder="Ex: Salário, Aluguel, Compras..." required>
                                @error('name')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Valor -->
                            <div class="form-group">
                                <label for="amount" class="form-label required">Valor (R$)</label>
                                <input type="number" name="amount" id="amount"
                                    class="form-input @error('amount') error @enderror"
                                    value="{{ old('amount', $transaction->amount) }}" step="0.01" min="0.01"
                                    placeholder="0,00" required>
                                @error('amount')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-grid cols-2 mb-6">
                            <!-- Data de Vencimento -->
                            <div class="form-group">
                                <label for="due_date" class="form-label required">Data de Vencimento</label>
                                <input type="date" name="due_date" id="due_date"
                                    class="form-input @error('due_date') error @enderror"
                                    value="{{ old('due_date', $transaction->due_date ? \Carbon\Carbon::parse($transaction->due_date)->format('Y-m-d') : '') }}"
                                    x-ref="dueDateInput" x-on:change="validateDueDate()" required>
                                @error('due_date')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Situação -->
                            <div class="form-group">
                                <label for="situation" class="form-label required">Situação</label>
                                <select name="situation" id="situation"
                                    class="form-select @error('situation') error @enderror" x-model="situation"
                                    x-on:change="onSituationChange()" x-ref="situationSelect"
                                    :disabled="!selectedType || isLoadingSituations" required>
                                    <option value="">Selecione a situação</option>
                                    <template x-for="situationOption in situations" :key="situationOption.value">
                                        <option :value="situationOption.value" x-text="situationOption.label"></option>
                                    </template>
                                </select>
                                @error('situation')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Data de Pagamento (condicional) -->
                        <div class="form-group mb-6" x-show="showPaymentDate" x-transition>
                            <label for="payment_date" class="form-label required">Data de Pagamento</label>
                            <input type="date" name="payment_date" id="payment_date"
                                class="form-input @error('payment_date') error @enderror" x-ref="paymentDateInput"
                                value="{{ old('payment_date', $transaction->payment_date) }}">
                            @error('payment_date')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Descrição -->
                        <div class="form-group mb-6">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea name="description" id="description" class="form-textarea @error('description') error @enderror"
                                rows="3" placeholder="Descrição detalhada da transação...">{{ old('description', $transaction->description) }}</textarea>
                            @error('description')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Observações -->
                        <div class="form-group mb-8">
                            <label for="notes" class="form-label">Observações</label>
                            <textarea name="notes" id="notes" class="form-textarea @error('notes') error @enderror" rows="2"
                                placeholder="Observações adicionais...">{{ old('notes', $transaction->notes) }}</textarea>
                            @error('notes')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Informações de Auditoria -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Informações do Sistema</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                <div>
                                    <strong>Criado em:</strong> {{ $transaction->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div>
                                    <strong>Última atualização:</strong>
                                    {{ $transaction->updated_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-between">
                            <div>
                                <!-- Botão de Marcar como Pago/Recebido (se aplicável) -->
                                @if (!in_array($transaction->situation, ['pago', 'recebido']))
                                    <form action="{{ route('transactions.mark-as-paid', $transaction) }}"
                                        method="POST" class="inline">
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
                                @endif
                            </div>

                            <div class="flex space-x-4">
                                <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-secondary">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Atualizar Transação
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
