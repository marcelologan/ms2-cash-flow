// ===================================================================
// GRÁFICO DE ANÁLISE POR CATEGORIAS
// ===================================================================

import Chart from 'chart.js/auto';

export function initCategoryChart() {
    const ctx = document.getElementById('categoryChart');
    if (!ctx || !window.categoryData || window.categoryData.length === 0) return null;

    const categoryData = window.categoryData;
    const colors = [
        '#45AAF2', '#26DE81', '#EB3B5A', '#FED330', '#8854D0',
        '#FF6B9D', '#45AAF2', '#26DE81', '#EB3B5A', '#FED330'
    ];

    return new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: categoryData.map(item => item.category),
            datasets: [{
                data: categoryData.map(item => item.total),
                backgroundColor: colors.slice(0, categoryData.length),
                borderColor: colors.slice(0, categoryData.length),
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
                            return `${context.label}: R$ ${new Intl.NumberFormat('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(context.parsed)} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}
