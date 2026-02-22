<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* --- ESTILOS GERAIS --- */
    body { background-color: var(--bg-main) !important; margin: 0; font-family: 'Inter', sans-serif; color: var(--text-main); }
    .page-wrapper { padding: 20px; width: 100%; box-sizing: border-box; min-height: 100vh; }
    .page-title { font-size: 26px; font-weight: 800; margin-bottom: 5px; color: var(--text-main); text-transform: uppercase; letter-spacing: -1px; }
    .header-breadcrumb { color: var(--text-muted); margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid var(--border-color); }
    
    /* --- DASHBOARD CARDS --- */
    .dashboard-grid { display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap; }
    .stat-card { flex: 1; min-width: 220px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 24px; display: flex; align-items: center; justify-content: space-between; transition: all 0.3s ease; box-shadow: var(--shadow-sm); }
    .stat-card:hover { border-color: var(--accent); transform: translateY(-5px); box-shadow: var(--shadow-md); }
    .stat-icon { font-size: 28px; color: var(--accent); background: rgba(242, 204, 125, 0.1); width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; border-radius: 12px; }
    .stat-info h3 { margin: 0; font-size: 28px; color: var(--text-main); font-weight: 800; }
    .stat-info p { margin: 0; color: var(--text-muted); text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: 700; }

    /* --- ACTIONS & SEARCH --- */
    .actions-bar { display: flex; justify-content: flex-start; align-items: center; gap: 20px; margin-bottom: 25px; flex-wrap: wrap; }
    .btn-main-action { display: inline-flex; align-items: center; gap: 10px; background-color: var(--accent) !important; color: #000 !important; padding: 12px 24px; border-radius: 10px; font-weight: 700; text-transform: uppercase; font-size: 13px; text-decoration: none; transition: 0.3s; border: none; box-shadow: 0 4px 15px rgba(197, 160, 45, 0.2); }
    .btn-main-action:hover { background-color: var(--text-main) !important; color: var(--bg-main) !important; transform: scale(1.02); }

    .search-container { position: relative; flex: 1; max-width: 400px; }
    .search-container i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--accent); }
    .search-input { width: 100%; background: var(--bg-card); border: 1px solid var(--border-color); padding: 12px 15px 12px 45px; border-radius: 10px; color: var(--text-main); outline: none; transition: 0.3s; box-shadow: var(--shadow-sm); }
    .search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(197, 160, 45, 0.1); }

    /* --- TABLE --- */
    .user-table { width: 100%; border-collapse: separate; border-spacing: 0 12px; color: var(--text-main); }
    .user-table thead th { color: var(--text-muted) !important; text-transform: uppercase; font-size: 11px; padding: 10px 15px; letter-spacing: 1.5px; font-weight: 800; }
    .user-table tbody tr { background: var(--bg-card); transition: all 0.2s ease; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); }
    .user-table tbody tr:hover { transform: scale(1.005); box-shadow: var(--shadow-md); border-color: var(--accent); }
    .user-table tbody tr.tr-inativo td { background: rgba(220, 53, 69, 0.08) !important; }
    .user-table tbody tr.tr-inativo td:first-child { border-left: 4px solid #dc3545 !important; }
    .user-table td { padding: 16px 15px !important; border: none; vertical-align: middle; }
    .user-table td:first-child { border-radius: 12px 0 0 12px; border-left: 1px solid var(--border-color); }
    .user-table td:last-child { border-radius: 0 12px 12px 0; border-right: 1px solid var(--border-color); }
    .id-column { font-family: monospace; color: var(--text-muted) !important; font-weight: bold; }
    
    .badge-status { padding: 6px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
    .badge-active { background: rgba(40, 167, 69, 0.1); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); }
    .badge-inactive { background: rgba(220, 53, 69, 0.15) !important; color: #dc3545 !important; border: 1px solid rgba(220, 53, 69, 0.4); }

    .btn-action-small { padding: 8px 14px; border-radius: 8px; font-size: 11px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: 0.3s; margin: 0 2px; }
    .btn-edit { background: var(--bg-main); color: var(--accent); border: 1px solid var(--accent); }
    .btn-edit:hover { background: var(--accent); color: #000; }
    .btn-toggle { background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: #dc3545; }
    .btn-toggle:hover { background: #dc3545; color: #fff; transform: scale(1.1); box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3); }
    
    .btn-activate { background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.3); color: #28a745; }
    .btn-activate:hover { background: #28a745 !important; color: #fff !important; transform: scale(1.05); box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2); }

    /* --- PAGINAÇÃO (FIXED) --- */
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
    <h3 class="page-title"><i class="fas fa-users" style="color: #f2cc7d;"></i> Gerenciar Usuários</h3>

    <header class="header-breadcrumb">
        <h5><b><i class="fas fa-tachometer-alt"></i> Painel de Controle - Koketsu</b></h5>
    </header>

    <div class="dashboard-grid">
        <div class="stat-card" style="border-left: 4px solid #f2cc7d;">
            <div class="stat-info"><h3><?= $total_admin; ?></h3><p>Admins</p></div>
            <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #28a745;">
            <div class="stat-info"><h3><?= $total_ativos; ?></h3><p>Ativos</p></div>
            <div class="stat-icon" style="color:#28a745; background:rgba(40,167,69,0.1);"><i class="fas fa-signal"></i></div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #ff4444;">
            <div class="stat-info"><h3><?= $total_inativos; ?></h3><p style="color: #ff4444;">Inativos</p></div>
            <div class="stat-icon" style="color:#ff4444; background:rgba(255,68,68,0.1);"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #f2cc7d;">
            <div class="stat-info"><h3><?= $total_usuarios; ?></h3><p>Total Geral</p></div>
            <div class="stat-icon"><i class="fas fa-users"></i></div>
        </div>
    </div>

    <div class="actions-bar">
        <a href="/backend/usuario/criar" class="btn-main-action"><i class="fas fa-user-plus"></i> Novo Usuário</a>
        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" id="userInput" onkeyup="filterUsers()" placeholder="Buscar usuário por nome ou email..." class="search-input">
        </div>
    </div>

    <main>
        <table class="user-table" id="userTable">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Nível</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php foreach ($usuarios as $usuario): ?>
                <?php $is_inativo = !empty($usuario['excluido_em']); ?>
                <tr class="user-row <?= $is_inativo ? 'tr-inativo' : '' ?>"> 
                    <td class="id-column">#<?= $usuario['id_usuarios'] ?></td>
                    <td class="user-name" style="font-weight: 700; color: var(--text-main);"><?= htmlspecialchars($usuario['nome_usuarios']) ?></td>
                    <td class="email-column" style="color: #888;"><?= htmlspecialchars($usuario['email_usuarios']) ?></td>
                    <td><span style="color: #f2cc7d; font-size: 12px; font-weight: 800;"><i class="fas fa-circle" style="font-size: 8px;"></i> <?= strtoupper($usuario['nivel_acesso']) ?></span></td>
                    <td style="text-align: center;">
                        <span class="badge-status <?= $is_inativo ? 'badge-inactive' : 'badge-active' ?>"><?= $is_inativo ? 'Inativo' : 'Ativo' ?></span>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; justify-content: center; gap: 5px;">
                            <a href="/backend/usuario/editar/<?= $usuario['id_usuarios'] ?>" class="btn-action-small btn-edit" title="Editar"><i class="fas fa-pencil-alt"></i></a>
                            <?php if ($is_inativo): ?>
                                <a href="/backend/usuario/ativar/<?= $usuario['id_usuarios'] ?>" class="btn-action-small btn-activate" title="Ativar"><i class="fas fa-check"></i></a>
                            <?php else: ?>
                                <a href="/backend/usuario/excluir/<?= $usuario['id_usuarios'] ?>" class="btn-action-small btn-toggle" title="Desativar" onclick="return confirm('Deseja inativar este usuário?')"><i class="fas fa-power-off"></i></a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
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
    const table = document.getElementById("userTable");
    const allRows = Array.from(table.querySelectorAll(".user-row"));
    
    // Filtro
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

    info.innerText = `Mostrando página ${currentPage} de ${totalPages || 1} (${totalActive} usuários)`;

    if (totalPages <= 1) return;

    // Função auxiliar para criar botões
    const createBtn = (text, page, isActive = false, isDisabled = false) => {
        const btn = document.createElement("button");
        btn.innerHTML = text;
        btn.className = `page-link ${isActive ? 'active' : ''} ${isDisabled ? 'disabled' : ''}`;
        if (!isDisabled) btn.onclick = () => { currentPage = page; displayTable(); };
        return btn;
    };

    // Anterior
    container.appendChild(createBtn('<i class="fas fa-chevron-left"></i>', currentPage - 1, false, currentPage === 1));

    // Lógica Inteligente: Página 1 sempre aparece
    const range = 1; // Quantidade ao redor da atual

    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - range && i <= currentPage + range)) {
            
            // Reticências à esquerda
            if (i === currentPage - range && i > 2) {
                const dots = document.createElement("span");
                dots.className = "pagination-dots";
                dots.innerText = "...";
                container.appendChild(dots);
            }

            container.appendChild(createBtn(i, i, i === currentPage));

            // Reticências à direita
            if (i === currentPage + range && i < totalPages - 1) {
                const dots = document.createElement("span");
                dots.className = "pagination-dots";
                dots.innerText = "...";
                container.appendChild(dots);
            }
        }
    }

    // Próximo
    container.appendChild(createBtn('<i class="fas fa-chevron-right"></i>', currentPage + 1, false, currentPage === totalPages));
}

function filterUsers() {
    const filter = document.getElementById("userInput").value.toUpperCase();
    const rows = document.querySelectorAll(".user-row");

    rows.forEach(row => {
        const name = row.querySelector(".user-name").textContent.toUpperCase();
        const email = row.querySelector(".email-column").textContent.toUpperCase();
        row.setAttribute('data-filtered', (name.includes(filter) || email.includes(filter)) ? 'true' : 'false');
    });
    
    currentPage = 1;
    displayTable();
}

document.addEventListener("DOMContentLoaded", displayTable);
</script>