<style>
    /* Estilos de Categorias */
    .cat-wrapper { padding-top: 10px; }
    
    .cat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .cat-title h1 { font-size: 2em; font-weight: 800; color: var(--text-main); margin: 0; letter-spacing: -1px; }
    .cat-title p { color: var(--text-muted); font-size: 0.9em; margin-top: 4px; }

    .btn-add-cat {
        background: var(--accent);
        color: #000;
        padding: 12px 24px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.8em;
        letter-spacing: 1px;
        transition: 0.3s;
        box-shadow: 0 4px 15px rgba(197, 160, 45, 0.2);
    }
    .btn-add-cat:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(197, 160, 45, 0.3); background: var(--text-main); color: var(--bg-main); }

    /* Stats Grid */
    .cat-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .cat-stat-card {
        background: var(--bg-card);
        padding: 20px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 15px;
        transition: 0.3s;
    }
    .cat-stat-card:hover { border-color: var(--accent); transform: translateY(-3px); }
    .cat-stat-icon { width: 48px; height: 48px; background: rgba(var(--accent), 0.1); color: var(--accent); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2em; }
    .cat-stat-info h3 { margin: 0; font-size: 1.2em; color: var(--text-main); font-weight: 800; }
    .cat-stat-info p { margin: 0; font-size: 11px; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 1px; }

    /* Table Design */
    .table-container {
        background: var(--bg-card);
        border-radius: 16px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .cat-table { width: 100%; border-collapse: collapse; }
    .cat-table thead th { background: var(--bg-main); color: var(--accent); text-align: left; padding: 18px 20px; font-size: 0.75em; text-transform: uppercase; letter-spacing: 1px; font-weight: 800; border-bottom: 1px solid var(--border-color); }
    .cat-table tbody td { padding: 18px 20px; border-bottom: 1px solid var(--border-color); color: var(--text-main); font-size: 0.95em; }
    .cat-table tr:hover { background: rgba(var(--accent), 0.02); }

    .badge-status { padding: 5px 12px; border-radius: 8px; font-size: 10px; font-weight: 900; text-transform: uppercase; display: inline-block; }
    .status-active { background: rgba(46, 213, 115, 0.1); color: #2ed573; border: 1px solid rgba(46, 213, 115, 0.2); }
    .status-inactive { background: rgba(255, 71, 87, 0.1); color: #ff4757; border: 1px solid rgba(255, 71, 87, 0.2); }

    .action-btns { display: flex; gap: 8px; }
    .btn-edit-cat { color: var(--accent); background: rgba(var(--accent), 0.1); width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; border-radius: 8px; transition: 0.3s; border: 1px solid transparent; }
    .btn-edit-cat:hover { background: var(--accent); color: #000; }
    .btn-del-cat { color: #ff4757; background: rgba(255, 71, 87, 0.1); width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; border-radius: 8px; transition: 0.3s; border: 1px solid transparent; }
    .btn-del-cat:hover { background: #ff4757; color: #fff; }

    /* Pagination */
    .pagination-container { display: flex; justify-content: space-between; align-items: center; margin-top: 30px; padding: 20px; background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-color); }
    .pagination-info { color: var(--text-muted); font-size: 13px; font-weight: 600; }
    .pagination-buttons { display: flex; align-items: center; gap: 8px; }
    .page-link { padding: 8px 16px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 8px; cursor: pointer; font-weight: 700; font-size: 13px; transition: 0.3s; }
    .page-link:hover:not(.disabled) { border-color: var(--accent); color: var(--accent); }
    .page-link.active { background: var(--accent); color: #000; border-color: var(--accent); }
    .page-link.disabled { opacity: 0.3; cursor: not-allowed; }
    .pagination-dots { color: var(--text-muted); padding: 0 5px; font-weight: bold; }
</style>

<div class="cat-wrapper">
    <header class="cat-header">
        <div class="cat-title">
            <h1>🏷️ Categorias</h1>
            <p>Gerencie as classificações de produtos da sua loja.</p>
        </div>
        <a href="/backend/categoria/criar" class="btn-add-cat">Nova Categoria</a>
    </header>

    <div class="cat-stats">
        <div class="cat-stat-card">
            <div class="cat-stat-icon"><i class="fa fa-tags"></i></div>
            <div class="cat-stat-info">
                <h3><?= count($categorias ?? []) ?></h3>
                <p>Total de Categorias</p>
            </div>
        </div>
        <div class="cat-stat-card">
            <div class="cat-stat-icon"><i class="fa fa-check-circle"></i></div>
            <div class="cat-stat-info">
                <h3><?= count(array_filter($categorias ?? [], fn($c) => empty($c['excluido_em']))) ?></h3>
                <p>Ativas</p>
            </div>
        </div>
    </div>

    <?php if (isset($categorias) && count($categorias) > 0): ?>
    <div class="table-container">
        <table class="cat-table">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Nome da Categoria</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th style="width: 120px; text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                <tr class="cat-row">
                    <td style="font-family: monospace; font-weight: 700; color: var(--text-muted);">#<?= str_pad($categoria['id_categorias'], 3, '0', STR_PAD_LEFT) ?></td>
                    <td style="font-weight: 800;"><?= htmlspecialchars($categoria['nome_categorias']) ?></td>
                    <td style="color: var(--text-muted); font-size: 0.9em;"><?= htmlspecialchars($categoria['descricao_categorias'] ?: 'Sem descrição informada') ?></td>
                    <td>
                        <?php if(!empty($categoria['excluido_em'])): ?>
                            <span class="badge-status status-inactive">Inativo</span>
                        <?php else: ?>
                            <span class="badge-status status-active">Ativo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-btns" style="justify-content: center;">
                            <a href="/backend/categoria/editar/<?= htmlspecialchars($categoria['id_categorias']) ?>" class="btn-edit-cat" title="Editar"><i class="fa fa-edit"></i></a>
                            <a href="/backend/categoria/excluir/<?= htmlspecialchars($categoria['id_categorias']) ?>" class="btn-del-cat" title="Excluir"><i class="fa fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        <div class="pagination-info" id="paginationInfo">Carregando...</div>
        <div class="pagination-buttons" id="paginationButtons"></div>
    </div>
    
    <?php else: ?>
        <div style="background: var(--bg-card); border: 2px dashed var(--border-color); padding: 60px; text-align: center; border-radius: 16px;">
            <i class="fa fa-search" style="font-size: 3em; color: var(--text-muted); margin-bottom: 20px; display: block;"></i>
            <h2 style="color: var(--text-main); font-weight: 800;">Nenhuma categoria encontrada</h2>
            <p style="color: var(--text-muted);">Sua loja ainda não possui categorias cadastradas.</p>
            <a href="/backend/categoria/criar" style="color: var(--accent); font-weight: 800; text-decoration: none; margin-top: 15px; display: inline-block;">Clique aqui para criar a primeira</a>
        </div>
    <?php endif; ?>
</div>

<script>
const rowsPerPage = 10;
let currentPage = 1;

function displayTable() {
    const rows = Array.from(document.querySelectorAll(".cat-row"));
    const totalPages = Math.ceil(rows.length / rowsPerPage);
    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    rows.forEach(row => row.style.display = "none");
    rows.slice(start, end).forEach(row => row.style.display = "");
    updatePaginationButtons(totalPages, rows.length);
}

function updatePaginationButtons(totalPages, total) {
    const container = document.getElementById("paginationButtons");
    const info = document.getElementById("paginationInfo");
    if (!container) return;
    container.innerHTML = "";
    info.innerText = `P\u00e1gina ${currentPage} de ${totalPages || 1} (${total} categorias)`;
    if (totalPages <= 1) return;
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
            if (i === currentPage - 1 && i > 2) { const d = document.createElement("span"); d.className = "pagination-dots"; d.innerText = "..."; container.appendChild(d); }
            container.appendChild(createBtn(i, i, i === currentPage));
            if (i === currentPage + 1 && i < totalPages - 1) { const d = document.createElement("span"); d.className = "pagination-dots"; d.innerText = "..."; container.appendChild(d); }
        }
    }
    container.appendChild(createBtn('<i class="fa fa-chevron-right"></i>', currentPage + 1, false, currentPage === totalPages));
}

document.addEventListener("DOMContentLoaded", displayTable);
</script>
