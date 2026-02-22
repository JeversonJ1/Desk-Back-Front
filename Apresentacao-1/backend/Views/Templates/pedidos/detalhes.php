<div class="page-wrapper">
    <div class="header-details">
        <h3 class="page-title">
            <i class="fa fa-info-circle"></i> Detalhes do Pedido #<?= htmlspecialchars($pedido['id_pedido']) ?>
        </h3>
        <a href="/backend/pedido/listar" class="btn-back">
            <i class="fa fa-arrow-left"></i> Voltar à Lista
        </a>
    </div>

    <?php 
    // Lógica para cor do status personalizada para o seu Dark Mode
    $status = strtolower($pedido['status_pedido'] ?? 'desconhecido');
    $status_style = 'border: 1px solid #555; color: #bbb;'; // Padrão
    
    if ($status === 'pago' || $status === 'concluido') {
        $status_style = 'background: rgba(40, 167, 69, 0.2); color: #28a745; border: 1px solid #28a745;';
    } elseif ($status === 'pendente' || $status === 'em andamento') {
        $status_style = 'background: rgba(226, 201, 62, 0.2); color: #e2c93e; border: 1px solid #e2c93e;';
    } elseif ($status === 'cancelado') {
        $status_style = 'background: rgba(220, 53, 69, 0.2); color: #dc3545; border: 1px solid #dc3545;';
    }
    ?>

    <div class="details-grid">
        <div class="info-card">
            <h4 class="card-header-title"><i class="fa fa-file-text-o"></i> Informações do Pedido</h4>
            <div class="card-body">
                <div class="detail-item">
                    <span>ID DO PEDIDO:</span>
                    <strong>#<?= htmlspecialchars($pedido['id_pedido'] ?? 'N/A') ?></strong>
                </div>
                <div class="detail-item">
                    <span>DATA:</span>
                    <strong><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'] ?? '')) ?></strong>
                </div>
                <div class="detail-item">
                    <span>STATUS ATUAL:</span>
                    <span class="status-badge" style="<?= $status_style ?>">
                        <?= strtoupper(htmlspecialchars($pedido['status_pedido'] ?? 'Desconhecido')) ?>
                    </span>
                </div>
                <div class="detail-item total-highlight">
                    <span>TOTAL PAGO:</span>
                    <strong class="gold-text">R$ <?= number_format($pedido['total_pedido'] ?? 0, 2, ',', '.') ?></strong>
                </div>
            </div>
        </div>

        <div class="info-card">
            <h4 class="card-header-title"><i class="fa fa-user"></i> Informações do Cliente</h4>
            <div class="card-body">
                <div class="detail-item">
                    <span>ID PERFIL:</span>
                    <strong><?= htmlspecialchars($pedido['id_perfil'] ?? 'N/A') ?></strong>
                </div>
                <div class="detail-item">
                    <span>NOME DO CLIENTE:</span>
                    <strong><?= htmlspecialchars($pedido['nome_cliente'] ?? 'Perfil Não Encontrado') ?></strong>
                </div>
                <div class="detail-item" style="margin-top: 20px;">
                    <a href="/backend/perfil/editar/<?= htmlspecialchars($pedido['id_perfil']) ?>" class="btn-action">
                        <i class="fa fa-eye"></i> Visualizar Perfil Completo
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="items-card">
        <h4 class="card-header-title">
            <i class="fa fa-shopping-basket"></i> Itens Comprados (<?= count($itens ?? []) ?>)
        </h4>
        
        <div class="table-responsive">
            <?php if (!empty($itens)): ?>
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>PRODUTO</th>
                            <th>SKU</th>
                            <th style="text-align: right;">PREÇO UNITÁRIO</th>
                            <th style="text-align: center;">QTD</th>
                            <th style="text-align: right;">SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $subtotal_geral = 0;
                        foreach ($itens as $item): 
                            $subtotal = $item['quantidade'] * $item['preco_unitario'];
                            $subtotal_geral += $subtotal;
                        ?>
                        <tr>
                            <td class="product-name"><?= htmlspecialchars($item['nome_produtos'] ?? 'Produto Desconhecido') ?></td>
                            <td class="sku-text"><?= htmlspecialchars($item['sku_produto'] ?? 'N/A') ?></td>
                            <td style="text-align: right;">R$ <?= number_format($item['preco_unitario'] ?? 0, 2, ',', '.') ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($item['quantidade'] ?? 0) ?></td>
                            <td style="text-align: right;" class="gold-text">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="footer-row">
                            <td colspan="4" style="text-align: right;">TOTAL CALCULADO:</td>
                            <td style="text-align: right;" class="gold-text">R$ <?= number_format($subtotal_geral, 2, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            <?php else: ?>
                <div class="empty-notice">
                    <i class="fa fa-exclamation-triangle"></i> Nenhum item encontrado para este pedido.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Estrutura Principal */
.page-wrapper {
    padding: 20px;
    max-width: 1100px;
    margin: 0 auto;
}

.header-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    border-bottom: 1px solid #222;
    padding-bottom: 15px;
}

.page-title { color: #fff; margin: 0; font-weight: 600; }

.btn-back {
    background: #333;
    color: #fff;
    padding: 8px 15px;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    transition: 0.3s;
}

.btn-back:hover { background: #444; }

/* Grid de Informações */
.details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 25px;
}

.info-card, .items-card {
    background: #111;
    border: 1px solid #222;
    border-radius: 12px;
    padding: 20px;
}

.card-header-title {
    color: #e2c93e; /* Dourado */
    font-size: 16px;
    text-transform: uppercase;
    margin-bottom: 20px;
    border-bottom: 1px solid #222;
    padding-bottom: 10px;
    letter-spacing: 1px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 14px;
}

.detail-item span { color: #888; }
.detail-item strong { color: #fff; }

.total-highlight {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px dotted #333;
    font-size: 18px;
}

.gold-text { color: #e2c93e !important; }

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
}

.btn-action {
    display: inline-block;
    background: transparent;
    border: 1px solid #e2c93e;
    color: #e2c93e;
    padding: 8px 15px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    transition: 0.3s;
}

.btn-action:hover { background: #e2c93e; color: #000; }

/* Estilo da Tabela */
.custom-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.custom-table th {
    text-align: left;
    color: #666;
    font-size: 12px;
    padding: 12px;
    border-bottom: 2px solid #222;
}

.custom-table td {
    padding: 15px 12px;
    color: #ddd;
    border-bottom: 1px solid #222;
    font-size: 14px;
}

.product-name { color: #fff !important; font-weight: 500; }
.sku-text { font-family: monospace; color: #888; }

.footer-row td {
    border-bottom: none;
    font-weight: bold;
    font-size: 16px;
    padding-top: 25px;
}

.empty-notice {
    padding: 40px;
    text-align: center;
    color: #dc3545;
}

/* Responsividade */
@media (max-width: 800px) {
    .details-grid { grid-template-columns: 1fr; }
}
</style>