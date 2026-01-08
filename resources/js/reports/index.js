// ===================================================================
// INICIALIZADOR DE RELATÓRIOS - VERSÃO DEFINITIVA
// ===================================================================

import { initCashFlowChart } from './cash-flow.js';
import { initCategoryChart } from './category-analysis.js';
import { initComparisonChart } from './time-comparison.js';

// Namespace global para evitar conflitos
window.ReportsManager = window.ReportsManager || {
    charts: {},
    initialized: false,

    // Função para limpar tudo
    cleanup() {
        console.log('🧹 Limpando todos os gráficos...');
        Object.keys(this.charts).forEach(chartId => {
            if (this.charts[chartId] && typeof this.charts[chartId].destroy === 'function') {
                try {
                    this.charts[chartId].destroy();
                    console.log(`🗑️ Gráfico ${chartId} destruído`);
                } catch (error) {
                    console.error(`❌ Erro ao destruir ${chartId}:`, error);
                }
            }
        });
        this.charts = {};
        this.initialized = false;
    },

    // Função para inicializar
    init() {
        // Prevenir múltiplas inicializações
        if (this.initialized) {
            console.log('⚠️ ReportsManager já inicializado, ignorando...');
            return;
        }

        console.log('🚀 Inicializando ReportsManager...');

        // Limpar primeiro
        this.cleanup();

        // Mapa de inicializadores
        const initializers = {
            'cashFlowChart': initCashFlowChart,
            'categoryChart': initCategoryChart,
            'comparisonChart': initComparisonChart
        };

        // Inicializar apenas gráficos presentes na página
        let chartsCreated = 0;
        Object.keys(initializers).forEach(chartId => {
            const canvas = document.getElementById(chartId);
            if (canvas) {
                try {
                    console.log(`📊 Criando ${chartId}...`);
                    const chart = initializers[chartId]();
                    if (chart) {
                        this.charts[chartId] = chart;
                        chartsCreated++;
                        console.log(`✅ ${chartId} criado com sucesso`);
                    } else {
                        console.log(`⚠️ ${chartId} não foi criado (sem dados)`);
                    }
                } catch (error) {
                    console.error(`❌ Erro ao criar ${chartId}:`, error);
                }
            }
        });

        this.initialized = true;
        console.log(`✅ ReportsManager inicializado. ${chartsCreated} gráfico(s) criado(s).`);
    },

    // Função para recriar (para filtros)
    recreate() {
        console.log('🔄 Recriando gráficos...');
        this.initialized = false;
        setTimeout(() => this.init(), 100);
    }
};

// Inicialização única e segura
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(() => window.ReportsManager.init(), 200);
    });
} else {
    // DOM já carregado
    setTimeout(() => window.ReportsManager.init(), 200);
}

// Cleanup ao sair
window.addEventListener('beforeunload', function () {
    if (window.ReportsManager) {
        window.ReportsManager.cleanup();
    }
});

// Exportar função para uso externo
export function recreateCharts() {
    if (window.ReportsManager) {
        window.ReportsManager.recreate();
    }
}