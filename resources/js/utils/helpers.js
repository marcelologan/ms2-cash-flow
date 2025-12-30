// Utilitários gerais
export const Utils = {
    // Formatação de datas
    formatDate(date, format = 'dd/mm/yyyy') {
        if (!date) return '';
        
        const d = new Date(date);
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        
        switch (format) {
            case 'dd/mm/yyyy':
                return `${day}/${month}/${year}`;
            case 'yyyy-mm-dd':
                return `${year}-${month}-${day}`;
            case 'dd/mm':
                return `${day}/${month}`;
            default:
                return `${day}/${month}/${year}`;
        }
    },
    
    // Formatação de valores monetários
    formatCurrency(value) {
        if (!value && value !== 0) return 'R$ 0,00';
        
        const number = typeof value === 'string' ? parseFloat(value) : value;
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(number);
    },
    
    // Parse de valor monetário
    parseCurrency(value) {
        if (!value) return 0;
        return parseFloat(value.replace(/[^\d,-]/g, '').replace(',', '.')) || 0;
    },
    
    // Debounce para otimizar eventos
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    // Capitalizar primeira letra
    capitalize(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    },
    
    // Truncar texto
    truncate(str, length = 50) {
        if (!str) return '';
        return str.length > length ? str.substring(0, length) + '...' : str;
    },
    
    // Verificar se é dispositivo móvel
    isMobile() {
        return window.innerWidth < 768;
    },
    
    // Scroll suave para elemento
    scrollTo(element, offset = 0) {
        const targetElement = typeof element === 'string' ? 
            document.querySelector(element) : element;
        
        if (targetElement) {
            const top = targetElement.offsetTop - offset;
            window.scrollTo({
                top: top,
                behavior: 'smooth'
            });
        }
    },
    
    // Copiar texto para clipboard
    async copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            return true;
        } catch (err) {
            // Fallback para navegadores mais antigos
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                document.body.removeChild(textArea);
                return true;
            } catch (err) {
                document.body.removeChild(textArea);
                return false;
            }
        }
    },
    
    // Gerar ID único
    generateId() {
        return Date.now().toString(36) + Math.random().toString(36).substr(2);
    }
};

// Extensões para elementos DOM
export const DOM = {
    // Adicionar classe com animação
    addClass(element, className, duration = 300) {
        element.classList.add(className);
        if (duration > 0) {
            setTimeout(() => {
                element.style.transition = `all ${duration}ms ease`;
            }, 10);
        }
    },
    
    // Remover classe com animação
    removeClass(element, className, duration = 300) {
        if (duration > 0) {
            element.style.transition = `all ${duration}ms ease`;
            setTimeout(() => {
                element.classList.remove(className);
            }, duration);
        } else {
            element.classList.remove(className);
        }
    },
    
    // Fade in
    fadeIn(element, duration = 300) {
        element.style.opacity = '0';
        element.style.display = 'block';
        element.style.transition = `opacity ${duration}ms ease`;
        
        setTimeout(() => {
            element.style.opacity = '1';
        }, 10);
    },
    
    // Fade out
    fadeOut(element, duration = 300) {
        element.style.transition = `opacity ${duration}ms ease`;
        element.style.opacity = '0';
        
        setTimeout(() => {
            element.style.display = 'none';
        }, duration);
    },
    
    // Slide down
    slideDown(element, duration = 300) {
        element.style.height = '0';
        element.style.overflow = 'hidden';
        element.style.transition = `height ${duration}ms ease`;
        element.style.display = 'block';
        
        const height = element.scrollHeight;
        setTimeout(() => {
            element.style.height = height + 'px';
        }, 10);
        
        setTimeout(() => {
            element.style.height = 'auto';
            element.style.overflow = 'visible';
        }, duration);
    },
    
    // Slide up
    slideUp(element, duration = 300) {
        const height = element.scrollHeight;
        element.style.height = height + 'px';
        element.style.overflow = 'hidden';
        element.style.transition = `height ${duration}ms ease`;
        
        setTimeout(() => {
            element.style.height = '0';
        }, 10);
        
        setTimeout(() => {
            element.style.display = 'none';
        }, duration);
    }
};