<?php
/** @var array $clientes */
/** @var array $config */
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* ===== DESIGN SYSTEM KOKETSU COMUNICADOS ===== */
    :root {
        --bg-main:    #0f0f0f;
        --bg-card:    #1a1a1a;
        --border-color: #222222;
        --text-main:  #ffffff;
        --text-muted: #888888;

        --accent:     #F2C84B;
        --accent-glow: rgba(242,200,75,0.25);
        --accent-dim:  rgba(242,200,75,0.07);

        --copper:     #C47A3A;
        --copper-glow: rgba(196,122,58,0.25);
        --copper-dim:  rgba(196,122,58,0.08);

        --steel:      #4E9EBF;
        --steel-glow: rgba(78,158,191,0.22);
        --steel-dim:  rgba(78,158,191,0.07);

        --amethyst:   #8B5CF6;
        --amethyst-glow: rgba(139,92,246,0.22);
        --amethyst-dim:  rgba(139,92,246,0.07);

        --success:    #51cf66;
        --danger:     #dc3545;

        --shadow-sm:  0 4px 10px rgba(0,0,0,0.3);
        --shadow-md:  0 8px 25px rgba(0,0,0,0.5);
    }

    body { background-color: var(--bg-main) !important; margin:0; font-family:Arial,sans-serif; color:var(--text-main); }

    .page-wrapper {
        padding:30px; width:100%; box-sizing:border-box;
        background-color:var(--bg-main) !important; min-height:100vh; color:var(--text-main);
        font-family:Arial,sans-serif;
    }

    /* ===== TITLE ===== */
    .page-title {
        font-size:28px; font-weight:800; margin-bottom:5px;
        color:var(--text-main); text-transform:uppercase; letter-spacing:-0.5px;
        display:flex; align-items:center; gap:12px;
    }
    .page-title i { color:var(--accent); text-shadow:0 0 10px var(--accent-glow); }
    .title-badge {
        background:var(--accent-dim); border:1px solid rgba(242,200,75,0.2);
        color:var(--accent); font-size:13px; font-weight:700;
        padding:4px 12px; border-radius:20px; letter-spacing:0;
    }
    .header-breadcrumb {
        color:var(--text-muted); margin-bottom:30px; padding-bottom:12px;
        border-bottom:1px solid var(--border-color); font-size:14px;
    }

    /* ===== STAT CARDS ===== */
    .dashboard-grid {
        display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
        gap:20px; margin-bottom:35px;
    }
    .stat-card {
        background:linear-gradient(135deg,#1a1a1a,#0f0f0f);
        border:2px solid var(--border-color); border-radius:16px; padding:24px;
        display:flex; align-items:center; justify-content:space-between;
        transition:all 0.3s cubic-bezier(0.25,0.8,0.25,1);
        box-shadow:var(--shadow-sm); position:relative; overflow:hidden;
    }
    .stat-card::before {
        content:''; position:absolute; top:0; left:0; width:100%; height:3px; transition:0.3s;
    }
    .stat-card.card-inscritos::before  { background:var(--accent); }
    .stat-card.card-mes::before        { background:var(--steel); }
    .stat-card.card-ultima::before     { background:var(--amethyst); }
    .stat-card.card-export::before     { background:var(--success); }

    .stat-card:hover { transform:translateY(-5px); box-shadow:var(--shadow-md); }
    .stat-card.card-inscritos:hover  { border-color:var(--accent); }
    .stat-card.card-mes:hover        { border-color:var(--steel); }
    .stat-card.card-ultima:hover     { border-color:var(--amethyst); }
    .stat-card.card-export:hover     { border-color:var(--success); }

    .stat-icon {
        font-size:26px; width:50px; height:50px;
        display:flex; align-items:center; justify-content:center;
        border-radius:12px; transition:0.3s;
    }
    .card-inscritos .stat-icon { color:var(--accent);    background:var(--accent-dim); }
    .card-mes       .stat-icon { color:var(--steel);     background:var(--steel-dim); }
    .card-ultima    .stat-icon { color:var(--amethyst);  background:var(--amethyst-dim); }
    .card-export    .stat-icon { color:var(--success);   background:rgba(81,207,102,0.08); }
    .stat-card:hover .stat-icon { transform:scale(1.1) rotate(5deg); }

    .stat-info h3 { margin:0 0 4px; font-size:32px; color:var(--text-main); font-weight:800; letter-spacing:-1px; }
    .stat-info p  { margin:0; color:var(--text-muted); text-transform:uppercase; font-size:11px; letter-spacing:1px; font-weight:700; }

    /* ===== ACTIONS BAR ===== */
    .actions-bar {
        background:linear-gradient(135deg,#141414,#0d0d0d);
        border:1px solid var(--border-color); border-radius:16px; padding:20px;
        margin-bottom:25px; display:flex; justify-content:space-between;
        align-items:center; gap:20px; flex-wrap:wrap;
    }
    .filters-wrapper { display:flex; gap:12px; flex-wrap:wrap; align-items:center; }
    .filter-label { font-size:11px; color:var(--text-muted); text-transform:uppercase; font-weight:700; letter-spacing:1px; margin-right:5px; }

    .search-input {
        background:#0f0f0f; border:2px solid var(--border-color);
        padding:12px 16px 12px 46px; border-radius:10px; color:var(--text-main);
        font-size:14px; transition:all 0.3s ease; outline:none;
        font-family:Arial,sans-serif;
    }
    .search-input:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-glow); }

    .pill-button {
        background:transparent; border:1px solid var(--border-color); color:var(--text-muted);
        padding:8px 16px; border-radius:20px; font-size:12px; font-weight:700;
        cursor:pointer; transition:all 0.2s ease; display:flex; align-items:center; gap:5px;
        font-family:Arial,sans-serif;
    }
    .pill-button:hover { border-color:var(--accent); color:var(--accent); box-shadow:0 2px 8px var(--accent-glow); }
    .pill-button.active { background:var(--accent); border-color:var(--accent); color:#000; box-shadow:0 4px 12px var(--accent-glow); }

    .btn-main-action {
        display:inline-flex; align-items:center; gap:8px;
        background:linear-gradient(135deg,var(--accent),#d4a800);
        color:#000 !important; padding:12px 22px; border-radius:10px; font-weight:700;
        text-transform:uppercase; font-size:12px; text-decoration:none; letter-spacing:0.5px;
        transition:all 0.2s ease; border:none; cursor:pointer; font-family:Arial,sans-serif;
        box-shadow:0 4px 12px var(--accent-glow);
    }
    .btn-main-action:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(242,200,75,0.4); }
    .btn-main-action:disabled { opacity:0.6; cursor:not-allowed; transform:none; }

    /* ===== BULK BAR ===== */
    .bulk-bar {
        background:rgba(242,200,75,0.06); border:1px solid rgba(242,200,75,0.2);
        border-radius:10px; padding:14px 20px; margin-bottom:18px;
        display:none; align-items:center; gap:16px; flex-wrap:wrap;
        animation:slideIn 0.25s ease;
    }
    .bulk-bar.visible { display:flex; }
    @keyframes slideIn { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }
    .bulk-info { color:var(--accent); font-weight:700; font-size:14px; }

    /* ===== TABLE ===== */
    .table-container {
        background:linear-gradient(135deg,#141414,#0d0d0d);
        border:1px solid var(--border-color); border-radius:16px;
        padding:10px; overflow-x:auto; box-shadow:var(--shadow-sm);
    }
    .newsletter-table { width:100%; border-collapse:separate; border-spacing:0; color:var(--text-main); }
    .newsletter-table thead th {
        color:var(--accent) !important; background-color:var(--bg-card);
        text-transform:uppercase; font-size:11px; padding:16px;
        letter-spacing:1px; font-weight:800; border-bottom:2px solid var(--border-color);
        text-align:left; cursor:pointer; user-select:none; transition:color 0.2s; white-space:nowrap;
    }
    .newsletter-table thead th:first-child { border-radius:10px 0 0 0; cursor:default; }
    .newsletter-table thead th:last-child  { border-radius:0 10px 0 0; cursor:default; }
    .newsletter-table thead th:hover:not(:first-child):not(:last-child) { color:#fff !important; }
    .newsletter-table thead th .sort-icon { margin-left:6px; opacity:0.35; font-size:9px; }
    .newsletter-table thead th.sort-asc .sort-icon,
    .newsletter-table thead th.sort-desc .sort-icon { opacity:1; color:var(--accent); }
    .newsletter-table tbody tr { background:transparent; transition:all 0.2s ease; }
    .newsletter-table tbody tr:not(:last-child) td { border-bottom:1px solid var(--border-color); }
    .newsletter-table tbody tr:hover { background:rgba(242,200,75,0.03); }
    .newsletter-table td { padding:16px !important; vertical-align:middle; font-size:14px; }

    .row-checkbox { width:16px; height:16px; accent-color:var(--accent); cursor:pointer; }

    /* Status Badge */
    .status-badge {
        display:inline-flex; align-items:center; gap:6px; padding:5px 11px;
        border-radius:20px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;
    }
    .status-badge.active {
        background:rgba(81,207,102,0.08); color:var(--success);
        border:1px solid rgba(81,207,102,0.2);
    }

    .btn-select-small {
        background:var(--steel-dim); border:1px solid var(--steel-glow); color:var(--steel);
        padding:8px 14px; border-radius:8px; font-size:11px; font-weight:700;
        text-transform:uppercase; letter-spacing:0.5px; cursor:pointer; transition:all 0.2s ease;
        display:inline-flex; align-items:center; gap:6px; font-family:Arial,sans-serif;
    }
    .btn-select-small:hover { background:rgba(78,158,191,0.15); border-color:var(--steel); }

    /* Zero State */
    .zero-state-container { text-align:center; padding:60px 20px !important; color:var(--text-muted); }
    .zero-state-container i { font-size:48px; color:#333; display:block; margin-bottom:15px; }

    /* ===== PAGINATION ===== */
    .pagination-container {
        display:flex; justify-content:space-between; align-items:center;
        margin-top:25px; padding:16px 20px;
        background:linear-gradient(135deg,#141414,#0d0d0d);
        border-radius:12px; border:1px solid var(--border-color); flex-wrap:wrap; gap:15px;
    }
    .pagination-info { color:var(--text-muted); font-size:13px; font-weight:600; }
    .pagination-buttons { display:flex; align-items:center; gap:8px; }
    .page-link {
        padding:8px 14px; background:#0f0f0f; border:1px solid var(--border-color);
        color:var(--text-main); border-radius:8px; cursor:pointer; font-weight:700;
        font-size:12px; transition:all 0.2s ease; display:flex; align-items:center; justify-content:center;
    }
    .page-link:hover:not(.disabled) { border-color:var(--accent); color:var(--accent); box-shadow:0 2px 8px var(--accent-glow); }
    .page-link.active { background:var(--accent); color:#000; border-color:var(--accent); box-shadow:0 4px 10px var(--accent-glow); }
    .page-link.disabled { opacity:0.25; cursor:not-allowed; }

    /* ===== SPLIT LAYOUT ===== */
    .split-layout { display:grid; grid-template-columns:1fr 1fr; gap:28px; margin-top:40px; align-items:start; }
    @media (max-width:1100px) { .split-layout { grid-template-columns:1fr; } }

    /* ===== FORM ===== */
    .promo-form-container {
        background:linear-gradient(135deg,#141414,#0d0d0d);
        border:1px solid var(--border-color); border-radius:16px; padding:30px;
        box-shadow:var(--shadow-sm);
    }
    .promo-form-container h4 {
        color:var(--accent); text-transform:uppercase; font-weight:800;
        margin-top:0; margin-bottom:8px; font-size:18px; letter-spacing:-0.5px;
        display:flex; align-items:center; gap:10px;
    }
    .form-group { margin-bottom:20px; }
    .form-group label { display:block; color:var(--text-muted); font-size:11px; font-weight:700; text-transform:uppercase; margin-bottom:8px; letter-spacing:0.5px; }
    .form-control-k {
        width:100%; background:#0f0f0f; border:2px solid var(--border-color);
        border-radius:10px; padding:12px 16px; color:var(--text-main); outline:none;
        font-family:Arial,sans-serif; font-size:14px; transition:all 0.3s ease; box-sizing:border-box;
    }
    .form-control-k:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-glow); }
    textarea.form-control-k { min-height:140px; resize:vertical; }
    .char-counter-wrapper { display:flex; justify-content:flex-end; font-size:11px; color:var(--text-muted); margin-top:5px; font-weight:600; }
    .char-counter-wrapper.warning { color:var(--danger); }

    /* ===== LIVE PREVIEW PANEL ===== */
    .live-preview-panel {
        background:linear-gradient(135deg,#141414,#0d0d0d);
        border:1px solid var(--border-color); border-radius:16px; padding:24px;
        box-shadow:var(--shadow-sm); position:sticky; top:100px;
    }
    .live-preview-panel h4 {
        color:var(--accent); text-transform:uppercase; font-weight:800;
        margin-top:0; margin-bottom:16px; font-size:16px; letter-spacing:-0.5px;
        display:flex; align-items:center; gap:10px;
    }
    .live-dot {
        width:8px; height:8px; border-radius:50%; background:var(--success);
        box-shadow:0 0 8px rgba(81,207,102,0.4); animation:pulse 2s infinite;
        display:inline-block; flex-shrink:0;
    }
    @keyframes pulse { 0%,100%{opacity:1}50%{opacity:0.4} }

    /* Mockup */
    .newsletter-mockup { background:#000; border:1px solid #1a1a1a; border-radius:10px; overflow:hidden; font-family:Arial,sans-serif; }
    .mockup-header { background:#000; padding:20px; text-align:center; border-bottom:2px solid #F2C84B; }
    .mockup-header-logo { height:44px; width:auto; display:block; margin:0 auto 8px; }
    .mockup-header-text { font-size:9px; letter-spacing:3px; text-transform:uppercase; color:#F2C84B; font-weight:600; margin:0; }
    .mockup-banner { width:100%; max-height:260px; object-fit:cover; display:none; }
    .mockup-content { padding:24px 20px; text-align:center; }
    .mockup-badge { display:inline-block; background:linear-gradient(135deg,#F2C84B,#d4a800); color:#000; font-size:8px; font-weight:800; letter-spacing:2px; text-transform:uppercase; padding:4px 14px; border-radius:100px; margin-bottom:16px; }
    .mockup-divider { width:50px; height:2px; background:linear-gradient(90deg,transparent,#F2C84B,transparent); margin:0 auto 16px; border:none; }
    .mockup-subject { font-size:18px; font-weight:800; color:#F2C84B; text-transform:uppercase; letter-spacing:1.5px; margin:0 0 12px; line-height:1.2; }
    .mockup-text { font-size:13px; color:#ccc; line-height:1.7; margin:0 0 20px; white-space:pre-line; }
    .mockup-btn { display:inline-block; background:linear-gradient(135deg,#F2C84B,#d4a800); color:#000; font-weight:800; font-size:11px; letter-spacing:2px; text-transform:uppercase; padding:12px 28px; border-radius:4px; text-decoration:none; }
    .mockup-footer { background:#000; padding:16px 20px; text-align:center; font-size:10px; color:#555; border-top:1px solid #1a1a1a; }
    .mockup-footer a { color:#F2C84B; text-decoration:none; }
    .mockup-footer-brand { font-size:10px; font-weight:800; letter-spacing:2px; text-transform:uppercase; color:#F2C84B; margin:0 0 8px; }

    /* ===== MODAIS ===== */
    .modal-overlay {
        position:fixed; top:0; left:0; right:0; bottom:0;
        background:rgba(0,0,0,0.85); backdrop-filter:blur(6px);
        z-index:3000; display:flex; align-items:center; justify-content:center;
        opacity:0; pointer-events:none; transition:opacity 0.3s ease;
    }
    .modal-overlay.active { opacity:1; pointer-events:auto; }
    .modal-card {
        background:linear-gradient(135deg,#1a1a1a,#0f0f0f);
        border:2px solid var(--border-color); border-radius:16px;
        width:90%; max-width:480px; box-shadow:0 24px 60px rgba(0,0,0,0.9);
        transform:translateY(30px) scale(0.97); transition:transform 0.3s cubic-bezier(.25,.8,.25,1);
        overflow:hidden;
    }
    .modal-overlay.active .modal-card { transform:translateY(0) scale(1); }
    .modal-header { padding:20px 24px; border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center; }
    .modal-header h4 { margin:0; color:var(--accent); font-weight:800; text-transform:uppercase; font-size:15px; letter-spacing:0.5px; display:flex; align-items:center; gap:8px; }
    .modal-body { padding:24px; }
    .modal-body p { color:var(--text-muted); font-size:14px; line-height:1.7; margin:0 0 6px; }
    .modal-body strong { color:var(--text-main); }
    .modal-footer { padding:16px 24px; border-top:1px solid var(--border-color); display:flex; justify-content:flex-end; gap:12px; }
    .btn-modal-cancel { background:transparent; border:1px solid var(--border-color); color:var(--text-muted); padding:10px 20px; border-radius:8px; cursor:pointer; font-size:13px; font-weight:700; text-transform:uppercase; transition:all 0.2s; font-family:Arial,sans-serif; }
    .btn-modal-cancel:hover { border-color:#fff; color:#fff; }
    .btn-modal-confirm { padding:10px 24px; border-radius:8px; cursor:pointer; font-size:13px; font-weight:800; text-transform:uppercase; transition:all 0.2s; font-family:Arial,sans-serif; border:none; }
    .btn-modal-confirm.gold { background:linear-gradient(135deg,var(--accent),#d4a800); color:#000; box-shadow:0 4px 12px var(--accent-glow); }
    .btn-modal-confirm.gold:hover { box-shadow:0 6px 18px rgba(242,200,75,0.5); transform:translateY(-1px); }
    .modal-close { background:transparent; border:none; color:var(--text-muted); font-size:18px; cursor:pointer; transition:0.2s; }
    .modal-close:hover { color:var(--accent); }

    /* ===== SPINNER ===== */
    @keyframes spin { to{ transform:rotate(360deg); } }
    .spinner { display:inline-block; width:14px; height:14px; border:2px solid rgba(0,0,0,0.3); border-top-color:#000; border-radius:50%; animation:spin 0.7s linear infinite; }
</style>

<?php
// Calcular stats dinâmicos para clientes ativos
$totalClientes = count($clientes);
$clientesMes = 0;
$ultimoCadastro = null;
$agora = new DateTime();
foreach ($clientes as $c) {
    $rawDate = $c['criado_em'] ?? null;
    if (!$rawDate) continue;
    try {
        $data = new DateTime($rawDate);
        if ($data->format('Y-m') === $agora->format('Y-m')) $clientesMes++;
        if (!$ultimoCadastro || $data > $ultimoCadastro) $ultimoCadastro = $data;
    } catch (Exception $e) { continue; }
}
$ultimoCadastroTexto = $ultimoCadastro ? $ultimoCadastro->diff($agora)->days . 'd atrás' : 'N/A';
if ($ultimoCadastro && $ultimoCadastro->diff($agora)->days === 0) $ultimoCadastroTexto = 'hoje';
?>

<div class="page-wrapper">
    <h3 class="page-title">
        <i class="fas fa-envelope-open-text"></i>
        Gestão de Comunicados (Clientes Ativos)
        <span class="title-badge"><?= $totalClientes ?> clientes ativos</span>
    </h3>
    <header class="header-breadcrumb">
        <span><i class="fas fa-tachometer-alt"></i> Painel de Controle — Koketsu</span>
    </header>

    <!-- ===== STATS ===== -->
    <div class="dashboard-grid">
        <div class="stat-card card-inscritos">
            <div class="stat-info">
                <h3 id="statActive"><?= $totalClientes ?></h3>
                <p>Clientes Ativos</p>
            </div>
            <div class="stat-icon"><i class="fas fa-user-check"></i></div>
        </div>
        <div class="stat-card card-mes">
            <div class="stat-info">
                <h3><?= $clientesMes ?></h3>
                <p>Novos este mês</p>
            </div>
            <div class="stat-icon"><i class="fas fa-calendar-plus"></i></div>
        </div>
        <div class="stat-card card-ultima">
            <div class="stat-info">
                <h3 style="font-size:22px;"><?= $ultimoCadastroTexto ?></h3>
                <p>Último Cadastro</p>
            </div>
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
        </div>
        <div class="stat-card card-export">
            <div class="stat-info">
                <h3>CSV</h3>
                <p>Pronto para Exportar</p>
            </div>
            <div class="stat-icon"><i class="fas fa-file-export"></i></div>
        </div>
    </div>

    <!-- ===== ACTIONS BAR ===== -->
    <div class="actions-bar">
        <div class="filters-wrapper">
            <div style="position:relative;width:300px;">
                <i class="fas fa-search" style="position:absolute;left:15px;top:50%;transform:translateY(-50%);color:var(--accent);font-size:15px;"></i>
                <input type="text" id="newsInput" oninput="applyCombinedFilters()" placeholder="Buscar por nome ou e-mail..." class="search-input">
            </div>
            <span class="filter-label">Período:</span>
            <button class="pill-button active" onclick="setDateFilter(this,'all')">Todos</button>
            <button class="pill-button" onclick="setDateFilter(this,'today')">Hoje</button>
            <button class="pill-button" onclick="setDateFilter(this,'week')">7 dias</button>
            <button class="pill-button" onclick="setDateFilter(this,'month')">30 dias</button>
        </div>
        <a href="/backend/admin/newsletter/exportar" class="btn-main-action">
            <i class="fas fa-download"></i> Exportar CSV
        </a>
    </div>

    <!-- ===== BULK BAR ===== -->
    <div class="bulk-bar" id="bulkBar">
        <span class="bulk-info"><span id="bulkCount">0</span> e-mail(s) selecionado(s)</span>
        <button class="btn-main-action" style="padding:9px 18px;font-size:11px;" onclick="abrirModalEnvioMassa()">
            <i class="fas fa-paper-plane"></i> Enviar para Selecionados
        </button>
        <button class="pill-button" onclick="desmarcarTodos()">
            <i class="fas fa-times"></i> Cancelar
        </button>
    </div>

    <!-- ===== TABELA ===== -->
    <main>
        <div class="table-container">
            <table class="newsletter-table" id="newsTable">
                <thead>
                    <tr>
                        <th style="width:44px;">
                            <input type="checkbox" class="row-checkbox" id="checkAll" onchange="toggleCheckAll(this)" title="Selecionar todos">
                        </th>
                        <th style="width:70px;" onclick="sortTable('id')">
                            ID <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th onclick="sortTable('nome')">
                            Nome do Cliente <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th onclick="sortTable('email')">
                            E-mail do Cliente <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th onclick="sortTable('date')">
                            Data do Cadastro <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th>Status</th>
                        <th style="text-align:center;width:150px;">Ações</th>
                    </tr>
                </thead>
                <tbody id="newsTableBody">
                    <?php foreach ($clientes as $c): ?>
                    <tr class="news-row"
                        data-date="<?= $c['criado_em'] ?? '' ?>"
                        data-nome="<?= strtolower(htmlspecialchars($c['nome_usuarios'])) ?>"
                        data-email="<?= strtolower(htmlspecialchars($c['email_usuarios'])) ?>"
                        data-id="<?= $c['id_usuarios'] ?>">
                        <td>
                            <input type="checkbox" class="row-checkbox row-check"
                                   value="<?= htmlspecialchars($c['email_usuarios']) ?>"
                                   onchange="onRowCheckChange()">
                        </td>
                        <td style="font-family:monospace;color:#888;">#<?= $c['id_usuarios'] ?></td>
                        <td class="nome-text" style="font-weight:600;"><?= htmlspecialchars($c['nome_usuarios']) ?></td>
                        <td class="email-text" style="color:var(--accent); font-weight:600;"><?= htmlspecialchars($c['email_usuarios']) ?></td>
                        <td style="color:var(--text-muted);"><?= ($c['criado_em'] ?? null) ? date('d/m/Y H:i', strtotime($c['criado_em'])) : '—' ?></td>
                        <td>
                            <span class="status-badge active">
                                <i class="fas fa-circle" style="font-size:6px;"></i> Ativo
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <button type="button" class="btn-select-small" title="Direcionar e-mail para o formulário abaixo"
                                    onclick="selecionarDestinatario('<?= htmlspecialchars($c['email_usuarios']) ?>')">
                                <i class="fas fa-arrow-down"></i> Selecionar
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <tr id="zeroStateRow" style="display:none;">
                        <td colspan="7" class="zero-state-container">
                            <i class="fas fa-search-minus"></i>
                            Nenhum cliente encontrado com este filtro.
                        </td>
                    </tr>
                    <?php if (empty($clientes)): ?>
                    <tr id="emptyDbRow">
                        <td colspan="7" class="zero-state-container">
                            <i class="fas fa-user-slash"></i>
                            Nenhum cliente ativo capturado ainda.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="pagination-container" id="paginationContainer">
            <div class="pagination-info" id="paginationInfo">Carregando...</div>
            <div class="pagination-buttons" id="paginationButtons"></div>
        </div>

        <!-- ===== SPLIT LAYOUT: FORM + LIVE PREVIEW ===== -->
        <div class="split-layout">

            <!-- FORMULÁRIO -->
            <div class="promo-form-container">
                <h4><i class="fas fa-paper-plane"></i> Enviar Promoção / Novidade</h4>
                <p id="destinatarioInfo" style="color:var(--text-muted);font-size:13px;margin-bottom:25px;">
                    Esta ferramenta enviará o e-mail para <strong>todos os <?= $totalClientes ?></strong> clientes ativos.
                </p>

                <form id="disparoForm" action="/backend/admin/newsletter/enviar" method="POST" enctype="multipart/form-data" onsubmit="return onFormSubmit(event)">

                    <div class="form-group">
                        <label>Destinatário (Enviar para)</label>
                        <select name="destinatario" id="destinatarioSelect" class="form-control-k" onchange="alternarDestinatario(this)">
                            <option value="todos">Todos os Clientes (<?= $totalClientes ?>)</option>
                            <option value="custom">✏ E-mail Personalizado / Manual...</option>
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?= htmlspecialchars($c['email_usuarios']) ?>"><?= htmlspecialchars($c['nome_usuarios']) ?> &lt;<?= htmlspecialchars($c['email_usuarios']) ?>&gt;</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group" id="customEmailGroup" style="display:none;">
                        <label>E-mail do Destinatário</label>
                        <input type="email" name="email_personalizado" id="customEmailInput" class="form-control-k" placeholder="Ex: cliente@outlook.com">
                    </div>

                    <div class="form-group">
                        <label>Assunto do E-mail</label>
                        <input type="text" name="assunto" id="formAssunto" class="form-control-k"
                               placeholder="Ex: Nova Coleção Koketsu Streetwear 🚀" required oninput="updatePreview()">
                    </div>

                    <div class="form-group">
                        <label>Banner da Promoção <span style="color:#555;font-size:10px;">(Opcional — 600×400px, máx. 2MB)</span></label>
                        <input type="file" name="imagem_promo" id="formBanner" class="form-control-k" accept="image/*" onchange="updateBanner()">
                    </div>

                    <div class="form-group">
                        <label>Conteúdo da Mensagem</label>
                        <textarea name="mensagem" id="formMensagem" class="form-control-k"
                                  placeholder="Escreva a novidade para seus clientes..." maxlength="1000"
                                  onkeyup="countChars(this);updatePreview()" required></textarea>
                        <div class="char-counter-wrapper" id="counterWrapper">
                            <span id="charCount">0</span> / 1000 caracteres
                        </div>
                    </div>

                    <div style="display:flex;justify-content:flex-end;">
                        <button type="submit" id="submitDisparoBtn" class="btn-main-action">
                            <i class="fas fa-rocket"></i> <span id="submitLabel">Disparar para Todos</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- LIVE PREVIEW -->
            <div class="live-preview-panel">
                <h4>
                    <span class="live-dot"></span>
                    Preview ao Vivo
                </h4>
                <div class="newsletter-mockup">
                    <div class="mockup-header">
                        <img src="/frontend/assets/img/logo2026.png" alt="Koketsu Grife" class="mockup-header-logo"
                             onerror="this.style.display='none';document.getElementById('mockupFallbackTitle').style.display='block'">
                        <h1 id="mockupFallbackTitle" style="color:#F2C84B;margin:0 0 6px;font-size:22px;font-style:italic;font-weight:900;display:none;">KOKETSU</h1>
                        <p class="mockup-header-text">Premium Streetwear &nbsp;•&nbsp; Exclusividade</p>
                    </div>

                    <img id="mockupBanner" class="mockup-banner" src="" alt="Banner">

                    <div class="mockup-content">
                        <div class="mockup-badge">✦ &nbsp; Novidades & Lançamentos &nbsp; ✦</div>
                        <hr class="mockup-divider">
                        <h2 id="mockupSubject" class="mockup-subject">Assunto do e-mail</h2>
                        <div id="mockupText" class="mockup-text">Escreva sua mensagem no formulário ao lado...</div>
                        <a href="#" class="mockup-btn">Ver Coleção Completa</a>
                    </div>

                    <div class="mockup-footer">
                        <p class="mockup-footer-brand">Koketsu Grife</p>
                        <a href="#">Instagram</a> &nbsp;|&nbsp;
                        <a href="#">Facebook</a> &nbsp;|&nbsp;
                        <a href="#">WhatsApp</a><br><br>
                        Você recebeu este e-mail porque faz parte da base de clientes da Koketsu Grife.<br><br>
                        © <?= date('Y') ?> Koketsu Grife. Todos os direitos reservados.
                    </div>
                </div>
            </div>

        </div><!-- /split-layout -->
    </main>
</div>

<!-- ===== MODAL: DISPARO MASSA ===== -->
<div class="modal-overlay" id="modalEnvioMassa">
    <div class="modal-card">
        <div class="modal-header">
            <h4><i class="fas fa-paper-plane"></i> Confirmar Envio</h4>
            <button class="modal-close" onclick="fecharModal('modalEnvioMassa')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p id="modalEnvioMassaTexto">Confirma o disparo do e-mail?</p>
        </div>
        <div class="modal-footer">
            <button class="btn-modal-cancel" onclick="fecharModal('modalEnvioMassa')">Cancelar</button>
            <button class="btn-modal-confirm gold" onclick="confirmarEnvio()">
                <i class="fas fa-rocket"></i> Confirmar Disparo
            </button>
        </div>
    </div>
</div>

<!-- ===== MODAL: DISPARO DO FORMULÁRIO ===== -->
<div class="modal-overlay" id="modalFormConfirm">
    <div class="modal-card">
        <div class="modal-header">
            <h4><i class="fas fa-rocket"></i> Confirmar Disparo</h4>
            <button class="modal-close" onclick="fecharModal('modalFormConfirm')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p id="modalFormTexto">Confirma o disparo do e-mail?</p>
            <p style="font-size:12px;margin-top:10px;color:#666;">Esta ação enviará o e-mail imediatamente para os destinatários selecionados.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-modal-cancel" onclick="fecharModal('modalFormConfirm')">Cancelar</button>
            <button class="btn-modal-confirm gold" id="btnConfirmSubmit" onclick="submitFormulario()">
                <i class="fas fa-rocket"></i> Confirmar Disparo
            </button>
        </div>
    </div>
</div>

<script>
    /* ======================== ESTADO ======================== */
    const rowsPerPage = 10;
    let currentPage = 1;
    let currentDateFilter = 'all';
    let sortCol = null;
    let sortDir = 'asc';

    /* ======================== FILTROS ======================== */
    function applyCombinedFilters() {
        const searchText = document.getElementById("newsInput").value.toLowerCase().trim();
        const rows = document.querySelectorAll(".news-row");
        let activeCount = 0;

        const now = new Date();
        const startOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const startOfWeek = new Date(startOfDay.getTime() - 7 * 24 * 60 * 60 * 1000);
        const startOfMonth = new Date(startOfDay.getTime() - 30 * 24 * 60 * 60 * 1000);

        rows.forEach(row => {
            const email = row.dataset.email || '';
            const nome = row.dataset.nome || '';
            const rawDate = row.dataset.date;
            const rowDate = rawDate ? new Date(rawDate) : null;

            const matchesText = !searchText || email.includes(searchText) || nome.includes(searchText);
            let matchesDate = true;
            if (rowDate) {
                if (currentDateFilter === 'today') matchesDate = rowDate >= startOfDay;
                else if (currentDateFilter === 'week') matchesDate = rowDate >= startOfWeek;
                else if (currentDateFilter === 'month') matchesDate = rowDate >= startOfMonth;
            }

            const visible = matchesText && matchesDate;
            row.setAttribute('data-filtered', visible ? 'true' : 'false');
            if (visible) activeCount++;
        });

        const zeroState = document.getElementById("zeroStateRow");
        if (zeroState) zeroState.style.display = (activeCount === 0 && rows.length > 0) ? "" : "none";

        currentPage = 1;
        displayTable();
    }

    function setDateFilter(btn, filter) {
        document.querySelectorAll('.filters-wrapper .pill-button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentDateFilter = filter;
        applyCombinedFilters();
    }

    /* ======================== SORTING ======================== */
    function sortTable(col) {
        const thead = document.querySelectorAll('.newsletter-table thead th');
        thead.forEach(th => { th.classList.remove('sort-asc','sort-desc'); });

        if (sortCol === col) {
            sortDir = sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            sortCol = col;
            sortDir = 'asc';
        }

        const colMap = { id: 1, nome: 2, email: 3, date: 4 };
        const thIdx = colMap[col];
        if (thIdx !== undefined) {
            thead[thIdx].classList.add(sortDir === 'asc' ? 'sort-asc' : 'sort-desc');
            const sortIcon = thead[thIdx].querySelector('.sort-icon');
            if (sortIcon) sortIcon.className = `fas fa-sort-${sortDir === 'asc' ? 'up' : 'down'} sort-icon`;
        }

        const tbody = document.getElementById("newsTableBody");
        const rows = Array.from(tbody.querySelectorAll('.news-row'));

        rows.sort((a, b) => {
            let valA, valB;
            if (col === 'id') {
                valA = parseInt(a.dataset.id);
                valB = parseInt(b.dataset.id);
            } else if (col === 'nome') {
                valA = a.dataset.nome;
                valB = b.dataset.nome;
            } else if (col === 'email') {
                valA = a.dataset.email;
                valB = b.dataset.email;
            } else if (col === 'date') {
                valA = new Date(a.dataset.date);
                valB = new Date(b.dataset.date);
            }
            if (valA < valB) return sortDir === 'asc' ? -1 : 1;
            if (valA > valB) return sortDir === 'asc' ? 1 : -1;
            return 0;
        });

        rows.forEach(r => tbody.appendChild(r));
        displayTable();
    }

    /* ======================== PAGINAÇÃO ======================== */
    function displayTable() {
        const rows = Array.from(document.querySelectorAll(".news-row"));
        const filtered = rows.filter(r => r.getAttribute('data-filtered') !== 'false');
        const totalPages = Math.max(1, Math.ceil(filtered.length / rowsPerPage));

        if (currentPage > totalPages) currentPage = totalPages;
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach(r => r.style.display = "none");
        filtered.slice(start, end).forEach(r => r.style.display = "");

        updatePagination(totalPages, filtered.length);
    }

    function updatePagination(totalPages, total) {
        const container = document.getElementById("paginationButtons");
        const info = document.getElementById("paginationInfo");
        const wrap = document.getElementById("paginationContainer");
        if (!container || !info) return;
        container.innerHTML = "";

        if (total === 0) {
            info.innerText = "0 clientes encontrados";
            if (wrap) wrap.style.display = "none";
            return;
        }
        if (wrap) wrap.style.display = "";
        const start = (currentPage - 1) * rowsPerPage + 1;
        const end = Math.min(currentPage * rowsPerPage, total);
        info.innerText = `Exibindo ${start}–${end} de ${total} clientes`;

        if (totalPages <= 1) { if (wrap) wrap.style.display = "none"; return; }

        const btn = (txt, page, active = false, disabled = false) => {
            const b = document.createElement("button");
            b.innerHTML = txt;
            b.className = `page-link${active ? ' active' : ''}${disabled ? ' disabled' : ''}`;
            if (!disabled) b.onclick = () => { currentPage = page; displayTable(); };
            return b;
        };

        container.appendChild(btn('<i class="fa fa-chevron-left"></i>', currentPage - 1, false, currentPage === 1));
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                container.appendChild(btn(i, i, i === currentPage));
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                const dots = document.createElement("span");
                dots.textContent = "...";
                dots.style.cssText = "color:#444;padding:0 6px;line-height:36px;";
                container.appendChild(dots);
            }
        }
        container.appendChild(btn('<i class="fa fa-chevron-right"></i>', currentPage + 1, false, currentPage === totalPages));
    }

    /* ======================== CHECKBOXES ======================== */
    function onRowCheckChange() {
        const checks = document.querySelectorAll('.row-check');
        const checked = Array.from(checks).filter(c => c.checked);
        const bulkBar = document.getElementById('bulkBar');
        const bulkCount = document.getElementById('bulkCount');

        bulkCount.textContent = checked.length;
        if (checked.length > 0) {
            bulkBar.classList.add('visible');
        } else {
            bulkBar.classList.remove('visible');
            document.getElementById('checkAll').checked = false;
        }

        const allVisible = Array.from(document.querySelectorAll('.news-row'))
            .filter(r => r.style.display !== 'none')
            .map(r => r.querySelector('.row-check'));
        document.getElementById('checkAll').checked =
            allVisible.length > 0 && allVisible.every(c => c.checked);
    }

    function toggleCheckAll(master) {
        const rows = Array.from(document.querySelectorAll('.news-row'))
            .filter(r => r.style.display !== 'none');
        rows.forEach(r => {
            const cb = r.querySelector('.row-check');
            if (cb) cb.checked = master.checked;
        });
        onRowCheckChange();
    }

    function desmarcarTodos() {
        document.querySelectorAll('.row-check').forEach(c => c.checked = false);
        document.getElementById('checkAll').checked = false;
        document.getElementById('bulkBar').classList.remove('visible');
    }

    /* ======================== MODAL ENVIO MASSA ======================== */
    function abrirModalEnvioMassa() {
        const checks = Array.from(document.querySelectorAll('.row-check:checked'));
        const count = checks.length;
        if (count === 0) return;
        document.getElementById('modalEnvioMassaTexto').innerHTML =
            `Esta ação enviará o e-mail para <strong>${count} cliente(s) selecionado(s)</strong>.<br><small style="color:#666;font-size:11px;">Preencha o formulário abaixo primeiro e confirme aqui.</small>`;
        abrirModal('modalEnvioMassa');
    }

    function confirmarEnvio() {
        const checks = Array.from(document.querySelectorAll('.row-check:checked'));
        if (checks.length === 0) { fecharModal('modalEnvioMassa'); return; }
        const email = checks[0].value;
        selecionarDestinatario(email);
        fecharModal('modalEnvioMassa');
        document.querySelector('.promo-form-container').scrollIntoView({ behavior:'smooth' });
    }

    /* ======================== MODAL FORM SUBMIT ======================== */
    function onFormSubmit(e) {
        e.preventDefault();
        const select = document.getElementById('destinatarioSelect');
        const dest = select.value;
        let texto = '';
        if (dest === 'todos') {
            texto = `Confirma o disparo do e-mail para <strong>todos os <?= $totalClientes ?> clientes ativos</strong>?`;
        } else if (dest === 'custom') {
            const em = document.getElementById('customEmailInput').value || 'e-mail informado';
            texto = `Confirma o envio exclusivo para <strong>${em}</strong>?`;
        } else {
            texto = `Confirma o envio exclusivo para <strong>${dest}</strong>?`;
        }
        document.getElementById('modalFormTexto').innerHTML = texto;
        abrirModal('modalFormConfirm');
        return false;
    }

    function submitFormulario() {
        fecharModal('modalFormConfirm');
        const btn = document.getElementById('submitDisparoBtn');
        const label = document.getElementById('submitLabel');
        btn.disabled = true;
        label.innerHTML = '<span class="spinner"></span> Enviando...';
        document.getElementById('disparoForm').submit();
    }

    /* ======================== MODAL HELPERS ======================== */
    function abrirModal(id) {
        document.getElementById(id).classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function fecharModal(id) {
        document.getElementById(id).classList.remove('active');
        document.body.style.overflow = '';
    }
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', e => {
            if (e.target === overlay) fecharModal(overlay.id);
        });
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(m => fecharModal(m.id));
        }
    });

    /* ======================== DESTINATÁRIO ======================== */
    function selecionarDestinatario(email) {
        const select = document.getElementById("destinatarioSelect");
        let exists = false;
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value === email) { exists = true; break; }
        }
        select.value = exists ? email : 'custom';
        if (!exists) document.getElementById('customEmailInput').value = email;
        alternarDestinatario(select);
        document.querySelector('.split-layout').scrollIntoView({ behavior:'smooth' });
    }

    function alternarDestinatario(select) {
        const val = select.value;
        const infoMsg = document.getElementById("destinatarioInfo");
        const submitLabel = document.getElementById("submitLabel");
        const customGroup = document.getElementById("customEmailGroup");
        const customInput = document.getElementById("customEmailInput");

        if (val === "todos") {
            customGroup.style.display = "none";
            customInput.removeAttribute("required");
            infoMsg.innerHTML = `Esta ferramenta enviará o e-mail para <strong>todos os <?= $totalClientes ?></strong> clientes ativos cadastrados.`;
            submitLabel.innerHTML = 'Disparar para Todos';
        } else if (val === "custom") {
            customGroup.style.display = "";
            customInput.setAttribute("required","required");
            customInput.focus();
            infoMsg.innerHTML = `Envio <strong>exclusivo</strong> para o destinatário personalizado informado abaixo.`;
            submitLabel.innerHTML = 'Enviar Exclusivo';
        } else {
            customGroup.style.display = "none";
            customInput.removeAttribute("required");
            infoMsg.innerHTML = `Envio <strong>exclusivo</strong> para <strong>${val}</strong>.`;
            submitLabel.innerHTML = 'Enviar Comunicado';
        }
    }

    /* ======================== CONTADOR ======================== */
    function countChars(textarea) {
        const len = textarea.value.length;
        document.getElementById("charCount").innerText = len;
        const wrap = document.getElementById("counterWrapper");
        len >= 950 ? wrap.classList.add("warning") : wrap.classList.remove("warning");
    }

    /* ======================== LIVE PREVIEW ======================== */
    function updatePreview() {
        const assunto = document.getElementById("formAssunto").value || "Assunto do e-mail";
        const mensagem = document.getElementById("formMensagem").value || "Escreva sua mensagem no formulário ao lado...";
        document.getElementById("mockupSubject").textContent = assunto;
        document.getElementById("mockupText").textContent = mensagem;
    }

    function updateBanner() {
        const bannerInput = document.getElementById("formBanner");
        const mockupBanner = document.getElementById("mockupBanner");
        if (bannerInput && bannerInput.files && bannerInput.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                mockupBanner.src = e.target.result;
                mockupBanner.style.display = "block";
            };
            reader.readAsDataURL(bannerInput.files[0]);
        } else {
            mockupBanner.style.display = "none";
        }
    }

    /* ======================== INIT ======================== */
    document.addEventListener("DOMContentLoaded", () => {
        const customInput = document.getElementById("customEmailInput");
        if (customInput) {
            customInput.addEventListener("input", () => alternarDestinatario(document.getElementById("destinatarioSelect")));
        }
        applyCombinedFilters();
    });
</script>
