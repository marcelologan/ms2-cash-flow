import Alpine from 'alpinejs';

// ===================================================================
// COMPONENTES PARA FORMULÁRIO DE CRIAÇÃO DE TRANSAÇÕES
// ===================================================================

Alpine.data('transactionForm', () => ({
    selectedType: '',
    categories: [],
    selectedCategory: '',
    situations: [],
    situation: '',
    showPaymentDate: false,
    isLoadingCategories: false,
    isLoadingSituations: false,

    // Inicialização do formulário de criação
    init() {
        const oldType = this.$refs.typeSelect?.value || '';
        const oldSituation = this.$refs.situationSelect?.value || '';

        if (oldType) {
            this.selectedType = oldType;
            this.loadCategories(oldType);
            this.loadSituations(oldType);
        }

        if (oldSituation) {
            this.situation = oldSituation;
            this.updatePaymentDateVisibility();
        }
    },

    // Carregamento de categorias por tipo
    async loadCategories(type) {
        if (!type) {
            this.categories = [];
            this.selectedCategory = '';
            return;
        }

        this.isLoadingCategories = true;

        try {
            const response = await fetch(`/transactions/categories/${type}`);
            const data = await response.json();

            this.categories = data;

            const oldCategoryId = this.$refs.categorySelect?.dataset.oldValue;
            if (oldCategoryId) {
                this.selectedCategory = oldCategoryId;
            } else {
                this.selectedCategory = '';
            }

        } catch (error) {
            console.error('Erro ao carregar categorias:', error);
            this.categories = [];
        } finally {
            this.isLoadingCategories = false;
        }
    },

    // Carregamento de situações por tipo
    async loadSituations(type) {
        if (!type) {
            this.situations = [];
            this.situation = '';
            return;
        }

        this.isLoadingSituations = true;

        try {
            const response = await fetch(`/transactions/situations/${type}`);
            const data = await response.json();

            this.situations = Object.entries(data).map(([value, label]) => ({
                value: value,
                label: label
            }));

            const oldSituation = this.$refs.situationSelect?.dataset.oldValue;
            if (oldSituation) {
                this.situation = oldSituation;
                this.updatePaymentDateVisibility();
            } else {
                this.situation = '';
            }

        } catch (error) {
            console.error('Erro ao carregar situações:', error);
            this.situations = [];
        } finally {
            this.isLoadingSituations = false;
        }
    },

    // Eventos de mudança
    onTypeChange() {
        this.loadCategories(this.selectedType);
        this.loadSituations(this.selectedType);
        this.situation = '';
        this.showPaymentDate = false;
    },

    onSituationChange() {
        this.updatePaymentDateVisibility();
    },

    // Controle de visibilidade da data de pagamento
    updatePaymentDateVisibility() {
        this.showPaymentDate = ['pago', 'recebido'].includes(this.situation);

        if (this.showPaymentDate && !this.$refs.paymentDateInput?.value) {
            const today = new Date().toISOString().split('T')[0];
            if (this.$refs.paymentDateInput) {
                this.$refs.paymentDateInput.value = today;
            }
        } else if (!this.showPaymentDate && this.$refs.paymentDateInput) {
            this.$refs.paymentDateInput.value = '';
        }
    },

    // Validação de data de vencimento
    validateDueDate() {
        const dueDateInput = this.$refs.dueDateInput;
        const situationSelect = this.$refs.situationSelect;

        if (!dueDateInput || !situationSelect || !this.situation) return;

        const dueDate = new Date(dueDateInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        dueDate.setHours(0, 0, 0, 0);

        const pendingSituations = ['a_pagar', 'a_receber'];
        const overdueSituations = ['vencido', 'atrasado'];

        dueDateInput.classList.remove('error');

        if (pendingSituations.includes(this.situation) && dueDate < today) {
            dueDateInput.classList.add('error');
            this.showDateError('Data deve ser futura para situação pendente');
            return;
        }

        if (overdueSituations.includes(this.situation) && dueDate >= today) {
            dueDateInput.classList.add('error');
            this.showDateError('Data deve ser passada para situação atrasada/vencida');
            return;
        }

        this.clearDateError();
    },

    // Utilitários para erro de data
    showDateError(message) {
        let errorElement = this.$refs.dueDateInput.parentElement.querySelector('.date-error');
        if (!errorElement) {
            errorElement = document.createElement('span');
            errorElement.className = 'form-error date-error';
            this.$refs.dueDateInput.parentElement.appendChild(errorElement);
        }
        errorElement.textContent = message;
    },

    clearDateError() {
        const errorElement = this.$refs.dueDateInput?.parentElement.querySelector('.date-error');
        if (errorElement) {
            errorElement.remove();
        }
    }
}));

// ===================================================================
// COMPONENTES PARA FORMULÁRIO DE EDIÇÃO DE TRANSAÇÕES
// ===================================================================

Alpine.data('transactionEditForm', () => ({
    selectedType: '',
    categories: [],
    selectedCategory: '',
    situations: [],
    situation: '',
    showPaymentDate: false,
    isLoadingCategories: false,
    isLoadingSituations: false,

    // Inicialização específica para edição
    async initWithData() {
        // Pegar dados da transação
        const transactionData = this.$refs.transactionData;
        const type = transactionData.dataset.type;
        const categoryId = transactionData.dataset.category;
        const situationValue = transactionData.dataset.situation;

        console.log('Iniciando edit com:', { type, categoryId, situationValue });

        // Definir tipo primeiro
        if (type) {
            this.selectedType = type;

            // Carregar categorias e situações
            await this.loadCategories(type);
            await this.loadSituations(type);

            // Aguardar um pouco para garantir que carregou
            setTimeout(() => {
                if (categoryId) {
                    this.selectedCategory = categoryId;
                    console.log('Categoria definida:', categoryId);
                }

                if (situationValue) {
                    this.situation = situationValue;
                    this.updatePaymentDateVisibility();
                    console.log('Situação definida:', situationValue);
                }
            }, 100);
        }
    },

    // Carregamento de categorias
    async loadCategories(type) {
        if (!type) {
            this.categories = [];
            this.selectedCategory = '';
            return;
        }

        this.isLoadingCategories = true;

        try {
            const response = await fetch(`/transactions/categories/${type}`);
            const data = await response.json();
            this.categories = data;
            console.log('Categorias carregadas:', data);
        } catch (error) {
            console.error('Erro ao carregar categorias:', error);
            this.categories = [];
        } finally {
            this.isLoadingCategories = false;
        }
    },

    // Carregamento de situações
    async loadSituations(type) {
        if (!type) {
            this.situations = [];
            this.situation = '';
            return;
        }

        this.isLoadingSituations = true;

        try {
            const response = await fetch(`/transactions/situations/${type}`);
            const data = await response.json();

            this.situations = Object.entries(data).map(([value, label]) => ({
                value: value,
                label: label
            }));
            console.log('Situações carregadas:', this.situations);
        } catch (error) {
            console.error('Erro ao carregar situações:', error);
            this.situations = [];
        } finally {
            this.isLoadingSituations = false;
        }
    },

    // Eventos de mudança
    onTypeChange() {
        this.loadCategories(this.selectedType);
        this.loadSituations(this.selectedType);
        this.situation = '';
        this.selectedCategory = '';
        this.showPaymentDate = false;
    },

    onSituationChange() {
        this.updatePaymentDateVisibility();
    },

    // Controle de visibilidade da data de pagamento
    updatePaymentDateVisibility() {
        this.showPaymentDate = ['pago', 'recebido'].includes(this.situation);

        if (this.showPaymentDate && this.$refs.paymentDateInput && !this.$refs.paymentDateInput.value) {
            const today = new Date().toISOString().split('T')[0];
            this.$refs.paymentDateInput.value = today;
        } else if (!this.showPaymentDate && this.$refs.paymentDateInput) {
            this.$refs.paymentDateInput.value = '';
        }
    },

    // Validação de data
    validateDueDate() {
        // Implementar se necessário
    }
}));

// ===================================================================
// COMPONENTES PARA FILTROS DA LISTAGEM DE TRANSAÇÕES
// ===================================================================

Alpine.data('transactionFilters', () => ({
    selectedType: '',
    selectedSituation: '',
    selectedCategory: '',
    availableCategories: [],
    availableSituations: [],
    isLoadingCategories: false,
    isLoadingSituations: false,

    // Inicialização dos filtros
    async initFilters() {
        this.selectedType = document.getElementById('type').value;
        this.selectedSituation = document.getElementById('situation').value;
        this.selectedCategory = document.getElementById('category_id').value;

        await this.loadAllCategories();

        if (this.selectedType) {
            await this.loadSituationsForType(this.selectedType);
        } else {
            await this.loadAllSituations();
        }
    },

    // Carregamento de todas as categorias
    async loadAllCategories() {
        this.isLoadingCategories = true;
        try {
            const response = await fetch('/transactions/categories/all');
            if (response.ok) {
                const data = await response.json();
                this.availableCategories = data;
            } else {
                this.availableCategories = [];
            }
        } catch (error) {
            console.error('Erro ao carregar categorias:', error);
            this.availableCategories = [];
        } finally {
            this.isLoadingCategories = false;
        }
    },

    // Carregamento de categorias por tipo
    async loadCategoriesForType(type) {
        if (!type) {
            await this.loadAllCategories();
            return;
        }

        this.isLoadingCategories = true;
        try {
            const response = await fetch(`/transactions/categories/${type}`);
            const data = await response.json();
            this.availableCategories = data;
        } catch (error) {
            console.error('Erro ao carregar categorias:', error);
            this.availableCategories = [];
        } finally {
            this.isLoadingCategories = false;
        }
    },

    // Carregamento de todas as situações
    async loadAllSituations() {
        this.isLoadingSituations = true;
        try {
            const response = await fetch('/transactions/situations/all');
            if (response.ok) {
                const data = await response.json();
                this.availableSituations = Object.entries(data).map(([value, label]) => ({
                    value: value,
                    label: label
                }));
            } else {
                this.setFallbackSituations();
            }
        } catch (error) {
            console.error('Erro ao carregar situações:', error);
            this.setFallbackSituations();
        } finally {
            this.isLoadingSituations = false;
        }
    },

    // Carregamento de situações por tipo
    async loadSituationsForType(type) {
        if (!type) {
            await this.loadAllSituations();
            return;
        }

        this.isLoadingSituations = true;
        try {
            const response = await fetch(`/transactions/situations/${type}`);
            const data = await response.json();

            this.availableSituations = Object.entries(data).map(([value, label]) => ({
                value: value,
                label: label
            }));
        } catch (error) {
            console.error('Erro ao carregar situações:', error);
            await this.loadAllSituations();
        } finally {
            this.isLoadingSituations = false;
        }
    },

    // Situações de fallback
    setFallbackSituations() {
        this.availableSituations = [
            { value: 'a_pagar', label: 'A Pagar' },
            { value: 'pago', label: 'Pago' },
            { value: 'vencido', label: 'Vencido' },
            { value: 'a_receber', label: 'A Receber' },
            { value: 'recebido', label: 'Recebido' },
            { value: 'atrasado', label: 'Atrasado' }
        ];
    },

    // Evento de mudança de tipo nos filtros
    async onTypeChange() {
        this.selectedSituation = '';
        document.getElementById('situation').value = '';

        await Promise.all([
            this.loadCategoriesForType(this.selectedType),
            this.loadSituationsForType(this.selectedType)
        ]);
    }
}));