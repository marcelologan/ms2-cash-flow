// Formatação de valores monetários
export function formatMoney(input) {
    let value = input.value.replace(/\D/g, '');
    value = (value / 100).toFixed(2);
    value = value.replace('.', ',');
    value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    input.value = 'R$ ' + value;
}

// Máscara para valores monetários
export function moneyMask(selector) {
    const inputs = document.querySelectorAll(selector);
    inputs.forEach(input => {
        input.addEventListener('input', () => formatMoney(input));
        
        // Limpar valor ao focar se estiver vazio
        input.addEventListener('focus', () => {
            if (input.value === 'R$ 0,00') {
                input.value = '';
            }
        });
        
        // Restaurar valor se sair vazio
        input.addEventListener('blur', () => {
            if (input.value === '' || input.value === 'R$ ') {
                input.value = 'R$ 0,00';
            }
        });
    });
}

// Validação de formulários
export function validateForm(formSelector) {
    const form = document.querySelector(formSelector);
    if (!form) return;
    
    const requiredFields = form.querySelectorAll('[required]');
    
    form.addEventListener('submit', (e) => {
        let isValid = true;
        
        requiredFields.forEach(field => {
            const errorElement = field.parentNode.querySelector('.form-error');
            
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('error');
                
                if (!errorElement) {
                    const error = document.createElement('div');
                    error.className = 'form-error';
                    error.textContent = 'Este campo é obrigatório';
                    field.parentNode.appendChild(error);
                }
            } else {
                field.classList.remove('error');
                if (errorElement) {
                    errorElement.remove();
                }
            }
        });
        
        if (!isValid) {
            e.preventDefault();
        }
    });
}

// Auto-resize para textareas
export function autoResizeTextarea(selector) {
    const textareas = document.querySelectorAll(selector);
    textareas.forEach(textarea => {
        textarea.addEventListener('input', () => {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        });
    });
}