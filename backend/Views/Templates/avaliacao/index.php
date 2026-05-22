<style>
    /* --- DESIGN SYSTEM KOKETSU PREMIUM --- */
    :root {
        --bg-main: #0f0f0f;
        --bg-card: #1a1a1a;
        --border-color: #222222;
        --text-main: #ffffff;
        --text-muted: #888888;
        
        /* Accents do Guia de Identidade Visual */
        --accent: #F2C84B;
        --accent-glow: rgba(242, 200, 75, 0.25);
        --accent-dim: rgba(242, 200, 75, 0.07);
        
        --copper: #C47A3A;
        --copper-glow: rgba(196, 122, 58, 0.25);
        --copper-dim: rgba(196, 122, 58, 0.08);
        
        --steel: #4E9EBF;
        --steel-glow: rgba(78, 158, 191, 0.22);
        --steel-dim: rgba(78, 158, 191, 0.07);
        
        --amethyst: #8B5CF6;
        
        /* Cores de Status Semânticos */
        --success: #51cf66;
        --alert: #ffa94d;
        --danger: #dc3545;
        
        --shadow-sm: 0 4px 10px rgba(0, 0, 0, 0.3);
        --shadow-md: 0 8px 25px rgba(0, 0, 0, 0.5);
    }

    /* --- AJUSTES GERAIS DE LAYOUT --- */
    .page-wrapper {
        padding: 30px;
        width: 100%;
        box-sizing: border-box;
        background-color: var(--bg-main) !important;
        min-height: 100vh;
        color: var(--text-main);
        font-family: Arial, sans-serif;
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
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border-color);
        font-size: 14px;
    }

    .header-breadcrumb i {
        margin-right: 5px;
    }

    /* --- DASHBOARD CARDS PREMIUM --- */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 35px;
    }

    .stat-card {
        background: linear-gradient(135deg, #1a1a1a, #0f0f0f);
        border: 2px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: var(--shadow-sm);
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
        transition: 0.3s;
    }

    /* Cores das barras superiores dos cards segundo o guia */
    .stat-card.card-total::before { background: var(--steel); }
    .stat-card.card-media::before { background: var(--accent); }
    .stat-card.card-novas::before { background: var(--danger); }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }
    
    .stat-card.card-total:hover { border-color: var(--steel); }
    .stat-card.card-media:hover { border-color: var(--accent); }
    .stat-card.card-novas:hover { border-color: var(--danger); }

    .stat-icon {
        font-size: 26px;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        transition: 0.3s;
    }

    .card-total .stat-icon {
        color: var(--steel);
        background: var(--steel-dim);
    }
    .card-media .stat-icon {
        color: var(--accent);
        background: var(--accent-dim);
    }
    .card-novas .stat-icon {
        color: var(--danger);
        background: rgba(220, 53, 69, 0.08);
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .stat-info h3 {
        margin: 0 0 4px 0;
        font-size: 32px;
        color: var(--text-main);
        font-weight: 800;
        letter-spacing: -1px;
    }

    .stat-info p {
        margin: 0;
        color: var(--text-muted);
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
        font-weight: 700;
    }

    /* --- FILTROS E BUSCA (ACTIONS BAR) --- */
    .actions-bar {
        background: linear-gradient(135deg, #141414, #0d0d0d);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 25px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .actions-top-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .search-container {
        position: relative;
        flex: 1;
        max-width: 450px;
        min-width: 280px;
    }

    .search-container i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--accent);
        font-size: 15px;
    }

    .search-input {
        width: 100%;
        background: #0f0f0f;
        border: 2px solid var(--border-color);
        padding: 12px 16px 12px 46px;
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

    /* Pílulas de filtro por Estrela */
    .filter-pills {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-label {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1px;
        margin-right: 5px;
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
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .pill-button:hover {
        border-color: var(--accent);
        color: var(--accent);
        box-shadow: 0 2px 8px var(--accent-glow);
    }

    .pill-button.active {
        background: var(--accent);
        border-color: var(--accent);
        color: #000000;
        box-shadow: 0 4px 12px var(--accent-glow);
    }

    /* --- TABELA DE AVALIAÇÕES --- */
    .table-container {
        background: linear-gradient(135deg, #141414, #0d0d0d);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 10px;
        overflow-x: auto;
        box-shadow: var(--shadow-sm);
    }

    .valuation-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        color: var(--text-main);
    }

    .valuation-table thead th {
        color: var(--accent) !important;
        background-color: #1a1a1a;
        text-transform: uppercase;
        font-size: 11px;
        padding: 16px;
        letter-spacing: 1px;
        font-weight: 800;
        border-bottom: 2px solid var(--border-color);
        text-align: left;
    }

    .valuation-table thead th:first-child { border-radius: 10px 0 0 0; }
    .valuation-table thead th:last-child { border-radius: 0 10px 0 0; }

    .valuation-table tbody tr {
        background: transparent;
        transition: all 0.2s ease;
    }

    .valuation-table tbody tr:not(:last-child) td {
        border-bottom: 1px solid var(--border-color);
    }

    .valuation-table tbody tr:hover {
        background: rgba(242, 200, 75, 0.03);
    }

    .valuation-table td {
        padding: 16px !important;
        vertical-align: middle;
        font-size: 14px;
    }

    .id-column {
        font-family: monospace;
        color: var(--steel) !important;
        font-weight: 700;
    }

    .product-column {
        font-weight: 700;
        color: var(--text-main);
    }
    
    .product-badge {
        background: rgba(255, 255, 255, 0.04);
        padding: 6px 12px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        display: inline-block;
        color: var(--accent);
    }

    .customer-column {
        color: var(--text-main);
        font-weight: 600;
    }

    /* Fallbacks elegantes de cliente */
    .guest-client {
        color: var(--text-muted);
        font-style: italic;
        font-weight: 500;
        opacity: 0.85;
    }

    .guest-client::before {
        content: '\f2c0'; /* Fa-user-secret / fa-user-circle */
        font-family: 'FontAwesome';
        margin-right: 6px;
        font-size: 12px;
        color: var(--copper);
    }

    /* Comentários expansíveis em clique */
    .comment-column {
        color: var(--text-muted);
        font-size: 13px;
        max-width: 320px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .comment-text-wrapper {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        padding-right: 20px;
    }

    .comment-column.expanded .comment-text-wrapper {
        white-space: normal;
        overflow: visible;
        text-overflow: clip;
        word-break: break-word;
    }

    .comment-column:hover .comment-text-wrapper {
        color: var(--text-main);
    }

    .comment-expand-indicator {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 10px;
        color: var(--text-muted);
        transition: 0.3s;
    }

    .comment-column.expanded .comment-expand-indicator {
        transform: translateY(-50%) rotate(180deg);
        color: var(--accent);
    }

    .rating-stars {
        color: var(--accent);
        font-size: 13px;
        text-shadow: 0 0 4px rgba(242, 200, 75, 0.15);
        display: flex;
        gap: 3px;
    }

    .rating-stars .fa-star-o {
        color: #333333;
    }

    /* --- BOTÕES DE AÇÃO PREMIUM --- */
    .btn-action-small {
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        margin: 0 3px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-edit {
        background: transparent;
        color: var(--accent);
        border: 1px solid var(--accent);
    }

    .btn-edit:hover {
        background: var(--accent);
        color: #000000;
        box-shadow: 0 2px 10px var(--accent-glow);
        transform: translateY(-1px);
    }

    .btn-delete {
        background: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-muted);
    }

    .btn-delete:hover {
        border-color: var(--danger);
        color: var(--danger);
        background: rgba(220, 53, 69, 0.05);
        transform: translateY(-1px);
    }

    /* --- PAGINAÇÃO --- */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 25px;
        padding: 16px 20px;
        background: linear-gradient(135deg, #141414, #0d0d0d);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        flex-wrap: wrap;
        gap: 15px;
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
        background: #0f0f0f;
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
        color: #000000;
        border-color: var(--accent);
        box-shadow: 0 4px 10px var(--accent-glow);
    }

    .page-link.disabled {
        opacity: 0.25;
        cursor: not-allowed;
    }

    .pagination-dots {
        color: var(--text-muted);
        padding: 0 4px;
        font-weight: bold;
    }
</style>

<div class="page-wrapper">
    <h3 class="page-title"><i class="fa fa-star"></i> Gerenciar Avaliações</h3>

    <header class="header-breadcrumb">
        <h5><b><i class="fa fa-dashboard"></i> Painel de Controle - Koketsu</b></h5>
    </header>

    <!-- Metricas do Painel -->
    <div class="dashboard-grid">
        <div class="stat-card card-total">
            <div class="stat-info">
                <h3><?php echo count($avaliacoes); ?></h3>
                <p>Avaliações Totais</p>
            </div>
            <div class="stat-icon"><i class="fa fa-comments"></i></div>
        </div>

        <div class="stat-card card-media">
            <div class="stat-info">
                <?php
                $media = 0;
                if (count($avaliacoes) > 0) {
                    $soma = 0;
                    foreach ($avaliacoes as $a)
                        $soma += $a['nota_avaliacoes'];
                    $media = round($soma / count($avaliacoes), 1);
                }
                ?>
                <h3><?php echo number_format($media, 1, '.', ''); ?></h3>
                <p>Média de Notas</p>
            </div>
            <div class="stat-icon"><i class="fa fa-star"></i></div>
        </div>

        <div class="stat-card card-novas">
            <div class="stat-info">
                <?php
                $recentes = 0;
                $hoje = date('Y-m-d');
                foreach ($avaliacoes as $a) {
                    $data_comp = !empty($a['data_avaliacao_avaliacoes']) ? $a['data_avaliacao_avaliacoes'] : ($a['criado_em'] ?? '');
                    if (!empty($data_comp) && date('Y-m-d', strtotime($data_comp)) == $hoje)
                        $recentes++;
                }
                ?>
                <h3><?php echo $recentes; ?></h3>
                <p>Novas Hoje</p>
            </div>
            <div class="stat-icon"><i class="fa fa-bolt"></i></div>
        </div>
    </div>

    <!-- Barra de Ações com Filtro Combinado -->
    <div class="actions-bar">
        <div class="actions-top-row">
            <div class="search-container">
                <i class="fa fa-search"></i>
                <input type="text" id="valuationInput" onkeyup="filterValuations()"
                    placeholder="Buscar por produto ou cliente..." class="search-input">
            </div>
            
            <div class="filter-pills">
                <span class="filter-label">Filtrar Nota:</span>
                <button class="pill-button active" onclick="setRatingFilter(this, 'all')">Todas</button>
                <button class="pill-button" onclick="setRatingFilter(this, 5)">5 <i class="fa fa-star"></i></button>
                <button class="pill-button" onclick="setRatingFilter(this, 4)">4 <i class="fa fa-star"></i></button>
                <button class="pill-button" onclick="setRatingFilter(this, 3)">3 <i class="fa fa-star"></i></button>
                <button class="pill-button" onclick="setRatingFilter(this, 2)">2 <i class="fa fa-star"></i></button>
                <button class="pill-button" onclick="setRatingFilter(this, 1)">1 <i class="fa fa-star"></i></button>
            </div>
        </div>
    </div>

    <main>
        <div class="table-container">
            <table class="valuation-table" id="valuationTable">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Produto</th>
                        <th>Cliente</th>
                        <th style="width: 120px; text-align: center;">Nota</th>
                        <th>Comentário <span style="font-size: 9px; font-weight: normal; color: var(--text-muted); text-transform: none;">(clique para ver mais)</span></th>
                        <th style="width: 110px;">Data</th>
                        <th style="width: 180px; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($avaliacoes) > 0): ?>
                        <?php foreach ($avaliacoes as $avaliacao): ?>
                            <tr class="valuation-row" data-rating="<?= $avaliacao['nota_avaliacoes'] ?>">
                                <td class="id-column">#<?= $avaliacao['id_avaliacoes'] ?></td>
                                <td class="product-column">
                                    <span class="product-badge">
                                        <?= htmlspecialchars($avaliacao['nome_produto'] ?? 'Produto não encontrado') ?>
                                    </span>
                                </td>
                                <td class="customer-column">
                                    <?php if (!empty($avaliacao['nome_cliente'])): ?>
                                        <?= htmlspecialchars($avaliacao['nome_cliente']) ?>
                                    <?php else: ?>
                                        <span class="guest-client" title="Perfil sem cadastro de usuário completo">
                                            Consumidor Convidado #<?= $avaliacao['id_cliente'] ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="rating-stars" style="justify-content: center;">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fa fa-star<?= $i <= $avaliacao['nota_avaliacoes'] ? '' : '-o' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                                <td class="comment-column" onclick="toggleComment(this)" title="Clique para expandir">
                                    <span class="comment-text-wrapper">
                                        <?= htmlspecialchars($avaliacao['comentario_avaliacoes']) ?>
                                    </span>
                                    <i class="fa fa-chevron-down comment-expand-indicator"></i>
                                </td>
                                <td style="color: var(--text-muted); font-size: 13px;">
                                    <?php
                                    $data_exibir = !empty($avaliacao['data_avaliacao_avaliacoes']) ? $avaliacao['data_avaliacao_avaliacoes'] : ($a['criado_em'] ?? '');
                                    echo !empty($data_exibir) ? date('d/m/Y', strtotime($data_exibir)) : '---';
                                    ?>
                                </td>

                                <td style="text-align: center;">
                                    <a href="/backend/avaliacao/excluir/<?= $avaliacao['id_avaliacoes'] ?>"
                                        class="btn-action-small btn-delete">
                                        <i class="fa fa-trash"></i> Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 60px; color: var(--text-muted);">
                                <i class="fa fa-comments-o" style="font-size: 54px; display: block; margin-bottom: 15px; color: var(--border-color);"></i>
                                Nenhuma avaliação encontrada.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Barra de Paginacao -->
        <div class="pagination-container">
            <div class="pagination-info" id="paginationInfo">Carregando avaliações...</div>
            <div class="pagination-buttons" id="paginationButtons"></div>
        </div>
    </main>
</div>

<script>
    const rowsPerPage = 10;
    let currentPage = 1;
    let currentRatingFilter = 'all';

    // Alternar visualização estendida dos comentários
    function toggleComment(cell) {
        cell.classList.toggle('expanded');
    }

    // Definir filtro de estrelas
    function setRatingFilter(btn, rating) {
        document.querySelectorAll('.filter-pills .pill-button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentRatingFilter = rating;
        currentPage = 1;
        applyCombinedFilters();
    }

    // Filtragem unificada (Estrelas + Busca Textual)
    function applyCombinedFilters() {
        const searchText = document.getElementById("valuationInput").value.toUpperCase();
        const rows = document.querySelectorAll(".valuation-row");
        
        rows.forEach(row => {
            const product = row.querySelector(".product-column") ? row.querySelector(".product-column").textContent.toUpperCase() : "";
            const customer = row.querySelector(".customer-column") ? row.querySelector(".customer-column").textContent.toUpperCase() : "";
            const rating = parseInt(row.getAttribute('data-rating')) || 0;
            
            const matchesText = product.includes(searchText) || customer.includes(searchText);
            const matchesRating = (currentRatingFilter === 'all' || rating === parseInt(currentRatingFilter));
            
            if (matchesText && matchesRating) {
                row.setAttribute('data-filtered', 'true');
            } else {
                row.setAttribute('data-filtered', 'false');
            }
        });
        
        currentPage = 1;
        displayTable();
    }

    function filterValuations() {
        applyCombinedFilters();
    }

    function displayTable() {
        const table = document.getElementById("valuationTable");
        const allRows = Array.from(table.querySelectorAll(".valuation-row"));
        const filteredRows = allRows.filter(row => row.getAttribute('data-filtered') !== 'false');
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        
        if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        
        allRows.forEach(row => row.style.display = "none");
        filteredRows.slice(start, end).forEach(row => row.style.display = "");
        
        updatePaginationButtons(totalPages, filteredRows.length);
    }

    function updatePaginationButtons(totalPages, totalActive) {
        const container = document.getElementById("paginationButtons");
        const info = document.getElementById("paginationInfo");
        container.innerHTML = "";
        
        info.innerText = `Mostrando página ${currentPage} de ${totalPages || 1} (${totalActive} avaliações filtradas)`;
        if (totalPages <= 1) return;
        
        const createBtn = (text, page, isActive = false, isDisabled = false) => {
            const btn = document.createElement("button");
            btn.innerHTML = text;
            btn.className = `page-link ${isActive ? 'active' : ''} ${isDisabled ? 'disabled' : ''}`;
            if (!isDisabled) btn.onclick = () => { currentPage = page; displayTable(); };
            return btn;
        };
        
        container.appendChild(createBtn('<i class="fa fa-chevron-left"></i>', currentPage - 1, false, currentPage === 1));
        const range = 1;
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - range && i <= currentPage + range)) {
                if (i === currentPage - range && i > 2) {
                    const dots = document.createElement("span");
                    dots.className = "pagination-dots";
                    dots.innerText = "...";
                    container.appendChild(dots);
                }
                container.appendChild(createBtn(i, i, i === currentPage));
                if (i === currentPage + range && i < totalPages - 1) {
                    const dots = document.createElement("span");
                    dots.className = "pagination-dots";
                    dots.innerText = "...";
                    container.appendChild(dots);
                }
            }
        }
        container.appendChild(createBtn('<i class="fa fa-chevron-right"></i>', currentPage + 1, false, currentPage === totalPages));
    }

    // Inicializar filtros combinados no carregamento
    document.addEventListener("DOMContentLoaded", () => {
        applyCombinedFilters();
    });
</script>