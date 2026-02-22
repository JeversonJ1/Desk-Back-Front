<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* --- ESTILOS GERAIS (KOKETSU UI) --- */
    body { background-color: var(--bg-main) !important; margin: 0; font-family: 'Inter', sans-serif; color: var(--text-main); }
    .page-wrapper { padding: 20px; width: 100%; box-sizing: border-box; min-height: 100vh; }
    .page-title { font-size: 28px; font-weight: 800; color: var(--text-main); text-transform: uppercase; margin-bottom: 5px; }
    .header-breadcrumb { color: var(--text-muted); margin-bottom: 25px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px; }

    /* --- CARD DO GRÁFICO --- */
    .chart-container-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 25px; margin-bottom: 35px; box-shadow: var(--shadow-md); }

    /* --- BARRA DE AÇÕES --- */
    .actions-bar { display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 30px; }
    .search-group { flex: 1; max-width: 500px; position: relative; }
    .search-input { width: 100%; background: var(--bg-card); border: 1px solid var(--border-color); padding: 15px 15px 15px 45px; border-radius: 12px; color: var(--text-main); font-size: 16px; transition: 0.3s; }
    .search-input:focus { border-color: var(--accent); outline: none; box-shadow: 0 0 0 3px rgba(197, 160, 45, 0.1); }
    .search-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--accent); }

    .btn-main-action { background: var(--accent); color: #000 !important; padding: 15px 25px; border-radius: 12px; font-weight: 800; text-decoration: none; text-transform: uppercase; font-size: 14px; transition: 0.3s; }
    .btn-main-action:hover { background: var(--text-main); color: var(--bg-main) !important; transform: translateY(-2px); }

    /* --- TABELA DE PRODUTOS --- */
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0 15px; }
    .custom-table thead th { color: var(--text-muted); text-transform: uppercase; font-size: 11px; padding: 10px 20px; font-weight: 800; }
    .custom-table tbody tr { background: var(--bg-card); transition: all 0.3s ease; border: 1px solid var(--border-color); }
    .custom-table tbody tr:hover { transform: scale(1.01); border-color: var(--accent); }
    .custom-table td { padding: 15px 20px !important; color: var(--text-main); vertical-align: middle; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); }
    .custom-table td:first-child { border-radius: 15px 0 0 15px; border-left: 1px solid var(--border-color); }
    .custom-table td:last-child { border-radius: 0 15px 15px 0; border-right: 1px solid var(--border-color); }

    .prod-img { width: 80px; height: 80px; object-fit: cover; border-radius: 12px; border: 2px solid var(--border-color); }
    .prod-name { font-weight: 700; color: var(--text-main); font-size: 17px; display: block; }

    /* --- STATUS E BOTÕES --- */
    .badge-status { padding: 6px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
    .status-ativo { background: rgba(40, 167, 69, 0.1); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); }
    .status-inativo { background: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3); }

    .btn-edit { background: var(--bg-main); color: var(--accent); border: 1px solid var(--accent); padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 13px; transition: 0.3s; }
    .btn-edit:hover { background: var(--accent); color: #000; }
    
    .btn-delete { background: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3); padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 13px; transition: 0.3s; }
    .btn-delete:hover { background: #dc3545; color: #fff; transform: scale(1.05); box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2); }
    
    .btn-activate { background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.3); color: #28a745; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 13px; transition: 0.3s; }
    .btn-activate:hover { background: #28a745 !important; color: #fff !important; transform: scale(1.05); box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2); }
    
    /* --- PAGINAÇÃO (FIXED) --- */
    .pagination-container { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding: 20px; background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-color); }
    .pagination-buttons { display: flex; align-items: center; gap: 8px; }
    .page-link { padding: 8px 16px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 8px; cursor: pointer; font-weight: 700; transition: 0.3s; font-size: 13px; }
    .page-link:hover:not(.disabled) { border-color: var(--accent); color: var(--accent); }
    .page-link.active { background: var(--accent); color: #000; border-color: var(--accent); }
    .page-link.disabled { opacity: 0.3; cursor: not-allowed; }
    .pagination-dots { color: var(--text-muted); padding: 0 5px; font-weight: bold; }
    .tr-inativo td { background: rgba(220, 53, 69, 0.08) !important; }
    .tr-inativo td:first-child { border-left: 4px solid #dc3545 !important; }
</style>

<div class="page-wrapper">
    <h3 class="page-title"><i class="fas fa-cubes" style="color: #f2cc7d;"></i> Gerenciar Produtos</h3>
    <header class="header-breadcrumb">
        <h5><b><i class="fas fa-tachometer-alt"></i> Painel de produtos - Koketsu</b></h5>
    </header>

    <div class="chart-container-card">
        <h4 style="color: #f2cc7d; margin-top: 0; font-weight: 700;"><i class="fas fa-chart-bar"></i> Estoque por Categoria</h4>
        <div style="height: 300px;">
            <canvas id="vendasChart"></canvas>
        </div>
    </div>

    <div class="actions-bar">
        <div class="search-group">
            <i class="fas fa-search"></i>
            <input type="text" id="inputBusca" class="search-input" placeholder="Buscar produto por nome...">
        </div>
        <a href="/backend/produtos/criar" class="btn-main-action"><i class="fas fa-plus-circle"></i> Novo Produto</a>
    </div>

    <main>
        <table class="custom-table" id="tabelaProdutos">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Informações</th>
                    <th>Descrição</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $p): 
                    $is_inativo = !empty($p['excluido_em']); 
                ?>
                <tr class="item-produto <?= $is_inativo ? 'tr-inativo' : '' ?>"> 
                    <td width="100">
                        <img src="/backend/upload/<?= htmlspecialchars($p['imagem_produtos']); ?>" class="prod-img" onerror="this.src='https://placehold.co/100x100?text=Sem+Foto'">
                    </td>
                    <td>
                        <span class="prod-name nome-produto"><?= htmlspecialchars($p['nome_produtos']); ?></span>
                        <small style="color: #666;">ID: #<?= $p['id_produto'] ?></small>
                    </td>
                    <td style="max-width: 300px; color: #aaa; font-size: 14px;">
                        <?= htmlspecialchars($p['descricao_produtos']); ?>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge-status <?= $is_inativo ? 'status-inativo' : 'status-ativo' ?>">
                            <?= $is_inativo ? 'Inativo' : 'Ativo' ?>
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="/backend/produtos/editar/<?= $p['id_produto']; ?>" class="btn-edit" title="Editar"><i class="fas fa-pencil-alt"></i></a>
                            <?php if ($is_inativo): ?>
                                <a href="/backend/produtos/ativar/<?= $p['id_produto'] ?>" class="btn-activate" title="Ativar"><i class="fas fa-check"></i></a>
                            <?php else: ?>
                                <a href="/backend/produtos/excluir/<?= $p['id_produto'] ?>" class="btn-delete" title="Inativar" onclick="return confirm('Deseja inativar este produto?')"><i class="fas fa-trash-alt"></i></a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="pagination-container">
            <div id="paginationInfo" style="color: var(--text-muted); font-weight: 600;"></div>
            <div class="pagination-buttons" id="paginationButtons"></div>
        </div>
    </main>
</div>

<script>
    const rowsPerPage = 10;
    let currentPage = 1;

    function displayTable() {
        const table = document.getElementById("tabelaProdutos");
        const allRows = Array.from(table.querySelectorAll("tbody .item-produto"));
        
        const filteredRows = allRows.filter(row => row.getAttribute('data-filtered') !== 'false');
        
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        allRows.forEach(row => row.style.display = "none");
        filteredRows.slice(start, end).forEach(row => row.style.display = "");

        renderButtons(totalPages, filteredRows.length);
    }

    function renderButtons(totalPages, totalItems) {
        const container = document.getElementById("paginationButtons");
        const info = document.getElementById("paginationInfo");
        container.innerHTML = "";

        info.innerText = `Mostrando ${totalItems} produtos (Página ${currentPage} de ${totalPages || 1})`;

        if (totalPages <= 1) return;

        const createBtn = (content, targetPage, active = false, disabled = false) => {
            const btn = document.createElement("button");
            btn.innerHTML = content;
            btn.className = `page-link ${active ? 'active' : ''} ${disabled ? 'disabled' : ''}`;
            if(!disabled) btn.onclick = () => { currentPage = targetPage; displayTable(); };
            return btn;
        };

        // Botão Anterior
        container.appendChild(createBtn('<i class="fas fa-chevron-left"></i>', currentPage - 1, false, currentPage === 1));

        const range = 1; // Quantidade de páginas adjacentes para exibir

        for (let i = 1; i <= totalPages; i++) {
            // Lógica para mostrar: Primeira, Última, e as que rodeiam a atual
            if (i === 1 || i === totalPages || (i >= currentPage - range && i <= currentPage + range)) {
                
                // Adiciona reticências à esquerda
                if (i === currentPage - range && i > 2) {
                    const dots = document.createElement("span");
                    dots.className = "pagination-dots";
                    dots.innerText = "...";
                    container.appendChild(dots);
                }

                container.appendChild(createBtn(i, i, i === currentPage));

                // Adiciona reticências à direita
                if (i === currentPage + range && i < totalPages - 1) {
                    const dots = document.createElement("span");
                    dots.className = "pagination-dots";
                    dots.innerText = "...";
                    container.appendChild(dots);
                }
            }
        }

        // Botão Próximo
        container.appendChild(createBtn('<i class="fas fa-chevron-right"></i>', currentPage + 1, false, currentPage === totalPages));
    }

    document.getElementById('inputBusca').addEventListener('keyup', function() {
        const busca = this.value.toLowerCase();
        const rows = document.querySelectorAll('.item-produto');

        rows.forEach(row => {
            const nome = row.querySelector('.nome-produto').textContent.toLowerCase();
            row.setAttribute('data-filtered', nome.includes(busca) ? 'true' : 'false');
        });

        currentPage = 1;
        displayTable();
    });

    // Gráfico - Mantido conforme original
    const dadosGrafico = <?php echo json_encode($produto); ?>; 
    if (dadosGrafico && dadosGrafico.length > 0) {
        const style = getComputedStyle(document.body);
        new Chart(document.getElementById('vendasChart'), {
            type: 'bar',
            data: {
                labels: dadosGrafico.map(item => item.produto),
                datasets: [{
                    label: 'Estoque',
                    data: dadosGrafico.map(item => item.total),
                    backgroundColor: style.getPropertyValue('--accent').trim() || '#f2cc7d',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#888' }, grid: { display: false } },
                    y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#888' } }
                }
            }
        });
    }

    document.addEventListener("DOMContentLoaded", displayTable);
</script>