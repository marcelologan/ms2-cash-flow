<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="page-title">Nova Transação</h2>
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="content-wrapper">
        <div class="max-w-4xl mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informações da Transação</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('transactions.store') }}" method="POST" 
                          x-data="transactionForm()">
                        @csrf
                        
                        <div class="form-grid cols-2 mb-6">
                            <!-- Tipo -->
                            <div class="form-group">
                                <label for="type" class="form-label required">Tipo</label>
                                <select name="type" id="type" 
                                        class="form-select @error('type') error @enderror" 
                                        x-model="selectedType"
                                        x-on:change="onTypeChange()"
                                        x-ref="typeSelect"
                                        required>
                                    <option value="">Selecione o tipo</option>
                                    @foreach(\App\Enums\TransactionType::cases() as $type)
                                        <option value="{{ $type->value }}" {{ old('type') === $type->value ? 'selected' : '' }}>
                                            @if($type->value === 'entrada')💰 @else💸 @endif {{ $type->label() }}
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
                                        class="form-select @error('category_id') error @enderror" 
                                        x-model="selectedCategory"
                                        x-ref="categorySelect"
                                        data-old-value="{{ old('category_id') }}"
                                        :disabled="!selectedType || isLoadingCategories"
                                        required>
                                    <option value="">
                                        <span x-show="!selectedType">Primeiro selecione o tipo</span>
                                        <span x-show="selectedType && isLoadingCategories">Carregando...</span>
                                        <span x-show="selectedType && !isLoadingCategories && categories.length === 0">Nenhuma categoria encontrada</span>
                                        <span x-show="selectedType && !isLoadingCategories && categories.length > 0">Selecione a categoria</span>
                                    </option>
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
                                       value="{{ old('name') }}" 
                                       placeholder="Ex: Salário, Aluguel, Compras..."
                                       required>
                                @error('name')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Valor -->
                            <div class="form-group">
                                <label for="amount" class="form-label required">Valor (R$)</label>
                                <input type="number" name="amount" id="amount" 
                                       class="form-input @error('amount') error @enderror" 
                                       value="{{ old('amount') }}" 
                                       step="0.01" min="0.01"
                                       placeholder="0,00"
                                       required>
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
                                       value="{{ old('due_date', date('Y-m-d')) }}" 
                                       x-ref="dueDateInput"
                                       x-on:change="validateDueDate()"
                                       required>
                                @error('due_date')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Situação -->
                            <div class="form-group">
                                <label for="situation" class="form-label required">Situação</label>
                                <select name="situation" id="situation" 
                                        class="form-select @error('situation') error @enderror" 
                                        x-model="situation"
                                        x-on:change="onSituationChange(); validateDueDate()"
                                        x-ref="situationSelect"
                                        data-old-value="{{ old('situation') }}"
                                        :disabled="!selectedType || isLoadingSituations"
                                        required>
                                    <option value="">
                                        <span x-show="!selectedType">Primeiro selecione o tipo</span>
                                        <span x-show="selectedType && isLoadingSituations">Carregando...</span>
                                        <span x-show="selectedType && !isLoadingSituations && situations.length === 0">Nenhuma situação encontrada</span>
                                        <span x-show="selectedType && !isLoadingSituations && situations.length > 0">Selecione a situação</span>
                                    </option>
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
                                   class="form-input @error('payment_date') error @enderror" 
                                   x-ref="paymentDateInput"
                                   value="{{ old('payment_date') }}">
                            @error('payment_date')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Descrição -->
                        <div class="form-group mb-6">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea name="description" id="description" 
                                      class="form-textarea @error('description') error @enderror" 
                                      rows="3"
                                      placeholder="Descrição detalhada da transação...">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Observações -->
                        <div class="form-group mb-8">
                            <label for="notes" class="form-label">Observações</label>
                            <textarea name="notes" id="notes" 
                                      class="form-textarea @error('notes') error @enderror" 
                                      rows="2"
                                      placeholder="Observações adicionais...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                                Cancelar
            </a>
                            <button type="submit" class="btn btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Salvar Transação
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>