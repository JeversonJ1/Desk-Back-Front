<?php
/**
 * Exemplo de uso do Chart.js no projeto Koketsu
 * 
 * Adicione este arquivo ao seu dashboard ou crie uma nova página
 * Certifique-se de incluir: <script src="/node_modules/chart.js/dist/chart.umd.min.js"></script>
 */
?>

<div class="charts-container">
    <!-- Gráfico de Vendas -->
    <div class="chart-wrapper">
        <h3>Vendas por Mês</h3>
        <canvas id="ventasChart" width="400" height="100"></canvas>
    </div>

    <!-- Gráfico de Categorias -->
    <div class="chart-wrapper">
        <h3>Produtos por Categoria</h3>
        <canvas id="categoriasChart" width="400" height="100"></canvas>
    </div>

    <!-- Gráfico de Usuários -->
    <div class="chart-wrapper">
        <h3>Status de Usuários</h3>
        <canvas id="usuariosChart" width="400" height="100"></canvas>
    </div>
</div>

<style>
    .charts-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 30px;
        padding: 20px;
        background: #1a1a1a;
        border-radius: 8px;
        margin-top: 30px;
    }

    .chart-wrapper {
        background: #222;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .chart-wrapper h3 {
        color: #dfd155;
        margin-bottom: 15px;
        font-size: 18px;
        text-align: center;
    }

    .chart-wrapper canvas {
        max-width: 100%;
        height: auto;
    }
</style>

<script src="/node_modules/chart.js/dist/chart.umd.min.js"></script>

<script>
    // Gráfico de Vendas por Mês
    const ventasCtx = document.getElementById('ventasChart').getContext('2d');
    new Chart(ventasCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Vendas (R$)',
                data: [1200, 1900, 3000, 2500, 2200, 3200],
                borderColor: '#dfd155',
                backgroundColor: 'rgba(223, 209, 85, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#dfd155',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#bbb',
                        font: { size: 12 }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#888' },
                    grid: { color: '#333' }
                },
                x: {
                    ticks: { color: '#888' },
                    grid: { display: false }
                }
            }
        }
    });

    // Gráfico de Categorias
    const categoriasCtx = document.getElementById('categoriasChart').getContext('2d');
    new Chart(categoriasCtx, {
        type: 'doughnut',
        data: {
            labels: ['Camisetas', 'Calças', 'Moletom', 'Sapatos', 'Acessórios'],
            datasets: [{
                data: [30, 25, 20, 15, 10],
                backgroundColor: [
                    '#dfd155',
                    '#00d85a',
                    '#ff6b6b',
                    '#4ecdc4',
                    '#95a5a6'
                ],
                borderColor: '#222',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#bbb',
                        font: { size: 12 }
                    }
                }
            }
        }
    });

    // Gráfico de Usuários
    const usuariosCtx = document.getElementById('usuariosChart').getContext('2d');
    new Chart(usuariosCtx, {
        type: 'bar',
        data: {
            labels: ['Admins', 'Usuários Ativos', 'Usuários Inativos'],
            datasets: [{
                label: 'Quantidade',
                data: [3, 125, 8],
                backgroundColor: [
                    '#dfd155',
                    '#00d85a',
                    '#ff6b6b'
                ],
                borderColor: [
                    '#c4a33a',
                    '#00a840',
                    '#cc0000'
                ],
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#bbb',
                        font: { size: 12 }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { color: '#888' },
                    grid: { color: '#333' }
                },
                y: {
                    ticks: { color: '#888' },
                    grid: { display: false }
                }
            }
        }
    });
</script>
