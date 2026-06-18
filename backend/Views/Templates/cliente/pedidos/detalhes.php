<?php
/**
 * View: Detalhes do Pedido (cliente) - Versão Premium v2
 */
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="order-container">
    <div class="page-header">
        <h2 class="title"><i class="fa fa-receipt text-gold"></i> Detalhes do Pedido <span class="order-id">#<?= htmlspecialchars($pedido['id_pedido']) ?></span></h2>
        <div class="header-actions">
            <button id="btn-recomprar-wpp" class="btn-wpp-reorder" onclick="recomprarViaWhatsApp()">
                <i class="fab fa-whatsapp"></i> Repetir Pedido
            </button>
            <a class="btn-back" href="/backend/cliente/pedidos">
                <i class="fa fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
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

    <!-- Timeline de Status -->
    <?php
    $statusOrder = ['pendente', 'processando', 'enviado', 'entregue'];
    $currentStatus = strtolower($pedido['status_pedido']);
    $currentIdx = array_search($currentStatus, $statusOrder);
    if ($currentStatus === 'pago' || $currentStatus === 'concluido') $currentIdx = 3;
    if ($currentStatus === 'cancelado') $currentIdx = -1;
    $statusLabels = [
        'pendente'    => ['label' => 'Pedido Recebido', 'icon' => 'fa-check', 'desc' => 'Seu pedido foi confirmado'],
        'processando' => ['label' => 'Em Preparação',   'icon' => 'fa-box',   'desc' => 'Separando seus itens'],
        'enviado'     => ['label' => 'Enviado',          'icon' => 'fa-truck', 'desc' => 'A caminho de você'],
        'entregue'    => ['label' => 'Entregue',         'icon' => 'fa-home',  'desc' => 'Chegou até você!'],
    ];
    ?>
    <?php if ($currentStatus !== 'cancelado'): ?>
    <div class="order-timeline-card">
        <div class="section-title"><i class="fa fa-map-marker-alt"></i> Acompanhamento do Pedido</div>
        <div class="order-timeline">
            <?php foreach ($statusOrder as $i => $step):
                $info = $statusLabels[$step];
                $isDone    = $i < $currentIdx;
                $isCurrent = $i === $currentIdx;
                $stateClass = $isDone ? 'done' : ($isCurrent ? 'current' : 'pending');
            ?>
            <div class="timeline-node <?= $stateClass ?>">
                <div class="tl-dot">
                    <i class="fa <?= $isDone ? 'fa-check' : $info['icon'] ?>"></i>
                </div>
                <div class="tl-info">
                    <span class="tl-label"><?= $info['label'] ?></span>
                    <span class="tl-desc"><?= $info['desc'] ?></span>
                </div>
            </div>
            <?php if ($i < count($statusOrder) - 1): ?>
            <div class="tl-connector <?= $isDone ? 'done' : '' ?>"></div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="order-timeline-card cancelled-box">
        <i class="fa fa-times-circle fa-2x mb-2" style="color:#e74c3c;"></i>
        <p style="color:#e74c3c; font-weight:700; margin:0;">Este pedido foi cancelado.</p>
        <a href="/pages/catalogo.html" class="btn-back mt-3" style="display:inline-flex;">Explorar Loja</a>
    </div>
    <?php endif; ?>

    <!-- Endereço -->
    <div class="info-section">
        <div class="section-title"><i class="fa fa-location-dot"></i> Endereço de Entrega</div>
        <div class="address-box">
            <?= htmlspecialchars($pedido['endereco_perfil'] ?? 'Endereço não informado') ?>
        </div>
    </div>

    <!-- Itens -->
    <div class="items-section">
        <div class="section-title"><i class="fa fa-boxes-stacked"></i> Itens do Pedido</div>

        <?php if (!empty($itens)): ?>
            <div class="table-responsive">
                <table class="custom-table" id="order-items-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th class="text-center">Qtd</th>
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

