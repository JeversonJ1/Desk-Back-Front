<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">

<style>
    /* --- DESIGN SYSTEM KOKETSU NEWSLETTER PREMIUM --- */
    :root {
        --bg-main: #0a0a0a;
        --bg-card: rgba(26, 26, 26, 0.45);
        --bg-card-hover: rgba(36, 36, 36, 0.7);
        --border-color: rgba(255, 255, 255, 0.08);
        --border-color-hover: rgba(242, 200, 75, 0.35);
        --text-main: #ffffff;
        --text-muted: #8e8e93;
        
        /* Accents */
        --accent: #F2C84B;
        --accent-glow: rgba(242, 200, 75, 0.2);
        --accent-dim: rgba(242, 200, 75, 0.08);
        
        --copper: #C47A3A;
        --copper-glow: rgba(196, 122, 58, 0.2);
        
        --steel: #4E9EBF;
        --steel-glow: rgba(78, 158, 191, 0.18);
        --steel-dim: rgba(78, 158, 191, 0.06);
        
        /* Semantics */
        --success: #34c759;
        --success-glow: rgba(52, 199, 89, 0.15);
        --danger: #ff3b30;
        --danger-glow: rgba(255, 59, 48, 0.15);
        
        --glass-blur: blur(14px);
        --shadow-premium: 0 12px 40px rgba(0, 0, 0, 0.6);
        --transition-speed: 0.3s;
    }

    body { 
        background-color: var(--bg-main) !important; 
        margin: 0; 
        font-family: 'Outfit', sans-serif; 
        color: var(--text-main); 
    }
    
    .page-wrapper { 
        padding: 30px; 
        width: 100%; 
        box-sizing: border-box; 
        min-height: 100vh; 
    }
    
    .page-title { 
        font-size: 28px; 
        font-weight: 800; 
        margin-bottom: 5px; 
        color: var(--text-main); 
        text-transform: uppercase; 
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-title i {
        color: var(--accent);
        text-shadow: 0 0 10px var(--accent-glow);
    }
    
    .header-breadcrumb { 
        color: var(--text-muted); 
        margin-bottom: 30px; 
        padding-bottom: 15px; 
        border-bottom: 1px solid var(--border-color); 
        font-size: 14px;
    }

    .header-breadcrumb i {
        margin-right: 5px;
    }
    
    /* --- METRICAS GLASSMORPHISM --- */
    .dashboard-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px; 
        margin-bottom: 35px; 
    }
    
    .stat-card { 
        background: var(--bg-card); 
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 1px solid var(--border-color); 
        border-radius: 16px; 
        padding: 24px; 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        transition: all var(--transition-speed) cubic-bezier(0.25, 0.8, 0.25, 1); 
        box-shadow: var(--shadow-premium);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
        opacity: 0.3;
        transition: 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        border-color: var(--border-color-hover);
        box-shadow: 0 15px 35px var(--accent-glow);
    }

    .stat-card:hover::before {
        opacity: 1;
    }
    
    .stat-icon { 
        font-size: 26px; 
        color: var(--accent); 
        background: var(--accent-dim); 
        width: 52px; 
        height: 52px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        border-radius: 12px; 
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    .stat-info h3 { 
        margin: 0 0 4px 0; 
        font-size: 32px; 
        color: var(--text-main); 
        font-weight: 800; 
        letter-spacing: -0.5px;
    }
    
    .stat-info p { 
        margin: 0; 
        color: var(--text-muted); 
        text-transform: uppercase; 
        font-size: 11px; 
        letter-spacing: 1px; 
        font-weight: 700; 
    }

    /* --- FILTROS E ACOES --- */
    .actions-bar { 
        background: rgba(20, 20, 20, 0.5);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px;
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        gap: 20px; 
        margin-bottom: 25px; 
        flex-wrap: wrap; 
        box-shadow: var(--shadow-premium);
    }

    .filters-wrapper {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-label {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .pill-button {
        background: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-muted);
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .pill-button:hover {
        border-color: var(--accent);
        color: var(--accent);
        box-shadow: 0 2px 8px var(--accent-glow);
    }

    .pill-button.active {
        background: var(--accent);
        border-color: var(--accent);
        color: #000;
        box-shadow: 0 4px 12px var(--accent-glow);
    }
    
    .btn-main-action { 
        display: inline-flex; 
        align-items: center; 
        gap: 10px; 
        background: linear-gradient(135deg, var(--accent), #d4a800) !important; 
        color: #000 !important; 
        padding: 12px 24px; 
        border-radius: 10px; 
        font-weight: 700; 
        text-transform: uppercase; 
        font-size: 12px; 
        text-decoration: none; 
        transition: all 0.2s ease; 
        border: none; 
        cursor: pointer;
        box-shadow: 0 4px 15px var(--accent-glow);
    }

    .btn-main-action:hover { 
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(242, 200, 75, 0.4);
    }

    /* --- TABELA DE NEWSLETTER --- */
    .table-container {
        background: rgba(20, 20, 20, 0.5);
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 10px;
        box-shadow: var(--shadow-premium);
        overflow-x: auto;
    }
    
    .newsletter-table { 
        width: 100%; 
        border-collapse: separate; 
        border-spacing: 0; 
    }
    
    .newsletter-table thead th { 
        color: var(--accent) !important; 
        background-color: rgba(26, 26, 26, 0.6);
        text-transform: uppercase; 
        font-size: 11px; 
        padding: 16px; 
        letter-spacing: 1.5px; 
        font-weight: 800; 
        border-bottom: 2px solid var(--border-color);
        text-align: left;
    }
    
    .newsletter-table thead th:first-child { border-radius: 10px 0 0 0; }
    .newsletter-table thead th:last-child { border-radius: 0 10px 0 0; }

    .newsletter-table tbody tr { 
        background: transparent; 
        transition: all 0.2s ease; 
    }

    .newsletter-table tbody tr:not(:last-child) td {
        border-bottom: 1px solid var(--border-color);
    }
    
    .newsletter-table tbody tr:hover { 
        background: rgba(255, 255, 255, 0.02); 
    }
    
    .newsletter-table td { 
        padding: 16px !important; 
        vertical-align: middle; 
        color: var(--text-main); 
        font-size: 14px;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid transparent;
    }

    .status-badge.active {
        background: rgba(52, 199, 89, 0.08);
        color: var(--success);
        border-color: rgba(52, 199, 89, 0.2);
        box-shadow: inset 0 0 8px var(--success-glow);
    }

    .status-badge.inactive {
        background: rgba(255, 59, 48, 0.08);
        color: var(--danger);
        border-color: rgba(255, 59, 48, 0.2);
        box-shadow: inset 0 0 8px var(--danger-glow);
    }
    
    .btn-danger-small { 
        background: transparent; 
        border: 1px solid var(--border-color); 
        color: var(--text-muted); 
        padding: 8px 14px; 
        border-radius: 8px; 
        text-decoration: none; 
        font-size: 11px; 
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-danger-small:hover { 
        background: rgba(255, 59, 48, 0.08); 
        color: var(--danger); 
        border-color: var(--danger);
    }

    /* Zero State */
    .zero-state-container {
        text-align: center;
        padding: 60px 20px !important;
        color: var(--text-muted);
    }

    .zero-state-container i {
        font-size: 48px;
        color: var(--border-color);
        display: block;
        margin-bottom: 15px;
    }

    /* --- PAGINACAO --- */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 25px;
        padding: 16px 20px;
        background: rgba(20, 20, 20, 0.5);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        flex-wrap: wrap;
        gap: 15px;
        box-shadow: var(--shadow-premium);
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
        padding: 8px 14px;
        background: #0a0a0a;
        border: 1px solid var(--border-color);
        color: var(--text-main);
        border-radius: 8px;
        cursor: pointer;
        font-weight: 700;
        font-size: 12px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .page-link:hover:not(.disabled) {
        border-color: var(--accent);
        color: var(--accent);
        box-shadow: 0 2px 8px var(--accent-glow);
    }

    .page-link.active {
        background: var(--accent);
        color: #000;
        border-color: var(--accent);
        box-shadow: 0 4px 10px var(--accent-glow);
    }

    .page-link.disabled {
        opacity: 0.25;
        cursor: not-allowed;
    }

    /* --- FORM DISPARO --- */
    .promo-form-container { 
        background: var(--bg-card); 
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--border-color); 
        border-radius: 16px; 
        padding: 30px; 
        margin-top: 40px; 
        box-shadow: var(--shadow-premium);
    }
    
    .promo-form-container h4 { 
        color: var(--accent); 
        text-transform: uppercase; 
        font-weight: 800; 
        margin-top: 0;
        margin-bottom: 12px; 
        font-size: 18px;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-group { 
        margin-bottom: 20px; 
    }
    
    .form-group label { 
        display: block; 
        color: var(--text-muted); 
        font-size: 11px; 
        font-weight: 700; 
        text-transform: uppercase; 
        margin-bottom: 8px; 
        letter-spacing: 0.5px;
    }
    
    .form-control-k { 
        width: 100%; 
        background: #0a0a0a; 
        border: 1px solid var(--border-color); 
        border-radius: 8px; 
        padding: 12px 16px; 
        color: var(--text-main); 
        outline: none; 
        font-family: inherit;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .form-control-k:focus { 
        border-color: var(--accent); 
        box-shadow: 0 0 0 3px var(--accent-glow);
    }
    
    textarea.form-control-k { 
        min-height: 150px; 
        resize: vertical; 
    }

    /* Counter */
    .char-counter-wrapper {
        display: flex;
        justify-content: flex-end;
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 5px;
        font-weight: 600;
    }

    .char-counter-wrapper.warning {
        color: var(--danger);
    }

    /* --- PREMIUM LIVE PREVIEW MODAL --- */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(8px);
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    .modal-card {
        background: #0e0e0e;
        border: 2px solid var(--border-color);
        border-radius: 20px;
        width: 90%;
        max-width: 650px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8);
        transform: translateY(30px);
        transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .modal-overlay.active .modal-card {
        transform: translateY(0);
    }

    .modal-header {
        padding: 20px 25px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h4 {
        margin: 0;
        color: var(--accent);
        font-weight: 800;
        text-transform: uppercase;
        font-size: 16px;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-close {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-size: 18px;
        cursor: pointer;
        transition: 0.2s;
    }

    .modal-close:hover {
        color: var(--accent);
    }

    .modal-body {
        padding: 25px;
    }

    /* Newsletter Template Mockup */
    .newsletter-mockup {
        background: #000000;
        border: 1px solid #1a1a1a;
        border-radius: 12px;
        overflow: hidden;
        font-family: Arial, sans-serif;
    }

    .mockup-header {
        background: #0c0c0c;
        padding: 20px;
        text-align: center;
        border-bottom: 1px solid #141414;
    }

    .mockup-header img {
        height: 50px;
    }

    .mockup-banner {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
        display: none;
    }

    .mockup-content {
        padding: 30px 25px;
        color: #dddddd;
        line-height: 1.6;
        font-size: 15px;
    }

    .mockup-subject {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-main);
        margin: 0 0 15px 0;
    }

    .mockup-text {
        white-space: pre-line;
        color: #aaaaaa;
    }

    .mockup-footer {
        background: #0c0c0c;
        padding: 20px;
        text-align: center;
        font-size: 11px;
        color: #555555;
        border-top: 1px solid #141414;
    }

    .mockup-footer a {
        color: var(--accent);
        text-decoration: none;
    }
</style>

<div class="page-wrapper">
    <h3 class="page-title"><i class="fas fa-envelope-open-text"></i> Gestão de Newsletter</h3>

    <header class="header-breadcrumb">
        <h5><b><i class="fas fa-tachometer-alt"></i> Painel de Controle - Koketsu</b></h5>
    </header>

    <!-- Metricas com Glassmorphism -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h3 id="statActive"><?= count($inscritos); ?></h3>
                <p>Inscritos Ativos</p>
            </div>
            <div class="stat-icon"><i class="fas fa-user-check"></i></div>
        </div>
        
        <div class="stat-card">
            <div class="stat-info">
                <h3>CSV</h3>
                <p>Pronto para Exportar</p>
            </div>
            <div class="stat-icon"><i class="fas fa-file-export"></i></div>
        </div>
    </div>

    <!-- Filtros combinados e busca -->
    <div class="actions-bar">
        <div class="filters-wrapper">
            <div class="search-container" style="position: relative; width: 320px;">
                <i class="fas fa-search" style="position: absolute; left: 15px; top: 13px; color: var(--accent);"></i>
                <input type="text" id="newsInput" onkeyup="filterNewsletter()" placeholder="Buscar por e-mail..." class="form-control-k" style="padding-left: 45px;">
            </div>

            <span class="filter-label" style="margin-left: 10px;">Filtro de Inscrição:</span>
            <button class="pill-button active" onclick="setDateFilter(this, 'all')">Todos</button>
            <button class="pill-button" onclick="setDateFilter(this, 'today')">Hoje</button>
            <button class="pill-button" onclick="setDateFilter(this, 'week')">Esta Semana</button>
            <button class="pill-button" onclick="setDateFilter(this, 'month')">Este Mês</button>
        </div>
        
        <a href="/backend/admin/newsletter/exportar" class="btn-main-action">
            <i class="fas fa-download"></i> Exportar Lista (CSV)
        </a>
    </div>

    <main>
        <!-- Tabela -->
        <div class="table-container">
            <table class="newsletter-table" id="newsTable">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>E-mail do Cliente</th>
                        <th>Data da Inscrição</th>
                        <th>Status</th>
                        <th style="text-align: center; width: 300px;">Ações</th>
                    </tr>
                </thead>
                <tbody id="newsTableBody">
                    <?php foreach ($inscritos as $news): ?>
                    <tr class="news-row" data-date="<?= $news['data_inscricao'] ?>">
                        <td style="font-family: monospace; color: #888;">#<?= $news['id_newsletter'] ?></td>
                        <td class="email-text" style="font-weight: 600;"><?= htmlspecialchars($news['email_newsletter']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($news['data_inscricao'])) ?></td>
                        <td>
                            <span class="status-badge active"><i class="fas fa-circle" style="font-size: 6px;"></i> Ativo</span>
                        </td>
                        <td style="text-align: center;">
                            <button type="button" 
                                    class="btn-main-action" 
                                    style="padding: 7px 12px; font-size: 11px; background-color: var(--steel) !important; color: #fff !important; margin-right: 5px; border-radius: 6px;"
                                    onclick="selecionarDestinatario('<?= htmlspecialchars($news['email_newsletter']) ?>')">
                                <i class="fas fa-paper-plane"></i> Enviar Cupom
                            </button>
                            <a href="/backend/admin/newsletter/excluir/<?= $news['id_newsletter'] ?>" 
                               class="btn-danger-small" 
                               style="padding: 7px 12px; font-size: 11px; border-radius: 6px; display: inline-block;"
                               onclick="return confirm('Tem certeza que deseja remover este e-mail da lista?')">
                                <i class="fas fa-trash"></i> Remover
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <!-- Linha do Zero State (Fallback da Busca) -->
                    <tr id="zeroStateRow" style="display: none;">
                        <td colspan="5" class="zero-state-container">
                            <i class="fas fa-search-minus"></i>
                            Nenhum e-mail encontrado com este termo ou filtro.
                        </td>
                    </tr>
                    
                    <?php if (empty($inscritos)): ?>
                    <tr id="emptyDbRow">
                        <td colspan="5" class="zero-state-container">
                            <i class="fas fa-envelope-open"></i>
                            Nenhum e-mail capturado ainda.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Barra de Paginacao -->
        <div class="pagination-container" id="paginationContainer">
            <div class="pagination-info" id="paginationInfo">Carregando lista...</div>
            <div class="pagination-buttons" id="paginationButtons"></div>
        </div>

        <!-- Formulário de Disparo -->
        <div class="promo-form-container">
            <h4><i class="fas fa-paper-plane"></i> Enviar Promoção / Novidade</h4>
            <p id="destinatarioInfo" style="color: var(--text-muted); font-size: 13px; margin-bottom: 25px;">
                Esta ferramenta enviará o e-mail abaixo para **todos os <?= count($inscritos) ?>** inscritos ativos da lista.
            </p>
            
            <form id="disparoForm" action="/backend/admin/newsletter/enviar" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Destinatário (Enviar para)</label>
                    <select name="destinatario" id="destinatarioSelect" class="form-control-k" onchange="alternarDestinatario(this)">
                        <option value="todos">Todos os Inscritos</option>
                        <option value="custom">E-mail Personalizado / Manual...</option>
                        <?php foreach ($inscritos as $news): ?>
                            <option value="<?= htmlspecialchars($news['email_newsletter']) ?>"><?= htmlspecialchars($news['email_newsletter']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group" id="customEmailGroup" style="display: none;">
                    <label>E-mail do Destinatário</label>
                    <input type="email" name="email_personalizado" id="customEmailInput" class="form-control-k" placeholder="Ex: cliente@outlook.com">
                </div>
                
                <div class="form-group">
                    <label>Assunto do E-mail</label>
                    <input type="text" name="assunto" id="formAssunto" class="form-control-k" placeholder="Ex: Cupom de 20% OFF - Coleção 2026 🚀" required>
                </div>
                
                <div class="form-group">
                    <label>Banner da Promoção (Opcional)</label>
                    <input type="file" name="imagem_promo" id="formBanner" class="form-control-k" accept="image/*">
                    <small style="color: #666; font-size: 11px;">Imagens sugeridas: 600x400px. Tamanho máx: 2MB.</small>
                </div>
                
                <div class="form-group">
                    <label>Conteúdo da Mensagem</label>
                    <textarea name="mensagem" id="formMensagem" class="form-control-k" placeholder="Escreva aqui a novidade para seus clientes..." maxlength="1000" onkeyup="countChars(this)" required></textarea>
                    <div class="char-counter-wrapper" id="counterWrapper">
                        <span id="charCount">0</span> / 1000 caracteres
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <button type="button" class="btn-main-action" style="background: transparent !important; color: var(--accent) !important; border: 1px solid var(--accent); box-shadow: none;" onclick="openPreviewModal()">
                        <i class="fas fa-eye"></i> Pré-visualizar E-mail
                    </button>
                    
                    <button type="submit" id="submitDisparoBtn" class="btn-main-action" onclick="return confirm('Confirmar disparo para toda a base de inscritos?')">
                        <i class="fas fa-rocket"></i> Disparar para Todos
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<!-- PREMIUM LIVE PREVIEW MODAL -->
<div class="modal-overlay" id="previewModal">
    <div class="modal-card">
        <div class="modal-header">
            <h4><i class="fas fa-envelope-open-text"></i> Pré-visualização da Newsletter</h4>
            <button class="modal-close" onclick="closePreviewModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="newsletter-mockup">
                <div class="mockup-header">
                    <img src="/assets/img/logo2026.png" alt="Koketsu" onerror="this.src='/img/logo2026.png'">
                </div>
                <img id="mockupBanner" class="mockup-banner" src="" alt="Banner promocional">
                <div class="mockup-content">
                    <h2 id="mockupSubject" class="mockup-subject">Assunto do E-mail</h2>
                    <div id="mockupText" class="mockup-text">Conteúdo do e-mail.</div>
                </div>
                <div class="mockup-footer">
                    Você está recebendo este e-mail porque se inscreveu na newsletter da Koketsu Grife.<br>
                    Para parar de receber, <a href="#">clique aqui para cancelar inscrição</a>.<br>
                    &copy; <?= date('Y'); ?> Koketsu Grife. Todos os direitos reservados.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const rowsPerPage = 10;
    let currentPage = 1;
    let currentDateFilter = 'all';

    // Sistema combinado de busca e filtros
    function applyCombinedFilters() {
        const searchText = document.getElementById("newsInput").value.toUpperCase();
        const rows = document.querySelectorAll(".news-row");
        let activeCount = 0;
        
        const now = new Date();
        const startOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const startOfWeek = new Date(startOfDay.getTime() - 7 * 24 * 60 * 60 * 1000);
        const startOfMonth = new Date(startOfDay.getTime() - 30 * 24 * 60 * 60 * 1000);

        rows.forEach(row => {
            const email = row.querySelector(".email-text") ? row.querySelector(".email-text").textContent.toUpperCase() : "";
            const rawDateStr = row.getAttribute('data-date');
            const rowDate = new Date(rawDateStr);
            
            // Text filter
            const matchesText = email.includes(searchText);
            
            // Date filter
            let matchesDate = false;
            if (currentDateFilter === 'all') {
                matchesDate = true;
            } else if (currentDateFilter === 'today') {
                matchesDate = rowDate >= startOfDay;
            } else if (currentDateFilter === 'week') {
                matchesDate = rowDate >= startOfWeek;
            } else if (currentDateFilter === 'month') {
                matchesDate = rowDate >= startOfMonth;
            }
            
            if (matchesText && matchesDate) {
                row.setAttribute('data-filtered', 'true');
                activeCount++;
            } else {
                row.setAttribute('data-filtered', 'false');
            }
        });
        
        // Exibir Zero State se nada coincidir
        const zeroState = document.getElementById("zeroStateRow");
        if (zeroState) {
            zeroState.style.display = (activeCount === 0 && rows.length > 0) ? "" : "none";
        }
        
        // Atualizar metricas de estatisticas exibidas
        const statActive = document.getElementById("statActive");
        if (statActive && searchText === "" && currentDateFilter === "all") {
            statActive.innerText = rows.length;
        }

        displayTable();
    }

    function filterNewsletter() {
        applyCombinedFilters();
    }

    function setDateFilter(btn, filter) {
        document.querySelectorAll('.filters-wrapper .pill-button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentDateFilter = filter;
        currentPage = 1;
        applyCombinedFilters();
    }

    // Renderizador de Tabela Paginada
    function displayTable() {
        const rows = Array.from(document.querySelectorAll(".news-row"));
        const filteredRows = rows.filter(row => row.getAttribute('data-filtered') !== 'false');
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        
        if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        
        rows.forEach(row => row.style.display = "none");
        filteredRows.slice(start, end).forEach(row => row.style.display = "");
        
        updatePagination(totalPages, filteredRows.length);
    }

    function updatePagination(totalPages, totalActive) {
        const container = document.getElementById("paginationButtons");
        const info = document.getElementById("paginationInfo");
        const pagWrapper = document.getElementById("paginationContainer");
        
        if (!container || !info) return;
        
        container.innerHTML = "";
        
        if (totalActive === 0) {
            info.innerText = "Mostrando 0 de 0 inscritos";
            if (pagWrapper) pagWrapper.style.display = "none";
            return;
        }
        
        if (pagWrapper) pagWrapper.style.display = "";
        info.innerText = `Mostrando página ${currentPage} de ${totalPages || 1} (${totalActive} e-mails filtrados)`;
        if (totalPages <= 1) {
            if (pagWrapper) pagWrapper.style.display = "none";
            return;
        }
        
        const createBtn = (text, page, isActive = false, isDisabled = false) => {
            const btn = document.createElement("button");
            btn.innerHTML = text;
            btn.className = `page-link ${isActive ? 'active' : ''} ${isDisabled ? 'disabled' : ''}`;
            if (!isDisabled) btn.onclick = () => { currentPage = page; displayTable(); };
            return btn;
        };
        
        container.appendChild(createBtn('<i class="fa fa-chevron-left"></i>', currentPage - 1, false, currentPage === 1));
        
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                container.appendChild(createBtn(i, i, i === currentPage));
            }
        }
        
        container.appendChild(createBtn('<i class="fa fa-chevron-right"></i>', currentPage + 1, false, currentPage === totalPages));
    }

    // Selecionar Destinatario
    function selecionarDestinatario(email) {
        const select = document.getElementById("destinatarioSelect");
        if (select) {
            // Verifica se o e-mail existe na lista de opções do select
            let optionExists = false;
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].value === email) {
                    optionExists = true;
                    break;
                }
            }
            
            if (optionExists) {
                select.value = email;
            } else {
                select.value = "custom";
                const customInput = document.getElementById("customEmailInput");
                if (customInput) {
                    customInput.value = email;
                }
            }
            
            alternarDestinatario(select);
            
            // Focar e rolar
            const assuntoInput = document.getElementById("formAssunto");
            if (assuntoInput) assuntoInput.focus();
            
            document.querySelector(".promo-form-container").scrollIntoView({ behavior: 'smooth' });
        }
    }

    function alternarDestinatario(select) {
        const email = select.value;
        const infoMsg = document.getElementById("destinatarioInfo");
        const submitBtn = document.getElementById("submitDisparoBtn");
        const customGroup = document.getElementById("customEmailGroup");
        const customInput = document.getElementById("customEmailInput");
        
        if (email === "todos") {
            if (customGroup) customGroup.style.display = "none";
            if (customInput) customInput.removeAttribute("required");
            
            if (infoMsg) {
                infoMsg.innerHTML = `Esta ferramenta enviará o e-mail abaixo para <strong>todos os <?= count($inscritos) ?></strong> inscritos ativos da lista.`;
            }
            if (submitBtn) {
                submitBtn.innerHTML = `<i class="fas fa-rocket"></i> Disparar para Todos`;
                submitBtn.setAttribute("onclick", `return confirm('Confirmar disparo para toda a base de inscritos?')`);
            }
        } else if (email === "custom") {
            if (customGroup) customGroup.style.display = "";
            if (customInput) {
                customInput.setAttribute("required", "required");
                customInput.focus();
                
                const val = customInput.value || "o e-mail informado";
                if (infoMsg) {
                    infoMsg.innerHTML = `Esta ferramenta enviará o e-mail abaixo de forma <strong>exclusiva</strong> para o destinatário personalizado informado abaixo.`;
                }
                if (submitBtn) {
                    submitBtn.innerHTML = `<i class="fas fa-paper-plane"></i> Enviar Cupom Exclusivo`;
                    submitBtn.setAttribute("onclick", `return confirm('Confirmar envio exclusivo para ${val}?')`);
                }
            }
        } else {
            if (customGroup) customGroup.style.display = "none";
            if (customInput) customInput.removeAttribute("required");
            
            if (infoMsg) {
                infoMsg.innerHTML = `Esta ferramenta enviará o e-mail abaixo de forma <strong>exclusiva</strong> para <strong>${email}</strong>.`;
            }
            if (submitBtn) {
                submitBtn.innerHTML = `<i class="fas fa-paper-plane"></i> Enviar Cupom Exclusivo`;
                submitBtn.setAttribute("onclick", `return confirm('Confirmar envio exclusivo para ${email}?')`);
            }
        }
    }

    // Contador de Caracteres
    function countChars(textarea) {
        const len = textarea.value.length;
        const countSpan = document.getElementById("charCount");
        const wrapper = document.getElementById("counterWrapper");
        
        if (countSpan) countSpan.innerText = len;
        
        if (len >= 950) {
            if (wrapper) wrapper.classList.add("warning");
        } else {
            if (wrapper) wrapper.classList.remove("warning");
        }
    }

    // PRE-VISUALIZACAO EM TEMPO REAL (FileReader)
    function openPreviewModal() {
        const assunto = document.getElementById("formAssunto").value || "Coleção Koketsu Grife 2026";
        const mensagem = document.getElementById("formMensagem").value || "Sua novidade ou cupom exclusivo aparecerá aqui...";
        const bannerInput = document.getElementById("formBanner");
        
        document.getElementById("mockupSubject").innerText = assunto;
        document.getElementById("mockupText").innerHTML = mensagem.replace(/\n/g, "<br>");
        
        const mockupBanner = document.getElementById("mockupBanner");
        
        if (bannerInput && bannerInput.files && bannerInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                mockupBanner.src = e.target.result;
                mockupBanner.style.display = "block";
            };
            reader.readAsDataURL(bannerInput.files[0]);
        } else {
            mockupBanner.style.display = "none";
        }
        
        document.getElementById("previewModal").classList.add("active");
    }

    function closePreviewModal() {
        document.getElementById("previewModal").classList.remove("active");
    }

    // Inicializar filtros
    document.addEventListener("DOMContentLoaded", () => {
        const customInput = document.getElementById("customEmailInput");
        if (customInput) {
            customInput.addEventListener("keyup", () => {
                const submitBtn = document.getElementById("submitDisparoBtn");
                if (submitBtn && document.getElementById("destinatarioSelect").value === "custom") {
                    const val = customInput.value || "o e-mail informado";
                    submitBtn.setAttribute("onclick", `return confirm('Confirmar envio exclusivo para ${val}?')`);
                }
            });
        }
        applyCombinedFilters();
    });
</script>
