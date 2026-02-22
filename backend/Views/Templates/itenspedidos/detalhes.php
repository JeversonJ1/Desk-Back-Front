<?php 
if (empty($itempedido)): ?>
    <div class="error-container">
        <div class="error-card">
            <h3><i class="fa fa-times-circle"></i> Erro!</h3>
            <p>Item de Pedido não encontrado ou ID inválido. Verifique a URL.</p>
            <a href="/backend/itenspedidos/listar" class="btn-back-lux">VOLTAR À LISTA</a>
        </div>
    </div>
<?php else: 
    $subtotal = $itempedido['quantidade'] * $itempedido['preco_unitario'];
?>

<div class="page-wrapper">
    <div class="top-navigation">
        <a href="/backend/itenspedidos/listar" class="btn-back-lux">
            <i class="fa fa-chevron-left"></i> VOLTAR À LISTA
        </a>
    </div>

    <div class="premium-card">
        <div class="card-header">
            <div class="header-icon"><i class="fa fa-box"></i></div>
            <h1 class="gold-gradient-text">DETALHES DO ITEM <span class="item-id">#<?= htmlspecialchars($itempedido['id_itens_pedidos']) ?></span></h1>
            <p class="subtitle">Informações específicas do produto dentro do pedido</p>
        </div>

        <div class="info-grid">
            <div class="info-box main-info">
                <h3 class="section-title"><i class="fa fa-cube"></i> Produto</h3>
                <div class="details-row">
                    <div class="detail-item">
                        <label>Nome do Produto</label>
                        <span><?= htmlspecialchars($itempedido['nome_produto'] ?? 'ID ' . $itempedido['id_produto']) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>ID do Pedido</label>
                        <a href="/backend/pedido/listar/<?= htmlspecialchars($itempedido['id_pedido']) ?>" class="order-link">
                            #<?= htmlspecialchars($itempedido['id_pedido']) ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="info-box stats-info">
                <div class="stat-item">
                    <label>Quantidade</label>
                    <span class="quantity-badge"><?= htmlspecialchars($itempedido['quantidade']) ?></span>
                </div>
                <div class="stat-item">
                    <label>Preço Unitário</label>
                    <span class="gold-text">R$ <?= number_format($itempedido['preco_unitario'], 2, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <div class="subtotal-section">
            <p>Subtotal deste Item</p>
            <h2 class="total-value">R$ <?= number_format($subtotal, 2, ',', '.') ?></h2>
        </div>

        <div class="footer-meta">
            <span><i class="fa fa-clock"></i> Criado em: <?= htmlspecialchars($itempedido['criado_em'] ?? 'N/A') ?></span>
            <span class="separator">|</span>
            <span><i class="fa fa-history"></i> Última atualização: <?= htmlspecialchars($itempedido['atualizado_em'] ?? 'Nunca') ?></span>
        </div>

        <div class="action-buttons">
            <a href="/backend/itenspedidos/editar/<?= htmlspecialchars($itempedido['id_itens_pedidos']) ?>" class="btn-edit-lux">
                <i class="fa fa-edit"></i> EDITAR ITEM
            </a>
        </div>
    </div>
</div>

<style>
    /* Base do Dash Koketsu */
    .page-wrapper {
        padding: 40px 20px;
        max-width: 800px;
        margin: 0 auto;
    }

    .top-navigation { margin-bottom: 25px; }

    .btn-back-lux {
        color: #f2cc7d;
        text-decoration: none;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.5px;
        padding: 10px 20px;
        border: 1px solid rgba(242, 204, 125, 0.3);
        border-radius: 50px;
        transition: 0.3s;
    }

    .btn-back-lux:hover {
        background: rgba(242, 204, 125, 0.1);
        border-color: #f2cc7d;
    }

    /* Card Premium */
    .premium-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 40px;
        box-shadow: var(--shadow-md);
    }

    .card-header { text-align: center; margin-bottom: 35px; }
    .header-icon { font-size: 24px; color: var(--text-muted); margin-bottom: 10px; }

    .gold-gradient-text {
        background: linear-gradient(135deg, #f2cc7d 0%, #b8860b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 26px;
        font-weight: 900;
        letter-spacing: 1px;
    }

    .item-id { color: var(--text-muted); -webkit-text-fill-color: var(--text-muted); }
    .subtitle { color: var(--text-muted); font-size: 13px; margin-top: 5px; }

    /* Layout de Informações */
    .info-grid { display: flex; gap: 20px; margin-bottom: 30px; }
    .info-box { 
        background: var(--bg-main); 
        padding: 25px; 
        border-radius: 15px; 
        border: 1px solid var(--border-color);
    }
    .main-info { flex: 2; }
    .stats-info { flex: 1; display: flex; flex-direction: column; justify-content: space-around; }

    .section-title {
        color: #f2cc7d;
        font-size: 14px;
        text-transform: uppercase;
        margin-bottom: 20px;
        letter-spacing: 1px;
    }

    .detail-item label, .stat-item label {
        display: block;
        color: var(--text-muted);
        font-size: 11px;
        text-transform: uppercase;
        margin-bottom: 5px;
        font-weight: 700;
    }

    .detail-item span, .gold-text { color: var(--text-main); font-size: 16px; font-weight: 500; }
    .gold-text { color: #f2cc7d; }

    .order-link { color: #00bcd4; text-decoration: none; font-weight: 700; transition: 0.3s; }
    .order-link:hover { color: #fff; text-decoration: underline; }

    .quantity-badge {
        background: #00bcd4;
        color: #000;
        padding: 4px 12px;
        border-radius: 50px;
        font-weight: 900;
        font-size: 14px;
    }

    /* Subtotal Premium */
    .subtotal-section {
        text-align: center;
        padding: 30px;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 20px;
    }
    .subtotal-section p { color: var(--text-muted); text-transform: uppercase; font-size: 12px; letter-spacing: 2px; }
    .total-value {
        color: #f2cc7d;
        font-size: 42px;
        font-weight: 900;
        margin: 10px 0;
        text-shadow: 0 0 20px rgba(242, 204, 125, 0.2);
    }

    .footer-meta { text-align: center; color: var(--text-muted); font-size: 11px; margin-bottom: 30px; }
    .separator { margin: 0 10px; }

    /* Botão Editar Gradiente */
    .action-buttons { text-align: center; }
    .btn-edit-lux {
        display: inline-block;
        background: linear-gradient(180deg, #f2cc7d 0%, #d4a74a 100%);
        color: #000;
        text-decoration: none;
        padding: 15px 40px;
        border-radius: 12px;
        font-weight: 900;
        font-size: 13px;
        letter-spacing: 2px;
        transition: 0.3s;
        box-shadow: var(--shadow-md);
    }
    .btn-edit-lux:hover {
        transform: translateY(-3px);
        filter: brightness(1.2);
        box-shadow: 0 15px 30px rgba(212, 167, 74, 0.3);
    }

    @media (max-width: 600px) {
        .info-grid { flex-direction: column; }
    }
</style>

<?php endif; ?>