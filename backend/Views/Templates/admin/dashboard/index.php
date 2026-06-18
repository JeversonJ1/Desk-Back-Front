<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
  /* ===== DASHBOARD RESET & BASE ===== */
  .dash { padding: 0; background: var(--bg-main); min-height: 100vh; font-family: Arial, sans-serif; }

  /* ===== HEADER ===== */
  .dash-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 32px; flex-wrap: wrap; gap: 16px;
  }
  .dash-header-left h1 {
    font-size: 28px; font-weight: 900; color: var(--text-main);
    text-transform: uppercase; letter-spacing: -1px; margin: 0;
  }
  .dash-header-left p { color: var(--text-muted); margin: 4px 0 0; font-size: 13px; }
  .dash-date {
    background: var(--bg-card); border: 2px solid var(--border-color);
    border-radius: 10px; padding: 10px 18px; color: var(--text-muted);
    font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;
  }

  /* ===== KPI GRID ===== */
  .kpi-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 20px; margin-bottom: 28px; }
  @media(max-width:900px){ .kpi-grid { grid-template-columns: repeat(2,1fr); } }
  @media(max-width:500px){ .kpi-grid { grid-template-columns: 1fr; } }

  .kpi-card {
    background: var(--bg-card); border: 2px solid var(--border-color);
    border-radius: 18px; padding: 24px 20px; position: relative;
    overflow: hidden; transition: .3s ease; cursor: default;
  }
  .kpi-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,.6); border-color: var(--accent); }
  .kpi-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; background: var(--kpi-color, #F2C84B);
  }
  .kpi-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
  .kpi-icon {
    width: 48px; height: 48px; border-radius: 14px;
    background: var(--kpi-bg, rgba(242,200,75,.12));
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: var(--kpi-color, #F2C84B);
  }
  .kpi-badge {
    font-size: 10px; font-weight: 800; text-transform: uppercase;
    letter-spacing: .05em; padding: 4px 8px; border-radius: 6px;
    background: rgba(40,167,69,.12); color: #28a745;
  }
  .kpi-badge.warn { background: rgba(255,165,0,.12); color: orange; }
  .kpi-value { font-size: 34px; font-weight: 900; color: var(--text-main); letter-spacing: -1px; }
  .kpi-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: var(--text-muted); margin-top: 4px; }

  /* ===== MAIN GRID ===== */
  .main-grid { display: grid; grid-template-columns: 1fr 380px; gap: 24px; margin-bottom: 24px; }
  @media(max-width:1100px){ .main-grid { grid-template-columns: 1fr; } }

  /* ===== CARDS GENÉRICOS ===== */
  .panel {
    background: var(--bg-card); border: 2px solid var(--border-color);
    border-radius: 18px; overflow: hidden;
  }
  .panel-head {
    display: flex; justify-content: space-between; align-items: center;
    padding: 20px 24px 16px; border-bottom: 2px solid var(--border-color);
  }
  .panel-title { font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; color: var(--text-main); display: flex; align-items: center; gap: 8px; }
  .panel-title i { color: var(--accent); }
  .panel-link { font-size: 12px; color: var(--accent); text-decoration: none; font-weight: 700; transition: .2s; }
  .panel-link:hover { text-decoration: underline; }
  .panel-body { padding: 20px 24px; }

  /* ===== CHART AREA ===== */
  .chart-wrap { height: 260px; position: relative; }

  /* ===== STATUS PILLS GRID ===== */
  .status-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 20px 24px; }
  .status-pill {
    display: flex; align-items: center; gap: 10px;
    background: var(--bg-main); border: 2px solid var(--border-color);
    border-radius: 12px; padding: 14px 16px; transition: .2s;
  }
  .status-pill:hover { border-color: var(--accent); background: var(--accent-dim); }
  .sp-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
  .sp-count { font-size: 22px; font-weight: 900; color: var(--text-main); }
  .sp-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--text-muted); }

  /* ===== PEDIDOS RECENTES ===== */
  .orders-list { display: flex; flex-direction: column; }
  .order-row {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 24px; border-bottom: 1px solid var(--border-color);
    transition: .2s; text-decoration: none; color: inherit;
  }
  .order-row:last-child { border-bottom: none; }
  .order-row:hover { background: var(--accent-dim); }
  .order-avatar {
    width: 40px; height: 40px; border-radius: 50%; object-fit: cover;
    border: 2px solid var(--border-color); flex-shrink: 0;
    background: #222; display: flex; align-items: center; justify-content: center; color: var(--text-muted);
  }
  .order-avatar img { width:100%; height:100%; border-radius:50%; object-fit:cover; }
  .order-info { flex: 1; min-width: 0; }
  .order-name { font-size: 13px; font-weight: 700; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .order-id { font-size: 11px; color: var(--text-muted); }
  .order-right { text-align: right; flex-shrink: 0; }
  .order-value { font-size: 14px; font-weight: 800; color: var(--accent); }
  .order-date { font-size: 11px; color: var(--text-muted); }
  .order-badge {
    font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: .05em;
    padding: 3px 8px; border-radius: 20px; white-space: nowrap;
  }
  .badge-pago      { background: rgba(40,167,69,.15);  color: #28a745; }
  .badge-pendente  { background: rgba(255,193,7,.15);  color: #ffc107; }
  .badge-cancelado { background: rgba(220,53,69,.15);  color: #dc3545; }
  .badge-enviado   { background: rgba(23,162,184,.15); color: #17a2b8; }
  .badge-concluido { background: rgba(111,66,193,.15); color: #6f42c1; }

  /* ===== BOTTOM GRID ===== */
  .bottom-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
  @media(max-width:900px){ .bottom-grid { grid-template-columns: 1fr; } }

  /* ===== TOP PRODUTOS ===== */
  .top-prod-row {
    display: flex; align-items: center; gap: 14px;
    padding: 12px 24px; border-bottom: 1px solid var(--border-color);
    transition: .2s;
  }
  .top-prod-row:last-child { border-bottom: none; }
  .top-prod-row:hover { background: var(--accent-dim); }
  .top-prod-img {
    width: 48px; height: 48px; border-radius: 10px; object-fit: cover;
    border: 1px solid var(--border-color); flex-shrink: 0;
  }
  .top-prod-info { flex: 1; min-width: 0; }
  .top-prod-name { font-size: 13px; font-weight: 700; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .top-prod-sold { font-size: 11px; color: var(--text-muted); }
  .top-prod-bar { height: 4px; border-radius: 2px; background: var(--border-color); margin-top: 6px; overflow: hidden; }
  .top-prod-fill { height: 100%; border-radius: 2px; background: linear-gradient(90deg, #F2C84B, #f0a500); }
  .top-prod-rev { font-size: 13px; font-weight: 800; color: var(--accent); white-space: nowrap; }

  /* ===== ESTOQUE BAIXO ===== */
  .stock-row {
    display: flex; align-items: center; gap: 14px;
    padding: 12px 24px; border-bottom: 1px solid var(--border-color); transition: .2s;
  }
  .stock-row:last-child { border-bottom: none; }
  .stock-row:hover { background: rgba(220,53,69,.04); }
  .stock-img {
    width: 44px; height: 44px; border-radius: 10px; object-fit: cover;
    border: 1px solid var(--border-color); flex-shrink: 0;
  }
  .stock-name { font-size: 13px; font-weight: 700; color: var(--text-main); flex: 1; }
  .stock-qty {
    font-size: 20px; font-weight: 900;
    color: #dc3545;
  }
  .stock-label { font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); }
</style>

<?php
// Helpers
$statusConfig = [
    'pago'      => ['label'=>'Pago',      'color'=>'#28a745', 'badge'=>'badge-pago'],
    'pendente'  => ['label'=>'Pendente',  'color'=>'#ffc107', 'badge'=>'badge-pendente'],
    'cancelado' => ['label'=>'Cancelado', 'color'=>'#dc3545', 'badge'=>'badge-cancelado'],
    'enviado'   => ['label'=>'Enviado',   'color'=>'#17a2b8', 'badge'=>'badge-enviado'],
    'concluido' => ['label'=>'Concluído', 'color'=>'#6f42c1', 'badge'=>'badge-concluido'],
];

// Preenche dias faltantes na receita da semana
$receitaMap = [];
foreach ($receitaSemana as $r) $receitaMap[$r['dia']] = (float)$r['receita'];
$labels7 = []; $valores7 = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-{$i} days"));
    $labels7[] = date('d/m', strtotime($d));
    $valores7[] = $receitaMap[$d] ?? 0;
}

$maxVendido = !empty($topProdutos) ? max(array_column($topProdutos,'total_vendido')) : 1;
?>

<div class="dash">

  <!-- HEADER -->
  <div class="dash-header">
    <div class="dash-header-left">
      <h1><i class="fas fa-chart-line" style="color:#F2C84B;font-size:22px;"></i> Dashboard</h1>
      <p>Bem-vindo de volta, <strong style="color:#F2C84B;"><?= htmlspecialchars($nomeUsuario) ?></strong> — visão geral da Koketsu Grife</p>
    </div>
    <div class="dash-date">
      <i class="fas fa-calendar-alt" style="color:#F2C84B;"></i>
    <?php
      $diasPT  = ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado'];
      $mesesPT = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
      $dataFormatada = $diasPT[date('w')] . ', ' . date('d') . ' de ' . $mesesPT[date('n') - 1] . ' de ' . date('Y');
    ?>
    <?= $dataFormatada ?>
    </div>
  </div>

  <!-- KPIs -->
  <div class="kpi-grid">
    <!-- Receita -->
    <div class="kpi-card" style="--kpi-color:#F2C84B; --kpi-bg:rgba(242,200,75,.12);">
      <div class="kpi-top">
        <div class="kpi-icon"><i class="fas fa-dollar-sign"></i></div>
        <span class="kpi-badge" style="background:rgba(242,200,75,.12);color:#F2C84B;">Receita</span>
      </div>
      <div class="kpi-value">R$ <?= number_format($receitaTotal, 2, ',', '.') ?></div>
      <div class="kpi-label">Receita Total (Confirmada)</div>
    </div>
    <!-- Pedidos -->
    <div class="kpi-card" style="--kpi-color:#4E9EBF; --kpi-bg:rgba(78,158,191,.12);">
      <div class="kpi-top">
        <div class="kpi-icon"><i class="fas fa-shopping-bag"></i></div>
        <span class="kpi-badge" style="background:rgba(78,158,191,.12);color:#4E9EBF;">Total</span>
      </div>
      <div class="kpi-value"><?= $totalPedidos ?></div>
      <div class="kpi-label">Pedidos Realizados</div>
    </div>
    <!-- Produtos -->
    <div class="kpi-card" style="--kpi-color:#8B5CF6; --kpi-bg:rgba(139,92,246,.12);">
      <div class="kpi-top">
        <div class="kpi-icon"><i class="fas fa-tags"></i></div>
        <span class="kpi-badge" style="background:rgba(139,92,246,.12);color:#8B5CF6;">Ativos</span>
      </div>
      <div class="kpi-value"><?= $totalProdutos ?></div>
      <div class="kpi-label">Produtos no Catálogo</div>
    </div>
    <!-- Usuários -->
    <div class="kpi-card" style="--kpi-color:#51cf66; --kpi-bg:rgba(81,207,102,.12);">
      <div class="kpi-top">
        <div class="kpi-icon"><i class="fas fa-users"></i></div>
        <?php if($novosUsuariosSemana > 0): ?>
        <span class="kpi-badge" style="background:rgba(81,207,102,.12);color:#51cf66;">+<?= $novosUsuariosSemana ?> esta semana</span>
        <?php endif; ?>
      </div>
      <div class="kpi-value"><?= $totalUsuarios ?></div>
      <div class="kpi-label">Usuários Cadastrados</div>
    </div>
  </div>

  <!-- MAIN GRID: Gráfico + Pedidos Recentes -->
  <div class="main-grid">

    <!-- ESQUERDA: Gráfico de Receita -->
    <div class="panel">
      <div class="panel-head">
        <span class="panel-title"><i class="fas fa-chart-area"></i> Receita — Últimos 7 Dias</span>
      </div>
      <div class="panel-body">
        <div class="chart-wrap">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>
      <!-- Status de Pedidos -->
      <div style="border-top:1px solid var(--border-color); padding: 16px 24px;">
        <p style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);margin:0 0 12px;">Status dos Pedidos</p>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <?php foreach($statusConfig as $key => $cfg): ?>
          <div style="display:flex;align-items:center;gap:6px;background:var(--bg-main);border:1px solid var(--border-color);border-radius:8px;padding:8px 12px;">
            <div style="width:8px;height:8px;border-radius:50%;background:<?= $cfg['color'] ?>;"></div>
            <span style="font-size:11px;color:var(--text-muted);font-weight:600;"><?= $cfg['label'] ?></span>
            <span style="font-size:14px;font-weight:900;color:var(--text-main);"><?= $pedidosPorStatus[$key] ?? 0 ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- DIREITA: Últimos Pedidos -->
    <div class="panel">
      <div class="panel-head">
        <span class="panel-title"><i class="fas fa-receipt"></i> Pedidos Recentes</span>
        <a href="/backend/pedido/listar" class="panel-link">Ver Todos →</a>
      </div>
      <div class="orders-list">
        <?php if(empty($ultimosPedidos)): ?>
        <p style="padding:24px;color:var(--text-muted);text-align:center;font-size:13px;">Nenhum pedido encontrado.</p>
        <?php else: ?>
        <?php foreach($ultimosPedidos as $ped):
          $st = strtolower($ped['status_pedido'] ?? 'pendente');
          $cfg = $statusConfig[$st] ?? ['label'=>ucfirst($st),'badge'=>'badge-pendente'];
          $foto = $ped['foto_usuarios'] ?? null;
          $fotoUrl = ($foto && !filter_var($foto, FILTER_VALIDATE_URL)) ? '/backend/upload/'.$foto : ($foto ?: null);
        ?>
        <div class="order-row">
          <div class="order-avatar">
            <?php if($fotoUrl): ?>
            <img src="<?= htmlspecialchars($fotoUrl) ?>" alt="" onerror="this.style.display='none';this.parentNode.innerHTML='<i class=\'fas fa-user\'></i>'">
            <?php else: ?><i class="fas fa-user"></i><?php endif; ?>
          </div>
          <div class="order-info">
            <div class="order-name"><?= htmlspecialchars($ped['nome_usuarios'] ?? 'Cliente') ?></div>
            <div class="order-id">#<?= $ped['id_pedido'] ?> · <span class="order-badge <?= $cfg['badge'] ?>"><?= $cfg['label'] ?></span></div>
          </div>
          <div class="order-right">
            <div class="order-value">R$ <?= number_format((float)$ped['total_pedido'],2,',','.') ?></div>
            <div class="order-date"><?= date('d/m H:i', strtotime($ped['data_pedido'])) ?></div>
          </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

  </div><!-- /main-grid -->

  <!-- BOTTOM GRID: Top Produtos + Estoque Baixo -->
  <div class="bottom-grid">

    <!-- Top Produtos -->
    <div class="panel">
      <div class="panel-head">
        <span class="panel-title"><i class="fas fa-trophy"></i> Top 5 Mais Vendidos</span>
        <a href="/backend/produtos/listar" class="panel-link">Ver Produtos →</a>
      </div>
      <?php if(empty($topProdutos)): ?>
      <p style="padding:24px;color:var(--text-muted);text-align:center;font-size:13px;">Dados insuficientes.</p>
      <?php else: ?>
      <?php foreach($topProdutos as $tp):
        $pct = $maxVendido > 0 ? round(($tp['total_vendido']/$maxVendido)*100) : 0;
        $imgRaw = $tp['imagem_produtos'] ?? '';
        $imgUrl = (empty($imgRaw) || $imgRaw === 'default.jpg') ? '/frontend/assets/img/LogoKoketsu.jpg' : '/backend/upload/'.$imgRaw;
      ?>
      <div class="top-prod-row">
        <img src="<?= htmlspecialchars($imgUrl) ?>" class="top-prod-img" onerror="this.src='/frontend/assets/img/LogoKoketsu.jpg'" alt="">
        <div class="top-prod-info">
          <div class="top-prod-name"><?= htmlspecialchars($tp['nome_produtos']) ?></div>
          <div class="top-prod-sold"><?= $tp['total_vendido'] ?> vendidos</div>
          <div class="top-prod-bar"><div class="top-prod-fill" style="width:<?= $pct ?>%;"></div></div>
        </div>
        <div class="top-prod-rev">R$ <?= number_format((float)$tp['receita_produto'],2,',','.') ?></div>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Estoque Baixo -->
    <div class="panel">
      <div class="panel-head">
        <span class="panel-title" style="color:#dc3545;"><i class="fas fa-exclamation-triangle" style="color:#dc3545;"></i> Estoque Crítico</span>
        <a href="/backend/produtos/listar" class="panel-link">Gerenciar →</a>
      </div>
      <?php if(empty($estoqueBaixo)): ?>
      <p style="padding:24px;color:#28a745;text-align:center;font-size:13px;"><i class="fas fa-check-circle"></i> Estoque saudável!</p>
      <?php else: ?>
      <?php foreach($estoqueBaixo as $ep):
        $imgRaw = $ep['imagem_produtos'] ?? '';
        $imgUrl = (empty($imgRaw) || $imgRaw === 'default.jpg') ? '/frontend/assets/img/LogoKoketsu.jpg' : '/backend/upload/'.$imgRaw;
        $cor = $ep['estoque_produtos'] <= 3 ? '#dc3545' : '#ff8c00';
      ?>
      <div class="stock-row">
        <img src="<?= htmlspecialchars($imgUrl) ?>" class="stock-img" onerror="this.src='/frontend/assets/img/LogoKoketsu.jpg'" alt="">
        <div class="stock-name"><?= htmlspecialchars($ep['nome_produtos']) ?></div>
        <div style="text-align:right;flex-shrink:0;">
          <div class="stock-qty" style="color:<?= $cor ?>;"><?= $ep['estoque_produtos'] ?></div>
          <div class="stock-label">unid.</div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div><!-- /bottom-grid -->

</div><!-- /dash -->

<script>
// Gráfico de Receita
const labels7 = <?= json_encode($labels7) ?>;
const valores7 = <?= json_encode($valores7) ?>;

const ctx = document.getElementById('revenueChart').getContext('2d');
const grad = ctx.createLinearGradient(0, 0, 0, 260);
grad.addColorStop(0, 'rgba(242,200,75,0.3)');
grad.addColorStop(1, 'rgba(242,200,75,0.0)');

new Chart(ctx, {
  type: 'line',
  data: {
    labels: labels7,
    datasets: [{
      label: 'Receita (R$)',
      data: valores7,
      borderColor: '#F2C84B',
      backgroundColor: grad,
      borderWidth: 2.5,
      pointBackgroundColor: '#F2C84B',
      pointRadius: 5,
      pointHoverRadius: 8,
      fill: true,
      tension: 0.4
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false }, tooltip: {
      callbacks: {
        label: ctx => ' R$ ' + ctx.raw.toFixed(2).replace('.',',')
      }
    }},
    scales: {
      x: { grid: { display: false }, ticks: { color: '#888', font: { size: 11 } } },
      y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.04)' }, ticks: {
        color: '#888', font: { size: 11 },
        callback: v => 'R$ ' + v.toLocaleString('pt-BR')
      }}
    }
  }
});
</script>