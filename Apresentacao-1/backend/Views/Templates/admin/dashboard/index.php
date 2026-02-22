<div class="dashboard-container w3-animate-opacity">
    <div class="dashboard-card">
        <h3 class="title">
            <i class="fa fa-dashboard"></i> Meu Painel
        </h3>

        <h1 class="welcome">
            Bem-vindo de volta, <?= htmlspecialchars($nomeUsuario); ?>!
        </h1>

        <h2 class="user-type">
            Seu usuário: <span><?= htmlspecialchars($Tipo); ?></span>
        </h2>

        <p class="subtitle">Esta é a sua área segura.</p>

        <div class="action-buttons">
            <a href="/backend/relatorios" class="action-btn relatorios">
                <i class="fa fa-bar-chart"></i> 
                <span>Visualizar Relatórios</span>
            </a>
            <a href="/backend/logout" class="action-btn logout">
                <i class="fa fa-sign-out"></i> 
                <span>Sair do Sistema</span>
            </a>
        </div>
    </div>
</div>

<style>
    /* Container principal ajustado para alinhar ao topo e centralizar horizontalmente */
    .dashboard-container {
        display: flex;
        justify-content: center;
        align-items: flex-start; /* Alinha o card no topo como na imagem */
        padding: 60px 20px;
        background-color: var(--bg-main) !important;
        min-height: 100vh;
    }

    /* Card com largura reduzida (max-width: 650px) para efeito compacto */
    .dashboard-card {
        background: var(--bg-card);
        padding: 40px;
        border-radius: 20px;
        width: 100%;
        max-width: 800px; /* Largura ideal para o estilo da imagem 2 */
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        transition: transform 0.3s ease;
    }

    .dashboard-card .title {
        font-size: 20px; /* Tamanho harmonizado */
        margin-bottom: 25px;
        color: var(--accent);
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dashboard-card .welcome {
        font-size: 28px; /* Reduzido para não quebrar em muitas linhas */
        font-weight: bold;
        color: var(--text-main);
        margin: 5px 0;
        letter-spacing: -0.5px;
    }

    .dashboard-card .user-type {
        font-size: 18px;
        color: var(--text-muted);
        margin-bottom: 10px;
    }

    .dashboard-card .user-type span {
        color: #28a745; 
        font-weight: 800;
        text-transform: uppercase;
    }

    .dashboard-card .subtitle {
        color: var(--text-muted);
        font-size: 14px;
        margin-bottom: 35px;
        opacity: 0.8;
    }

    /* Botões de ação */
    .action-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: bold;
        font-size: 15px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .action-btn.relatorios {
        background: var(--accent);
        color: #000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .action-btn.relatorios:hover {
        filter: brightness(1.1);
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    }

    .action-btn.logout {
        background: rgba(230, 57, 70, 0.1);
        color: #e63946;
        border: 1px solid rgba(230, 57, 70, 0.2);
    }

    .action-btn.logout:hover {
        background: #e63946;
        color: #fff;
        transform: translateY(-3px);
    }

    /* Responsividade para celulares */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 30px 15px;
        }

        .dashboard-card {
            padding: 30px 20px;
        }

        .dashboard-card .welcome {
            font-size: 24px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-btn {
            justify-content: center;
            width: 100%;
        }
    }
</style>