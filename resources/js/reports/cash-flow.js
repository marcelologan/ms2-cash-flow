// ===================================================================
// GRÁFICO DE FLUXO DE CAIXA - VERSÃO DEBUG
// ===================================================================

import Chart from 'chart.js/auto';

export function initCashFlowChart() {
    const ctx = document.getElementById('cashFlowChart');
    if (!ctx) {
        console.error('❌ Canvas cashFlowChart não encontrado');
        return null;
    }

    if (!window.monthlyData) {
        console.error('❌ window.monthlyData não encontrado');
        return null;
    }

    const monthlyData = window.monthlyData;
    
    // 🔍 DEBUG: Verificar estrutura dos dados
    console.log('🔍 DEBUG monthlyData:', monthlyData);
    console.log('🔍 DEBUG monthlyData type:', typeof monthlyData);
    console.log('🔍 DEBUG monthlyData length:', monthlyData.length);
    
    // Verificar se é array
    if (!Array.isArray(monthlyData)) {
        console.error('❌ monthlyData não é um array:', monthlyData);
        return null;
    }

    // Verificar se tem dados
    if (monthlyData.length === 0) {
        console.error('❌ monthlyData está vazio');
        return null;
    }

    // 🔍 DEBUG: Verificar primeiro item
    console.log('🔍 DEBUG primeiro item:', monthlyData[0]);

    // Processar dados com segurança
    const labels = monthlyData.map(item => {
        const label = item.month_name || `Mês ${item.month || 'N/A'}`;
        console.log('🔍 Label:', label);
        return label;
    });

    const entradas = monthlyData.map(item => {
        const value = parseFloat(item.entradas_previstas || 0);
        console.log('🔍 Entrada:', value);
        return value;
    });

    const saidas = monthlyData.map(item => {
        const value = parseFloat(item.saidas_previstas || 0);
        console.log('🔍 Saída:', value);
        return value;
    });

    const saldos = monthlyData.map(item => {
        const value = parseFloat(item.saldo_realizado || 0);
        console.log('🔍 Saldo:', value);
        return value;
    });

    console.log('🔍 DEBUG processado:', { labels, entradas, saidas, saldos });

    // Verificar se o canvas já tem um chart
    if (ctx.chart) {
        console.log('⚠️ Canvas já possui um chart, destruindo...');
        ctx.chart.destroy();
    }

    console.log('📊 Criando novo Chart...');

    try {
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Entradas Previstas',
                        data: entradas,
                        borderColor: '#45AAF2',
                        backgroundColor: 'rgba(69, 170, 242, 0.1)',
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: '#45AAF2',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    },
                    {
                        label: 'Saídas Previstas',
                        data: saidas,
                        borderColor: '#EB3B5A',
                        backgroundColor: 'rgba(235, 59, 90, 0.1)',
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: '#EB3B5A',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    },
                    {
                        label: 'Saldo Realizado',
                        data: saldos,
                        borderColor: '#26DE81',
                        backgroundColor: 'rgba(38, 222, 129, 0.2)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#26DE81',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                // 🔍 DEBUG: Desabilitar animações temporariamente
                animation: false,
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
                        borderColor: '#45AAF2',
                        borderWidth: 1,
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
                            text: 'Valores (R$)',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + new Intl.NumberFormat('pt-BR', {
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

        console.log('✅ Chart criado com sucesso:', chart);
        return chart;

    } catch (error) {
        console.error('❌ Erro ao criar Chart:', error);
        return null;
    }
}