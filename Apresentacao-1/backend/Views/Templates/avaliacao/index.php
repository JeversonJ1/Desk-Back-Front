<style>
    /* --- AJUSTES GERAIS DE LAYOUT --- */
    .page-wrapper {
        padding: 20px;
        width: 100%;
        box-sizing: border-box;
        background-color: var(--bg-main) !important;
        min-height: 100vh;
        color: var(--text-main);
    }

    .page-title {
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 5px;
        color: var(--text-main);
        text-transform: uppercase;
        letter-spacing: -1px;
    }

    .header-breadcrumb {
        color: var(--text-muted);
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border-color);
    }

    /* --- DASHBOARD CARDS PREMIUM --- */
    .dashboard-grid {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .stat-card {
        flex: 1;
        min-width: 220px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: 0.3s;
        box-shadow: var(--shadow-sm);
    }

    .stat-card:hover { 
        border-color: var(--accent); 
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .stat-icon {
        font-size: 30px;
        color: var(--accent);
        background: rgba(242, 204, 125, 0.1);
        width: 55px;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }

    .stat-info h3 { margin: 0; font-size: 28px; color: var(--text-main); font-weight: 800; }
    .stat-info p { margin: 0; color: var(--text-muted); text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: 700; }

    /* --- ÁREA DE AÇÕES (BOTÃO + PESQUISA) --- */
    .actions-bar {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    /* Estilo da Barra de Pesquisa */
    .search-container {
        position: relative;
        flex: 1;
        max-width: 400px;
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
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        padding: 13px 15px 13px 45px;
        border-radius: 10px;
        color: var(--text-main);
        font-size: 14px;
        transition: 0.3s;
        outline: none;
        box-shadow: var(--shadow-sm);
    }

    .search-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(197, 160, 45, 0.1);
    }

    /* --- TABELA DE AVALIAÇÕES --- */
    .valuation-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
        color: var(--text-main);
    }

    .valuation-table thead th {
        color: var(--text-muted) !important;
        text-transform: uppercase;
        font-size: 11px;
        padding: 15px;
        letter-spacing: 1.5px;
        font-weight: 800;
    }

    .valuation-table tbody tr {
        background: var(--bg-card);
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    .valuation-table tbody tr:hover { 
        transform: scale(1.005);
        box-shadow: var(--shadow-md);
        border-color: var(--accent);
    }

    .valuation-table td { padding: 18px 15px !important; border: none; vertical-align: middle; }
    .valuation-table td:first-child { border-radius: 12px 0 0 12px; border-left: 1px solid var(--border-color); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); }
    .valuation-table td:last-child { border-radius: 0 12px 12px 0; border-right: 1px solid var(--border-color); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); }

    .id-column { font-family: monospace; color: var(--text-muted) !important; font-weight: bold; }
    .product-column { font-weight: 700; color: var(--accent); }
    .customer-column { color: var(--text-main); font-weight: 600; }
    .comment-column { color: var(--text-muted); font-size: 13px; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .rating-stars { color: #f2cc7d; font-size: 14px; }
    .rating-stars .bi-star { color: #444; }

    .btn-action-small { padding: 8px 14px; border-radius: 8px; font-size: 11px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: 0.3s; margin: 0 2px; }
    .btn-edit { background: var(--bg-main); color: var(--accent); border: 1px solid var(--accent); }
    .btn-edit:hover { background: var(--accent); color: #000; }
    .btn-delete { background: transparent; border: 1px solid var(--border-color); color: var(--text-muted); }
    .btn-delete:hover { border-color: #ff4444; color: #ff4444; background: rgba(255, 68, 68, 0.05); }

    /* --- PAGINAÇÃO --- */
    .pagination-container { display: flex; justify-content: space-between; align-items: center; margin-top: 30px; padding: 20px; background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-color); }
    .pagination-info { color: var(--text-muted); font-size: 13px; font-weight: 600; }
    .pagination-buttons { display: flex; align-items: center; gap: 8px; }
    .page-link { padding: 8px 16px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 8px; cursor: pointer; font-weight: 700; font-size: 13px; transition: 0.3s; }
    .page-link:hover:not(.disabled) { border-color: var(--accent); color: var(--accent); }
    .page-link.active { background: var(--accent); color: #000; border-color: var(--accent); }
    .page-link.disabled { opacity: 0.3; cursor: not-allowed; }
    .pagination-dots { color: var(--text-muted); padding: 0 5px; font-weight: bold; }

</style>

<div class="page-wrapper">
    <h3 class="page-title"><i class="fa fa-star" style="color: #f2cc7d;"></i> Gerenciar Avaliações</h3>

    <header class="header-breadcrumb">
        <h5><b><i class="fa fa-dashboard"></i> Painel de Controle - Koketsu</b></h5>
    </header>

    <div class="dashboard-grid">
        <div class="stat-card" style="border-left: 4px solid #f2cc7d;">
            <div class="stat-info">
                <h3><?php echo count($avaliacoes); ?></h3>
                <p>Avaliações Totais</p>
            </div>
            <div class="stat-icon"><i class="fa fa-comments"></i></div>
        </div>
        
        <div class="stat-card" style="border-left: 4px solid #4caf50;">
            <div class="stat-info">
                <?php
                $media = 0;
                if(count($avaliacoes) > 0) {
                    $soma = 0;
                    foreach($avaliacoes as $a) $soma += $a['nota_avaliacoes'];
                    $media = round($soma / count($avaliacoes), 1);
                }
                ?>
                <h3><?php echo $media; ?></h3>
                <p>Média de Notas</p>
            </div>
            <div class="stat-icon"><i class="fa fa-line-chart"></i></div>
        </div>

        <div class="stat-card" style="border-left: 4px solid #e63946;">
            <div class="stat-info">
                <?php
                $recentes = 0;
                $hoje = date('Y-m-d');
                foreach($avaliacoes as $a) {
                    $data_comp = !empty($a['data_avaliacao_avaliacoes']) ? $a['data_avaliacao_avaliacoes'] : ($a['criado_em'] ?? '');
                    if(!empty($data_comp) && date('Y-m-d', strtotime($data_comp)) == $hoje) $recentes++;
                }
                ?>
                <h3><?php echo $recentes; ?></h3>
                <p>Novas Hoje</p>
            </div>
            <div class="stat-icon"><i class="fa fa-history"></i></div>
        </div>
    </div>

    <div class="actions-bar">
        <div class="search-container">
            <i class="fa fa-search"></i>
            <input type="text" id="valuationInput" onkeyup="filterValuations()" placeholder="Buscar por produto ou cliente..." class="search-input">
        </div>
    </div>

    <main>
        <table class="valuation-table" id="valuationTable">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Produto</th>
                    <th>Cliente</th>
                    <th style="text-align: center;">Nota</th>
                    <th>Comentário</th>
                    <th>Data</th>
                    <th style="text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($avaliacoes) > 0): ?>
                    <?php foreach ($avaliacoes as $avaliacao): ?>
                    <tr class="valuation-row"> 
                        <td class="id-column">#<?= $avaliacao['id_avaliacoes'] ?></td>
                        <td class="product-name product-column">
                            <?= htmlspecialchars($avaliacao['nome_produto'] ?? 'Produto não encontrado') ?>
                        </td>
                        <td class="customer-name customer-column">
                            <?= htmlspecialchars($avaliacao['nome_cliente'] ?? 'Cliente não encontrado') ?>
                        </td>
                        <td style="text-align: center;">
                            <div class="rating-stars">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <i class="fa fa-star<?= $i <= $avaliacao['nota_avaliacoes'] ? '' : '-o' ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </td>
                        <td class="comment-column" title="<?= htmlspecialchars($avaliacao['comentario_avaliacoes']) ?>">
                            <?= htmlspecialchars($avaliacao['comentario_avaliacoes']) ?>
                        </td>
                        <td style="color: var(--text-muted); font-size: 13px;">
                            <?php 
                                $data_exibir = !empty($avaliacao['data_avaliacao_avaliacoes']) ? $avaliacao['data_avaliacao_avaliacoes'] : ($avaliacao['criado_em'] ?? '');
                                echo !empty($data_exibir) ? date('d/m/Y', strtotime($data_exibir)) : '---';
                            ?>
                        </td>
                        
                        <td style="text-align: center;">
                            <a href="/backend/avaliacao/editar/<?= $avaliacao['id_avaliacoes'] ?>" class="btn-action-small btn-edit">
                                <i class="fa fa-pencil"></i> Editar
                            </a>
                            <a href="/backend/avaliacao/excluir/<?= $avaliacao['id_avaliacoes'] ?>" class="btn-action-small btn-delete">
                                <i class="fa fa-trash"></i> Excluir
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 50px; color: #666;">
                            <i class="fa fa-comments-o" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                            Nenhuma avaliação encontrada.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination-container">
            <div class="pagination-info" id="paginationInfo">Carregando...</div>
            <div class="pagination-buttons" id="paginationButtons"></div>
        </div>
    </main>
</div>

<script>
const rowsPerPage = 10;
let currentPage = 1;

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
    info.innerText = `Mostrando página ${currentPage} de ${totalPages || 1} (${totalActive} avaliações)`;
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

function filterValuations() {
    const filter = document.getElementById("valuationInput").value.toUpperCase();
    const rows = document.querySelectorAll(".valuation-row");
    rows.forEach(row => {
        const product = row.querySelector(".product-name") ? row.querySelector(".product-name").textContent.toUpperCase() : "";
        const customer = row.querySelector(".customer-name") ? row.querySelector(".customer-name").textContent.toUpperCase() : "";
        row.setAttribute('data-filtered', (product.includes(filter) || customer.includes(filter)) ? 'true' : 'false');
    });
    currentPage = 1;
    displayTable();
}

document.addEventListener("DOMContentLoaded", displayTable);
</script>
