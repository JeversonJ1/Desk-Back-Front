<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
  body { background-color: var(--bg-main) !important; margin: 0; font-family: Arial, sans-serif; color: var(--text-main); }
  .page-wrapper { padding: 0; width: 100%; box-sizing: border-box; min-height: 100vh; }

  /* --- HEADER --- */
  .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px; }
  .page-header-left h1 { font-size: 26px; font-weight: 900; color: var(--text-main); text-transform: uppercase; letter-spacing: -1px; margin: 0; }
  .page-header-left p { font-size: 13px; color: var(--text-muted); margin: 4px 0 0; }
  .btn-main-action {
    background: linear-gradient(135deg, #F2C84B, #f0a500); color: #000 !important;
    padding: 13px 24px; border-radius: 12px; font-weight: 800;
    text-decoration: none; text-transform: uppercase; font-size: 13px;
    transition: .3s; box-shadow: 0 4px 15px rgba(242,200,75,.25);
    display: inline-flex; align-items: center; gap: 8px;
  }
  .btn-main-action:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(242,200,75,.4); }

  /* --- STAT CARDS --- */
  .stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 28px; }
  @media(max-width:900px){ .stat-grid { grid-template-columns: repeat(2,1fr); } }
  .stat-card {
    background: var(--bg-card); border: 2px solid var(--border-color);
    border-radius: 16px; padding: 20px; display: flex;
    align-items: center; justify-content: space-between;
    transition: .3s; position: relative; overflow: hidden;
  }
  .stat-card:hover { border-color: var(--accent); transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0,0,0,.5), 0 0 0 1px var(--accent-dim); }
  .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; background: var(--sc-color,#F2C84B); }
  .stat-info h3 { margin:0; font-size:28px; font-weight:900; color:var(--text-main); }
  .stat-info p { margin:0; color:var(--text-muted); text-transform:uppercase; font-size:10px; letter-spacing:1px; font-weight:700; }
  .stat-icon {
    font-size:22px; color:var(--sc-color,#F2C84B);
    background:var(--sc-bg,rgba(242,200,75,.1));
    width:50px; height:50px; border-radius:14px;
    display:flex; align-items:center; justify-content:center;
  }

  /* --- ACTIONS BAR --- */
  .actions-bar { display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:24px; flex-wrap:wrap; }
  .search-group { flex:1; max-width:480px; position:relative; }
  .search-input {
    width:100%; background:var(--bg-card-flat); border: 2px solid var(--border-color);
    padding:12px 12px 12px 44px; border-radius:12px; color:var(--text-main);
    font-size:14px; transition:.3s; outline:none;
  }
  .search-input:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-glow); }
  .search-group i { position:absolute; left:15px; top:50%; transform:translateY(-50%); color:var(--accent); }
  .filter-btns { display:flex; gap:8px; }
  .filter-btn {
    padding:10px 16px; border-radius:10px; font-size:12px; font-weight:700;
    cursor:pointer; border:1px solid var(--border-color); background:var(--bg-card);
    color:var(--text-muted); transition:.2s; text-transform:uppercase; letter-spacing:.05em;
  }
  .filter-btn.active { background:var(--accent); color:#000; border-color:var(--accent); }
  .filter-btn:hover:not(.active) { border-color:var(--accent); color:var(--accent); }

  /* --- GRÁFICO --- */
  .chart-card { background:var(--bg-card); border:1px solid var(--border-color); border-radius:16px; padding:24px; margin-bottom:28px; }
  .chart-card h4 { color:var(--accent); margin:0 0 16px; font-size:14px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; }
  .chart-wrap { height:240px; }

  /* --- TABELA --- */
  .table-card { background:var(--bg-card); border: 2px solid var(--border-color); border-radius:16px; overflow:hidden; }
  .table-head { display:flex; justify-content:space-between; align-items:center; padding:18px 24px; border-bottom:1px solid var(--border-color); }
  .table-title { font-size:14px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--text-main); }
  .table-count { font-size:12px; color:var(--text-muted); font-weight:600; }

  .custom-table { width:100%; border-collapse:collapse; }
  .custom-table thead th {
    color: var(--accent) !important;
    background-color: var(--bg-card-flat) !important;
    text-transform:uppercase; font-size:10px;
    padding:14px 20px; font-weight:800; letter-spacing:1px;
    border-bottom: 2px solid var(--border-color); text-align:left;
  }
  .custom-table tbody tr { border-bottom:1px solid rgba(255,255,255,.04); transition:.2s; }
  .custom-table tbody tr:hover { background: var(--accent-dim) !important; }
  .custom-table tbody tr.tr-inativo { background:rgba(220,53,69,.05); }
  .custom-table tbody tr.tr-inativo td:first-child { border-left:3px solid #dc3545; }
  .custom-table td { padding:16px 20px; color:var(--text-main); vertical-align:middle; }

  .prod-img { width:64px; height:64px; object-fit:cover; border-radius:12px; border:1px solid var(--border-color); }
  .prod-name { font-weight:700; color:var(--text-main); font-size:14px; display:block; margin-bottom:2px; }
  .prod-id { font-size:11px; color:var(--text-muted); font-family:monospace; }
  .prod-price { font-weight:800; font-size:15px; color:var(--accent); }
  .prod-installment { font-size:10px; color:var(--text-muted); margin-top:2px; }

  .stock-bar { width:80px; }
  .stock-num { font-size:16px; font-weight:900; }
  .stock-progress { height:4px; border-radius:2px; background:var(--border-color); margin-top:6px; overflow:hidden; }
  .stock-fill { height:100%; border-radius:2px; }

  .badge-status { padding:5px 12px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; }
  .status-ativo   { background:rgba(40,167,69,.12); color:#28a745; border:1px solid rgba(40,167,69,.3); }
  .status-inativo { background:rgba(220,53,69,.12); color:#dc3545; border:1px solid rgba(220,53,69,.3); }

  .btn-edit {
    background:var(--bg-main); color:var(--accent); border:1px solid var(--accent);
    padding:7px 14px; border-radius:8px; text-decoration:none;
    font-weight:700; font-size:12px; transition:.2s; display:inline-flex; align-items:center; gap:5px;
  }
  .btn-edit:hover { background:var(--accent); color:#000; }
  .btn-delete {
    background:rgba(220,53,69,.1); color:#dc3545; border:1px solid rgba(220,53,69,.3);
    padding:7px 14px; border-radius:8px; text-decoration:none;
    font-weight:700; font-size:12px; transition:.2s; display:inline-flex; align-items:center; gap:5px;
  }
  .btn-delete:hover { background:#dc3545; color:#fff; border-color:#dc3545; }
  .btn-activate {
    background:rgba(40,167,69,.1); border:1px solid rgba(40,167,69,.3); color:#28a745;
    padding:7px 14px; border-radius:8px; text-decoration:none;
    font-weight:700; font-size:12px; transition:.2s; display:inline-flex; align-items:center; gap:5px;
  }
  .btn-activate:hover { background:#28a745; color:#fff; }

  /* --- PAGINAÇÃO --- */
  .pagination-container {
    display:flex; justify-content:space-between; align-items:center;
    padding:16px 24px; border-top:1px solid var(--border-color);
  }
  .pagination-buttons { display:flex; gap:6px; }
  .page-link {
    padding:8px 14px; background:var(--bg-main); border:1px solid var(--border-color);
    color:var(--text-main); border-radius:8px; cursor:pointer;
    font-weight:700; transition:.2s; font-size:12px;
  }
  .page-link:hover:not(.disabled) { border-color:var(--accent); color:var(--accent); }
  .page-link.active { background:var(--accent); color:#000; border-color:var(--accent); }
  .page-link.disabled { opacity:.3; cursor:not-allowed; }
  .pagination-dots { color:var(--text-muted); padding:0 4px; font-weight:bold; }

  /* Empty State */
  .empty-state { text-align:center; padding:60px 24px; }
  .empty-state i { font-size:48px; color:var(--border-color); display:block; margin-bottom:16px; }
  .empty-state p { color:var(--text-muted); font-size:14px; }
</style>

<?php
$totalAtivos   = count(array_filter($produtos, fn($p) => empty($p['excluido_em'])));
$totalInativos = count(array_filter($produtos, fn($p) => !empty($p['excluido_em'])));
$valorInventario = array_sum(array_map(fn($p) => (float)$p['preco_produtos'] * (int)$p['estoque_produtos'], array_filter($produtos, fn($p) => empty($p['excluido_em']))));
$estoqueTotal    = array_sum(array_map(fn($p) => (int)$p['estoque_produtos'], array_filter($produtos, fn($p) => empty($p['excluido_em']))));
?>

<div class="page-wrapper">

  <!-- HEADER -->
  <div class="page-header">
    <div class="page-header-left">
      <h1><i class="fas fa-boxes" style="color:#F2C84B;"></i> Produtos</h1>
      <p>Gerencie o catálogo completo da Koketsu Grife</p>
    </div>
    <a href="/backend/produtos/criar" class="btn-main-action">
      <i class="fas fa-plus-circle"></i> Novo Produto
    </a>
  </div>

  <!-- STATS -->
  <div class="stat-grid">
    <div class="stat-card" style="--sc-color:#F2C84B; --sc-bg:rgba(242,200,75,.1);">
      <div class="stat-info">
        <h3><?= $totalAtivos ?></h3>
        <p>Produtos Ativos</p>
      </div>
      <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
    </div>
    <div class="stat-card" style="--sc-color:#dc3545; --sc-bg:rgba(220,53,69,.1);">
      <div class="stat-info">
        <h3><?= $totalInativos ?></h3>
        <p>Inativados</p>
      </div>
      <div class="stat-icon"><i class="fas fa-ban"></i></div>
    </div>
    <div class="stat-card" style="--sc-color:#4E9EBF; --sc-bg:rgba(78,158,191,.1);">
      <div class="stat-info">
        <h3><?= number_format($estoqueTotal, 0, ',', '.') ?></h3>
        <p>Unidades em Estoque</p>
      </div>
      <div class="stat-icon"><i class="fas fa-warehouse"></i></div>
    </div>
    <div class="stat-card" style="--sc-color:#C47A3A; --sc-bg:rgba(196,122,58,.1);">
      <div class="stat-info">
        <h3>R$ <?= number_format($valorInventario, 0, ',', '.') ?></h3>
        <p>Valor do Inventário</p>
      </div>
      <div class="stat-icon"><i class="fas fa-chart-pie"></i></div>
    </div>
  </div>

  <!-- GRÁFICO -->
  <?php if (!empty($produto)): ?>
  <div class="chart-card">
    <h4><i class="fas fa-chart-bar"></i> Estoque por Categoria</h4>
    <div class="chart-wrap">
      <canvas id="vendasChart"></canvas>
    </div>
  </div>
  <?php endif; ?>

  <!-- TABELA -->
  <div class="table-card">
    <div class="table-head">
      <span class="table-title"><i class="fas fa-list" style="color:#F2C84B;"></i> Lista de Produtos</span>
      <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <div class="search-group" style="max-width:320px;">
          <i class="fas fa-search"></i>
          <input type="text" id="inputBusca" class="search-input" placeholder="Buscar por nome...">
        </div>
        <div class="filter-btns">
          <button class="filter-btn active" data-filter="todos">Todos</button>
          <button class="filter-btn" data-filter="ativo">Ativos</button>
          <button class="filter-btn" data-filter="inativo">Inativos</button>
        </div>
        <span class="table-count" id="tableCount"><?= count($produtos) ?> produtos</span>
      </div>
    </div>

    <div style="overflow-x:auto;">
      <table class="custom-table" id="tabelaProdutos">
        <thead>
          <tr>
            <th width="80">Foto</th>
            <th>Produto</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Estoque</th>
            <th style="text-align:center;">Status</th>
            <th style="text-align:center;">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($produtos as $p):
            $is_inativo = !empty($p['excluido_em']);
            $estoqueNum = (int)$p['estoque_produtos'];
            $stockPct   = min(100, $estoqueNum / 2); // max visual ~200 unidades
            $stockColor = $estoqueNum <= 5 ? '#dc3545' : ($estoqueNum <= 20 ? '#ffc107' : '#28a745');
            $parcs = number_format($p['preco_produtos'] / 6, 2, ',', '.');
          ?>
          <tr class="item-produto <?= $is_inativo ? 'tr-inativo' : '' ?>"
              data-status="<?= $is_inativo ? 'inativo' : 'ativo' ?>">
            <td>
              <img src="/backend/upload/<?= htmlspecialchars($p['imagem_produtos']) ?>"
                   class="prod-img"
                   onerror="this.src='https://placehold.co/64x64?text=📦'">
            </td>
            <td>
              <span class="prod-name nome-produto"><?= htmlspecialchars($p['nome_produtos']) ?></span>
              <span class="prod-id">#<?= $p['id_produto'] ?></span>
            </td>
            <td style="max-width:220px; color:var(--text-muted); font-size:13px;">
              <?= htmlspecialchars(mb_strimwidth($p['descricao_produtos'] ?? '', 0, 80, '...')) ?>
            </td>
            <td>
              <div class="prod-price">R$ <?= number_format((float)$p['preco_produtos'],2,',','.') ?></div>
              <div class="prod-installment">6x de R$ <?= $parcs ?></div>
            </td>
            <td>
              <div class="stock-bar">
                <div class="stock-num" style="color:<?= $stockColor ?>"><?= $estoqueNum ?></div>
                <div class="stock-progress">
                  <div class="stock-fill" style="width:<?= $stockPct ?>%;background:<?= $stockColor ?>;"></div>
                </div>
              </div>
            </td>
            <td style="text-align:center;">
              <span class="badge-status <?= $is_inativo ? 'status-inativo' : 'status-ativo' ?>">
                <?= $is_inativo ? 'Inativo' : 'Ativo' ?>
              </span>
            </td>
            <td style="text-align:center;">
              <div style="display:flex;gap:6px;justify-content:center;">
                <a href="/backend/produtos/editar/<?= $p['id_produto'] ?>" class="btn-edit" title="Editar">
                  <i class="fas fa-pencil-alt"></i>
                </a>
                <?php if ($is_inativo): ?>
                <a href="/backend/produtos/ativar/<?= $p['id_produto'] ?>" class="btn-activate" title="Ativar">
                  <i class="fas fa-check"></i>
                </a>
                <?php else: ?>
                <a href="/backend/produtos/excluir/<?= $p['id_produto'] ?>" class="btn-delete"
                   title="Inativar" onclick="return confirm('Deseja inativar este produto?')">
                  <i class="fas fa-trash-alt"></i>
                </a>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if(empty($produtos)): ?>
    <div class="empty-state">
      <i class="fas fa-box-open"></i>
      <p>Nenhum produto cadastrado ainda.</p>
      <a href="/backend/produtos/criar" class="btn-main-action" style="display:inline-flex;margin-top:16px;">
        <i class="fas fa-plus"></i> Cadastrar Produto
      </a>
    </div>
    <?php endif; ?>

    <div class="pagination-container">
      <div id="paginationInfo" style="color:var(--text-muted);font-weight:600;font-size:13px;"></div>
      <div class="pagination-buttons" id="paginationButtons"></div>
    </div>
  </div>

</div>

<script>
const rowsPerPage = 12;
let currentPage = 1;
let currentFilter = 'todos';

// Filter buttons
document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    currentFilter = this.dataset.filter;
    currentPage = 1;
    applyFilters();
    displayTable();
  });
});

function applyFilters() {
  const busca = document.getElementById('inputBusca').value.toLowerCase();
  document.querySelectorAll('.item-produto').forEach(row => {
    const nome   = row.querySelector('.nome-produto')?.textContent.toLowerCase() || '';
    const status = row.dataset.status;
    const matchSearch = nome.includes(busca);
    const matchFilter = currentFilter === 'todos' || status === currentFilter;
    row.setAttribute('data-filtered', matchSearch && matchFilter ? 'true' : 'false');
  });
}

function displayTable() {
  const allRows = Array.from(document.querySelectorAll('.item-produto'));
  const filtered = allRows.filter(r => r.getAttribute('data-filtered') !== 'false');
  const totalPages = Math.ceil(filtered.length / rowsPerPage);
  if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
  const start = (currentPage - 1) * rowsPerPage;

  allRows.forEach(r => r.style.display = 'none');
  filtered.slice(start, start + rowsPerPage).forEach(r => r.style.display = '');

  document.getElementById('tableCount').textContent = `${filtered.length} produto${filtered.length !== 1 ? 's' : ''}`;
  renderPagination(totalPages, filtered.length, start);
}

function renderPagination(totalPages, total, start) {
  const container = document.getElementById('paginationButtons');
  const info = document.getElementById('paginationInfo');
  container.innerHTML = '';
  const end = Math.min(start + rowsPerPage, total);
  info.textContent = total === 0 ? 'Nenhum resultado' : `Exibindo ${start+1}–${end} de ${total} produtos`;
  if (totalPages <= 1) return;

  const mk = (html, page, active=false, disabled=false) => {
    const btn = document.createElement('button');
    btn.innerHTML = html; btn.className = `page-link ${active?'active':''} ${disabled?'disabled':''}`;
    if (!disabled) btn.onclick = () => { currentPage = page; displayTable(); };
    return btn;
  };
  container.appendChild(mk('<i class="fas fa-chevron-left"></i>', currentPage-1, false, currentPage===1));
  for (let i=1; i<=totalPages; i++) {
    if (i===1 || i===totalPages || (i>=currentPage-1 && i<=currentPage+1)) {
      if (i===currentPage-1 && i>2) { const d=document.createElement('span'); d.className='pagination-dots'; d.textContent='...'; container.appendChild(d); }
      container.appendChild(mk(i, i, i===currentPage));
      if (i===currentPage+1 && i<totalPages-1) { const d=document.createElement('span'); d.className='pagination-dots'; d.textContent='...'; container.appendChild(d); }
    }
  }
  container.appendChild(mk('<i class="fas fa-chevron-right"></i>', currentPage+1, false, currentPage===totalPages));
}

document.getElementById('inputBusca').addEventListener('input', () => {
  currentPage = 1; applyFilters(); displayTable();
});

// Gráfico
const dadosGrafico = <?= json_encode($produto ?? []) ?>;
if (dadosGrafico && dadosGrafico.length > 0) {
  new Chart(document.getElementById('vendasChart').getContext('2d'), {
    type: 'bar',
    data: {
      labels: dadosGrafico.map(d => d.categoria),
      datasets: [{
        label: 'Estoque',
        data: dadosGrafico.map(d => d.total),
        backgroundColor: dadosGrafico.map((_, i) => i % 2 === 0 ? '#F2C84B' : 'rgba(242,200,75,.5)'),
        borderRadius: 8, borderSkipped: false
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { color: '#888', font: { size: 11 } } },
        y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#888', font: { size: 11 } } }
      }
    }
  });
}

document.addEventListener('DOMContentLoaded', () => { applyFilters(); displayTable(); });
</script>