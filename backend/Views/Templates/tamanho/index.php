<style>
    /* Estilos de Tamanhos */
    .size-wrapper { padding-top: 10px; }
    
    .size-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .size-title h1 { font-size: 2em; font-weight: 800; color: var(--text-main); margin: 0; letter-spacing: -1px; }
    .size-title p { color: var(--text-muted); font-size: 0.9em; margin-top: 4px; }

    .btn-add-size {
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
    .btn-add-size:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(197, 160, 45, 0.3); background: var(--text-main); color: var(--bg-main); }

    /* Table Design */
    .table-container {
        background: var(--bg-card);
        border-radius: 16px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .size-table { width: 100%; border-collapse: collapse; }
    .size-table thead th { background: var(--bg-main); color: var(--accent); text-align: left; padding: 18px 20px; font-size: 0.75em; text-transform: uppercase; letter-spacing: 1px; font-weight: 800; border-bottom: 1px solid var(--border-color); }
    .size-table tbody td { padding: 18px 20px; border-bottom: 1px solid var(--border-color); color: var(--text-main); font-size: 0.95em; }
    .size-table tr:hover { background: rgba(var(--accent), 0.02); }

    .badge-status { padding: 5px 12px; border-radius: 8px; font-size: 10px; font-weight: 900; text-transform: uppercase; display: inline-block; }
    .status-active { background: rgba(46, 213, 115, 0.1); color: #2ed573; border: 1px solid rgba(46, 213, 115, 0.2); }
    .status-inactive { background: rgba(255, 71, 87, 0.1); color: #ff4757; border: 1px solid rgba(255, 71, 87, 0.2); }

    .action-btns { display: flex; gap: 8px; justify-content: center; }
    .btn-action-base { width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; border-radius: 8px; transition: 0.3s; border: 1px solid transparent; text-decoration: none; }
    
    .btn-edit-size { color: var(--accent); background: rgba(var(--accent), 0.1); }
    .btn-edit-size:hover { background: var(--accent); color: #000; }
    
    .btn-toggle-status { font-size: 0.8em; padding: 0 15px; width: auto; font-weight: 800; text-transform: uppercase; }
    .btn-activate { color: #2ed573; background: rgba(46, 213, 115, 0.1); }
    .btn-activate:hover { background: #2ed573; color: #fff; }
    .btn-deactivate { color: #ff4757; background: rgba(255, 71, 87, 0.1); }
    .btn-deactivate:hover { background: #ff4757; color: #fff; }

    /* Size Badge */
    .size-badge { background: var(--bg-main); padding: 4px 10px; border-radius: 6px; border: 1px solid var(--border-color); color: var(--text-main); font-weight: 700; font-family: monospace; }

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

<div class="size-wrapper">
    <header class="size-header">
        <div class="size-title">
            <h1>📏 Grade de Tamanhos</h1>
            <p>Gerencie as variações de dimensões e estoque por tamanho.</p>
        </div>
        <a href="/backend/tamanho/criar" class="btn-add-size">Novo Tamanho</a>
    </header>

    <?php if (isset($tamanhos) && count($tamanhos) > 0): ?>
    <div class="table-container">
        <table class="size-table">
            <thead>
                <tr>
                    <th style="width: 80px;">ID Grade</th>
                    <th style="width: 100px;">ID Prod.</th>
                    <th>Tamanho</th>
                    <th>Quantidade</th>
                    <th>Status</th>
                    <th style="width: 180px; text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tamanhos as $tamanho): ?>
                <tr class="size-row">
                    <td style="font-family: monospace; font-weight: 700; color: var(--text-muted);">#T<?= str_pad($tamanho['id_tamanhos'], 3, '0', STR_PAD_LEFT) ?></td>
                    <td style="font-family: monospace; color: var(--text-muted);">#P<?= str_pad($tamanho['id_produto'], 3, '0', STR_PAD_LEFT) ?></td>
                    <td><span class="size-badge"><?= htmlspecialchars($tamanho['tamanho_tamanhos']) ?></span></td>
                    <td style="font-weight: 700; color: var(--accent);"><?= htmlspecialchars($tamanho['quantidade_tamanhos']) ?> un.</td>
                    <td>
                        <?php if(!empty($tamanho['excluido_em'])): ?>
                            <span class="badge-status status-inactive">Inativo</span>
                        <?php else: ?>
                            <span class="badge-status status-active">Ativo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="/backend/tamanho/editar/<?= htmlspecialchars($tamanho['id_tamanhos']) ?>" class="btn-action-base btn-edit-size" title="Editar"><i class="fa fa-edit"></i></a>
                            
                            <?php if(!empty($tamanho['excluido_em'])): ?>
                                <a href="/backend/tamanho/excluir/<?= htmlspecialchars($tamanho['id_tamanhos']) ?>" class="btn-action-base btn-toggle-status btn-activate">Ativar</a>
                            <?php else: ?>
                                <a href="/backend/tamanho/excluir/<?= htmlspecialchars($tamanho['id_tamanhos']) ?>" class="btn-action-base btn-toggle-status btn-deactivate">Desativar</a>
                            <?php endif; ?>
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
            <i class="fa fa-ruler-combined" style="font-size: 3em; color: var(--text-muted); margin-bottom: 20px; display: block;"></i>
            <h2 style="color: var(--text-main); font-weight: 800;">Nenhum tamanho encontrado</h2>
            <p style="color: var(--text-muted);">Defina a grade de medidas para o estoque dos seus produtos.</p>
            <a href="/backend/tamanho/criar" style="color: var(--accent); font-weight: 800; text-decoration: none; margin-top: 15px; display: inline-block;">Adicionar primeiro tamanho</a>
        </div>
    <?php endif; ?>
</div>

<script>
const rowsPerPage = 10;
let currentPage = 1;
function displayTable() {
    const rows = Array.from(document.querySelectorAll(".size-row"));
    const totalPages = Math.ceil(rows.length / rowsPerPage);
    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
    const start = (currentPage - 1) * rowsPerPage;
    rows.forEach(row => row.style.display = "none");
    rows.slice(start, start + rowsPerPage).forEach(row => row.style.display = "");
    updatePaginationButtons(totalPages, rows.length);
}
function updatePaginationButtons(totalPages, total) {
    const container = document.getElementById("paginationButtons");
    const info = document.getElementById("paginationInfo");
    if (!container) return;
    container.innerHTML = "";
    info.innerText = `P\u00e1gina ${currentPage} de ${totalPages || 1} (${total} tamanhos)`;
    if (totalPages <= 1) return;
    const createBtn = (text, page, active = false, disabled = false) => {
        const btn = document.createElement("button");
        btn.innerHTML = text;
        btn.className = `page-link ${active ? 'active' : ''} ${disabled ? 'disabled' : ''}`;
        if (!disabled) btn.onclick = () => { currentPage = page; displayTable(); };
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
