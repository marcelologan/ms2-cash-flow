/**
 * Funcionalidade de export PDF para transações
 */
class TransactionExport {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        const exportButton = document.querySelector('[data-export="pdf"]');
        if (exportButton) {
            exportButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.exportToPdf();
            });
        }
    }

    exportToPdf() {
        try {
            const params = new URLSearchParams(window.location.search);
            const pdfUrl = window.routes.transactionsExportPdf + '?' + params.toString();

            this.showLoading();
            window.open(pdfUrl, '_blank');

            setTimeout(() => {
                this.hideLoading();
            }, 1000);

        } catch (error) {
            console.error('Erro ao exportar PDF:', error);
            alert('Erro ao gerar PDF. Tente novamente.');
        }
    }

    showLoading() {
        const button = document.querySelector('[data-export="pdf"]');
        if (button) {
            button.disabled = true;
            button.innerHTML = `
                <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Gerando PDF...
            `;
        }
    }

    hideLoading() {
        const button = document.querySelector('[data-export="pdf"]');
        if (button) {
            button.disabled = false;
            button.innerHTML = `
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Exportar PDF
            `;
        }
    }
}

// Auto-inicializar se estiver na página certa
if (document.querySelector('[data-export="pdf"]')) {
    document.addEventListener('DOMContentLoaded', () => {
        new TransactionExport();
    });
}

export default TransactionExport;