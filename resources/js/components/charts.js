// ===================================================================
// COMPONENTES DE GRÁFICOS PARA RELATÓRIOS
// ===================================================================

import Chart from 'chart.js/auto';

export class ReportCharts {
    constructor() {
        this.charts = new Map();
        this.defaultColors = {
            main: 'rgb(69, 170, 242)',
            success: 'rgb(38, 222, 129)',
            danger: 'rgb(235, 59, 90)',
            warning: 'rgb(254, 211, 48)',
            link: 'rgb(136, 84, 208)'
        };
    }

    /**
     * Criar gráfico de fluxo de caixa
     */
    createCashFlowChart(canvasId, monthlyData) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => item.month_name),
                datasets: [
                    {
                        label: 'Entradas Previstas',
                        data: monthlyData.map(item => item.entradas_previstas),
                        borderColor: this.defaultColors.main,
                        backgroundColor: this.hexToRgba(this.defaultColors.main, 0.1),
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: this.defaultColors.main,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    },
                    {
                        label: 'Saídas Previstas',
                        data: monthlyData.map(item => item.saidas_previstas),
                        borderColor: this.defaultColors.danger,
                        backgroundColor: this.hexToRgba(this.defaultColors.danger, 0.1),
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: this.defaultColors.danger,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    },
                    {
                        label: 'Saldo Realizado',
                        data: monthlyData.map(item => item.saldo_realizado),
                        borderColor: this.defaultColors.success,
                        backgroundColor: this.hexToRgba(this.defaultColors.success, 0.2),
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: this.defaultColors.success,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: this.defaultColors.main,
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': R\$ ' + 
                                       new Intl.NumberFormat('pt-BR', {
                                           minimumFractionDigits: 2,
                                           maximumFractionDigits: 2
                                       }).format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Meses',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Valores (R\$)',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            callback: function(value) {
                                return 'R\$ ' + new Intl.NumberFormat('pt-BR', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(value);
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });

        this.charts.set(canvasId, chart);
        return chart;
    }

    /**
     * Criar gráfico de pizza para categorias
     */
    createCategoryPieChart(canvasId, categoryData) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        const colors = this.generateColors(categoryData.length);

        const chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: categoryData.map(item => item.category),
                datasets: [{
                    data: categoryData.map(item => item.total),
                    backgroundColor: colors.background,
                    borderColor: colors.border,
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return {
                                            text: `${label} (${percentage}%)`,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            strokeStyle: data.datasets[0].borderColor[i],
                                            lineWidth: data.datasets[0].borderWidth,
                                            hidden: false,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return `${context.label}: R\$ ${new Intl.NumberFormat('pt-BR', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }).format(context.parsed)} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        this.charts.set(canvasId, chart);
        return chart;
    }

    /**
     * Criar gráfico de barras para comparação temporal
     */
    createComparisonBarChart(canvasId, yearData) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        const years = Object.keys(yearData);
        const entradas = years.map(year => yearData[year].entradas);
        const saidas = years.map(year => yearData[year].saidas);
        const saldos = years.map(year => yearData[year].saldo);

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: years,
                datasets: [
                    {
                        label: 'Entradas',
                        data: entradas,
                        backgroundColor: this.hexToRgba(this.defaultColors.main, 0.8),
                        borderColor: this.defaultColors.main,
                        borderWidth: 1
                    },
                    {
                        label: 'Saídas',
                        data: saidas,
                        backgroundColor: this.hexToRgba(this.defaultColors.danger, 0.8),
                        borderColor: this.defaultColors.danger,
                        borderWidth: 1
                    },
                    {
                        label: 'Saldo',
                        data: saldos,
                        backgroundColor: this.hexToRgba(this.defaultColors.success, 0.8),
                        borderColor: this.defaultColors.success,
                        borderWidth: 1,
                        type: 'line',
                        tension: 0.4,
                        fill: false,
                        pointRadius: 6,
                        pointBackgroundColor: this.defaultColors.success
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': R\$ ' + 
                                       new Intl.NumberFormat('pt-BR', {
                                           minimumFractionDigits: 2,
                                           maximumFractionDigits: 2
                                       }).format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Anos'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Valores (R\$)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'R\$ ' + new Intl.NumberFormat('pt-BR', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(value);
                            }
                        }
                    }
                }
            }
        });

        this.charts.set(canvasId, chart);
        return chart;
    }

    /**
     * Destruir gráfico específico
     */
    destroyChart(canvasId) {
        const chart = this.charts.get(canvasId);
        if (chart) {
            chart.destroy();
            this.charts.delete(canvasId);
        }
    }

    /**
     * Destruir todos os gráficos
     */
    destroyAllCharts() {
        this.charts.forEach(chart => chart.destroy());
        this.charts.clear();
    }

    /**
     * Utilitários
     */
    hexToRgba(hex, alpha) {
        const r = parseInt(hex.slice(4, 7));
        const g = parseInt(hex.slice(8, 11));
        const b = parseInt(hex.slice(12, 15));
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }

    generateColors(count) {
        const baseColors = [
            this.defaultColors.main,
            this.defaultColors.success,
            this.defaultColors.danger,
            this.defaultColors.warning,
            this.defaultColors.link
        ];

        const background = [];
        const border = [];

        for (let i = 0; i < count; i++) {
            const color = baseColors[i % baseColors.length];
            background.push(this.hexToRgba(color, 0.8));
            border.push(color);
        }

        return { background, border };
    }
}

// Instância global
export const reportCharts = new ReportCharts();

// Auto-inicialização para páginas de relatórios
document.addEventListener('DOMContentLoaded', function() {
    // Cash Flow Chart
    const cashFlowCanvas = document.getElementById('cashFlowChart');
    if (cashFlowCanvas && window.monthlyData) {
        reportCharts.createCashFlowChart('cashFlowChart', window.monthlyData);
    }

    // Category Chart
    const categoryCanvas = document.getElementById('categoryChart');
    if (categoryCanvas && window.categoryData) {
        reportCharts.createCategoryPieChart('categoryChart', window.categoryData);
    }

    // Comparison Chart
    const comparisonCanvas = document.getElementById('comparisonChart');
    if (comparisonCanvas && window.yearData) {
        reportCharts.createComparisonBarChart('comparisonChart', window.yearData);
    }
});

// Cleanup ao sair da página
window.addEventListener('beforeunload', function() {
    reportCharts.destroyAllCharts();
});