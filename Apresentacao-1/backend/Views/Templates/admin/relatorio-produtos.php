<style>
    /* Estilos de Relatório de Produtos */
    .rep-container {
        padding-top: 10px;
    }

    /* Header Estilo Koketsu */
    .rep-header {
        background: var(--bg-card);
        padding: 24px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        box-shadow: var(--shadow-sm);
    }

    .rep-header h1 {
        font-size: 1.8em;
        margin: 0;
        font-weight: 800;
        letter-spacing: -1px;
        color: var(--text-main);
    }

    .btn-panel {
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

    .btn-panel:hover {
        background: var(--text-main);
        color: var(--bg-main);
        transform: translateY(-2px);
    }

    /* Dashboard Cards */
    .stats-flex {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .card-stat {
        background: var(--bg-card);
        padding: 24px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: var(--shadow-sm);
        transition: 0.3s;
    }
    
    .card-stat:hover {
        border-color: var(--accent);
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .card-icon {
        background: rgba(255, 215, 0, 0.1);
        color: var(--accent);
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 1.6rem;
    }

    .card-info span {
        display: block;
        color: var(--text-muted);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .card-info strong {
        font-size: 1.3rem;
        color: var(--text-main);
        font-weight: 800;
    }

    /* Busca */
    .search-row {
        margin-bottom: 25px;
        position: relative;
    }

    .search-row input {
        width: 100%;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        padding: 15px 20px 15px 45px;
        border-radius: 12px;
        color: var(--text-main);
        outline: none;
        transition: 0.3s;
        box-shadow: var(--shadow-sm);
    }

    .search-row input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(197, 160, 45, 0.1);
    }

    .search-row i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--accent);
    }

    /* Tabela Premium */
    .table-box {
        background: var(--bg-card);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
    }

    .prod-table {
        width: 100%;
        border-collapse: collapse;
    }

    .prod-table thead th {
        background: var(--bg-main);
        color: var(--accent);
        text-align: left;
        padding: 20px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 800;
        border-bottom: 1px solid var(--border-color);
    }

    .prod-table tbody td {
        padding: 18px 20px;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.95rem;
        color: var(--text-main);
    }

    .prod-table tbody tr:hover {
        background: rgba(var(--accent), 0.02);
    }

    /* Estilos de Célula */
    .flex-prod {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .circle-avatar {
        width: 38px;
        height: 38px;
        background: var(--bg-main);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--accent);
        border: 1px solid var(--border-color);
    }

    .perf-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .perf-high { background: rgba(255, 71, 87, 0.1); color: #ff4757; border: 1px solid rgba(255, 71, 87, 0.2); }
    .perf-mid { background: rgba(255, 165, 2, 0.1); color: #ffa502; border: 1px solid rgba(255, 165, 2, 0.2); }
    .perf-low { background: rgba(46, 213, 115, 0.1); color: #2ed573; border: 1px solid rgba(46, 213, 115, 0.2); }

    .bar-outer {
        width: 100px;
        height: 6px;
        background: var(--bg-main);
        border-radius: 10px;
        margin-top: 8px;
        overflow: hidden;
    }

    .bar-inner {
        height: 100%;
        border-radius: 10px;
        background: var(--accent);
    }
</style>

<div class="rep-container">
    <div class="rep-header">
        <h1><i class="fas fa-chart-line"></i> Análise de Produtos</h1>
        <a href="/backend/relatorios" class="btn-panel"><i class="fa fa-chevron-left"></i> Painel</a>
    </div>

    <?php 
        $totalProdutos = count($produtosMaisVendidos ?? []);
        $totalReceita = array_sum(array_column($produtosMaisVendidos ?? [], 'receita'));
        $maxQuantidade = $totalProdutos > 0 ? max(array_column($produtosMaisVendidos, 'quantidade_total')) : 0;
    ?>

    <div class="stats-flex">
        <div class="card-stat">
            <div class="card-icon"><i class="fas fa-box"></i></div>
            <div class="card-info">
                <span>Itens Analisados</span>
                <strong><?php echo $totalProdutos; ?> Produtos</strong>
            </div>
        </div>
        <div class="card-stat">
            <div class="card-icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="card-info">
                <span>Receita Acumulada</span>
                <strong style="color: var(--accent);">R$ <?php echo number_format($totalReceita, 2, ',', '.'); ?></strong>
            </div>
        </div>
        <div class="card-stat">
            <div class="card-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="card-info">
                <span>Data do Relatório</span>
                <strong><?php echo date('d/m/Y H:i'); ?></strong>
            </div>
        </div>
    </div>

    <div class="search-row">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInputProd" placeholder="Filtrar por nome, categoria ou código...">
    </div>

    <div class="table-box">
        <table class="prod-table" id="relatorioProdTable">
            <thead>
                <tr>
                    <th style="width: 80px;">Cód.</th>
                    <th>Produto</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Vendas</th>
                    <th>Qtd Total</th>
                    <th>Receita</th>
                    <th style="width: 180px;">Performance</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($produtosMaisVendidos)): ?>
                    <?php foreach ($produtosMaisVendidos as $produto): ?>
                        <?php 
                            $qtd = $produto['quantidade_total'] ?? 0;
                            if ($qtd > 40) {
                                $perfClass = 'perf-high';
                                $perfText = '🔥 ALTA';
                            } elseif ($qtd >= 15) {
                                $perfClass = 'perf-mid';
                                $perfText = '⭐ MÉDIA';
                            } else {
                                $perfClass = 'perf-low';
                                $perfText = '📉 BAIXA';
                            }
                            $perc = ($maxQuantidade > 0) ? ($qtd / $maxQuantidade * 100) : 0;
                        ?>
                        <tr>
                            <td style="color: var(--text-muted); font-family: monospace; font-weight: bold;">#<?php echo $produto['id_produto']; ?></td>
                            <td>
                                <div class="flex-prod">
                                    <div class="circle-avatar"><?php echo strtoupper(substr($produto['nome_produtos'], 0, 2)); ?></div>
                                    <span style="font-weight: 700;"><?php echo htmlspecialchars($produto['nome_produtos']); ?></span>
                                </div>
                            </td>
                            <td><span style="color: var(--text-muted); font-weight: 600;"><?php echo $produto['nome_categorias'] ?? 'Geral'; ?></span></td>
                            <td>R$ <?php echo number_format($produto['preco_produtos'], 2, ',', '.'); ?></td>
                            <td style="font-weight: 700;"><?php echo $produto['total_vendido']; ?>x</td>
                            <td style="font-weight: 800; color: var(--text-main);"><?php echo $produto['quantidade_total']; ?></td>
                            <td style="color: var(--accent); font-weight: 800;">
                                R$ <?php echo number_format($produto['receita'], 2, ',', '.'); ?>
                            </td>
                            <td>
                                <span class="perf-badge <?php echo $perfClass; ?>"><?php echo $perfText; ?></span>
                                <div class="bar-outer">
                                    <div class="bar-inner" style="width: <?php echo $perc; ?>%;"></div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('searchInputProd').addEventListener('keyup', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('#relatorioProdTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
        });
    });
</script>
