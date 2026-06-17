<?php
/** @var int $total_usuarios */
/** @var int $total_ativos */
/** @var int $total_inativos */
/** @var int $total_admin */
/** @var array $usuarios */
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
  body { background-color: var(--bg-main) !important; margin: 0; font-family: Arial, sans-serif; color: var(--text-main); }
  .page-wrapper { padding: 0; width: 100%; box-sizing: border-box; min-height: 100vh; }

  /* --- HEADER --- */
  .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; gap:16px; }
  .page-header-left h1 { font-size:26px; font-weight:900; color:var(--text-main); text-transform:uppercase; letter-spacing:-1px; margin:0; }
  .page-header-left p { font-size:13px; color:var(--text-muted); margin:4px 0 0; }
  .btn-main-action {
    background:linear-gradient(135deg, #F2C84B, #f0a500); color:#000 !important;
    padding:13px 24px; border-radius:12px; font-weight:800;
    text-decoration:none; text-transform:uppercase; font-size:13px;
    transition:.3s; box-shadow:0 4px 15px rgba(242,200,75,.25);
    display:inline-flex; align-items:center; gap:8px;
  }
  .btn-main-action:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(242,200,75,.4); }

  /* --- STAT CARDS --- */
  .stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px; }
  @media(max-width:900px){ .stat-grid { grid-template-columns:repeat(2,1fr); } }
  .stat-card {
    background:var(--bg-card); border: 2px solid var(--border-color);
    border-radius:16px; padding:20px; display:flex;
    align-items:center; justify-content:space-between;
    transition:.3s; position:relative; overflow:hidden;
  }
  .stat-card:hover { border-color:var(--accent); transform:translateY(-3px); box-shadow:0 12px 30px rgba(0,0,0,.5), 0 0 0 1px var(--accent-dim); }
  .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:var(--sc-color,#F2C84B); }
  .stat-info h3 { margin:0; font-size:28px; font-weight:900; color:var(--text-main); }
  .stat-info p { margin:0; color:var(--text-muted); text-transform:uppercase; font-size:10px; letter-spacing:1px; font-weight:700; }
  .stat-icon {
    font-size:22px; color:var(--sc-color,#F2C84B);
    background:var(--sc-bg,rgba(242,200,75,.1));
    width:50px; height:50px; border-radius:14px;
    display:flex; align-items:center; justify-content:center;
  }

  /* --- ACTIONS BAR --- */
  .actions-bar { display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:20px; flex-wrap:wrap; }
  .search-group { flex:1; max-width:380px; position:relative; }
  .search-input {
    width:100%; background:var(--bg-card-flat); border: 2px solid var(--border-color);
    padding:12px 12px 12px 42px; border-radius:12px; color:var(--text-main);
    font-size:14px; transition:.3s; outline:none;
  }
  .search-input:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-glow); }
  .search-group i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--accent); }
  .filter-btns { display:flex; gap:8px; flex-wrap:wrap; }
  .filter-btn {
    padding:9px 16px; border-radius:10px; font-size:12px; font-weight:700;
    cursor:pointer; border:1px solid var(--border-color); background:var(--bg-card);
    color:var(--text-muted); transition:.2s; text-transform:uppercase; letter-spacing:.05em;
  }
  .filter-btn.active { background:var(--accent); color:#000; border-color:var(--accent); }
  .filter-btn:hover:not(.active) { border-color:var(--accent); color:var(--accent); }

  /* --- TABLE --- */
  .table-card { background:var(--bg-card); border: 2px solid var(--border-color); border-radius:16px; overflow:hidden; }
  .table-head { display:flex; justify-content:space-between; align-items:center; padding:18px 24px; border-bottom:1px solid var(--border-color); gap:12px; flex-wrap:wrap; }
  .table-title { font-size:14px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--text-main); }
  .table-count { font-size:12px; color:var(--text-muted); font-weight:600; }

  .user-table { width:100%; border-collapse:collapse; }
  .user-table thead th {
    color: var(--accent) !important;
    background-color: var(--bg-card-flat) !important;
    text-transform:uppercase; font-size:10px;
    padding:14px 20px; font-weight:800; letter-spacing:1px;
    border-bottom: 2px solid var(--border-color); text-align:left;
  }
  .user-table tbody tr { border-bottom:1px solid rgba(255,255,255,.04); transition:.2s; }
  .user-table tbody tr:last-child { border-bottom:none; }
  .user-table tbody tr:hover { background: var(--accent-dim) !important; }
  .user-table tbody tr.tr-inativo { background:rgba(220,53,69,.05); }
  .user-table tbody tr.tr-inativo td:first-child { border-left:3px solid #dc3545; }
  .user-table td { padding:14px 20px; color:var(--text-main); vertical-align:middle; }

  /* User cell */
  .user-cell { display:flex; align-items:center; gap:14px; }
  .user-av {
    width:44px; height:44px; border-radius:50%; object-fit:cover;
    border:2px solid var(--border-color); flex-shrink:0;
    background:var(--bg-main); display:flex; align-items:center; justify-content:center;
    font-size:18px; color:var(--text-muted); overflow:hidden;
  }
  .user-av img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
  .user-name { font-size:14px; font-weight:700; color:var(--text-main); display:block; }
  .user-email { font-size:11px; color:var(--text-muted); display:block; margin-top:1px; }

  /* Level badges */
  .nivel-badge {
    display:inline-flex; align-items:center; gap:5px;
    padding:5px 12px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.06em;
  }
  .nivel-admin   { background:rgba(242,200,75,.15); color:#F2C84B; border:1px solid rgba(242,200,75,.3); }
  .nivel-vendedor { background:rgba(23,162,184,.15); color:#17a2b8; border:1px solid rgba(23,162,184,.3); }
  .nivel-cliente { background:rgba(108,117,125,.12); color:#aaa; border:1px solid rgba(108,117,125,.2); }

  .badge-status { padding:5px 12px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; }
  .badge-active   { background:rgba(40,167,69,.12); color:#28a745; border:1px solid rgba(40,167,69,.3); }
  .badge-inactive { background:rgba(220,53,69,.12); color:#dc3545; border:1px solid rgba(220,53,69,.3); }

  .btn-action {
    padding:7px 12px; border-radius:8px; font-size:11px; font-weight:700;
    text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:.2s;
  }
  .btn-edit    { background:var(--bg-main); color:var(--accent); border:1px solid var(--accent); }
  .btn-edit:hover { background:var(--accent); color:#000; }
  .btn-toggle  { background:rgba(220,53,69,.1); border:1px solid rgba(220,53,69,.3); color:#dc3545; }
  .btn-toggle:hover { background:#dc3545; color:#fff; }
  .btn-activate { background:rgba(40,167,69,.1); border:1px solid rgba(40,167,69,.3); color:#28a745; }
  .btn-activate:hover { background:#28a745; color:#fff; }

  .id-chip {
    background:var(--bg-main); border:1px solid var(--border-color); color:var(--text-muted);
    padding:3px 8px; border-radius:6px; font-size:11px; font-family:monospace; font-weight:700;
  }

  /* --- PAGINATION --- */
  .pagination-container { display:flex; justify-content:space-between; align-items:center; padding:16px 24px; border-top:1px solid var(--border-color); }
  .pagination-buttons { display:flex; gap:6px; }
  .page-link { padding:8px 14px; background:var(--bg-main); border:1px solid var(--border-color); color:var(--text-main); border-radius:8px; cursor:pointer; font-weight:700; transition:.2s; font-size:12px; }
  .page-link:hover:not(.disabled) { border-color:var(--accent); color:var(--accent); }
  .page-link.active { background:var(--accent); color:#000; border-color:var(--accent); }
  .page-link.disabled { opacity:.3; cursor:not-allowed; }
  .pagination-dots { color:var(--text-muted); padding:0 4px; font-weight:bold; }

  .empty-state { text-align:center; padding:60px 24px; }
  .empty-state i { font-size:48px; color:var(--border-color); display:block; margin-bottom:16px; }
  .empty-state p { color:var(--text-muted); font-size:14px; }
</style>

<div class="page-wrapper">

  <!-- HEADER -->
  <div class="page-header">
    <div class="page-header-left">
      <h1><i class="fas fa-users" style="color:#F2C84B;"></i> Usuários</h1>
      <p>Gerencie todos os membros da plataforma Koketsu</p>
    </div>
    <a href="/backend/usuario/criar" class="btn-main-action">
      <i class="fas fa-user-plus"></i> Novo Usuário
    </a>
  </div>

  <!-- STAT CARDS -->
  <div class="stat-grid">
    <div class="stat-card" style="--sc-color:#F2C84B; --sc-bg:rgba(242,200,75,.1);">
      <div class="stat-info">
        <h3><?= $total_usuarios ?></h3>
        <p>Total de Usuários</p>
      </div>
      <div class="stat-icon"><i class="fas fa-users"></i></div>
    </div>
    <div class="stat-card" style="--sc-color:#28a745; --sc-bg:rgba(40,167,69,.1);">
      <div class="stat-info">
        <h3><?= $total_ativos ?></h3>
        <p>Usuários Ativos</p>
      </div>
      <div class="stat-icon"><i class="fas fa-signal"></i></div>
    </div>
    <div class="stat-card" style="--sc-color:#dc3545; --sc-bg:rgba(220,53,69,.1);">
      <div class="stat-info">
        <h3><?= $total_inativos ?></h3>
        <p>Inativados</p>
      </div>
      <div class="stat-icon"><i class="fas fa-user-slash"></i></div>
    </div>
    <div class="stat-card" style="--sc-color:#8B5CF6; --sc-bg:rgba(139,92,246,.1);">
      <div class="stat-info">
        <h3><?= $total_admin ?></h3>
        <p>Administradores</p>
      </div>
      <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
    </div>
  </div>

  <!-- TABLE CARD -->
  <div class="table-card">
    <div class="table-head">
      <span class="table-title"><i class="fas fa-list" style="color:#F2C84B;"></i> Lista de Usuários</span>
      <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <div class="search-group">
          <i class="fas fa-search"></i>
          <input type="text" id="userInput" class="search-input" placeholder="Buscar por nome ou email...">
        </div>
        <div class="filter-btns">
          <button class="filter-btn active" data-filter="todos">Todos</button>
          <button class="filter-btn" data-filter="admin">Admin</button>
          <button class="filter-btn" data-filter="vendedor">Vendedor</button>
          <button class="filter-btn" data-filter="cliente">Cliente</button>
          <button class="filter-btn" data-filter="ativo">Ativos</button>
          <button class="filter-btn" data-filter="inativo">Inativos</button>
        </div>
        <span class="table-count" id="tableCount"><?= count($usuarios) ?> usuários</span>
      </div>
    </div>

    <div style="overflow-x:auto;">
      <table class="user-table" id="userTable">
        <thead>
          <tr>
            <th width="50">ID</th>
            <th>Usuário</th>
            <th>Nível</th>
            <th>Cadastro</th>
            <th style="text-align:center;">Status</th>
            <th style="text-align:center;">Ações</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          <?php foreach ($usuarios as $usuario):
            $is_inativo = !empty($usuario['excluido_em']);
            $nivel = strtolower($usuario['nivel_acesso'] ?? 'cliente');
            $nivelClass = match($nivel) {
              'admin'    => 'nivel-admin',
              'vendedor' => 'nivel-vendedor',
              default    => 'nivel-cliente'
            };
            $nivelIcon = match($nivel) {
              'admin'    => 'fa-shield-alt',
              'vendedor' => 'fa-store',
              default    => 'fa-user'
            };
            $foto = $usuario['foto_usuarios'] ?? null;
            $fotoUrl = ($foto && !filter_var($foto, FILTER_VALIDATE_URL) && !str_starts_with($foto, '/img/'))
                       ? '/backend/upload/' . $foto
                       : ($foto ?: null);
            $dataCadastro = !empty($usuario['criado_em']) ? date('d/m/Y', strtotime($usuario['criado_em'])) : '—';
          ?>
          <tr class="user-row <?= $is_inativo ? 'tr-inativo' : '' ?>"
              data-status="<?= $is_inativo ? 'inativo' : 'ativo' ?>"
              data-nivel="<?= $nivel ?>">
            <td><span class="id-chip">#<?= $usuario['id_usuarios'] ?></span></td>
            <td>
              <div class="user-cell">
                <div class="user-av">
                  <?php if($fotoUrl): ?>
                  <img src="<?= htmlspecialchars($fotoUrl) ?>"
                       alt="<?= htmlspecialchars($usuario['nome_usuarios']) ?>"
                       onerror="this.style.display='none';this.parentNode.innerHTML='<i class=\'fas fa-user\'></i>'">
                  <?php else: ?><i class="fas fa-user"></i><?php endif; ?>
                </div>
                <div>
                  <span class="user-name user-name-col"><?= htmlspecialchars($usuario['nome_usuarios']) ?></span>
                  <span class="user-email email-col"><?= htmlspecialchars($usuario['email_usuarios']) ?></span>
                </div>
              </div>
            </td>
            <td>
              <span class="nivel-badge <?= $nivelClass ?>">
                <i class="fas <?= $nivelIcon ?>"></i>
                <?= strtoupper($usuario['nivel_acesso']) ?>
              </span>
            </td>
            <td style="color:var(--text-muted);font-size:13px;"><?= $dataCadastro ?></td>
            <td style="text-align:center;">
              <span class="badge-status <?= $is_inativo ? 'badge-inactive' : 'badge-active' ?>">
                <?= $is_inativo ? 'Inativo' : 'Ativo' ?>
              </span>
            </td>
            <td style="text-align:center;">
              <div style="display:flex;justify-content:center;gap:6px;">
                <?php if ($nivel !== 'cliente'): ?>
                  <a href="/backend/usuario/editar/<?= $usuario['id_usuarios'] ?>"
                     class="btn-action btn-edit" title="Editar">
                    <i class="fas fa-pencil-alt"></i>
                  </a>
                <?php else: ?>
                  <span class="btn-action" title="Clientes só podem editar o próprio perfil"
                        style="opacity:.35; cursor:not-allowed; border:1px solid rgba(108,117,125,.2); color:#6c757d; background:transparent;">
                    <i class="fas fa-lock"></i>
                  </span>
                <?php endif; ?>
                <?php if ($is_inativo): ?>
                <a href="/backend/usuario/ativar/<?= $usuario['id_usuarios'] ?>"
                   class="btn-action btn-activate" title="Ativar">
                  <i class="fas fa-check"></i>
                </a>
                <?php else: ?>
                <a href="/backend/usuario/excluir/<?= $usuario['id_usuarios'] ?>"
                   class="btn-action btn-toggle" title="Desativar"
                   onclick="return confirm('Deseja inativar este usuário?')">
                  <i class="fas fa-power-off"></i>
                </a>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if(empty($usuarios)): ?>
    <div class="empty-state">
      <i class="fas fa-users"></i>
      <p>Nenhum usuário cadastrado.</p>
    </div>
    <?php endif; ?>

    <div class="pagination-container">
      <div class="pagination-info" id="paginationInfo" style="color:var(--text-muted);font-size:13px;font-weight:600;"></div>
      <div class="pagination-buttons" id="paginationButtons"></div>
    </div>
  </div>

</div>

<script>
const rowsPerPage = 10;
let currentPage = 1;
let currentFilter = 'todos';

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
  const search = document.getElementById('userInput').value.toLowerCase();
  document.querySelectorAll('.user-row').forEach(row => {
    const name   = row.querySelector('.user-name-col')?.textContent.toLowerCase() || '';
    const email  = row.querySelector('.email-col')?.textContent.toLowerCase() || '';
    const status = row.dataset.status;
    const nivel  = row.dataset.nivel;

    const matchSearch = name.includes(search) || email.includes(search);
    let matchFilter = true;
    if (currentFilter === 'ativo')    matchFilter = status === 'ativo';
    else if (currentFilter === 'inativo')  matchFilter = status === 'inativo';
    else if (['admin','vendedor','cliente'].includes(currentFilter)) matchFilter = nivel === currentFilter;

    row.setAttribute('data-filtered', matchSearch && matchFilter ? 'true' : 'false');
  });
}

function displayTable() {
  const allRows = Array.from(document.querySelectorAll('.user-row'));
  const filtered = allRows.filter(r => r.getAttribute('data-filtered') !== 'false');
  const totalPages = Math.ceil(filtered.length / rowsPerPage);
  if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
  const start = (currentPage - 1) * rowsPerPage;

  allRows.forEach(r => r.style.display = 'none');
  filtered.slice(start, start + rowsPerPage).forEach(r => r.style.display = '');

  const end = Math.min(start + rowsPerPage, filtered.length);
  document.getElementById('tableCount').textContent = `${filtered.length} usuário${filtered.length !== 1 ? 's' : ''}`;
  renderPagination(totalPages, filtered.length, start, end);
}

function renderPagination(totalPages, total, start, end) {
  const container = document.getElementById('paginationButtons');
  const info = document.getElementById('paginationInfo');
  container.innerHTML = '';
  info.textContent = total === 0 ? 'Nenhum resultado' : `Exibindo ${start+1}–${end} de ${total} usuários`;
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

document.getElementById('userInput').addEventListener('input', () => {
  currentPage = 1; applyFilters(); displayTable();
});

document.addEventListener('DOMContentLoaded', () => { applyFilters(); displayTable(); });
</script>