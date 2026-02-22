<?php
// Calcular estatísticas de pedidos
$total_pedidos = $total_pedidos ?? 0;
$total_ativos = 0;
$total_inativos = 0;
$total_valor = 0;

foreach ($pedidos as $p) {
    if (empty($p['excluido_em'])) {
        $total_ativos++;
        $total_valor += $p['total_pedido'];
    } else {
        $total_inativos++;
    }
}
?>

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

    .btn-main-action {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background-color: var(--accent) !important;
        color: #000 !important;
        padding: 14px 24px;
        border-radius: 10px;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
        text-decoration: none;
        transition: 0.3s;
        border: none;
        box-shadow: 0 4px 15px rgba(242, 204, 125, 0.2);
        white-space: nowrap;
    }

    .btn-main-action:hover { background-color: var(--text-main) !important; color: var(--bg-main) !important; transform: scale(1.02); }

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

    /* --- TABELA DE PEDIDOS --- */
    .order-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
        color: var(--text-main);
    }

    .order-table thead th {
        color: var(--text-muted) !important;
        text-transform: uppercase;
        font-size: 11px;
        padding: 15px;
        letter-spacing: 1.5px;
        font-weight: 800;
    }

    .order-table tbody tr {
        background: var(--bg-card);
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    .order-table tbody tr:hover { 
        transform: scale(1.005);
        box-shadow: var(--shadow-md);
        border-color: var(--accent);
    }

    .order-table td { padding: 18px 15px !important; border: none; vertical-align: middle; }
    .order-table td:first-child { border-radius: 12px 0 0 12px; border-left: 1px solid var(--border-color); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); }
    .order-table td:last-child { border-radius: 0 12px 12px 0; border-right: 1px solid var(--border-color); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); }

    .id-column { font-family: monospace; color: var(--text-muted) !important; font-weight: bold; }
    .client-column { font-weight: 700; color: var(--text-main); font-size: 1.05em; }
    .price-column { color: var(--accent); font-weight: 800; font-size: 1.1em; }

    .badge-status { padding: 6px 14px; border-radius: 20px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge-pago, .badge-concluido, .badge-enviado { background: rgba(40, 167, 69, 0.1); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); }
    .badge-pendente, .badge-processamento { background: rgba(242, 204, 125, 0.1); color: var(--accent); border: 1px solid rgba(242, 204, 125, 0.3); }
    .badge-cancelado { background: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3); }

    .btn-action-small { padding: 8px 14px; border-radius: 8px; font-size: 11px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: 0.3s; margin: 0 2px; }
    .btn-view { background: var(--bg-main); color: #4dabf7; border: 1px solid #4dabf7; }
    .btn-view:hover { background: #4dabf7; color: #000; }
    .btn-edit { background: var(--bg-main); color: var(--accent); border: 1px solid var(--accent); }
    .btn-edit:hover { background: var(--accent); color: #000; }
    .btn-delete { background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: #dc3545; }
    .btn-delete:hover { background: #dc3545; color: #fff; transform: scale(1.05); box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2); }
    .btn-activate { background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.3); color: #28a745; }
    .btn-activate:hover { background: #28a745 !important; color: #fff !important; transform: scale(1.05); box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2); }

    /* Pedidos Excluídos */
    .tr-deleted td { 
        background: rgba(220, 53, 69, 0.08) !important; 
    }
    .tr-deleted td:first-child { 
        border-left: 4px solid #dc3545 !important;
    }


    .tr-deleted .price-column {
        text-decoration: line-through;
        color: var(--text-muted) !important;
    }

    .badge-deleted {
        background: #ff4444;
        color: #fff;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 9px;
        margin-left: 5px;
    }

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
    <h3 class="page-title"><i class="fas fa-shopping-cart" style="color: #f2cc7d;"></i> Gerenciar Pedidos</h3>

    <header class="header-breadcrumb">
        <h5><b><i class="fas fa-tachometer-alt"></i> Painel de Controle - Koketsu</b></h5>
    </header>

    <div class="actions-bar">
        <a href="/backend/pedido/criar" class="btn-main-action">
            <i class="fas fa-plus-circle"></i> Novo Pedido
        </a>

        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" id="orderInput" onkeyup="filterOrders()" placeholder="Buscar pedido por ID ou cliente..." class="search-input">
        </div>
    </div>

    <main>
        <table class="order-table" id="orderTable">
            <thead>
                <tr>
                    <th style="width: 100px;">ID</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Endereço</th>
                    <th style="text-align: right;">Total</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($pedidos) && is_array($pedidos) && count($pedidos) > 0): ?>
                    <?php foreach ($pedidos as $pedido): 
                        $is_deleted = !empty($pedido['excluido_em']);
                        $status = strtolower($pedido['status_pedido']);
                        $badge_class = 'badge-pendente';
                        
                        if (in_array($status, ['pago', 'enviado', 'concluido'])) {
                            $badge_class = 'badge-pago';
                        } elseif ($status === 'cancelado') {
                            $badge_class = 'badge-cancelado';
                        }
                    ?>
                    <tr class="order-row <?= $is_deleted ? 'tr-deleted' : '' ?>">
                        <td class="id-column order-id">
                            #<?= $pedido['id_pedido'] ?>
                            <?php if ($is_deleted): ?>
                                <span class="badge-deleted">EXCLUÍDO</span>
                            <?php endif; ?>
                        </td>
                        <td class="client-column"><?= htmlspecialchars($pedido['nome_cliente'] ?? 'N/A') ?></td>
                        <td style="color: #888; font-size: 0.9em;"><?= date('d/m/y H:i', strtotime($pedido['data_pedido'])) ?></td>
                        <td style="color: #999; font-size: 0.85em; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?= htmlspecialchars($pedido['endereco_perfil'] ?? 'Sem endereço') ?>
                        </td>
                        <td class="price-column" style="text-align: right;">R$ <?= number_format($pedido['total_pedido'], 2, ',', '.') ?></td>
                        
                        <td style="text-align: center;">
                            <span class="badge-status <?= $badge_class ?>">
                                <?= ucfirst(htmlspecialchars($pedido['status_pedido'])) ?>
                            </span>
                        </td>
                        
                        <td style="text-align: center;">
                            <a href="/backend/pedido/detalhes/<?= $pedido['id_pedido'] ?>" class="btn-action-small btn-view">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            
                            <?php if (!$is_deleted): ?>
                                <a href="/backend/pedido/editar/<?= $pedido['id_pedido'] ?>" class="btn-action-small btn-edit">
                                    <i class="fas fa-pencil-alt"></i> Editar
                                </a>
                                <a href="/backend/pedido/excluir/<?= $pedido['id_pedido'] ?>" class="btn-action-small btn-delete">
                                    <i class="fas fa-trash-alt"></i> Excluir
                                </a>
                            <?php else: ?>
                                <a href="/backend/pedido/ativar/<?= $pedido['id_pedido'] ?>" class="btn-action-small btn-activate">
                                    <i class="fas fa-check"></i> Ativar
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: #666;">
                            <i class="fas fa-folder-open" style="font-size: 48px; display: block; margin-bottom: 15px;"></i>
                            Nenhum pedido encontrado.
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
    const table = document.getElementById("orderTable");
    const allRows = Array.from(table.querySelectorAll(".order-row"));
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
    info.innerText = `Mostrando página ${currentPage} de ${totalPages || 1} (${totalActive} pedidos)`;
    if (totalPages <= 1) return;
    const createBtn = (text, page, isActive = false, isDisabled = false) => {
        const btn = document.createElement("button");
        btn.innerHTML = text;
        btn.className = `page-link ${isActive ? 'active' : ''} ${isDisabled ? 'disabled' : ''}`;
        if (!isDisabled) btn.onclick = () => { currentPage = page; displayTable(); };
        return btn;
    };
    container.appendChild(createBtn('<i class="fas fa-chevron-left"></i>', currentPage - 1, false, currentPage === 1));
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
    container.appendChild(createBtn('<i class="fas fa-chevron-right"></i>', currentPage + 1, false, currentPage === totalPages));
}

function filterOrders() {
    const filter = document.getElementById("orderInput").value.toUpperCase();
    const rows = document.querySelectorAll(".order-row");
    rows.forEach(row => {
        const id = row.querySelector(".order-id") ? row.querySelector(".order-id").textContent.toUpperCase() : "";
        const client = row.querySelector(".client-column") ? row.querySelector(".client-column").textContent.toUpperCase() : "";
        row.setAttribute('data-filtered', (id.includes(filter) || client.includes(filter)) ? 'true' : 'false');
    });
    currentPage = 1;
    displayTable();
}

document.addEventListener("DOMContentLoaded", displayTable);
</script>