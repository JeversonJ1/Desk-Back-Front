
<div class="client-dashboard-luxury">
    <!-- Header com Perfil -->
    <header class="luxury-header">
        <div class="user-profile-section">
            <?php
                $foto = $perfil['foto_usuarios'] ?? null;
                $iniciais = strtoupper(substr($nomeUsuario ?? 'K', 0, 1));
            ?>
            <div class="avatar-ring">
                <?php if ($foto): ?>
                    <img src="/backend/upload/<?= htmlspecialchars($foto) ?>" alt="Foto de perfil"
                         onerror="this.onerror=null; this.style.display='none'; document.querySelector('.avatar-initials').style.display='flex';">
                    <div class="avatar-initials" style="display:none;"><?= $iniciais ?></div>
                <?php else: ?>
                    <div class="avatar-initials"><?= $iniciais ?></div>
                <?php endif; ?>
                <div class="avatar-status-dot"></div>
            </div>
            <div class="welcome-box">
                <h2>BEM-VINDO, <span class="gold-text"><?= htmlspecialchars($nomeUsuario ?? 'CLIENTE') ?></span></h2>
                <p><i class="fas fa-crown" style="color: var(--k-gold); margin-right: 5px;"></i> Membro Exclusivo Koketsu Grife</p>
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
                <span class="v-label">GASTO TOTAL</span>
                <span class="v-value gold-text">
                    R$ <?= number_format(array_sum(array_column($pedidosRecentes ?? [], 'total_pedido')), 2, ',', '.') ?>
                </span>
            </div>
        </div>
    </header>

    <!-- Cards de Navegação -->
    <section class="luxury-grid">
        <a href="/backend/cliente/meu-perfil/<?= htmlspecialchars($usuarioId ?? '0') ?>" class="k-card">
            <div class="k-icon"><i class="fas fa-user-edit"></i></div>
            <div class="k-info">
                <h3>Meu Perfil</h3>
                <p>Dados pessoais e segurança</p>
            </div>
            <i class="fas fa-arrow-right k-arrow"></i>
        </a>

        <a href="/backend/cliente/pedidos" class="k-card">
            <div class="k-icon"><i class="fas fa-shopping-bag"></i></div>
            <div class="k-info">
                <h3>Meus Pedidos</h3>
                <p><?= $totalPedidos ?? 0 ?> compra(s) no histórico</p>
            </div>
            <i class="fas fa-arrow-right k-arrow"></i>
        </a>

        <a href="/backend/cliente/avaliacoes" class="k-card">
            <div class="k-icon"><i class="fas fa-star"></i></div>
            <div class="k-info">
                <h3>Minhas Avaliações</h3>
                <p><?= $totalAvaliacoes ?? 0 ?> avaliação(ões) enviada(s)</p>
            </div>
            <i class="fas fa-arrow-right k-arrow"></i>
        </a>

        <a href="/backend/configuracoes" class="k-card">
            <div class="k-icon"><i class="fas fa-cog"></i></div>
            <div class="k-info">
                <h3>Preferências</h3>
                <p>Notificações e ajustes</p>
            </div>
            <i class="fas fa-arrow-right k-arrow"></i>
        </a>

        <a href="/pages/catalogo.html" class="k-card">
            <div class="k-icon"><i class="fas fa-store"></i></div>
            <div class="k-info">
                <h3>Explorar Loja</h3>
                <p>Ver novos lançamentos</p>
            </div>
            <i class="fas fa-arrow-right k-arrow"></i>
        </a>

        <a href="/pages/catalogo.html" class="k-card k-card-gold">
            <div class="k-icon k-icon-dark"><i class="fas fa-bolt"></i></div>
            <div class="k-info">
                <h3>Comprar Agora</h3>
                <p>Peças exclusivas disponíveis</p>
            </div>
            <i class="fas fa-arrow-right k-arrow"></i>
        </a>
    </section>

    <!-- Conteúdo Principal -->
    <div class="main-content-row">
        <!-- Últimos Pedidos -->
        <div class="content-box recent-orders">
            <div class="box-header">
                <h3><i class="fas fa-list-ul gold-text"></i> ÚLTIMAS AQUISIÇÕES</h3>
                <a href="/backend/cliente/pedidos" class="gold-link">VER TUDO →</a>
            </div>

            <div class="table-container">
                <?php if (empty($pedidosRecentes)): ?>
                    <div class="k-empty">
                        <i class="fas fa-shopping-cart fa-2x mb-3" style="color: var(--k-gold); opacity: 0.5;"></i>
                        <p>Nenhuma compra no histórico ainda.</p>
                        <a href="/pages/catalogo.html" class="btn-gold-sm">EXPLORAR LOJA</a>
                    </div>
                <?php else: ?>
                    <?php
                    function getStatusClass($status) {
                        switch (strtolower($status)) {
                            case 'concluido': case 'pago': case 'entregue': return 'status-success';
                            case 'pendente': return 'status-warning';
                            case 'cancelado': return 'status-danger';
                            case 'enviado': return 'status-info';
                            default: return 'status-info';
                        }
                    }
                    function getStatusIcon($status) {
                        switch (strtolower($status)) {
                            case 'concluido': case 'pago': case 'entregue': return 'fa-check-circle';
                            case 'pendente': return 'fa-clock';
                            case 'cancelado': return 'fa-times-circle';
                            case 'enviado': return 'fa-truck';
                            default: return 'fa-circle';
                        }
                    }
                    ?>
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
                            <?php foreach ($pedidosRecentes as $pedido): ?>
                            <tr>
                                <td class="gold-text font-bold">#<?= $pedido['id_pedido'] ?></td>
                                <td><?= date('d/m/Y', strtotime($pedido['data_pedido'])) ?></td>
                                <td class="bold">R$ <?= number_format($pedido['total_pedido'], 2, ',', '.') ?></td>
                                <td>
                                    <span class="k-badge <?= getStatusClass($pedido['status_pedido']) ?>">
                                        <i class="fas <?= getStatusIcon($pedido['status_pedido']) ?>"></i>
                                        <?= strtoupper($pedido['status_pedido']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/backend/cliente/pedidos/detalhes/<?= $pedido['id_pedido'] ?>" class="btn-eye" title="Ver detalhes">
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

        <!-- Suporte Koketsu -->
        <div class="content-box support-box">
            <div class="support-icon-top">
                <i class="fas fa-headset"></i>
            </div>
            <h3>CONCIERGE<br><span class="gold-text">KOKETSU</span></h3>
            <p>Atendimento prioritário e exclusivo para membros VIP.</p>
            <div class="support-links">
                <a href="#" id="wpp-suporte-link" class="s-link s-link-wpp" target="_blank">
                    <i class="fab fa-whatsapp"></i> ATENDIMENTO VIA WHATSAPP
                </a>
                <a href="mailto:contato@koketsu.com.br" class="s-link">
                    <i class="fas fa-envelope"></i> SUPORTE VIA E-MAIL
                </a>
                <a href="/pages/catalogo.html" class="s-link">
                    <i class="fas fa-tag"></i> VER PROMOÇÕES
                </a>
            </div>
            <div class="member-badge">
                <i class="fas fa-crown"></i> MEMBRO VIP ATIVO
            </div>
        </div>
    </div>
</div>

<script>
// Carregar número WhatsApp de atendimento
(async () => {
    try {
        const cfg = await fetch('/api/config.php').then(r => r.json());
        const num = cfg.whatsapp_numero || '5511985477260';
        const msg = encodeURIComponent('Olá! Sou cliente Koketsu e preciso de atendimento.');
        const link = document.getElementById('wpp-suporte-link');
        if (link) link.href = `https://wa.me/${num}?text=${msg}`;
    } catch(e) {
        const link = document.getElementById('wpp-suporte-link');
        if (link) link.href = 'https://wa.me/5511985477260';
    }
})();
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Montserrat:wght@300;400;600;700&display=swap');

    :root {
        --k-gold: #f2cc7d;
        --k-gold-dark: #b8860b;
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

    /* ── Header ──────────────────────────────────────────────── */
    .luxury-header {
        background: var(--bg-card-custom);
        border: 1px solid var(--border-custom);
        border-radius: 20px;
        padding: 32px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.15);
        gap: 20px;
        flex-wrap: wrap;
    }

    .user-profile-section {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    /* Avatar */
    .avatar-ring {
        position: relative;
        width: 72px; height: 72px;
        flex-shrink: 0;
    }
    .avatar-ring img {
        width: 72px; height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--k-gold);
    }
    .avatar-initials {
        width: 72px; height: 72px;
        background: linear-gradient(135deg, var(--k-gold), var(--k-gold-dark));
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; font-weight: 900;
        font-family: 'Oswald', sans-serif;
        color: #000;
    }
    .avatar-status-dot {
        position: absolute;
        bottom: 3px; right: 3px;
        width: 14px; height: 14px;
        background: #25D366;
        border-radius: 50%;
        border: 2px solid var(--bg-card-custom);
    }

    .welcome-box h2 {
        font-family: 'Oswald', sans-serif;
        font-size: 1.8rem;
        letter-spacing: 2px;
        margin: 0;
        color: var(--text-color-custom);
    }
    .welcome-box p {
        color: var(--muted-custom);
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 0.72rem;
        margin-top: 4px;
    }

    .header-stats { display: flex; gap: 32px; align-items: center; }
    .mini-stat { text-align: right; }
    .v-label { display: block; font-size: 0.6rem; color: var(--muted-custom); letter-spacing: 2px; text-transform: uppercase; margin-bottom: 2px; }
    .v-value { font-size: 1.4rem; font-weight: 700; font-family: 'Oswald', sans-serif; color: var(--text-color-custom); }
    .v-divider { width: 1px; height: 40px; background: var(--border-custom); }

    /* ── Navigation Cards ────────────────────────────────────── */
    .luxury-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .k-card {
        background: var(--bg-card-custom);
        border: 1px solid var(--border-custom);
        padding: 24px;
        border-radius: 14px;
        text-decoration: none;
        color: var(--text-color-custom);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.3s ease;
    }
    .k-card:hover {
        transform: translateY(-4px);
        border-color: var(--k-gold);
        box-shadow: 0 12px 30px rgba(242, 204, 125, 0.12);
        color: var(--text-color-custom);
    }
    .k-card-gold {
        background: linear-gradient(135deg, rgba(242,204,125,0.08), rgba(184,134,11,0.04));
        border-color: rgba(242,204,125,0.25);
    }
    .k-card-gold:hover { background: linear-gradient(135deg, rgba(242,204,125,0.15), rgba(184,134,11,0.08)); }

    .k-icon {
        width: 48px; height: 48px;
        background: linear-gradient(135deg, var(--k-gold), var(--k-gold-dark));
        color: #000;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .k-icon-dark {
        background: #000;
        color: var(--k-gold);
        border: 1px solid rgba(242,204,125,0.3);
    }

    .k-info h3 { font-family: 'Oswald', sans-serif; margin: 0; font-size: 1rem; letter-spacing: 1px; color: var(--text-color-custom); }
    .k-info p { margin: 2px 0 0; font-size: 0.75rem; color: var(--muted-custom); }
    .k-arrow { margin-left: auto; opacity: 0.15; transition: 0.3s; color: var(--text-color-custom); flex-shrink: 0; }
    .k-card:hover .k-arrow { opacity: 1; color: var(--k-gold); transform: translateX(5px); }

    /* ── Main Row ─────────────────────────────────────────────── */
    .main-content-row { display: flex; gap: 20px; }

    .content-box {
        background: var(--bg-card-custom);
        border: 1px solid var(--border-custom);
        border-radius: 16px;
        padding: 28px;
    }
    .recent-orders { flex: 2; }
    .support-box {
        flex: 1;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }
    .box-header h3 {
        font-family: 'Oswald', sans-serif;
        margin: 0;
        letter-spacing: 2px;
        color: var(--text-color-custom);
        font-size: 1rem;
    }

    /* ── Table ─────────────────────────────────────────────────── */
    .k-table { width: 100%; border-collapse: collapse; }
    .k-table th {
        text-align: left;
        padding: 12px 14px;
        color: var(--muted-custom);
        font-size: 0.65rem;
        letter-spacing: 2px;
        border-bottom: 1px solid var(--border-custom);
    }
    .k-table td {
        padding: 16px 14px;
        border-bottom: 1px solid var(--border-custom);
        font-size: 0.88rem;
        color: var(--text-color-custom);
    }
    .k-table tbody tr:last-child td { border-bottom: none; }
    .k-table tbody tr { transition: background 0.2s; }
    .k-table tbody tr:hover { background: rgba(255,255,255,0.02); }

    /* ── Badges ─────────────────────────────────────────────────── */
    .k-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .status-success { background: rgba(46,204,113,0.1); color: #2ecc71; border: 1px solid rgba(46,204,113,0.2); }
    .status-warning { background: rgba(241,196,15,0.1); color: #f1c40f; border: 1px solid rgba(241,196,15,0.2); }
    .status-danger  { background: rgba(231,76,60,0.1);  color: #e74c3c; border: 1px solid rgba(231,76,60,0.2); }
    .status-info    { background: rgba(52,152,219,0.1); color: #3498db; border: 1px solid rgba(52,152,219,0.2); }

    .btn-eye { color: var(--muted-custom); transition: 0.3s; font-size: 1rem; }
    .btn-eye:hover { color: var(--k-gold); }

    /* ── Support Box ─────────────────────────────────────────── */
    .support-icon-top {
        width: 64px; height: 64px;
        background: linear-gradient(135deg, var(--k-gold), var(--k-gold-dark));
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem;
        color: #000;
        margin: 0 auto;
        box-shadow: 0 8px 24px rgba(242,204,125,0.3);
    }
    .support-box h3 {
        font-family: 'Oswald', sans-serif;
        letter-spacing: 2px;
        font-size: 1.1rem;
        color: var(--text-color-custom);
        margin: 0;
        line-height: 1.3;
    }
    .support-box p {
        color: var(--muted-custom);
        font-size: 0.78rem;
        line-height: 1.6;
        margin: 0;
    }

    .support-links { width: 100%; display: flex; flex-direction: column; gap: 10px; }
    .s-link {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        padding: 12px 16px;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--border-custom);
        border-radius: 10px;
        text-decoration: none;
        color: var(--text-color-custom);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        transition: 0.3s;
    }
    .s-link:hover { background: var(--k-gold); color: #000; border-color: var(--k-gold); }
    .s-link-wpp { border-color: rgba(37,211,102,0.25); }
    .s-link-wpp:hover { background: #25D366; border-color: #25D366; }

    .member-badge {
        background: linear-gradient(135deg, rgba(242,204,125,0.1), rgba(184,134,11,0.05));
        border: 1px solid rgba(242,204,125,0.25);
        border-radius: 20px;
        padding: 8px 16px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1px;
        color: var(--k-gold);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* ── Utilities ───────────────────────────────────────────── */
    .gold-text { color: var(--k-gold); }
    .gold-link { color: var(--k-gold); text-decoration: none; font-size: 0.78rem; font-weight: 700; letter-spacing: 1px; transition: opacity 0.2s; }
    .gold-link:hover { opacity: 0.7; }
    .font-bold { font-weight: 700; }
    .btn-gold-sm {
        display: inline-block;
        background: var(--k-gold); color: #000;
        padding: 10px 24px; border-radius: 8px;
        text-decoration: none; font-weight: 800;
        font-size: 0.75rem; letter-spacing: 1px;
        text-transform: uppercase; margin-top: 12px;
        transition: 0.3s;
    }
    .btn-gold-sm:hover { brightness: 1.1; transform: translateY(-1px); }

    .k-empty {
        text-align: center;
        padding: 40px 20px;
        color: var(--muted-custom);
    }
    .k-empty p { font-size: 0.9rem; margin-top: 12px; }

    /* ── Responsive ──────────────────────────────────────────── */
    @media (max-width: 1100px) {
        .luxury-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 850px) {
        .luxury-header { flex-direction: column; align-items: flex-start; padding: 24px; }
        .header-stats { width: 100%; justify-content: space-between; }
        .luxury-grid { grid-template-columns: 1fr; }
        .main-content-row { flex-direction: column; }
    }
    @media (max-width: 500px) {
        .user-profile-section { flex-direction: column; align-items: flex-start; gap: 12px; }
        .header-stats { flex-wrap: wrap; gap: 16px; }
    }
</style>