<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
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

  /* --- GRÁFICOS --- */
  .charts-row { display:grid; grid-template-columns:2fr 1fr; gap:16px; margin-bottom:28px; }
  @media(max-width:900px){ .charts-row { grid-template-columns:1fr; } }
  .chart-card { background:var(--bg-card); border:1px solid var(--border-color); border-radius:16px; padding:24px; position:relative; overflow:hidden; }
  .chart-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg,#F2C84B,#C47A3A); }
  .chart-card-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:10px; }
  .chart-card-header h4 { color:var(--accent); margin:0; font-size:13px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; }
  .chart-filter-btns { display:flex; gap:6px; }
  .chart-filter-btn { padding:5px 12px; border-radius:8px; font-size:11px; font-weight:700; cursor:pointer; border:1px solid var(--border-color); background:transparent; color:var(--text-muted); transition:.2s; text-transform:uppercase; }
  .chart-filter-btn.active { background:var(--accent); color:#000; border-color:var(--accent); }
  .chart-filter-btn:hover:not(.active) { border-color:var(--accent); color:var(--accent); }
  .chart-wrap { height:260px; position:relative; }
  .chart-wrap-donut { height:260px; }

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

  .prod-img { width:160px; height:160px; object-fit:cover; border-radius:14px; border:2px solid var(--border-color); box-shadow: 0 4px 12px rgba(0,0,0,.4); transition:.2s; position:relative; z-index:1; }
  .prod-img:hover { transform:scale(1.25); border-color:var(--accent); box-shadow:0 8px 30px rgba(242,200,75,.4); z-index:10; }

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

  <!-- GRÁFICOS -->
  <?php if (!empty($produto)): ?>
  <div class="charts-row">
    <div class="chart-card">
      <div class="chart-card-header">
        <h4><i class="fas fa-chart-bar"></i> <span id="barChartTitle">Estoque por Categoria</span></h4>
        <div class="chart-filter-btns">
          <button class="chart-filter-btn active" data-metric="estoque" onclick="switchMetric(this,'estoque')">Estoque</button>
          <button class="chart-filter-btn" data-metric="valor" onclick="switchMetric(this,'valor')">Valor (R$)</button>
          <button class="chart-filter-btn" data-metric="produtos" onclick="switchMetric(this,'produtos')">Qtd. Produtos</button>
        </div>
      </div>
      <div class="chart-wrap">
        <canvas id="barChart"></canvas>
      </div>
    </div>
    <div class="chart-card">
      <div class="chart-card-header">
        <h4><i class="fas fa-chart-pie"></i> Distribuição</h4>
      </div>
      <div class="chart-wrap-donut">
        <canvas id="donutChart"></canvas>
      </div>
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

    <div style="overflow-x:auto;" id="tabelaWrapper">
      <table class="custom-table" id="tabelaProdutos">
        <thead>
          <tr>
            <th width="110">Foto</th>
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
              <?php 
                $imgRaw = $p['imagem_produtos'] ?? '';
                if (empty($imgRaw)) {
                    $imgSrc = 'https://placehold.co/160x160?text=📦';
                } else if (str_starts_with($imgRaw, 'http') || str_starts_with($imgRaw, '/')) {
                    $imgSrc = $imgRaw;
                } else {
                    $imgSrc = '/backend/upload/' . $imgRaw;
                }
              ?>
              <img src="<?= htmlspecialchars($imgSrc) ?>"
                   class="prod-img"
                   onerror="this.src='https://placehold.co/160x160?text=📦'">
            </td>
            <td>
              <span class="prod-name nome-produto"><?= htmlspecialchars($p['nome_produtos']) ?></span>
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

// ── Gráficos ─────────────────────────────────────────────────────
const dadosGrafico = <?= json_encode($produto ?? []) ?>;
const allProdutos  = <?= json_encode($produtos ?? []) ?>;

const PALETTE = ['#F2C84B','#C47A3A','#4E9EBF','#7B5EA7','#3AA87B','#E05252','#5B8FF9','#F58E2E','#A0D45E','#E07BB5'];

let barChartInstance = null;
let donutInstance    = null;

function getBarData(metric) {
  if (!dadosGrafico || !dadosGrafico.length) return null;
  const labels = dadosGrafico.map(d => d.categoria);
  let data, label;
  if (metric === 'estoque') {
    data  = dadosGrafico.map(d => Number(d.total));
    label = 'Estoque';
  } else if (metric === 'valor') {
    // Calcula valor de inventário por categoria usando allProdutos
    const map = {};
    dadosGrafico.forEach(d => map[d.categoria] = 0);
    allProdutos.forEach(p => {
      if (!p.excluido_em && p.categoria && map[p.categoria] !== undefined)
        map[p.categoria] += parseFloat(p.preco_produtos||0) * parseInt(p.estoque_produtos||0);
    });
    data  = dadosGrafico.map(d => Math.round(map[d.categoria] || 0));
    label = 'Valor (R$)';
  } else {
    // contagem de produtos por categoria
    const map = {};
    dadosGrafico.forEach(d => map[d.categoria] = 0);
    allProdutos.forEach(p => { if (!p.excluido_em && p.categoria && map[p.categoria]!==undefined) map[p.categoria]++; });
    data  = dadosGrafico.map(d => map[d.categoria] || 0);
    label = 'Qtd. Produtos';
  }
  return { labels, data, label };
}

function buildGradients(ctx, count) {
  return Array.from({length: count}, (_, i) => {
    const g = ctx.createLinearGradient(0, 0, 0, 260);
    const base = PALETTE[i % PALETTE.length];
    g.addColorStop(0, base);
    g.addColorStop(1, base + '55');
    return g;
  });
}

function renderBarChart(metric) {
  const d = getBarData(metric);
  if (!d) return;
  const ctx = document.getElementById('barChart').getContext('2d');
  const grads = buildGradients(ctx, d.labels.length);
  if (barChartInstance) barChartInstance.destroy();
  barChartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: d.labels,
      datasets: [{
        label: d.label,
        data: d.data,
        backgroundColor: grads,
        borderRadius: 10,
        borderSkipped: false,
        borderWidth: 0
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      animation: { duration: 600, easing: 'easeOutQuart' },
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#1a1a1a',
          borderColor: '#F2C84B',
          borderWidth: 1,
          titleColor: '#F2C84B',
          bodyColor: '#ccc',
          callbacks: {
            label: ctx => metric === 'valor'
              ? ` R$ ${ctx.raw.toLocaleString('pt-BR')}`
              : ` ${ctx.raw.toLocaleString('pt-BR')}`
          }
        }
      },
      scales: {
        x: { grid: { display: false }, ticks: { color: '#888', font: { size: 11 } }, border: { display: false } },
        y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#888', font: { size: 11 } }, border: { display: false } }
      }
    }
  });
}

