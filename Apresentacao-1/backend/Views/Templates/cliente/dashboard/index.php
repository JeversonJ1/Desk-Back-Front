
<div class="client-dashboard-luxury">
    <header class="luxury-header">
        <div class="user-profile-section">
            <div class="welcome-box">
                <h2>BEM-VINDO, <span class="gold-text"><?= htmlspecialchars($nomeUsuario ?? 'CLIENTE') ?></span></h2>
                <p>Membro exclusivo Koketsu Grife</p>
            </div>
        </div>
        
        <div class="header-stats">
            <div class="mini-stat">
                <span class="v-label">PEDIDOS</span>
                <span class="v-value"><?= $totalPedidos ?? 0 ?></span>
            </div>
            <div class="v-divider"></div>
            <div class="mini-stat">
                <span class="v-label">AVALIAÇÕES</span>
                <span class="v-value"><?= $totalAvaliacoes ?? 0 ?></span>
            </div>
            <div class="v-divider"></div>
            <div class="mini-stat">
                <span class="v-label">STATUS</span>
                <span class="v-value gold-text"><i class="fas fa-crown"></i> VIP</span>
            </div>
        </div>
    </header>

    <section class="luxury-grid">
        <a href="/backend/cliente/meu-perfil/<?= htmlspecialchars($usuarioId ?? '0') ?>" class="k-card">
            <div class="k-icon"><i class="fas fa-user"></i></div>
            <div class="k-info">
                <h3>Meu Perfil</h3>
                <p>Dados e Segurança</p>
            </div>
            <i class="fas fa-arrow-right k-arrow"></i>
        </a>

        <a href="/backend/cliente/pedidos" class="k-card">
            <div class="k-icon"><i class="fas fa-shopping-bag"></i></div>
            <div class="k-info">
                <h3>Meus Pedidos</h3>
                <p>Histórico de Compras</p>
            </div>
            <i class="fas fa-arrow-right k-arrow"></i>
        </a>

        <a href="/backend/configuracoes" class="k-card">
            <div class="k-icon"><i class="fas fa-cog"></i></div>
            <div class="k-info">
                <h3>Preferências</h3>
                <p>Ajustes da Conta</p>
            </div>
            <i class="fas fa-arrow-right k-arrow"></i>
        </a>
    </section>

    <div class="main-content-row">
        <div class="content-box recent-orders">
            <div class="box-header">
                <h3><i class="fas fa-list-ul gold-text"></i> ÚLTIMAS AQUISIÇÕES</h3>
                <a href="/backend/cliente/pedidos" class="gold-link">VER TUDO</a>
            </div>

            <div class="table-container">
                <?php if (empty($pedidosRecentes)): ?>
                    <div class="k-empty">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Nenhum pedido encontrado no seu histórico.</p>
                        <a href="/" class="btn-gold-sm">EXPLORAR LOJA</a>
                    </div>
                <?php else: ?>
                    <table class="k-table">
                        <thead>
                            <tr>
                                <th>REF</th>
                                <th>DATA</th>
                                <th>VALOR</th>
                                <th>STATUS</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (!function_exists('getStatusClass')) {
                                function getStatusClass($status) {
                                    switch (strtolower($status)) {
                                        case 'concluido': case 'pago': return 'status-success';
                                        case 'pendente': return 'status-warning';
                                        case 'cancelado': return 'status-danger';
                                        default: return 'status-info';
                                    }
                                }
                            }
                            ?>
                            <?php foreach ($pedidosRecentes as $pedido): ?>
                            <tr>
                                <td class="gold-text">#<?= $pedido['id_pedido'] ?></td>
                                <td><?= date('d/m/Y', strtotime($pedido['data_pedido'])) ?></td>
                                <td class="bold">R$ <?= number_format($pedido['total_pedido'], 2, ',', '.') ?></td>
                                <td>
                                    <span class="k-badge <?= getStatusClass($pedido['status_pedido']) ?>">
                                        <?= strtoupper($pedido['status_pedido']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/backend/cliente/pedidos/detalhes/<?= $pedido['id_pedido'] ?>" class="btn-eye">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <div class="content-box support-box">
            <h3>CONCEIRGE KOKETSU</h3>
            <p>Atendimento prioritário para membros.</p>
            <div class="support-links">
                <a href="#" class="s-link"><i class="fab fa-whatsapp"></i> ATENDIMENTO VIA WHATSAPP</a>
                <a href="#" class="s-link"><i class="fas fa-envelope"></i> SUPORTE VIA E-MAIL</a>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Montserrat:wght@300;400;600&display=swap');

    :root {
        --k-gold: #f2cc7d;
        --k-gold-dark: #b8860b;
        /* Using variables linked to global theme */
        --bg-body-custom: var(--bg-main);
        --bg-card-custom: var(--bg-card);
        --text-color-custom: var(--text-main);
        --border-custom: var(--border-color);
        --muted-custom: var(--text-muted);
    }

    .client-dashboard-luxury {
        font-family: 'Montserrat', sans-serif;
        color: var(--text-color-custom);
        padding: 20px;
        background: var(--bg-body-custom);
    }

    /* Header */
    .luxury-header {
        background: var(--bg-card-custom);
        border: 1px solid var(--border-custom);
        border-radius: 20px;
        padding: 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        box-shadow: var(--shadow-md);
    }

    .welcome-box h2 {
        font-family: 'Oswald', sans-serif;
        font-size: 2rem;
        letter-spacing: 2px;
        margin: 0;
        color: var(--text-color-custom);
    }

    .welcome-box p {
        color: var(--muted-custom);
        text-transform: uppercase;
        letter-spacing: 3px;
        font-size: 0.75rem;
        margin-top: 5px;
    }

    .header-stats { display: flex; gap: 40px; }
    .mini-stat { text-align: right; }
    .v-label { display: block; font-size: 0.65rem; color: var(--muted-custom); letter-spacing: 2px; }
    .v-value { font-size: 1.5rem; font-weight: 700; font-family: 'Oswald', sans-serif; color: var(--text-color-custom); }
    .v-divider { width: 1px; height: 45px; background: var(--border-custom); }

    /* Grid de Cards */
    .luxury-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .k-card {
        background: var(--bg-card-custom);
        border: 1px solid var(--border-custom);
        padding: 30px;
        border-radius: 15px;
        text-decoration: none;
        color: var(--text-color-custom);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: 0.4s;
    }

    .k-card:hover {
        transform: translateY(-5px);
        background: var(--bg-card-custom);
        border-color: var(--k-gold);
        box-shadow: 0 10px 30px rgba(242, 204, 125, 0.1);
    }

    .k-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--k-gold), var(--k-gold-dark));
        color: #000;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .k-info h3 { font-family: 'Oswald', sans-serif; margin: 0; font-size: 1.1rem; letter-spacing: 1px; color: var(--text-color-custom); }
    .k-info p { margin: 0; font-size: 0.8rem; color: var(--muted-custom); }
    .k-arrow { margin-left: auto; opacity: 0.2; transition: 0.3s; color: var(--text-color-custom); }
    .k-card:hover .k-arrow { opacity: 1; color: var(--k-gold); transform: translateX(5px); }

    /* Main Row */
    .main-content-row { display: flex; gap: 20px; }
    .content-box {
        background: var(--bg-card-custom);
        border: 1px solid var(--border-custom);
        border-radius: 15px;
        padding: 30px;
        box-shadow: var(--shadow-sm);
    }
    .recent-orders { flex: 2; }
    .support-box { flex: 1; text-align: center; }

    .box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    .box-header h3 { font-family: 'Oswald', sans-serif; margin: 0; letter-spacing: 2px; color: var(--text-color-custom); }

    /* Tabela */
    .k-table { width: 100%; border-collapse: collapse; }
    .k-table th {
        text-align: left;
        padding: 15px;
        color: var(--muted-custom);
        font-size: 0.7rem;
        letter-spacing: 2px;
        border-bottom: 1px solid var(--border-custom);
    }
    .k-table td { padding: 20px 15px; border-bottom: 1px solid var(--border-custom); font-size: 0.9rem; color: var(--text-color-custom); }

    /* Badges Status */
    .k-badge {
        padding: 5px 12px;
        border-radius: 5px;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 1px;
    }
    .status-success { background: rgba(46, 204, 113, 0.1); color: #2ecc71; border: 1px solid #2ecc71; }
    .status-warning { background: rgba(241, 196, 15, 0.1); color: #f1c40f; border: 1px solid #f1c40f; }
    .status-danger { background: rgba(231, 76, 60, 0.1); color: #e74c3c; border: 1px solid #e74c3c; }
    .status-info { background: rgba(52, 152, 219, 0.1); color: #3498db; border: 1px solid #3498db; }

    .btn-eye { color: var(--muted-custom); transition: 0.3s; }
    .btn-eye:hover { color: var(--k-gold); }

    /* Support */
    .s-link {
        display: block;
        padding: 15px;
        background: var(--bg-body-custom);
        border: 1px solid var(--border-custom);
        border-radius: 10px;
        margin-top: 15px;
        text-decoration: none;
        color: var(--text-color-custom);
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 1px;
        transition: 0.3s;
    }
    .s-link:hover { background: var(--k-gold); color: #000; font-weight: 700; border-color: var(--k-gold); }

    .gold-text { color: var(--k-gold); }
    .gold-link { color: var(--k-gold); text-decoration: none; font-size: 0.8rem; font-weight: 700; }

    /* Responsive */
    @media (max-width: 1000px) {
        .luxury-grid { grid-template-columns: 1fr; }
        .main-content-row { flex-direction: column; }
    }
</style>