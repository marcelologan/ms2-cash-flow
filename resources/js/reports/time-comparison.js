// ===================================================================
// GRÁFICO DE COMPARAÇÃO TEMPORAL
// ===================================================================

import Chart from 'chart.js/auto';

export function initComparisonChart() {
    const ctx = document.getElementById('comparisonChart');
    if (!ctx || !window.yearData) return null;

    const yearData = window.yearData;
    const years = Object.keys(yearData);
    const entradas = years.map(year => yearData[year].entradas);
    const saidas = years.map(year => yearData[year].saidas);
    const saldos = years.map(year => yearData[year].saldo);

    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: years,
            datasets: [
                {
                    label: 'Entradas',
                    data: entradas,
                    backgroundColor: 'rgba(69, 170, 242, 0.8)',
                    borderColor: '#45AAF2',
                    borderWidth: 1
                },
                {
                    label: 'Saídas',
                    data: saidas,
                    backgroundColor: 'rgba(235, 59, 90, 0.8)',
                    borderColor: '#EB3B5A',
                    borderWidth: 1
                },
                {
                    label: 'Saldo',
                    data: saldos,
                    backgroundColor: 'rgba(38, 222, 129, 0.8)',
                    borderColor: '#26DE81',
                    borderWidth: 1,
                    type: 'line',
                    tension: 0.4,
                    fill: false,
                    pointRadius: 6,
                    pointBackgroundColor: '#26DE81'
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
                            return context.dataset.label + ': R$ ' + 
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
                        text: 'Valores (R$)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + new Intl.NumberFormat('pt-BR', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(value);
                        }
                    }
                }
            }
        }
    });
}