function renderDonut() {
  if (!dadosGrafico || !dadosGrafico.length) return;
  const ctx = document.getElementById('donutChart').getContext('2d');
  const labels = dadosGrafico.map(d => d.categoria);
  const data   = dadosGrafico.map(d => Number(d.total));
  if (donutInstance) donutInstance.destroy();
  donutInstance = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels,
      datasets: [{
        data,
        backgroundColor: PALETTE.slice(0, labels.length),
        borderColor: '#111',
        borderWidth: 3,
        hoverOffset: 10
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      cutout: '68%',
      animation: { duration: 800, easing: 'easeOutQuart' },
      plugins: {
        legend: {
          position: 'bottom',
          labels: { color: '#aaa', font: { size: 10 }, padding: 12, boxWidth: 12 }
        },
        tooltip: {
          backgroundColor: '#1a1a1a',
          borderColor: '#F2C84B',
          borderWidth: 1,
          titleColor: '#F2C84B',
          bodyColor: '#ccc'
        }
      }
    }
  });
}

function switchMetric(btn, metric) {
  document.querySelectorAll('.chart-filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  const titles = { estoque:'Estoque por Categoria', valor:'Valor por Categoria (R$)', produtos:'Produtos por Categoria' };
  document.getElementById('barChartTitle').textContent = titles[metric];
  renderBarChart(metric);
}

if (dadosGrafico && dadosGrafico.length > 0) {
  renderBarChart('estoque');
  renderDonut();
}

document.addEventListener('DOMContentLoaded', () => { applyFilters(); displayTable(); });
</script>