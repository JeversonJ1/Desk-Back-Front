<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Koketsu Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-gold: var(--accent);
            --secondary-gold: var(--accent-hover);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-main) !important;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(197, 160, 45, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(197, 160, 45, 0.05) 0%, transparent 40%);
            color: var(--text-main);
            min-height: 100vh;
            line-height: 1.6;
        }

        /* Header Estilo Moderno */
        .header {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            padding: 40px 60px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(to right, var(--accent), var(--text-main));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .header p {
            color: var(--text-muted);
            font-weight: 400;
            letter-spacing: 0.5px;
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 40px 60px 40px;
        }

        /* Grid de Estatísticas Elevadas */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            padding: 30px;
            border-radius: 24px;
            backdrop-filter: blur(5px);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: var(--shadow-sm);
        }

        .stat-card:hover {
            transform: translateY(-10px);
            border-color: var(--accent);
            box-shadow: var(--shadow-md);
        }

        .stat-card h3 {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--text-main);
            z-index: 2;
            position: relative;
        }

        .stat-icon {
            position: absolute;
            right: 20px;
            bottom: 15px;
            font-size: 4rem;
            opacity: 0.05;
            filter: grayscale(1);
            transition: 0.4s;
        }

        .stat-card:hover .stat-icon {
            opacity: 0.15;
            transform: scale(1.1) rotate(-5deg);
        }

        /* Gráficos em Containers Minimalistas */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(600px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .chart-container {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            padding: 35px;
            border-radius: 24px;
            box-shadow: var(--shadow-sm);
        }

        .chart-container h3 {
            color: var(--accent);
            margin-bottom: 30px;
            font-size: 1.1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-container h3::before {
            content: "";
            width: 4px;
            height: 20px;
            background: var(--accent);
            border-radius: 10px;
        }

        .chart-wrapper {
            position: relative;
            height: 350px;
        }

        /* Botões Estilo Dashboard Luxo */
        .actions-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        .action-button {
            background: var(--bg-card);
            color: var(--accent);
            border: 1px solid var(--border-color);
            padding: 18px 35px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 700;
            text-decoration: none;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: var(--shadow-sm);
        }

        .action-button:hover {
            background: var(--accent);
            color: #000;
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .footer-text {
            text-align: center;
            color: var(--text-muted);
            margin-top: 60px;
            padding: 30px;
            font-size: 0.85rem;
            border-top: 1px solid var(--border-color);
        }

        @media (max-width: 1024px) {
            .charts-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .header { padding: 30px 20px; }
            .container { padding: 0 20px 40px 20px; }
        }

        /* Animações */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stat-card, .chart-container {
            animation: fadeIn 0.6s ease-out forwards;
        }

        @media (max-width: 1024px) {
            .charts-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .header { padding: 30px 20px; }
            .container { padding: 0 20px 40px 20px; }
        }

        /* Animações */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stat-card, .chart-container {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-chart-pie"></i> Relatórios Koketsu</h1>
        <p>Dashboard de Análise e Monitoramento em Tempo Real</p>
    </div>

    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Vendas Totais</h3>
                <div class="stat-value">R$ <?php echo number_format($vendastotais ?? 0, 2, ',', '.'); ?></div>
                <div class="stat-icon">💰</div>
            </div>

            <div class="stat-card">
                <h3>Produtos em Catálogo</h3>
                <div class="stat-value"><?php echo $produtostotais ?? 0; ?></div>
                <div class="stat-icon">📦</div>
            </div>

            <div class="stat-card">
                <h3>Base de Clientes</h3>
                <div class="stat-value"><?php echo $clientestotais ?? 0; ?></div>
                <div class="stat-icon">👥</div>
            </div>

            <div class="stat-card">
                <h3>Volume de Pedidos</h3>
                <div class="stat-value"><?php echo $pedidostotais ?? 0; ?></div>
                <div class="stat-icon">📋</div>
            </div>
        </div>

        <div class="charts-grid">
            <div class="chart-container">
                <h3>Performance de Vendas</h3>
                <div class="chart-wrapper">
                    <canvas id="vendasPorMesChart"></canvas>
                </div>
            </div>

            <div class="chart-container">
                <h3>Produtos Elite (Top 8)</h3>
                <div class="chart-wrapper">
                    <canvas id="produtosMaisVendidosChart"></canvas>
                </div>
            </div>

            <div class="chart-container">
                <h3>Fluxo de Status</h3>
                <div class="chart-wrapper">
                    <canvas id="statusPedidosChart"></canvas>
                </div>
            </div>

            <div class="chart-container">
                <h3>Mix por Categoria</h3>
                <div class="chart-wrapper">
                    <canvas id="categoriasChart"></canvas>
                </div>
            </div>
        </div>

        <div class="actions-grid">
            <a href="/backend/relatorios/detalhado" class="action-button"><i class="fas fa-list-check"></i> Relatório Detalhado</a>
            <a href="/backend/relatorios/financeiro" class="action-button"><i class="fas fa-wallet"></i> Financeiro</a>
            <a href="/backend/relatorios/produtos" class="action-button"><i class="fas fa-box-open"></i> Análise de Produtos</a>
            <a href="/backend/admin/dashboard" class="action-button"><i class="fas fa-arrow-left"></i> Voltar ao Dash</a>
        </div>

        <div class="footer-text">
            <p>© 2026 Koketsu Store • Todos os dados protegidos e criptografados.</p>
        </div>
    </div>

    <script>
        // Configuração global dos gráficos baseada no tema
        const style = getComputedStyle(document.body);
        const textColor = style.getPropertyValue('--text-muted').trim() || '#888';
        const gridColor = style.getPropertyValue('--border-color').trim() || 'rgba(0,0,0,0.1)';
        const accentColor = style.getPropertyValue('--accent').trim() || '#ffd700';

        Chart.defaults.color = textColor;
        Chart.defaults.borderColor = gridColor;
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";

        const chartColors = {
            primary: '#ffd700',
            secondary: '#ff6b6b',
            tertiary: '#4ecdc4',
            quaternary: '#45b7d1',
            quinary: '#96ceb4',
            senary: '#ffeaa7'
        };

        // Gráfico de Vendas por Mês
        const vendasPorMesData = <?php echo $vendasPorMes; ?>;
        const ctxVendas = document.getElementById('vendasPorMesChart').getContext('2d');
        new Chart(ctxVendas, {
            type: 'line',
            data: {
                labels: vendasPorMesData.labels,
                datasets: [{
                    label: 'Vendas (R$)',
                    data: vendasPorMesData.data,
                    borderColor: chartColors.primary,
                    backgroundColor: 'rgba(255, 215, 0, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: chartColors.primary,
                    pointBorderColor: '#000',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: { usePointStyle: true, padding: 20 }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Gráfico de Produtos Mais Vendidos
        const produtosData = <?php echo $produtosMaisVendidos; ?>;
        const ctxProdutos = document.getElementById('produtosMaisVendidosChart').getContext('2d');
        new Chart(ctxProdutos, {
            type: 'bar',
            data: {
                labels: produtosData.labels,
                datasets: [{
                    label: 'Quantidade Vendida',
                    data: produtosData.data,
                    backgroundColor: [
                        chartColors.primary, chartColors.secondary, chartColors.tertiary,
                        chartColors.quaternary, chartColors.quinary, chartColors.senary,
                        '#d4af37', '#daa520'
                    ],
                    borderColor: 'rgba(0,0,0,0)',
                    borderWidth: 0,
                    borderRadius: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                    y: { grid: { display: false } }
                }
            }
        });

        // Gráfico de Status de Pedidos
        const statusData = <?php echo $statusPedidos; ?>;
        const ctxStatus = document.getElementById('statusPedidosChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: statusData.labels,
                datasets: [{
                    data: statusData.data,
                    backgroundColor: [
                        chartColors.primary, chartColors.secondary, chartColors.tertiary,
                        chartColors.quaternary, chartColors.quinary
                    ],
                    borderColor: '#0f0f0f',
                    borderWidth: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true } }
                }
            }
        });

        // Gráfico de Categorias
        const categoriasData = <?php echo $categoriasMaisProdutos; ?>;
        const ctxCategorias = document.getElementById('categoriasChart').getContext('2d');
        new Chart(ctxCategorias, {
            type: 'radar',
            data: {
                labels: categoriasData.labels,
                datasets: [{
                    label: 'Quantidade de Produtos',
                    data: categoriasData.data,
                    borderColor: chartColors.primary,
                    backgroundColor: 'rgba(255, 215, 0, 0.15)',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: chartColors.primary,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        angleLines: { color: 'rgba(255, 255, 255, 0.1)' },
                        pointLabels: { color: '#888', font: { size: 10 } },
                        ticks: { display: false }
                    }
                }
            }
        });
    </script>
</body>
</html>