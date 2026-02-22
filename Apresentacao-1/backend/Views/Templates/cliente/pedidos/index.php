<?php
$usuarioId = $_SESSION['usuario_id'] ?? 0;
$nomeUsuario = $_SESSION['usuario_nome'] ?? 'Cliente';
?>
<div class="pedidos-container">
    <header class="pedidos-header">
        <a href="/backend/cliente/dashboard" class="back-btn"><i class="fas fa-arrow-left"></i> Voltar</a>
        <h1><i class="fas fa-shopping-bag"></i> Meus Pedidos</h1>
        <div></div>
    </header>

    <div id="pedidosContent" class="pedidos-content">
        <?php if (empty($pedidos)): ?>
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <h2>Nenhum pedido encontrado</h2>
                <p>Você ainda não fez nenhum pedido no seu histórico.</p>
                <a href="/" class="continue-shopping">Ir para a Loja</a>
            </div>
        <?php else: 
            // Ordenar pedidos do mais recente para o mais antigo
            usort($pedidos, function($a, $b) {
                return strtotime($b['data_pedido']) - strtotime($a['data_pedido']);
            });
            
            foreach ($pedidos as $p): 
                $statusPuro = strtolower($p['status_pedido'] ?? 'pendente');
                $statusClass = "status-" . $statusPuro;
                $totalFormatted = "R$ " . number_format($p['total_pedido'] ?? 0, 2, ',', '.');
                $dataFormatted = date('d/m/Y', strtotime($p['data_pedido']));
        ?>
            <div class="pedido-card">
                <div class="pedido-header">
                    <div>
                        <div class="pedido-numero">Pedido #<?= $p['id_pedido'] ?></div>
                        <div class="pedido-data"><?= $dataFormatted ?></div>
                    </div>
                    <span class="k-badge <?= $statusClass ?>"><?= strtoupper($p['status_pedido'] ?? 'pendente') ?></span>
                </div>
                <div class="pedido-body">
                    <div class="pedido-info">
                        <div class="info-row"><strong>ID:</strong> <?= $p['id_pedido'] ?></div>
                        <div class="info-row"><strong>Status:</strong> <?= ucfirst($p['status_pedido'] ?? 'pendente') ?></div>
                    </div>
                    <div class="pedido-total">
                        <div class="total-label">Total</div>
                        <div class="total-valor"><?= $totalFormatted ?></div>
                    </div>
                </div>
                <div class="pedido-footer">
                    <a href="/backend/cliente/pedidos/detalhes/<?= $p['id_pedido'] ?>" class="btn-detalhes">Ver Detalhes</a>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Montserrat:wght@300;400;600&display=swap');

    :root {
        --k-gold: #f2cc7d;
        --k-gold-dark: #b8860b;
        /* Themes Linked */
        --bg-body-custom: var(--bg-main);
        --bg-card-custom: var(--bg-card);
        --text-color-custom: var(--text-main);
        --border-custom: var(--border-color);
        --muted-custom: var(--text-muted);
    }

    .pedidos-container {
        font-family: 'Montserrat', sans-serif;
        color: var(--text-color-custom);
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
        background-color: var(--bg-body-custom);
        min-height: 100vh;
    }

    /* Header */
    .pedidos-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 25px 30px;
        background: var(--bg-card-custom);
        border-radius: 12px;
        border: 1px solid var(--border-custom);
        border-top: 4px solid var(--k-gold);
        box-shadow: var(--shadow-md);
        margin-bottom: 40px;
    }

    .back-btn {
        color: var(--k-gold);
        text-decoration: none;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 18px;
        background: rgba(242, 204, 125, 0.1);
        border-radius: 8px;
        transition: 0.3s;
        font-size: 0.9rem;
    }
    .back-btn:hover { background: var(--k-gold); color: #000; }

    .pedidos-header h1 {
        margin: 0;
        color: var(--text-color-custom);
        font-family: 'Oswald', sans-serif;
        font-size: 1.5rem;
        letter-spacing: 1px;
    }

    /* Lista */
    .pedidos-content { display: grid; gap: 25px; }

    .pedido-card {
        background: var(--bg-card-custom);
        border: 1px solid var(--border-custom);
        border-radius: 12px;
        padding: 25px;
        box-shadow: var(--shadow-sm);
        transition: 0.3s;
    }
    .pedido-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
        border-color: rgba(242, 204, 125, 0.3);
    }

    .pedido-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-custom);
    }

    .pedido-numero {
        font-weight: 700;
        color: var(--k-gold);
        font-size: 1.1rem;
        font-family: 'Oswald', sans-serif;
    }
    .pedido-data { color: var(--muted-custom); font-size: 0.85rem; margin-top: 5px; }

    .k-badge {
        padding: 6px 14px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    /* Status colors fixed for visibility on both themes */
    .status-pendente { background: rgba(247, 127, 0, 0.15); color: #e67e22; border: 1px solid rgba(230, 126, 34, 0.3); }
    .status-processando { background: rgba(255, 215, 0, 0.15); color: #d4ac0d; border: 1px solid rgba(241, 196, 15, 0.3); }
    .status-enviado { background: rgba(52, 152, 219, 0.15); color: #3498db; border: 1px solid rgba(52, 152, 219, 0.3); }
    .status-entregue, .status-concluido, .status-pago { background: rgba(46, 204, 113, 0.15); color: #2ecc71; border: 1px solid rgba(46, 204, 113, 0.3); }
    .status-cancelado { background: rgba(231, 76, 60, 0.15); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.3); }

    .pedido-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .pedido-info .info-row {
        display: flex; gap: 10px; margin-bottom: 5px; color: var(--muted-custom); font-size: 0.9rem;
    }
    .pedido-info strong { color: var(--text-color-custom); min-width: 80px; }

    .pedido-total {
        text-align: right;
        padding: 15px;
        background: rgba(125, 125, 125, 0.05);
        border-radius: 10px;
        border-left: 3px solid var(--k-gold);
    }
    .total-label { color: var(--muted-custom); font-size: 0.75rem; text-transform: uppercase; }
    .total-valor { color: var(--k-gold); font-size: 1.5rem; font-weight: 700; font-family: 'Oswald', sans-serif; }

    .pedido-footer {
        display: flex; justify-content: flex-end;
        padding-top: 15px; border-top: 1px solid var(--border-custom);
    }
    
    .btn-detalhes {
        padding: 10px 25px;
        border: 1px solid var(--k-gold);
        color: var(--k-gold);
        border-radius: 8px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        transition: 0.3s;
    }
    .btn-detalhes:hover { background: var(--k-gold); color: #000; }

    .loading, .empty-state, .error-state { text-align: center; padding: 60px 20px; color: var(--muted-custom); }
    .spinner {
        width: 40px; height: 40px; border: 4px solid rgba(125,125,125,0.2);
        border-top: 4px solid var(--k-gold);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    
    .empty-state h2 { color: var(--text-color-custom); }
    .continue-shopping {
        display: inline-block; background: var(--k-gold); color: #000; padding: 12px 30px;
        border-radius: 30px; text-decoration: none; font-weight: 700; margin-top: 20px;
    }

    @media (max-width: 768px) {
        .pedidos-header { flex-direction: column; align-items: flex-start; gap: 15px; }
        .pedido-body { grid-template-columns: 1fr; }
        .pedido-header { flex-direction: column; align-items: flex-start; gap: 10px; }
        .pedido-total { text-align: left; }
    }
</style>

