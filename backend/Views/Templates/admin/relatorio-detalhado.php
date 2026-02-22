<style>
    /* Estilos de Relatório Detalhado */
    .detalhado-wrapper {
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

    .rep-header h2 {
        font-size: 1.6em;
        margin: 0;
        font-weight: 800;
        letter-spacing: -1px;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .rep-header h2 i { color: var(--accent); }

    .btn-voltar-rep {
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

    .btn-voltar-rep:hover {
        background: var(--text-main);
        color: var(--bg-main);
        transform: translateX(-5px);
    }

    /* Info Card */
    .info-box {
        background: var(--bg-card);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        border-left: 5px solid var(--accent);
        color: var(--text-muted);
        box-shadow: var(--shadow-sm);
        font-size: 0.9em;
    }
    .info-box strong { color: var(--accent); }

    /* Tabela Premium */
    .table-card {
        background: var(--bg-card);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
    }

    .detalhado-table {
        width: 100%;
        border-collapse: collapse;
    }

    .detalhado-table thead th {
        background: var(--bg-main);
        color: var(--accent);
        text-align: left;
        padding: 18px 20px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 800;
        border-bottom: 1px solid var(--border-color);
    }

    .detalhado-table tbody td {
        padding: 18px 20px;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.92rem;
        color: var(--text-main);
    }

    .detalhado-table tbody tr:hover {
        background: rgba(var(--accent), 0.02);
    }

    /* Badges de Status Premium */
    .badge-status {
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        min-width: 100px;
        text-align: center;
        border-width: 1px;
        border-style: solid;
    }

    .status-pendente  { color: #ffa502; border-color: rgba(255, 165, 2, 0.3); background: rgba(255, 165, 2, 0.1); }
    .status-pago      { color: #2ed573; border-color: rgba(46, 213, 115, 0.3); background: rgba(46, 213, 115, 0.1); }
    .status-enviado   { color: #1e90ff; border-color: rgba(30, 144, 255, 0.3); background: rgba(30, 144, 255, 0.1); }
    .status-entregue  { color: #eccc68; border-color: rgba(236, 204, 104, 0.3); background: rgba(236, 204, 104, 0.1); }
    .status-concluido { color: #ff4757; border-color: rgba(255, 71, 87, 0.3); background: rgba(255, 71, 87, 0.1); }
    .status-cancelado { color: #a4b0be; border-color: rgba(164, 176, 190, 0.3); background: rgba(164, 176, 190, 0.1); }
    .status-default   { color: var(--text-muted); border-color: var(--border-color); background: var(--bg-main); }

    .price-tag { 
        color: var(--accent); 
        font-weight: 800; 
        font-family: 'Inter', sans-serif;
    }
</style>

<div class="detalhado-wrapper">
    <div class="rep-header">
        <h2><i class="fas fa-file-invoice-dollar"></i> Relatório Detalhado de Pedidos</h2>
        <a href="/backend/relatorios" class="btn-voltar-rep"><i class="fa fa-arrow-left"></i> Voltar</a>
    </div>

    <div class="info-box">
        <p><strong>Total de Pedidos:</strong> <?php echo count($pedidos ?? []); ?> registros encontrados</p>
        <p><strong>Referência:</strong> Dados consolidados até <?php echo date('d/m/Y H:i'); ?></p>
    </div>

    <div class="table-card">
        <table class="detalhado-table">
            <thead>
                <tr>
                    <th style="width: 120px;">ID Pedido</th>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Email</th>
                    <th>Valor Total</th>
                    <th>Status</th>
                    <th style="text-align: center;">Itens</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pedidos)): ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td style="font-family: monospace; font-weight: 800; color: var(--text-muted);">
                                #<?php echo str_pad($pedido['id_pedido'], 5, '0', STR_PAD_LEFT); ?>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($pedido['data_pedido'])); ?></td>
                            <td style="font-weight: 700;"><?php echo htmlspecialchars($pedido['nome_usuarios'] ?? 'Consumidor Final'); ?></td>
                            <td style="color: var(--text-muted); font-size: 0.85em;"><?php echo htmlspecialchars($pedido['email_usuarios'] ?? '---'); ?></td>
                            <td class="price-tag">R$ <?php echo number_format($pedido['total_pedido'] ?? 0, 2, ',', '.'); ?></td>
                            <td>
                                <?php 
                                    $status_map = [
                                        'pago' => 'status-pago',
                                        'concluido' => 'status-concluido',
                                        'concluído' => 'status-concluido',
                                        'enviado' => 'status-enviado',
                                        'entregue' => 'status-entregue',
                                        'cancelado' => 'status-cancelado',
                                        'pendente' => 'status-pendente'
                                    ];
                                    $s = strtolower(trim($pedido['status_pedido'] ?? 'pendente'));
                                    $cls = $status_map[$s] ?? 'status-default';
                                ?>
                                <span class="badge-status <?php echo $cls; ?>">
                                    <?php echo htmlspecialchars($pedido['status_pedido'] ?? 'Pendente'); ?>
                                </span>
                            </td>
                            <td style="text-align: center; font-weight: 800; color: var(--accent);">
                                <?php echo $pedido['quantidade'] ?? 0; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 60px; color: var(--text-muted);">
                            <i class="fas fa-ghost" style="font-size: 3em; opacity: 0.2; margin-bottom: 15px; display: block;"></i>
                            Nenhum pedido registrado no sistema.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer style="margin-top: 50px; text-align: center; font-size: 0.8em; color: var(--text-muted); padding-bottom: 40px;">
        &copy; <?php echo date('Y'); ?> Koketsu Store • Sistema de Inteligência de Vendas
    </footer>
</div>
