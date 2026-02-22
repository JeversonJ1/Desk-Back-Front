<style>
    /* Reset e Base de Relatório */
    .finance-wrapper { 
        padding-top: 10px;
    }
    
    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 35px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }

    .header-title h1 { font-size: 2em; font-weight: 800; letter-spacing: -1px; color: var(--text-main); margin: 0; }
    .header-title p { color: var(--text-muted); font-size: 0.95em; margin-top: 5px; }

    .btn-back-rep {
        background: var(--accent);
        color: #000;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.85em;
        transition: 0.3s;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(197, 160, 45, 0.2);
    }
    .btn-back-rep:hover { background: var(--text-main); color: var(--bg-main); transform: translateX(-5px); }

    /* Status Cards */
    .resumo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .resumo-card {
        background: var(--bg-card);
        padding: 25px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        transition: 0.3s;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    .resumo-card:hover { border-color: var(--accent); transform: translateY(-5px); box-shadow: var(--shadow-md); }
    .resumo-card i { position: absolute; right: 20px; top: 20px; font-size: 1.5em; color: var(--accent); opacity: 0.1; }
    .resumo-card h3 { font-size: 0.8em; text-transform: uppercase; color: var(--text-muted); letter-spacing: 1px; margin-bottom: 10px; font-weight: 800; }
    .resumo-valor { font-size: 1.8em; font-weight: 800; color: var(--accent); }
    .resumo-sub { font-size: 0.75em; color: var(--text-muted); margin-top: 5px; }

    /* Charts Section */
    .charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 40px;
    }

    .chart-box {
        background: var(--bg-card);
        padding: 25px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
    }
    .chart-box h3 { font-size: 1.1em; font-weight: 700; margin-bottom: 25px; color: var(--text-main); display: flex; align-items: center; gap: 10px; }
    .chart-box h3 i { color: var(--accent); }

    /* Table Design */
    .table-card {
        background: var(--bg-card);
        border-radius: 16px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        margin-bottom: 40px;
    }

    .rep-table { width: 100%; border-collapse: collapse; }
    .rep-table th { background: var(--bg-main); color: var(--accent); padding: 18px; text-align: left; font-size: 0.8em; text-transform: uppercase; font-weight: 800; letter-spacing: 1px; }
    .rep-table td { padding: 18px; border-bottom: 1px solid var(--border-color); font-size: 0.95em; color: var(--text-main); }
    .rep-table tr:last-child td { border-bottom: none; }
    .rep-table tr:hover { background: rgba(var(--accent), 0.02); }

    .valor-positivo { color: #2ecc71; font-weight: 700; }
    .badge-mes { background: var(--bg-main); padding: 6px 12px; border-radius: 8px; color: var(--text-main); font-size: 0.85em; font-weight: 700; border: 1px solid var(--border-color); }

    @media (max-width: 1100px) { .charts-grid { grid-template-columns: 1fr; } }
</style>

<div class="finance-wrapper">
    <header class="header-top">
        <div class="header-title">
            <h1>💵 Relatório Financeiro</h1>
            <p>Análise de saúde financeira e desempenho de vendas.</p>
        </div>
        <a href="/backend/relatorios" class="btn-back-rep"><i class="fa fa-arrow-left"></i> Voltar</a>
    </header>

    <div class="resumo-grid">
        <div class="resumo-card">
            <i class="fas fa-hand-holding-usd"></i>
            <h3>Receita Total</h3>
            <div class="resumo-valor">R$ <?php echo number_format($resumoFinanceiro['receita_total'] ?? 0, 2, ',', '.'); ?></div>
            <div class="resumo-sub">Vendas confirmadas</div>
        </div>

        <div class="resumo-card">
            <i class="fas fa-shopping-bag"></i>
            <h3>Pedidos</h3>
            <div class="resumo-valor"><?php echo $resumoFinanceiro['total_pedidos'] ?? 0; ?></div>
            <div class="resumo-sub">Volume total processado</div>
        </div>

        <div class="resumo-card">
            <i class="fas fa-chart-line"></i>
            <h3>Ticket Médio</h3>
            <div class="resumo-valor">R$ <?php echo number_format($resumoFinanceiro['ticket_medio'] ?? 0, 2, ',', '.'); ?></div>
            <div class="resumo-sub">Média por cliente</div>
        </div>

        <div class="resumo-card">
            <i class="fas fa-trophy"></i>
            <h3>Maior Venda</h3>
            <div class="resumo-valor">R$ <?php echo number_format($resumoFinanceiro['maior_venda'] ?? 0, 2, ',', '.'); ?></div>
            <div class="resumo-sub">Recorde de pedido único</div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-box">
            <h3><i class="fas fa-chart-area"></i> Evolução de Vendas</h3>
            <div style="height: 300px;">
                <canvas id="vendasMesesChart"></canvas>
            </div>
        </div>

        <div class="chart-box">
            <h3><i class="fas fa-crown"></i> Top Meses (Receita)</h3>
            <div style="height: 300px;">
                <canvas id="topMesesChart"></canvas>
            </div>
        </div>
    </div>

    <div class="table-card">
        <table class="rep-table">
            <thead>
                <tr>
                    <th>Mês de Referência</th>
                    <th>Qtd. Pedidos</th>
                    <th>Receita Bruta</th>
                    <th>Ticket Médio</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($vendasPorMesTabela)): ?>
                    <?php foreach ($vendasPorMesTabela as $mes): ?>
                        <tr>
                            <td><span class="badge-mes"><?php echo $mes['mes']; ?></span></td>
                            <td><?php echo $mes['quantidade']; ?> un.</td>
                            <td class="valor-positivo">R$ <?php echo number_format($mes['valor'] ?? 0, 2, ',', '.'); ?></td>
                            <td style="color: var(--accent); font-weight: 600;">R$ <?php echo number_format(($mes['valor'] ?? 0) / ($mes['quantidade'] ?? 1), 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align:center; padding: 40px; color: var(--text-muted);">Nenhum dado financeiro registrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer style="margin-top: 50px; text-align: center; color: var(--text-muted); font-size: 0.85em; padding-bottom: 30px;">
        <p>Gerado em: <?php echo date('d/m/Y H:i'); ?> • Koketsu Store Dashboard v2.5</p>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        // Função para obter as cores do tema atual
        function getThemeColors() {
            const style = getComputedStyle(document.documentElement);
            return {
                accent: style.getPropertyValue('--accent').trim(),
                text: style.getPropertyValue('--text-main').trim(),
                muted: style.getPropertyValue('--text-muted').trim(),
                border: style.getPropertyValue('--border-color').trim(),
                bgCard: style.getPropertyValue('--bg-card').trim()
            };
        }

        const colors = getThemeColors();
        const vendasData = <?php echo json_encode($vendasPorMes); ?>;
        const labels = vendasData.map(d => d.mes);
        const valores = vendasData.map(d => d.valor);

        // Configurações globais do Chart.js
        Chart.defaults.color = colors.muted;
        Chart.defaults.font.family = "'Inter', sans-serif";

        // Gráfico de Linha
        new Chart(document.getElementById('vendasMesesChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Vendas (R$)',
                    data: valores,
                    borderColor: colors.accent,
                    backgroundColor: colors.accent + '11',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: colors.accent
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        grid: { color: colors.border }, 
                        border: { display: false },
                        ticks: { color: colors.muted }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: colors.muted }
                    }
                }
            }
        });

        // Gráfico de Barras
        new Chart(document.getElementById('topMesesChart'), {
            type: 'bar',
            data: {
                labels: labels.slice(-5),
                datasets: [{
                    data: valores.slice(-5),
                    backgroundColor: colors.accent,
                    borderRadius: 8,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        grid: { color: colors.border }, 
                        border: { display: false },
                        ticks: { color: colors.muted }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: colors.muted }
                    }
                }
            }
        });
    })();
</script>
