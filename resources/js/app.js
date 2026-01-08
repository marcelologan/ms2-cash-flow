import './bootstrap';
import Alpine from 'alpinejs';

// ✅ IMPORT DIRETO - sempre carrega (MANTER APENAS ESTE)
import './components/transactions';

// Configuração global de rotas
window.routes = {
    transactionsExportPdf: document.querySelector('meta[name="export-pdf-url"]')?.getAttribute('content') || '/transactions/export-pdf'
};

// Se estiver na página de transações, importar funcionalidade de export
if (window.location.pathname.includes('/transactions')) {
    import('./components/transaction-export.js');
}

window.Alpine = Alpine;
Alpine.start();