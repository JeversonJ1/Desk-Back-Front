<?php
// Instanciar modelos para preencher os seletores do formulário de criação/edição no SPA
$db = \App\Koketsu\Database\Database::getInstance();
$produtosModel = new \App\Koketsu\Models\Produtos($db);
$produtosList = $produtosModel->buscarProdutosAtivos();

// Buscar perfis ativos (clientes) com seus nomes de usuário
$sqlPerfis = "SELECT p.id_perfil, p.endereco_perfil, p.telefone_perfil, u.nome_usuarios 
              FROM tbl_perfil p
              JOIN tbl_usuarios u ON p.id_usuarios = u.id_usuarios
              WHERE p.excluido_em IS NULL";
$stmtPerfis = $db->prepare($sqlPerfis);
$stmtPerfis->execute();
$perfisList = $stmtPerfis->fetchAll(PDO::FETCH_ASSOC);

// Calcular estatísticas iniciais em PHP
$total_pedidos = count($pedidos);
$total_ativos = 0;
$total_inativos = 0;
$faturamento_ativo = 0;

foreach ($pedidos as $p) {
    if (empty($p['excluido_em'])) {
        $total_ativos++;
        $p_status = strtolower($p['status_pedido'] ?? '');
        if (in_array($p_status, ['pago', 'enviado', 'concluido'])) {
            $faturamento_ativo += (float)$p['total_pedido'];
        }
    } else {
        $total_inativos++;
    }
}
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    /* --- ESTILOS DE LAYOUT --- */
    .page-wrapper {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        padding: 30px;
        width: 100%;
        box-sizing: border-box;
        background-color: var(--bg-main) !important;
        min-height: 100vh;
        color: var(--text-main);
    }

    .page-header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 15px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        margin: 0;
        color: var(--text-main);
        text-transform: uppercase;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-title i {
        color: var(--accent);
        filter: drop-shadow(0 0 8px var(--accent-glow));
    }

    .header-breadcrumb {
        font-size: 13px;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* --- KPI CARDS PREMIUM --- */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        border-radius: 16px;
        padding: 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 1px solid rgba(255, 255, 255, 0.05);
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0) 100%);
        pointer-events: none;
    }

    /* Cores dos Cards KPI */
    .kpi-total { background: linear-gradient(135deg, #4E9EBF 0%, #1A365D 100%); }
    .kpi-revenue { background: linear-gradient(135deg, #F2C84B 0%, #C47A3A 100%); color: #000; }
    .kpi-actives { background: linear-gradient(135deg, #51cf66 0%, #1A5F20 100%); }
    .kpi-inactives { background: linear-gradient(135deg, #dc3545 0%, #5A1A22 100%); }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        filter: brightness(1.1);
    }

    .stat-info p {
        margin: 0 0 5px 0;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
        font-weight: 700;
        opacity: 0.8;
    }

    .kpi-revenue .stat-info p { color: #000; opacity: 0.9; }

    .stat-info h3 {
        margin: 0;
        font-size: 26px;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .stat-icon {
        font-size: 26px;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.12);
        box-shadow: inset 0 1px 3px rgba(255, 255, 255, 0.2);
    }

    .kpi-revenue .stat-icon {
        background: rgba(0, 0, 0, 0.1);
        box-shadow: none;
        color: #000;
    }

    /* --- BARRA DE FILTROS & AÇÕES --- */
    .controls-panel {
        background: var(--bg-card-flat);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 25px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .actions-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .search-container {
        position: relative;
        flex: 1;
        max-width: 450px;
    }

    .search-container i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--accent);
    }

    .search-input {
        width: 100%;
        background: var(--bg-main);
        border: 2px solid var(--border-color);
        padding: 13px 15px 13px 45px;
        border-radius: 10px;
        color: var(--text-main);
        font-size: 14px;
        transition: all 0.3s ease;
        outline: none;
    }

    .search-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-glow);
    }

    .btn-create-order {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #F2C84B 0%, #d4a800 100%) !important;
        color: #000 !important;
        padding: 14px 26px;
        border-radius: 10px;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        box-shadow: var(--shadow-gold);
        cursor: pointer;
    }

    .btn-create-order:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(242, 200, 75, 0.4);
        filter: brightness(1.05);
    }

    /* --- ABAS DE FILTRO --- */
    .tabs-container {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 2px;
        border-bottom: 1px solid var(--border-color);
    }

    .tab-button {
        background: transparent;
        border: none;
        color: var(--text-muted);
        padding: 10px 18px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.2s ease;
        border-radius: 8px 8px 0 0;
        position: relative;
        white-space: nowrap;
    }

    .tab-button:hover {
        color: var(--text-main);
    }

    .tab-button.active {
        color: var(--accent);
        background: rgba(242, 200, 75, 0.05);
    }

    .tab-button.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--accent);
        border-radius: 3px 3px 0 0;
        box-shadow: 0 -2px 10px var(--accent-glow);
    }

    /* --- TABELA DE PEDIDOS PREMIUM --- */
    .table-container {
        overflow-x: auto;
    }

    .order-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
        color: var(--text-main);
    }

    .order-table thead th {
        color: var(--accent) !important;
        text-transform: uppercase;
        font-size: 11px;
        padding: 12px 15px;
        letter-spacing: 1.5px;
        font-weight: 800;
        border-bottom: 2px solid var(--border-color);
    }

    .order-table tbody tr {
        background: rgba(26, 26, 26, 0.6);
        backdrop-filter: blur(8px);
        transition: all 0.2s cubic-bezier(0.2, 0.8, 0.2, 1);
        border: 1px solid var(--border-color);
    }

    .order-table tbody tr:hover {
        transform: scale(1.006);
        background: rgba(26, 26, 26, 0.9);
        box-shadow: var(--shadow-sm);
        border-color: var(--accent);
    }

    .order-table td {
        padding: 16px 15px !important;
        border: none;
        vertical-align: middle;
    }

    .order-table td:first-child {
        border-radius: 12px 0 0 12px;
        border-left: 1px solid var(--border-color);
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
    }

    .order-table td:last-child {
        border-radius: 0 12px 12px 0;
        border-right: 1px solid var(--border-color);
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
    }

    .id-column {
        font-family: monospace;
        color: var(--text-muted) !important;
        font-weight: bold;
        font-size: 13px;
    }

    .client-column {
        font-weight: 700;
        color: var(--text-main);
    }

    .price-column {
        color: var(--accent);
        font-weight: 800;
        font-size: 15px;
    }

    /* Badges de Status */
    .badge-status {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    .status-pago, .status-concluido {
        background: rgba(81, 207, 102, 0.12);
        color: #51cf66;
        border: 1px solid rgba(81, 207, 102, 0.4);
    }

    .status-enviado {
        background: rgba(78, 158, 191, 0.12);
        color: #4E9EBF;
        border: 1px solid rgba(78, 158, 191, 0.4);
    }

    .status-pendente {
        background: rgba(242, 200, 75, 0.1);
        color: var(--accent);
        border: 1px solid rgba(242, 200, 75, 0.3);
    }

    .status-preparacao, .status-processamento {
        background: rgba(255, 169, 77, 0.1);
        color: #ffa94d;
        border: 1px solid rgba(255, 169, 77, 0.3);
    }

    .status-cancelado {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }

    /* Botões de Ação na Tabela */
    .actions-cell {
        display: flex;
        gap: 6px;
        justify-content: center;
    }

    .btn-action-small {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid var(--border-color);
        background: var(--bg-main);
        text-decoration: none;
    }

    .btn-view { color: #4dabf7; border-color: rgba(77, 171, 247, 0.3); }
    .btn-view:hover { background: #4dabf7; color: #000; box-shadow: 0 0 10px rgba(77, 171, 247, 0.4); }

    .btn-edit { color: var(--accent); border-color: rgba(242, 200, 75, 0.3); }
    .btn-edit:hover { background: var(--accent); color: #000; box-shadow: 0 0 10px var(--accent-glow); }

    .btn-delete { color: #dc3545; border-color: rgba(220, 53, 69, 0.3); }
    .btn-delete:hover { background: #dc3545; color: #fff; box-shadow: 0 0 10px rgba(220, 53, 69, 0.4); }

    .btn-activate { color: #51cf66; border-color: rgba(81, 207, 102, 0.3); }
    .btn-activate:hover { background: #51cf66; color: #fff; box-shadow: 0 0 10px rgba(81, 207, 102, 0.4); }

    .btn-status-quick {
        font-size: 12px;
        padding: 0 10px;
        border-radius: 8px;
        height: 34px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
        border: 1px solid var(--border-color);
        background: var(--bg-main);
    }

    .btn-status-prep { color: #ffa94d; border-color: rgba(255, 169, 77, 0.3); }
    .btn-status-prep:hover { background: #ffa94d; color: #000; }

    .btn-status-ship { color: #4E9EBF; border-color: rgba(78, 158, 191, 0.3); }
    .btn-status-ship:hover { background: #4E9EBF; color: #000; }

    .btn-status-undo { color: #888; border-color: rgba(136, 136, 136, 0.3); }
    .btn-status-undo:hover { background: #888; color: #000; }

    /* Estilo Especial para Excluídos */
    .tr-deleted td {
        background: rgba(220, 53, 69, 0.05) !important;
        opacity: 0.7;
    }
    
    .tr-deleted td:first-child {
        border-left: 3px solid #dc3545 !important;
    }

    .tr-deleted .price-column {
        text-decoration: line-through;
        color: var(--text-muted) !important;
    }

    .badge-deleted {
        background: #dc3545;
        color: #fff;
        font-size: 9px;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 4px;
        margin-left: 6px;
    }

    /* --- PAGINAÇÃO --- */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 25px;
        padding: 16px 20px;
        background: var(--bg-card-flat);
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .pagination-info {
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 600;
    }

    .pagination-buttons {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .page-link {
        padding: 8px 16px;
        background: var(--bg-main);
        border: 1px solid var(--border-color);
        color: var(--text-main);
        border-radius: 8px;
        cursor: pointer;
        font-weight: 700;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    .page-link:hover:not(.disabled) {
        border-color: var(--accent);
        color: var(--accent);
    }

    .page-link.active {
        background: linear-gradient(135deg, #F2C84B 0%, #d4a800 100%);
        color: #000;
        border-color: var(--accent);
    }

    .page-link.disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    /* --- TOAST NOTIFICATIONS --- */
    .toast-container {
        position: fixed;
        bottom: 25px;
        right: 25px;
        z-index: 10000;
        display: flex;
        flex-direction: column;
        gap: 10px;
        pointer-events: none;
    }

    .toast {
        background: #1a1a1a;
        color: #fff;
        padding: 16px 22px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        border-left: 5px solid var(--accent);
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 300px;
        pointer-events: auto;
        animation: toastIn 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        transition: all 0.3s ease;
    }

    .toast.hide {
        animation: toastOut 0.3s cubic-bezier(0.6, -0.28, 0.735, 0.045) forwards;
    }

    .toast-success { border-left-color: #51cf66; }
    .toast-error { border-left-color: #dc3545; }
    .toast-info { border-left-color: #4e9ebf; }

    @keyframes toastIn {
        from { transform: translateY(50px) scale(0.8); opacity: 0; }
        to { transform: translateY(0) scale(1); opacity: 1; }
    }

    @keyframes toastOut {
        to { transform: translateY(30px) scale(0.8); opacity: 0; }
    }

    /* --- SPA RIGHT SIDE DRAWER --- */
    .spa-drawer-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .spa-drawer-overlay.open {
        opacity: 1;
        visibility: visible;
    }

    .spa-drawer {
        position: fixed;
        top: 0;
        right: -650px;
        width: 650px;
        height: 100vh;
        background: #111111;
        border-left: 2px solid #222;
        box-shadow: -10px 0 40px rgba(0, 0, 0, 0.8);
        z-index: 9999;
        transition: right 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        color: #fff;
    }

    .spa-drawer.open {
        right: 0;
    }

    .drawer-header {
        padding: 25px;
        border-bottom: 1px solid #222;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #0d0d0d;
    }

    .drawer-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 800;
        color: var(--accent);
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-close-drawer {
        background: transparent;
        border: none;
        color: #888;
        font-size: 24px;
        cursor: pointer;
        transition: color 0.2s;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-close-drawer:hover {
        color: #fff;
        background: rgba(255,255,255,0.05);
    }

    .drawer-content {
        padding: 25px;
        flex: 1;
        overflow-y: auto;
        background: #111;
    }

    /* --- COMPONENTES INTERNOS DO DRAWER --- */
    .drawer-section {
        background: #161616;
        border: 1px solid #262626;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .drawer-section h4 {
        margin: 0 0 15px 0;
        font-size: 14px;
        text-transform: uppercase;
        color: var(--accent);
        border-bottom: 1px solid #262626;
        padding-bottom: 8px;
        letter-spacing: 0.5px;
    }

    .grid-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 14px;
        border-bottom: 1px dashed rgba(255,255,255,0.05);
        padding-bottom: 10px;
    }

    .detail-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .detail-row span { color: #888; }
    .detail-row strong { color: #fff; }

    .drawer-total-box {
        background: rgba(242, 200, 75, 0.05);
        border: 1px solid var(--accent);
        border-radius: 12px;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
    }

    .drawer-total-box span {
        font-weight: 800;
        text-transform: uppercase;
        font-size: 12px;
        color: var(--accent);
    }

    .drawer-total-box strong {
        font-size: 22px;
        color: var(--accent);
        font-weight: 800;
    }

    /* Formulários no Drawer */
    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        color: #aaa;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        background: #0d0d0d;
        border: 1px solid #222;
        border-radius: 8px;
        padding: 12px;
        color: #fff;
        font-size: 14px;
        box-sizing: border-box;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: var(--accent);
        outline: none;
        box-shadow: 0 0 0 3px var(--accent-glow);
    }

    textarea.form-control {
        resize: vertical;
    }

    .btn-submit-drawer {
        width: 100%;
        background: linear-gradient(135deg, #F2C84B 0%, #d4a800 100%);
        color: #000;
        font-weight: 800;
        text-transform: uppercase;
        border: none;
        padding: 15px;
        border-radius: 10px;
        font-size: 14px;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-gold);
    }

    .btn-submit-drawer:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(242, 200, 75, 0.4);
    }

    /* Itens de Compra e Autocomplete Dinâmico no Drawer */
    .drawer-items-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .drawer-items-table th {
        color: #888;
        text-transform: uppercase;
        font-size: 10px;
        font-weight: 800;
        text-align: left;
        padding: 10px 8px;
        border-bottom: 1px solid #222;
    }

    .drawer-items-table td {
        padding: 12px 8px;
        border-bottom: 1px solid rgba(255,255,255,0.03);
        vertical-align: middle;
    }

    .btn-remove-row {
        background: transparent;
        border: none;
        color: #dc3545;
        cursor: pointer;
        font-size: 15px;
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-remove-row:hover {
        background: rgba(220, 53, 69, 0.1);
    }

    .btn-add-item-row {
        background: rgba(255,255,255,0.05);
        color: #fff;
        border: 1px dashed #333;
        border-radius: 8px;
        padding: 10px;
        width: 100%;
        text-align: center;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        margin-top: 10px;
        transition: all 0.2s;
    }

    .btn-add-item-row:hover {
        background: rgba(242, 200, 75, 0.05);
        border-color: var(--accent);
        color: var(--accent);
    }

    .drawer-footer-actions {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .btn-secondary-drawer {
        flex: 1;
        background: #222;
        color: #fff;
        border: 1px solid #333;
        padding: 14px;
        border-radius: 10px;
        font-weight: 700;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }

    .btn-secondary-drawer:hover {
        background: #333;
    }

    /* --- AUTOCOMPLETE SELECT --- */
    .autocomplete-container {
        position: relative;
    }

    .autocomplete-list {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: #161616;
        border: 1px solid #333;
        border-radius: 0 0 8px 8px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 100;
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        display: none;
    }

    .autocomplete-item {
        padding: 10px 15px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.15s;
        border-bottom: 1px solid rgba(255,255,255,0.02);
    }

    .autocomplete-item:hover {
        background: var(--accent);
        color: #000;
        font-weight: 700;
    }
</style>

<div class="page-wrapper">
    <div class="page-header-container">
        <div>
            <h3 class="page-title"><i class="fas fa-shopping-bag"></i> Gerenciar Pedidos</h3>
            <span class="header-breadcrumb">Painel de Controle - Koketsu</span>
        </div>
        <button onclick="openCreateDrawer()" class="btn-create-order">
            <i class="fas fa-plus-circle"></i> Novo Pedido
        </button>
    </div>

    <!-- CARDS DE MÉTRICAS (KPIs) -->
    <div class="dashboard-grid">
        <div class="stat-card kpi-total">
            <div class="stat-info">
                <p>Total Geral</p>
                <h3 id="kpi-total-val"><?= $total_pedidos ?></h3>
            </div>
            <div class="stat-icon">
                <i class="fas fa-boxes"></i>
            </div>
        </div>

        <div class="stat-card kpi-revenue">
            <div class="stat-info">
                <p>Faturamento Ativo</p>
                <h3 id="kpi-revenue-val">R$ <?= number_format($faturamento_ativo, 2, ',', '.') ?></h3>
            </div>
            <div class="stat-icon">
                <i class="fas fa-coins"></i>
            </div>
        </div>

        <div class="stat-card kpi-actives">
            <div class="stat-info">
                <p>Pedidos Ativos</p>
                <h3 id="kpi-actives-val"><?= $total_ativos ?></h3>
            </div>
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>

        <div class="stat-card kpi-inactives">
            <div class="stat-info">
                <p>Pedidos Inativos</p>
                <h3 id="kpi-inactives-val"><?= $total_inativos ?></h3>
            </div>
            <div class="stat-icon">
                <i class="fas fa-trash-alt"></i>
            </div>
        </div>
    </div>

    <!-- CONTROLES, PESQUISA E ABAS -->
    <div class="controls-panel">
        <div class="actions-bar">
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" id="orderInput" onkeyup="filterOrders()" placeholder="Buscar pedido por ID ou cliente..." class="search-input">
            </div>

            <div class="tabs-container">
                <button onclick="changeTab('all')" class="tab-button active" id="tab-all">Todos</button>
                <button onclick="changeTab('pendente')" class="tab-button" id="tab-pendente">Pendentes</button>
                <button onclick="changeTab('preparacao')" class="tab-button" id="tab-preparacao">Em Preparação</button>
                <button onclick="changeTab('pago')" class="tab-button" id="tab-pago">Pagos/Concluídos</button>
                <button onclick="changeTab('cancelado')" class="tab-button" id="tab-cancelado">Cancelados</button>
                <button onclick="changeTab('deleted')" class="tab-button" id="tab-deleted">Excluídos</button>
            </div>
        </div>
    </div>

    <!-- TABELA DE RESULTADOS -->
    <div class="table-container">
        <table class="order-table" id="orderTable">
            <thead>
                <tr>
                    <th style="width: 90px; text-align: left;">ID</th>
                    <th style="text-align: left;">Cliente</th>
                    <th style="text-align: left; width: 140px;">Data</th>
                    <th style="text-align: left;">Endereço de Entrega</th>
                    <th style="text-align: right; width: 140px;">Total</th>
                    <th style="text-align: center; width: 130px;">Status</th>
                    <th style="text-align: center; width: 220px;">Ações</th>
                </tr>
            </thead>
            <tbody id="orderTableBody">
                <?php if (isset($pedidos) && is_array($pedidos) && count($pedidos) > 0): ?>
                    <?php foreach ($pedidos as $pedido):
                        $is_deleted = !empty($pedido['excluido_em']);
                        $status_orig = !empty(trim($pedido['status_pedido'] ?? '')) ? $pedido['status_pedido'] : 'Pendente';
                        $status = strtolower($status_orig);
                        $badge_class = 'status-pendente';

                        if (in_array($status, ['pago', 'concluido'])) {
                            $badge_class = 'status-pago';
                        } elseif ($status === 'enviado') {
                            $badge_class = 'status-enviado';
                        } elseif (in_array($status, ['preparação', 'processamento'])) {
                            $badge_class = 'status-preparacao';
                        } elseif ($status === 'cancelado') {
                            $badge_class = 'status-cancelado';
                        }
                        ?>
                        <tr class="order-row <?= $is_deleted ? 'tr-deleted' : '' ?>" 
                            id="row-<?= $pedido['id_pedido'] ?>"
                            data-id="<?= $pedido['id_pedido'] ?>"
                            data-deleted="<?= $is_deleted ? 'true' : 'false' ?>"
                            data-status="<?= $status ?>">
                            
                            <td class="id-column">
                                #<?= $pedido['id_pedido'] ?>
                                <span class="badge-deleted-placeholder"><?= $is_deleted ? '<span class="badge-deleted">EXCLUÍDO</span>' : '' ?></span>
                            </td>
                            <td class="client-column"><?= htmlspecialchars($pedido['nome_cliente'] ?? 'N/A') ?></td>
                            <td style="color: #888; font-size: 13px;">
                                <?= date('d/m/y H:i', strtotime($pedido['data_pedido'])) ?>
                            </td>
                            <td style="color: #999; font-size: 13px; max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" class="address-cell">
                                <?= htmlspecialchars($pedido['endereco_perfil'] ?? 'Sem endereço cadastrado') ?>
                            </td>
                            <td class="price-column" style="text-align: right;">
                                R$ <?= number_format($pedido['total_pedido'], 2, ',', '.') ?>
                            </td>

                            <td style="text-align: center;" class="status-cell">
                                <span class="badge-status <?= $badge_class ?>">
                                    <?= ucfirst(htmlspecialchars($status_orig)) ?>
                                </span>
                            </td>

                            <td style="text-align: center; white-space: nowrap;" class="actions-cell-container">
                                <div class="actions-cell">
                                    <!-- Ver Detalhes Drawer -->
                                    <button onclick="viewOrder(<?= $pedido['id_pedido'] ?>)" class="btn-action-small btn-view" title="Visualizar Detalhes">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Ações Disponíveis Apenas se Não Excluído -->
                                    <span class="active-actions-wrapper" style="<?= $is_deleted ? 'display:none;' : '' ?>">
                                        <!-- Transições Rápidas de Status -->
                                        <span class="quick-status-container">
                                            <?php if ($status === 'pendente' || $status === 'pago'): ?>
                                                <button onclick="changeStatus(<?= $pedido['id_pedido'] ?>, 'preparação')" class="btn-status-quick btn-status-prep" title="Preparar Pedido">
                                                    <i class="fas fa-box"></i> Prep
                                                </button>
                                            <?php elseif ($status === 'preparação' || $status === 'processamento'): ?>
                                                <button onclick="changeStatus(<?= $pedido['id_pedido'] ?>, 'pendente')" class="btn-status-quick btn-status-undo" title="Voltar para Pendente">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                                <button onclick="changeStatus(<?= $pedido['id_pedido'] ?>, 'enviado')" class="btn-status-quick btn-status-ship" title="Despachar Pedido">
                                                    <i class="fas fa-truck"></i> Enviar
                                                </button>
                                            <?php elseif ($status === 'enviado'): ?>
                                                <button onclick="changeStatus(<?= $pedido['id_pedido'] ?>, 'preparação')" class="btn-status-quick btn-status-undo" title="Voltar para Preparação">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            <?php endif; ?>
                                        </span>

                                        <!-- Editar propriedades do pedido -->
                                        <button onclick="editOrder(<?= $pedido['id_pedido'] ?>)" class="btn-action-small btn-edit" title="Editar Propriedades">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>

                                        <!-- Excluir Pedido -->
                                        <button onclick="deleteToggleOrder(<?= $pedido['id_pedido'] ?>, true)" class="btn-action-small btn-delete" title="Excluir Pedido">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </span>

                                    <!-- Restaurar se Excluído -->
                                    <span class="deleted-actions-wrapper" style="<?= !$is_deleted ? 'display:none;' : '' ?>">
                                        <button onclick="deleteToggleOrder(<?= $pedido['id_pedido'] ?>, false)" class="btn-action-small btn-activate" title="Reativar Pedido">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="empty-row">
                        <td colspan="7" style="text-align: center; padding: 50px 20px; color: #555;">
                            <i class="fas fa-folder-open" style="font-size: 48px; display: block; margin-bottom: 15px; color: #333;"></i>
                            Nenhum pedido encontrado no sistema.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <div class="pagination-container">
            <div class="pagination-info" id="paginationInfo">Carregando paginação...</div>
            <div class="pagination-buttons" id="paginationButtons"></div>
        </div>
    </div>
</div>

<!-- CONTAINER DE GAVETA LATERAL (SIDE DRAWER) -->
<div class="spa-drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
<div class="spa-drawer" id="spaDrawer">
    <div class="drawer-header">
        <h3 id="drawerTitle"><i class="fas fa-info-circle"></i> Detalhes</h3>
        <button class="btn-close-drawer" onclick="closeDrawer()">&times;</button>
    </div>
    <div class="drawer-content" id="drawerContent">
        <!-- O conteúdo será injetado dinamicamente via JS -->
    </div>
</div>

<!-- TOAST CONTAINER -->
<div class="toast-container" id="toastContainer"></div>

<script>
    // --- INJEÇÃO DE INVENTÁRIO & PERFIS DIRETAMENTE DO PHP ---
    const availableProducts = <?= json_encode($produtosList) ?>;
    const availableProfiles = <?= json_encode($perfisList) ?>;

    // --- VARIÁVEIS DO CLIENT-SIDE DASHBOARD ---
    const rowsPerPage = 10;
    let currentPage = 1;
    let activeFilterTab = 'all';

    // --- INICIALIZAÇÃO ---
    document.addEventListener("DOMContentLoaded", () => {
        applyFiltersAndPagination();
        recalculateKPIs();
    });

    // --- CONTROLE DE FILTROS E PAGINAÇÃO CLIENT-SIDE ---
    function changeTab(tabName) {
        activeFilterTab = tabName;
        
        // Atualizar classes ativas dos botões das abas
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        const activeBtn = document.getElementById(`tab-${tabName}`);
        if (activeBtn) activeBtn.classList.add('active');

        currentPage = 1;
        applyFiltersAndPagination();
    }

    function filterOrders() {
        currentPage = 1;
        applyFiltersAndPagination();
    }

    function applyFiltersAndPagination() {
        const searchText = document.getElementById("orderInput").value.toUpperCase();
        const rows = Array.from(document.querySelectorAll(".order-row"));
        
        if (rows.length === 0) return;

        // 1. Filtrar por busca textual + Tab de estado
        let visibleRowsCount = 0;
        rows.forEach(row => {
            const id = row.getAttribute('data-id').toUpperCase();
            const client = row.querySelector(".client-column") ? row.querySelector(".client-column").textContent.toUpperCase() : "";
            const address = row.querySelector(".address-cell") ? row.querySelector(".address-cell").textContent.toUpperCase() : "";
            const status = row.getAttribute('data-status');
            const isDeleted = row.getAttribute('data-deleted') === 'true';

            // Match busca textual
            const matchesText = id.includes(searchText) || client.includes(searchText) || address.includes(searchText);

            // Match tab de estado
            let matchesTab = false;
            if (activeFilterTab === 'all') {
                matchesTab = !isDeleted; // Guia "Todos" mostra apenas ativos
            } else if (activeFilterTab === 'deleted') {
                matchesTab = isDeleted; // Guia "Excluídos" mostra apenas excluídos
            } else {
                // Outras guias filtram por status específico e não devem conter excluídos
                matchesTab = !isDeleted && (status === activeFilterTab);
            }

            if (matchesText && matchesTab) {
                row.setAttribute('data-visible', 'true');
                visibleRowsCount++;
            } else {
                row.setAttribute('data-visible', 'false');
                row.style.display = "none";
            }
        });

        // 2. Renderizar paginação da lista visível
        const totalPages = Math.ceil(visibleRowsCount / rowsPerPage) || 1;
        if (currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        let currentIndex = 0;
        rows.forEach(row => {
            if (row.getAttribute('data-visible') === 'true') {
                if (currentIndex >= start && currentIndex < end) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
                currentIndex++;
            }
        });

        updatePaginationUI(totalPages, visibleRowsCount);
    }

    function updatePaginationUI(totalPages, totalVisible) {
        const container = document.getElementById("paginationButtons");
        const info = document.getElementById("paginationInfo");
        container.innerHTML = "";
        
        info.innerText = `Exibindo página ${currentPage} de ${totalPages} (${totalVisible} pedidos filtrados)`;

        if (totalPages <= 1) return;

        const createBtn = (text, page, isActive = false, isDisabled = false) => {
            const btn = document.createElement("button");
            btn.innerHTML = text;
            btn.className = `page-link ${isActive ? 'active' : ''} ${isDisabled ? 'disabled' : ''}`;
            if (!isDisabled) btn.onclick = () => { currentPage = page; applyFiltersAndPagination(); };
            return btn;
        };

        // Botão Anterior
        container.appendChild(createBtn('<i class="fas fa-chevron-left"></i>', currentPage - 1, false, currentPage === 1));

        // Páginas numeradas
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                container.appendChild(createBtn(i, i, i === currentPage));
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                const dots = document.createElement("span");
                dots.style.color = "#555";
                dots.style.padding = "0 5px";
                dots.innerText = "...";
                container.appendChild(dots);
            }
        }

        // Botão Próximo
        container.appendChild(createBtn('<i class="fas fa-chevron-right"></i>', currentPage + 1, false, currentPage === totalPages));
    }

    // --- RECALCULAR CARD DE INFORMAÇÕES (KPIs) EM TEMPO REAL ---
    function recalculateKPIs() {
        const rows = Array.from(document.querySelectorAll('.order-row'));
        let total = 0;
        let actives = 0;
        let inactives = 0;
        let revenue = 0;

        rows.forEach(row => {
            const isDeleted = row.getAttribute('data-deleted') === 'true';
            const priceText = row.querySelector('.price-column').textContent.replace(/[^\d,.-]/g, '').replace(',', '.');
            const price = parseFloat(priceText) || 0;
            const status = row.getAttribute('data-status');

            total++;
            if (isDeleted) {
                inactives++;
            } else {
                actives++;
                if (['pago', 'concluido', 'enviado'].includes(status)) {
                    revenue += price;
                }
            }
        });

        document.getElementById('kpi-total-val').textContent = total;
        document.getElementById('kpi-revenue-val').textContent = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(revenue);
        document.getElementById('kpi-actives-val').textContent = actives;
        document.getElementById('kpi-inactives-val').textContent = inactives;
    }

    // --- CONTROLE DE EXIBIÇÃO DE TOAST NOTIFICATIONS ---
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        let icon = '<i class="fas fa-check-circle"></i>';
        if (type === 'error') icon = '<i class="fas fa-exclamation-triangle"></i>';
        if (type === 'info') icon = '<i class="fas fa-info-circle"></i>';

        toast.innerHTML = `${icon} <span>${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }

    // --- CONTROLE DA GAVETA LATERAL (SIDE DRAWER) ---
    function openDrawer(title) {
        document.getElementById('drawerTitle').innerHTML = title;
        document.getElementById('drawerOverlay').classList.add('open');
        document.getElementById('spaDrawer').classList.add('open');
    }

    function closeDrawer() {
        document.getElementById('drawerOverlay').classList.remove('open');
        document.getElementById('spaDrawer').classList.remove('open');
    }

    // --- FLUXO 1: VISUALIZAR DETALHES DO PEDIDO ---
    async function viewOrder(id) {
        openDrawer(`<i class="fas fa-info-circle"></i> Detalhes do Pedido #${id}`);
        const content = document.getElementById('drawerContent');
        content.innerHTML = '<div style="text-align:center; padding:50px;"><i class="fas fa-circle-notch fa-spin" style="font-size:32px; color:var(--accent);"></i><p style="margin-top:15px; color:#888;">Carregando detalhes...</p></div>';

        try {
            const res = await fetch(`/backend/pedido/detalhes/${id}?json=1`);
            const data = await res.json();

            if (!data.success) {
                content.innerHTML = `<div class="drawer-section" style="text-align:center; color:#dc3545;"><i class="fas fa-times-circle" style="font-size:40px;"></i><p style="margin-top:15px;">${data.message}</p></div>`;
                return;
            }

            const p = data.pedido;
            const itens = data.itens || [];
            
            // Formatando status
            const status_orig = p.status_pedido && p.status_pedido.trim() !== "" ? p.status_pedido : "Pendente";
            const status = status_orig.toLowerCase();
            let badgeClass = 'status-pendente';
            if (['pago', 'concluido'].includes(status)) badgeClass = 'status-pago';
            else if (status === 'enviado') badgeClass = 'status-enviado';
            else if (['preparação', 'processamento'].includes(status)) badgeClass = 'status-preparacao';
            else if (status === 'cancelado') badgeClass = 'status-cancelado';

            // Formatando Telefone
            let formattedPhone = 'Não informado';
            if (p.telefone_perfil) {
                const telClean = p.telefone_perfil.replace(/\D/g, '');
                if (telClean.length === 11) {
                    formattedPhone = `(${telClean.substring(0, 2)}) ${telClean.substring(2, 3)} ${telClean.substring(3, 7)}-${telClean.substring(7)}`;
                } else if (telClean.length === 10) {
                    formattedPhone = `(${telClean.substring(0, 2)}) ${telClean.substring(2, 6)}-${telClean.substring(6)}`;
                } else {
                    formattedPhone = p.telefone_perfil;
                }
            }

            let itensHtml = '';
            let calculatedSubtotal = 0;

            itens.forEach(item => {
                const rowSubtotal = item.quantidade * item.preco_unitario;
                calculatedSubtotal += rowSubtotal;
                itensHtml += `
                    <tr>
                        <td style="font-weight:700;">${item.nome_produtos || 'Produto Desconhecido'}</td>
                        <td style="text-align:right;">R$ ${parseFloat(item.preco_unitario).toLocaleString('pt-BR', {minimumFractionDigits:2})}</td>
                        <td style="text-align:center; font-weight:700;">${item.quantidade}</td>
                        <td style="text-align:right; color:var(--accent); font-weight:700;">R$ ${rowSubtotal.toLocaleString('pt-BR', {minimumFractionDigits:2})}</td>
                    </tr>
                `;
            });

            content.innerHTML = `
                <!-- RESUMO GERAL -->
                <div class="drawer-section">
                    <h4>Resumo Geral</h4>
                    <div class="detail-row">
                        <span>ID do Pedido:</span>
                        <strong>#${p.id_pedido}</strong>
                    </div>
                    <div class="detail-row">
                        <span>Data / Hora:</span>
                        <strong>${new Date(p.data_pedido).toLocaleString('pt-BR')}</strong>
                    </div>
                    <div class="detail-row">
                        <span>Status:</span>
                        <span class="badge-status ${badgeClass}">${status_orig.toUpperCase()}</span>
                    </div>
                </div>

                <!-- CLIENTE E ENTREGA -->
                <div class="drawer-section">
                    <h4>Cliente &amp; Entrega</h4>
                    <div class="detail-row">
                        <span>Nome:</span>
                        <strong>${p.nome_cliente || 'Perfil não associado'}</strong>
                    </div>
                    
                    <div id="viewAddressBlock">
                        <div class="detail-row">
                            <span>Telefone:</span>
                            <strong id="det-phone">${formattedPhone}</strong>
                        </div>
                        <div class="detail-row" style="flex-direction:column; align-items:flex-start; gap:5px;">
                            <span>Endereço:</span>
                            <strong id="det-address" style="text-align:left; word-break:break-word; margin-top:3px;">${p.endereco_perfil || 'Não informado'}</strong>
                        </div>
                        <button onclick="showInlineEditAddress(${p.id_perfil}, ${p.id_usuarios}, '${p.data_cadastro_perfil}', '${p.telefone_perfil || ''}', ${p.id_pedido})" class="btn-secondary-drawer" style="margin-top:15px; width:100%; font-size:12px; padding:10px;">
                            <i class="fas fa-edit"></i> Editar Endereço de Entrega
                        </button>
                    </div>

                    <!-- Formulário Inline de Edição Rápida de Endereço -->
                    <form id="inlineAddressForm" style="display:none; margin-top:15px;" onsubmit="saveInlineAddress(event, ${p.id_perfil}, ${p.id_pedido})">
                        <input type="hidden" name="id_usuarios" value="${p.id_usuarios}">
                        <input type="hidden" name="data_cadastro" value="${p.data_cadastro_perfil}">
                        
                        <div class="form-group">
                            <label>Telefone de Contato</label>
                            <input type="text" name="telefone_perfil" class="form-control" value="${p.telefone_perfil || ''}" required>
                        </div>
                        <div class="form-group">
                            <label>Endereço Completo</label>
                            <textarea name="endereco_perfil" class="form-control" rows="3" required>${p.endereco_perfil || ''}</textarea>
                        </div>
                        <div style="display:flex; gap:10px;">
                            <button type="button" onclick="cancelInlineEditAddress()" class="btn-secondary-drawer" style="padding:10px; font-size:12px;">Cancelar</button>
                            <button type="submit" class="btn-submit-drawer" style="padding:10px; font-size:12px; flex:1;">Salvar Endereço</button>
                        </div>
                    </form>
                </div>

                <!-- ITENS COMPRADOS -->
                <div class="drawer-section">
                    <h4>Itens Comprados (${itens.length})</h4>
                    <table class="drawer-items-table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th style="text-align:right;">Preço</th>
                                <th style="text-align:center;">Qtd</th>
                                <th style="text-align:right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itensHtml || '<tr><td colspan="4" style="text-align:center; color:#555;">Nenhum item adicionado</td></tr>'}
                        </tbody>
                    </table>

                    <div class="drawer-total-box">
                        <span>Total Pago:</span>
                        <strong>R$ ${calculatedSubtotal.toLocaleString('pt-BR', {minimumFractionDigits:2})}</strong>
                    </div>
                </div>

                <div class="drawer-footer-actions">
                    <button onclick="editOrder(${p.id_pedido})" class="btn-submit-drawer" style="flex:1;"><i class="fas fa-edit"></i> Editar Propriedades</button>
                    <button onclick="closeDrawer()" class="btn-secondary-drawer">Fechar</button>
                </div>
            `;

        } catch (e) {
            content.innerHTML = `<div class="drawer-section" style="text-align:center; color:#dc3545;"><i class="fas fa-times-circle" style="font-size:40px;"></i><p style="margin-top:15px;">Erro ao carregar detalhes.</p></div>`;
        }
    }

    // Controle inline de endereço
    function showInlineEditAddress(idPerfil, idUsuarios, dataCadastro, telefone, idPedido) {
        document.getElementById('viewAddressBlock').style.display = 'none';
        document.getElementById('inlineAddressForm').style.display = 'block';
    }

    function cancelInlineEditAddress() {
        document.getElementById('viewAddressBlock').style.display = 'block';
        document.getElementById('inlineAddressForm').style.display = 'none';
    }

    async function saveInlineAddress(e, idPerfil, idPedido) {
        e.preventDefault();
        const form = document.getElementById('inlineAddressForm');
        const formData = new FormData(form);

        try {
            const res = await fetch(`/backend/perfil/atualizar/${idPerfil}`, {
                method: 'POST',
                body: formData
            });

            // O endpoint redireciona. Se a resposta retornar sucesso na navegação, deu certo!
            showToast("Dados de entrega atualizados com sucesso!");
            
            // Recarregar os detalhes do drawer
            viewOrder(idPedido);
            
            // Atualizar instantaneamente a linha correspondente na listagem principal
            const row = document.getElementById(`row-${idPedido}`);
            if (row) {
                const addressVal = form.querySelector('[name="endereco_perfil"]').value;
                row.querySelector('.address-cell').textContent = addressVal;
            }
        } catch (err) {
            showToast("Erro ao salvar endereço de entrega.", "error");
        }
    }

    // --- FLUXO 2: EDITAR PROPRIEDADES DO PEDIDO ---
    async function editOrder(id) {
        openDrawer(`<i class="fas fa-pencil-alt"></i> Editar Pedido #${id}`);
        const content = document.getElementById('drawerContent');
        content.innerHTML = '<div style="text-align:center; padding:50px;"><i class="fas fa-circle-notch fa-spin" style="font-size:32px; color:var(--accent);"></i><p style="margin-top:15px; color:#888;">Carregando...</p></div>';

        try {
            const res = await fetch(`/backend/pedido/detalhes/${id}?json=1`);
            const data = await res.json();

            if (!data.success) {
                content.innerHTML = `<div class="drawer-section" style="text-align:center; color:#dc3545;"><i class="fas fa-times-circle" style="font-size:40px;"></i><p style="margin-top:15px;">${data.message}</p></div>`;
                return;
            }

            const p = data.pedido;
            // Tratar datetime-local
            const formattedDate = p.data_pedido.replace(' ', 'T');

            content.innerHTML = `
                <form id="editOrderPropertiesForm" onsubmit="submitEditOrderProperties(event, ${p.id_pedido})">
                    <input type="hidden" name="id_pedido" value="${p.id_pedido}">
                    
                    <div class="drawer-section">
                        <h4>Propriedades Gerais</h4>
                        
                        <div class="form-group">
                            <label>Data &amp; Hora</label>
                            <input type="datetime-local" name="data_pedido" class="form-control" value="${formattedDate}" required>
                        </div>

                        <div class="form-group">
                            <label>Valor Total (R$)</label>
                            <input type="number" name="total_pedido" class="form-control" step="0.01" value="${parseFloat(p.total_pedido)}" required>
                        </div>

                        <div class="form-group">
                            <label>Status do Pedido</label>
                            <select name="status_pedido" class="form-control" required>
                                <option value="pendente" ${(!p.status_pedido || p.status_pedido.trim() === '' || p.status_pedido.toLowerCase() === 'pendente') ? 'selected' : ''}>Pendente</option>
                                <option value="preparação" ${p.status_pedido && p.status_pedido.toLowerCase() === 'preparação' ? 'selected' : ''}>Em Preparação</option>
                                <option value="processamento" ${p.status_pedido && p.status_pedido.toLowerCase() === 'processamento' ? 'selected' : ''}>Processamento</option>
                                <option value="enviado" ${p.status_pedido && p.status_pedido.toLowerCase() === 'enviado' ? 'selected' : ''}>Enviado</option>
                                <option value="concluido" ${p.status_pedido && p.status_pedido.toLowerCase() === 'concluido' ? 'selected' : ''}>Concluído</option>
                                <option value="cancelado" ${p.status_pedido && p.status_pedido.toLowerCase() === 'cancelado' ? 'selected' : ''}>Cancelado</option>
                            </select>
                        </div>
                    </div>

                    <div class="drawer-footer-actions">
                        <button type="submit" class="btn-submit-drawer" style="flex:1;"><i class="fas fa-save"></i> Salvar Propriedades</button>
                        <button type="button" onclick="viewOrder(${p.id_pedido})" class="btn-secondary-drawer">Cancelar</button>
                    </div>
                </form>
            `;
        } catch (e) {
            content.innerHTML = `<div class="drawer-section" style="text-align:center; color:#dc3545;"><i class="fas fa-times-circle" style="font-size:40px;"></i><p style="margin-top:15px;">Erro ao carregar dados do formulário.</p></div>`;
        }
    }

    async function submitEditOrderProperties(e, id) {
        e.preventDefault();
        const form = document.getElementById('editOrderPropertiesForm');
        
        const total = form.querySelector('[name="total_pedido"]').value;
        const dateVal = form.querySelector('[name="data_pedido"]').value;
        const status = form.querySelector('[name="status_pedido"]').value;

        try {
            const res = await fetch(`/backend/pedido/atualizar/${id}?json=1`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_pedido: id,
                    total_pedido: total,
                    data_pedido: dateVal,
                    status_pedido: status
                })
            });
            const data = await res.json();

            if (data.success) {
                showToast("Propriedades do pedido atualizadas!");
                closeDrawer();

                // Atualizar dinamicamente a listagem principal
                const row = document.getElementById(`row-${id}`);
                if (row) {
                    // Preço formatado
                    row.querySelector('.price-column').textContent = `R$ ${parseFloat(total).toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
                    
                    // Atualizar Badge de Status
                    const statusCell = row.querySelector('.status-cell');
                    let badgeClass = 'status-pendente';
                    if (['pago', 'concluido'].includes(status)) badgeClass = 'status-pago';
                    else if (status === 'enviado') badgeClass = 'status-enviado';
                    else if (['preparação', 'processamento'].includes(status)) badgeClass = 'status-preparacao';
                    else if (status === 'cancelado') badgeClass = 'status-cancelado';

                    statusCell.innerHTML = `<span class="badge-status ${badgeClass}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
                    
                    // Atualizar atributos de busca/filtro da linha
                    row.setAttribute('data-status', status.toLowerCase());

                    // Atualizar botões rápidos de status
                    updateQuickActionsUI(row, id, status.toLowerCase());
                }

                recalculateKPIs();
                applyFiltersAndPagination();
            } else {
                showToast(data.message, "error");
            }
        } catch (err) {
            showToast("Erro ao processar atualização.", "error");
        }
    }

    // Helper para redesenhar botões rápidos de status na linha
    function updateQuickActionsUI(row, id, status) {
        const quickContainer = row.querySelector('.quick-status-container');
        if (!quickContainer) return;

        let btns = '';
        if (status === 'pendente' || status === 'pago' || status === 'concluido') {
            btns = `
                <button onclick="changeStatus(${id}, 'preparação')" class="btn-status-quick btn-status-prep" title="Preparar Pedido">
                    <i class="fas fa-box"></i> Prep
                </button>
            `;
        } else if (status === 'preparação' || status === 'processamento') {
            btns = `
                <button onclick="changeStatus(${id}, 'pendente')" class="btn-status-quick btn-status-undo" title="Voltar para Pendente">
                    <i class="fas fa-undo"></i>
                </button>
                <button onclick="changeStatus(${id}, 'enviado')" class="btn-status-quick btn-status-ship" title="Despachar Pedido">
                    <i class="fas fa-truck"></i> Enviar
                </button>
            `;
        } else if (status === 'enviado') {
            btns = `
                <button onclick="changeStatus(${id}, 'preparação')" class="btn-status-quick btn-status-undo" title="Voltar para Preparação">
                    <i class="fas fa-undo"></i>
                </button>
            `;
        }
        quickContainer.innerHTML = btns;
    }

    // --- FLUXO 3: CRIAR NOVO PEDIDO DINÂMICO SPA ---
    function openCreateDrawer() {
        openDrawer('<i class="fas fa-plus-circle"></i> Novo Pedido');
        const content = document.getElementById('drawerContent');

        // Gerando opções de cliente
        let clientOptions = '<option value="">Selecione um Cliente...</option>';
        availableProfiles.forEach(p => {
            clientOptions += `<option value="${p.id_perfil}">${p.nome_usuarios} (${p.endereco_perfil || 'Sem endereço'})</option>`;
        });

        // Configurando datetime de hoje no fuso correto
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        const dateStr = now.toISOString().slice(0, 16);

        content.innerHTML = `
            <form id="createOrderForm" onsubmit="submitCreateOrder(event)">
                <div class="drawer-section">
                    <h4>Informações do Cliente</h4>
                    <div class="form-group">
                        <label>Cliente / Perfil</label>
                        <select name="id_perfil" class="form-control" required>
                            ${clientOptions}
                        </select>
                    </div>
                </div>

                <div class="drawer-section">
                    <h4>Propriedades do Pedido</h4>
                    <div class="grid-2col">
                        <div class="form-group">
                            <label>Data &amp; Hora</label>
                            <input type="datetime-local" name="data_pedido" class="form-control" value="${dateStr}" required>
                        </div>
                        <div class="form-group">
                            <label>Status Inicial</label>
                            <select name="status_pedido" class="form-control" required>
                                <option value="pendente" selected>Pendente</option>
                                <option value="preparação">Em Preparação</option>
                                <option value="pago">Pago</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="drawer-section">
                    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom: 1px solid #262626; padding-bottom: 8px; margin-bottom:15px;">
                        <h4 style="margin:0; border:none; padding:0;">Itens do Pedido</h4>
                        <button type="button" class="btn-create-order" onclick="addOrderItemRow()" style="padding:6px 12px; font-size:11px; border-radius:6px;">
                            <i class="fas fa-plus"></i> Item
                        </button>
                    </div>
                    
                    <table class="drawer-items-table" id="createOrderItemsTable">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th style="width: 80px; text-align:center;">Qtd</th>
                                <th style="width: 110px; text-align:right;">Preço</th>
                                <th style="width: 100px; text-align:right;">Total</th>
                                <th style="width: 40px; text-align:center;"></th>
                            </tr>
                        </thead>
                        <tbody id="createOrderItemsBody">
                            <!-- Inserção dinâmica de linhas -->
                        </tbody>
                    </table>

                    <div class="drawer-total-box">
                        <span>Total do Pedido:</span>
                        <strong id="createOrderTotalLabel">R$ 0,00</strong>
                        <input type="hidden" name="total_pedido" id="createOrderTotalInput" value="0.00">
                    </div>
                </div>

                <div class="drawer-footer-actions">
                    <button type="submit" class="btn-submit-drawer" style="flex:1;"><i class="fas fa-check-circle"></i> Fechar e Salvar Pedido</button>
                    <button type="button" onclick="closeDrawer()" class="btn-secondary-drawer">Cancelar</button>
                </div>
            </form>
        `;

        // Inicia adicionando uma linha vazia
        addOrderItemRow();
    }

    let itemIndex = 0;
    function addOrderItemRow() {
        const body = document.getElementById('createOrderItemsBody');
        const index = itemIndex++;

        let productOptions = '<option value="">Selecione...</option>';
        availableProducts.forEach(p => {
            productOptions += `<option value="${p.id_produto}" data-price="${p.preco_produtos}">${p.nome_produtos} (R$ ${parseFloat(p.preco_produtos).toFixed(2)})</option>`;
        });

        const row = document.createElement('tr');
        row.id = `item-row-${index}`;
        row.className = `order-item-form-row`;
        row.innerHTML = `
            <td>
                <select name="itens[${index}][id_produto]" class="form-control product-selector" required onchange="handleProductSelectChange(${index})">
                    ${productOptions}
                </select>
            </td>
            <td>
                <input type="number" name="itens[${index}][quantidade]" class="form-control quantity-input" min="1" value="1" required style="text-align:center; padding:8px;" oninput="recalculateOrderItemsTotal()">
            </td>
            <td>
                <input type="number" name="itens[${index}][preco_unitario]" class="form-control price-input" step="0.01" value="0.00" required style="text-align:right; padding:8px;" oninput="recalculateOrderItemsTotal()">
            </td>
            <td style="text-align:right; font-weight:700; color:var(--accent);" class="row-subtotal-label">
                R$ 0,00
            </td>
            <td style="text-align:center;">
                <button type="button" class="btn-remove-row" onclick="removeOrderItemRow(${index})">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;

        body.appendChild(row);
        recalculateOrderItemsTotal();
    }

    function removeOrderItemRow(index) {
        const row = document.getElementById(`item-row-${index}`);
        if (row) {
            row.remove();
            recalculateOrderItemsTotal();
        }
    }

    function handleProductSelectChange(index) {
        const row = document.getElementById(`item-row-${index}`);
        const selector = row.querySelector('.product-selector');
        const priceInput = row.querySelector('.price-input');
        
        const selectedOpt = selector.options[selector.selectedIndex];
        const basePrice = selectedOpt.getAttribute('data-price') || 0;
        
        priceInput.value = parseFloat(basePrice).toFixed(2);
        recalculateOrderItemsTotal();
    }

    function recalculateOrderItemsTotal() {
        const rows = document.querySelectorAll('.order-item-form-row');
        let orderTotal = 0;

        rows.forEach(row => {
            const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const subtotal = qty * price;
            
            row.querySelector('.row-subtotal-label').textContent = `R$ ${subtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
            orderTotal += subtotal;
        });

        document.getElementById('createOrderTotalLabel').textContent = `R$ ${orderTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
        document.getElementById('createOrderTotalInput').value = orderTotal.toFixed(2);
    }

    async function submitCreateOrder(e) {
        e.preventDefault();
        const form = document.getElementById('createOrderForm');
        
        // Convertendo o formulário para um objeto JSON estruturado
        const formData = new FormData(form);
        const data = {};
        
        formData.forEach((value, key) => {
            // Parser de arrays aninhados de itens (ex: itens[0][id_produto])
            if (key.includes('[')) {
                const match = key.match(/^(\w+)\[(\d+)\]\[(\w+)\]$/);
                if (match) {
                    const [_, arrayName, index, prop] = match;
                    if (!data[arrayName]) data[arrayName] = [];
                    if (!data[arrayName][index]) data[arrayName][index] = {};
                    data[arrayName][index][prop] = value;
                }
            } else {
                data[key] = value;
            }
        });

        // Filtrar possíveis slots vazios do array de itens
        if (data.itens) {
            data.itens = data.itens.filter(item => item !== null && item !== undefined);
        }

        try {
            const res = await fetch('/backend/pedido/salvar?json=1', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const response = await res.json();

            if (response.success) {
                showToast("Pedido cadastrado com sucesso!");
                closeDrawer();
                
                // Recarregar a página suavemente para atualizar a tabela inteira e estatísticas do banco
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showToast(response.message, "error");
            }
        } catch (err) {
            showToast("Erro ao criar o pedido.", "error");
        }
    }

    // --- FLUXO 4: ALTERAÇÃO RÁPIDA DE STATUS VIA BOTÕES DA LISTA ---
    async function changeStatus(id, newStatus) {
        try {
            const res = await fetch('/backend/pedido/mudar-status?json=1', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_pedido: id,
                    status: newStatus
                })
            });
            const data = await res.json();

            if (data.success) {
                showToast(`Pedido #${id} alterado para ${newStatus.toUpperCase()}`);
                
                // Atualizar dinamicamente os valores na linha da tabela
                const row = document.getElementById(`row-${id}`);
                if (row) {
                    row.setAttribute('data-status', newStatus.toLowerCase());
                    
                    const statusCell = row.querySelector('.status-cell');
                    let badgeClass = 'status-pendente';
                    if (['pago', 'concluido'].includes(newStatus)) badgeClass = 'status-pago';
                    else if (newStatus === 'enviado') badgeClass = 'status-enviado';
                    else if (['preparação', 'processamento'].includes(newStatus)) badgeClass = 'status-preparacao';
                    else if (newStatus === 'cancelado') badgeClass = 'status-cancelado';

                    statusCell.innerHTML = `<span class="badge-status ${badgeClass}">${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)}</span>`;

                    // Redesenhar os botões de ação rápida na linha
                    updateQuickActionsUI(row, id, newStatus.toLowerCase());
                }

                recalculateKPIs();
                applyFiltersAndPagination();
            } else {
                showToast(data.message, "error");
            }
        } catch (e) {
            showToast("Erro ao alterar o status do pedido.", "error");
        }
    }

    // --- FLUXO 5: EXCLUSÃO E REATIVAÇÃO ASSÍNCRONA (SOFT DELETE) ---
    async function deleteToggleOrder(id, shouldDelete) {
        const confirmMsg = shouldDelete 
            ? "Tem certeza de que deseja mover este pedido para a lixeira (exclusão lógica)?" 
            : "Deseja restaurar e reativar este pedido na listagem?";
            
        if (!confirm(confirmMsg)) return;

        const endpoint = shouldDelete ? '/backend/pedido/deletar?json=1' : '/backend/pedido/ativar?json=1';

        try {
            const res = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_pedido: id })
            });
            const data = await res.json();

            if (data.success) {
                showToast(shouldDelete ? `Pedido #${id} excluído com sucesso!` : `Pedido #${id} reativado!`);

                const row = document.getElementById(`row-${id}`);
                if (row) {
                    if (shouldDelete) {
                        row.classList.add('tr-deleted');
                        row.setAttribute('data-deleted', 'true');
                        row.querySelector('.badge-deleted-placeholder').innerHTML = '<span class="badge-deleted">EXCLUÍDO</span>';
                        
                        // Atualizar botões
                        row.querySelector('.active-actions-wrapper').style.display = 'none';
                        row.querySelector('.deleted-actions-wrapper').style.display = '';
                    } else {
                        row.classList.remove('tr-deleted');
                        row.setAttribute('data-deleted', 'false');
                        row.querySelector('.badge-deleted-placeholder').innerHTML = '';

                        // Atualizar botões
                        row.querySelector('.active-actions-wrapper').style.display = '';
                        row.querySelector('.deleted-actions-wrapper').style.display = 'none';
                    }
                }

                recalculateKPIs();
                applyFiltersAndPagination();
            } else {
                showToast(data.message, "error");
            }
        } catch (e) {
            showToast("Erro de comunicação ao alterar status.", "error");
        }
    }
</script>