// Sistema de notificações
export class NotificationSystem {
    constructor() {
        this.container = this.createContainer();
    }
    
    createContainer() {
        let container = document.getElementById('notification-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(container);
        }
        return container;
    }
    
    show(message, type = 'info', duration = 5000) {
        const notification = this.createNotification(message, type);
        this.container.appendChild(notification);
        
        // Animação de entrada
        setTimeout(() => {
            notification.classList.add('translate-x-0');
            notification.classList.remove('translate-x-full');
        }, 10);
        
        // Auto-remover
        setTimeout(() => {
            this.remove(notification);
        }, duration);
        
        return notification;
    }
    
    createNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `transform translate-x-full transition-transform duration-300 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5`;
        
        // Nova paleta de cores
        const colors = {
            success: 'border-l-4 border-success text-success-800 bg-success-50',
            error: 'border-l-4 border-danger text-danger-800 bg-danger-50',
            warning: 'border-l-4 border-warning text-warning-800 bg-warning-50',
            info: 'border-l-4 border-main text-main-800 bg-main-50'
        };
        
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };
        
        notification.innerHTML = `
            <div class="p-4 ${colors[type]}">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="text-lg">${icons[type]}</span>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button class="close-btn inline-flex text-gray-400 hover:text-gray-600 focus:outline-none">
                            <span class="sr-only">Fechar</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Adicionar evento de fechar
        const closeBtn = notification.querySelector('.close-btn');
        closeBtn.addEventListener('click', () => this.remove(notification));
        
        return notification;
    }
    
    remove(notification) {
        notification.classList.add('translate-x-full');
        notification.classList.remove('translate-x-0');
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }
    
    success(message, duration) {
        return this.show(message, 'success', duration);
    }
    
    error(message, duration) {
        return this.show(message, 'error', duration);
    }
    
    warning(message, duration) {
        return this.show(message, 'warning', duration);
    }
    
    info(message, duration) {
        return this.show(message, 'info', duration);
    }
}

// Instância global
export const notifications = new NotificationSystem();