<?php
/**
 * View: Detalhes do Pedido (cliente) - Versão Premium
 */
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="order-container">
    <div class="page-header">
        <h2 class="title"><i class="fa fa-receipt text-gold"></i> Detalhes do Pedido <span class="order-id">#<?= htmlspecialchars($pedido['id_pedido']) ?></span></h2>
        <a class="btn-back" href="/backend/cliente/pedidos">
            <i class="fa fa-arrow-left"></i> Voltar para Lista
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fa fa-calendar-day"></i></div>
            <div class="stat-info">
                <p>Data da Compra</p>
                <h3><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fa fa-circle-check"></i></div>
            <div class="stat-info">
                <p>Status Atual</p>
                <h3 class="badge-status <?= strtolower($pedido['status_pedido']) ?>">
                    <?= htmlspecialchars(ucfirst($pedido['status_pedido'])) ?>
                </h3>
            </div>
        </div>

        <div class="stat-card gold-card">
            <div class="stat-icon"><i class="fa fa-wallet"></i></div>
            <div class="stat-info">
                <p>Total do Pedido</p>
                <h3 class="price-total">R$ <?= number_format($pedido['total_pedido'],2,',','.') ?></h3>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="section-title">
            <i class="fa fa-location-dot"></i> Endereço de Entrega
        </div>
        <div class="address-box">
            <?= htmlspecialchars($pedido['endereco_perfil'] ?? 'Endereço não informado') ?>
        </div>
    </div>

    <div class="items-section">
        <div class="section-title">
            <i class="fa fa-boxes-stacked"></i> Itens do Pedido
        </div>
        
        <?php if (!empty($itens)): ?>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th class="text-center">Quantidade</th>
                            <th class="text-right">Preço Unit.</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itens as $it): ?>
                            <tr>
                                <td>
                                    <div class="product-cell">
                                        <div class="product-icon"><i class="fa fa-tag"></i></div>
                                        <span><?= htmlspecialchars($it['nome_produtos'] ?? 'Produto') ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="qty-badge"><?= htmlspecialchars($it['quantidade'] ?? 0) ?></span>
                                </td>
                                <td class="text-right">R$ <?= number_format($it['preco_unitario'] ?? 0,2,',','.') ?></td>
                                <td class="text-right text-gold font-bold">
                                    R$ <?= number_format(($it['quantidade'] * ($it['preco_unitario'] ?? 0)),2,',','.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fa fa-box-open"></i>
                <p>Nenhum item encontrado para este pedido.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Montserrat:wght@300;400;600&display=swap');

    :root {
        /* Map custom variables to global theme variables defined in header.php */
        --dark-bg: var(--bg-main);
        --card-bg: var(--bg-card);
        --gold: #f2cc7d;
        --text-color: var(--text-main);
        --text-gray: var(--text-muted);
        --border-color: var(--border-color);
        --badge-bg: rgba(242, 204, 125, 0.1);
        --badge-text: var(--text-color);
    }

    .order-container {
        padding: 40px 20px;
        color: var(--text-color);
        max-width: 1200px;
        margin: 0 auto;
        min-height: 100vh;
        background-color: var(--dark-bg);
        font-family: 'Montserrat', sans-serif;
    }

    /* Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        background: var(--card-bg);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .title { font-size: 24px; font-weight: 800; text-transform: uppercase; letter-spacing: -1px; margin: 0; color: var(--text-color); }
    .text-gold { color: var(--gold); }
    .order-id { color: var(--text-gray); font-weight: 400; font-size: 0.8em; }

    .btn-back {
        background: transparent;
        color: var(--gold);
        border: 1px solid var(--gold);
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 700;
        font-size: 13px;
        transition: 0.3s;
        display: flex; align-items: center; gap: 8px;
    }

    .btn-back:hover { background: var(--gold); color: #000; }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 25px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }

    .gold-card { border-left: 4px solid var(--gold); }

    .stat-icon {
        width: 50px;
        height: 50px;
        background: rgba(242, 204, 125, 0.15);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gold);
        font-size: 20px;
    }

    .stat-info p { color: var(--text-gray); font-size: 12px; text-transform: uppercase; margin: 0 0 5px 0; letter-spacing: 1px; }
    .stat-info h3 { font-size: 18px; margin: 0; font-weight: 700; color: var(--text-color); }

    .price-total { font-family: 'Montserrat', sans-serif; font-weight: 800 !important; }

    /* Badges de Status */
    .badge-status { 
        display: inline-block;
        padding: 6px 12px; 
        border-radius: 6px; 
        font-size: 14px; 
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-status.cancelado { color: #ff4444; background: rgba(255, 68, 68, 0.1); border: 1px solid rgba(255, 68, 68, 0.2); }
    .badge-status.pendente { color: #f39c12; background: rgba(243, 156, 18, 0.1); border: 1px solid rgba(243, 156, 18, 0.2); }
    .badge-status.pago, .badge-status.concluido { color: #00c851; background: rgba(0, 200, 81, 0.1); border: 1px solid rgba(0, 200, 81, 0.2); }
    .badge-status.enviado { color: #3498db; background: rgba(52, 152, 219, 0.1); border: 1px solid rgba(52, 152, 219, 0.2); }

    /* Sections */
    .info-section, .items-section {
        background: var(--card-bg);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 25px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }

    .section-title {
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 25px;
        color: var(--gold);
        display: flex;
        align-items: center;
        gap: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .address-box {
        color: var(--text-color);
        line-height: 1.6;
        background: var(--dark-bg);
        padding: 20px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        font-size: 0.95rem;
    }

    /* Table */
    .table-responsive { overflow-x: auto; }
    .custom-table { width: 100%; border-collapse: collapse; min-width: 600px; }
    
    .custom-table thead th {
        text-align: left;
        color: var(--text-gray);
        font-size: 12px;
        text-transform: uppercase;
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        background: rgba(0,0,0,0.02);
    }

    .custom-table tbody td {
        padding: 20px 15px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-color);
        vertical-align: middle;
    }

    .product-cell { display: flex; align-items: center; gap: 15px; }
    .product-icon { 
        width: 40px; height: 40px; 
        background: rgba(242, 204, 125, 0.1); 
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: var(--gold); 
    }

    .qty-badge {
        background: #333;
        color: #fff;
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 12px;
    }

    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .font-bold { font-weight: 800; }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: var(--text-gray);
    }

    .empty-state i { font-size: 40px; margin-bottom: 10px; display: block; opacity: 0.5; }

    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; gap: 15px; }
        .stats-grid { grid-template-columns: 1fr; }
        .btn-back { width: 100%; justify-content: center; }
    }
</style>