<script>
// Dados do pedido para o botão de recompra
const _orderItems = <?= json_encode(array_map(function($it) {
    return ['nome' => $it['nome_produtos'] ?? 'Produto', 'qtd' => $it['quantidade'] ?? 1, 'preco' => $it['preco_unitario'] ?? 0];
}, $itens ?? [])) ?>;
const _orderId = <?= (int)($pedido['id_pedido'] ?? 0) ?>;
const _orderTotal = <?= (float)($pedido['total_pedido'] ?? 0) ?>;

async function recomprarViaWhatsApp() {
    try {
        const cfg = await fetch('/api/config.php').then(r => r.json());
        const num = cfg.whatsapp_numero || '5511985477260';

        let msg = `🛒 *Quero repetir meu Pedido #${_orderId}*\n\n`;
        _orderItems.forEach(it => {
            msg += `▸ *${it.nome}* × ${it.qtd} — R$ ${parseFloat(it.preco).toFixed(2).replace('.', ',')}\n`;
        });
        msg += `\n*Total original: R$ ${_orderTotal.toFixed(2).replace('.', ',')}*\n\nPoderia me ajudar a refazer este pedido?`;

        window.open(`https://wa.me/${num}?text=${encodeURIComponent(msg)}`, '_blank');
    } catch(e) {
        window.open('https://wa.me/5511985477260', '_blank');
    }
}
</script>


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
        .header-actions { width: 100%; flex-direction: column; }
        .btn-wpp-reorder { width: 100%; justify-content: center; }
    }

    /* Header Actions */
    .header-actions { display: flex; align-items: center; gap: 12px; }

    /* WhatsApp Reorder Button */
    .btn-wpp-reorder {
        background: #25D366;
        color: #000;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 0.78rem;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        cursor: pointer;
        display: flex; align-items: center; gap: 8px;
        transition: 0.3s;
        box-shadow: 0 4px 12px rgba(37,211,102,0.3);
    }
    .btn-wpp-reorder:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37,211,102,0.4); }

    /* Order Timeline */
    .order-timeline-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 15px;
        padding: 28px 30px;
        margin-bottom: 25px;
    }
    .cancelled-box {
        text-align: center;
        padding: 40px;
    }

    .order-timeline {
        display: flex;
        align-items: center;
        gap: 0;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .timeline-node {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
        min-width: 100px;
    }

    .tl-dot {
        width: 44px; height: 44px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        border: 2px solid transparent;
        transition: all 0.3s;
    }

    .timeline-node.done .tl-dot {
        background: #2ecc71;
        color: #000;
        border-color: #2ecc71;
        box-shadow: 0 0 16px rgba(46,204,113,0.3);
    }
    .timeline-node.current .tl-dot {
        background: var(--gold);
        color: #000;
        border-color: var(--gold);
        box-shadow: 0 0 20px rgba(242,204,125,0.4);
        animation: pulseDot 2s ease infinite;
    }
    .timeline-node.pending .tl-dot {
        background: rgba(255,255,255,0.05);
        color: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.1);
    }

    @keyframes pulseDot {
        0%, 100% { box-shadow: 0 0 20px rgba(242,204,125,0.4); }
        50% { box-shadow: 0 0 32px rgba(242,204,125,0.7); }
    }

    .tl-info { text-align: center; }
    .tl-label {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-color);
    }
    .timeline-node.pending .tl-label { color: var(--text-gray); }
    .tl-desc {
        display: block;
        font-size: 0.62rem;
        color: var(--text-gray);
        margin-top: 2px;
    }

    .tl-connector {
        flex: 1;
        height: 2px;
        background: rgba(255,255,255,0.08);
        border-radius: 2px;
        min-width: 20px;
        margin-top: -24px; /* align with dots vertically */
    }
    .tl-connector.done { background: #2ecc71; }

    .mt-3 { margin-top: 12px; }
    .mb-2 { margin-bottom: 8px; }
</style>