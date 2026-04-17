<?php
use App\Koketsu\Core\Flash;
use App\Koketsu\Core\Session;

// Função auxiliar para determinar a classe ativa do menu
function isActive($link_uri, $current_uri)
{
  // Remove parâmetros GET para comparação limpa
  $clean_link = strtok($link_uri, '?');
  $clean_current = strtok($current_uri, '?');

  // Verifica se o link_uri é igual ao current_uri
  return ($clean_link == $clean_current) ? 'active-link' : '';
}

// Tenta obter a URI atual. O valor de $_SERVER['REQUEST_URI'] pode precisar de ajustes dependendo do seu ambiente.
$current_uri = $_SERVER['REQUEST_URI'] ?? '/backend/admin/dashboard';
?>

<!DOCTYPE html>
<html>

<head>
  <title>Koketsu | Loja de Roupas</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="icon" type="image/png" href="/assets/img/logo2026.png">
  <script>
    // Script bloqueante para evitar flash de cor incorreta
    (function () {
      const savedTheme = localStorage.getItem('theme') || 'dark';
      if (savedTheme === 'light' || (savedTheme === 'system' && window.matchMedia('(prefers-color-scheme: light)').matches)) {
        document.documentElement.classList.add('theme-light');
      }
    })();
  </script>
  <style>
    :root {
      --bg-main:      #0f0f0f;
      --bg-sidebar:   linear-gradient(180deg, #000000 0%, #111111 100%);
      --bg-top:       #0a0a0a;
      --bg-card:      linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
      --bg-card-flat: #1a1a1a;
      --text-main:    #ffffff;
      --text-muted:   #888888;
      --text-label:   #aaaaaa;
      --border-color: #222222;
      --border-hover: #333333;

      /* ── ACCENT PRINCIPAL: Dourado ── */
      --accent:       #F2C84B;
      --accent-glow:  rgba(242, 200, 75, 0.25);
      --accent-dim:   rgba(242, 200, 75, 0.07);

      /* ── ACCENT SECUNDÁRIO: Cobre / Bronze ── */
      --copper:       #C47A3A;
      --copper-glow:  rgba(196, 122, 58, 0.25);
      --copper-dim:   rgba(196, 122, 58, 0.08);

      /* ── ACCENT TERCIÁRIO: Azul Aço ── */
      --steel:        #4E9EBF;
      --steel-glow:   rgba(78, 158, 191, 0.22);
      --steel-dim:    rgba(78, 158, 191, 0.07);

      /* ── ACCENT PREMIUM: Ametista / Roxo ── */
      --amethyst:     #8B5CF6;
      --amethyst-glow: rgba(139, 92, 246, 0.22);
      --amethyst-dim:  rgba(139, 92, 246, 0.07);

      /* ── SOMBRAS ── */
      --shadow-sm:    0 4px 12px rgba(0,0,0,0.3);
      --shadow-md:    0 8px 24px rgba(0,0,0,0.5);
      --shadow-gold:  0 4px 12px rgba(242,200,75,0.3);

      /* ── INPUTS ── */
      --input-bg:     #0f0f0f;

      /* ── BORDER-RADIUS ── */
      --radius-sm:    6px;
      --radius-md:    10px;
      --radius-lg:    16px;

      /* ── CORES DE STATUS SEMÂNTICO ── */
      --color-success: #51cf66;
      --color-info:    #4dabf7;
      --color-warning: #ffa94d;
      --color-danger:  #dc3545;
      --color-orders:  #64c8ff;

      /* ── BARRAS DE MÉTRICAS DOS KPI CARDS ── */
      --kpi-revenue:  linear-gradient(135deg, #F2C84B, #C47A3A);   /* Dourado → Cobre */
      --kpi-orders:   linear-gradient(135deg, #4E9EBF, #1971c2);   /* Azul aço */
      --kpi-products: linear-gradient(135deg, #51cf66, #2f9e44);   /* Verde */
      --kpi-users:    linear-gradient(135deg, #8B5CF6, #5e3ab7);   /* Ametista */
    }

    /* ======= RESET & BASE ======= */
    *, *::before, *::after { box-sizing: border-box; }

    html, body {
      height: 100%; margin: 0; padding: 0;
      background-color: var(--bg-main) !important;
      color: var(--text-main) !important;
      font-family: Arial, sans-serif;
      display: flex; flex-direction: column;
    }

    /* ======= TOPBAR ======= */
    .w3-top, .w3-bar.w3-top {
      position: fixed; top: 0; left: 0; right: 0;
      background-color: var(--bg-top) !important;
      color: var(--text-main) !important;
      z-index: 1000; height: 80px; line-height: 60px;
      border-bottom: 1px solid var(--border-color);
    }

    /* ======= SIDEBAR ======= */
    .w3-sidebar {
      background: var(--bg-sidebar) !important;
      width: 260px !important; position: fixed !important;
      top: 0; left: 0; height: 100vh !important;
      overflow-y: auto; padding-top: 70px;
      border-right: 1px solid var(--border-color);
      scrollbar-width: thin;
      scrollbar-color: var(--accent) transparent;
    }
    .w3-sidebar::-webkit-scrollbar { width: 4px; }
    .w3-sidebar::-webkit-scrollbar-track { background: transparent; }
    .w3-sidebar::-webkit-scrollbar-thumb { background: var(--accent); border-radius: 4px; }

    .w3-sidebar a {
      display: flex; align-items: center; gap: 10px;
      color: var(--text-muted) !important;
      background-color: transparent !important;
      padding: 10px 16px !important;
      font-size: 14px; font-weight: 500;
      border-radius: var(--radius-md);
      transition: all 0.2s ease-in-out;
      margin: 2px 10px; text-decoration: none;
      letter-spacing: 0.02em;
    }
    .w3-sidebar a i {
      font-size: 15px; width: 20px; text-align: center;
      color: var(--accent); flex-shrink: 0;
    }
    .w3-sidebar a.active-link {
      background-color: var(--accent) !important;
      color: #000 !important; font-weight: 700;
      box-shadow: var(--shadow-gold);
    }
    .w3-sidebar a.active-link i { color: #000 !important; }
    .w3-sidebar a:hover:not(.active-link) {
      background-color: var(--accent-dim) !important;
      color: #fff !important; transform: translateX(3px);
    }
    .nav-section-label {
      font-size: 9px; font-weight: 800;
      text-transform: uppercase; letter-spacing: .14em;
      color: #444; padding: 14px 22px 6px; margin-top: 4px;
    }
    .nav-sub-item { margin-left: 20px !important; font-size: 13px !important; padding: 7px 14px !important; }
    .nav-sub-item i { font-size: 13px !important; color: #555 !important; }

    /* ======= ÁREA DE CONTEÚDO ======= */
    .w3-main {
      flex: 1; margin-left: 260px !important;
      margin-top: 80px !important; padding: 40px !important;
      background-color: var(--bg-main) !important;
      color: var(--text-main) !important;
    }

    /* ======= CARDS ======= */
    .w3-card, .w3-white, .w3-light-grey {
      background: var(--bg-card) !important;
      color: var(--text-main) !important;
      border: 2px solid var(--border-color) !important;
      border-radius: var(--radius-lg) !important;
      box-shadow: var(--shadow-sm);
      transition: border-color .25s, box-shadow .25s;
    }
    .w3-card:hover { border-color: var(--border-hover) !important; box-shadow: var(--shadow-md); }

    /* ======= INPUTS ======= */
    input, select, textarea {
      background-color: var(--input-bg) !important;
      color: var(--text-main) !important;
      border: 2px solid var(--border-color) !important;
      border-radius: var(--radius-md);
      padding: 10px 12px;
      font-family: Arial, sans-serif; font-size: 14px;
      transition: border-color .2s, box-shadow .2s; outline: none;
    }
    input:focus, select:focus, textarea:focus {
      border-color: var(--accent) !important;
      box-shadow: 0 0 0 3px var(--accent-glow) !important;
    }

    /* ======= TABELAS ======= */
    .w3-table { color: var(--text-main); width: 100%; border-collapse: collapse; }
    .w3-table thead th {
      color: var(--accent) !important;
      background-color: var(--bg-card-flat) !important;
      font-weight: 800; font-size: 11px;
      text-transform: uppercase; letter-spacing: 1px;
      padding: 14px 16px; border-bottom: 2px solid var(--border-color);
    }
    .w3-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.04); transition: background .15s; }
    .w3-table tbody tr:hover { background: var(--accent-dim) !important; }
    .w3-table td { padding: 14px 16px; vertical-align: middle; }
    .w3-table tr:nth-child(even) { background-color: rgba(255,255,255,0.02) !important; }

    /* ======= BOTÃO PRIMÁRIO DOURADO ======= */
    .btn-gold {
      display: inline-flex; align-items: center; gap: 8px;
      background: linear-gradient(135deg, #F2C84B, #d4a800);
      color: #000 !important; font-weight: 700;
      text-transform: uppercase; letter-spacing: .5px;
      padding: 12px 22px; border: none;
      border-radius: var(--radius-md);
      box-shadow: var(--shadow-gold);
      cursor: pointer; text-decoration: none;
      transition: transform .2s, box-shadow .2s;
    }
    .btn-gold:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(242,200,75,.45); }

    /* Tags */
    .w3-tag { padding: 4px 10px; font-size: 11px; font-weight: 700; border-radius: 20px; text-transform: uppercase; letter-spacing: .05em; }

    /* Alertas flash */
    .alert { padding: 14px 20px; border-radius: var(--radius-md); margin-bottom: 16px; font-weight: 600; font-size: 14px; }
    .alert-success { background: rgba(81,207,102,.12); border-left: 4px solid var(--color-success); color: var(--color-success); }
    .alert-danger  { background: rgba(220,53,69,.12);  border-left: 4px solid var(--color-danger);  color: var(--color-danger); }

    /* Scrollbar dourada */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: var(--bg-main); }
    ::-webkit-scrollbar-thumb { background: var(--accent); border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #f0a500; }

    hr { border: none; border-top: 1px solid var(--border-color) !important; margin: 0; }

    /* ======= RESPONSIVIDADE ======= */
    @media (max-width: 992px) {
      .w3-main { margin-left: 0 !important; padding: 20px !important; margin-top: 70px !important; }
      .w3-sidebar { width: 260px !important; display: none; z-index: 1001 !important; }
      #main-logo { height: 50px !important; }
      .w3-top, .w3-bar.w3-top { height: 70px; display: flex; align-items: center; }
    }
    @media (max-width: 600px) {
      .w3-main { padding: 14px !important; }
    }
    .w3-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
  </style>

</head>

<body class="">

  <?php
  $session = new Session();
  if ($session->has('usuario_id')):

    ?>

    <div class="w3-bar w3-top w3-theme w3-large" style="z-index:4">
      <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-black" onclick="w3_open();"><i
          class="fa fa-bars"></i>  Menu</button>
      <div class="w3-bar"
        style="background-color: var(--bg-top) !important; height:80px; display:flex; align-items:center; justify-content:center; border-bottom: 1px solid var(--border-color);">
        <a href="/">
          <img id="main-logo" src="/assets/img/logo2026.png" alt="Koketsu Logo"
            style="height: 70px; width: auto; max-width: 100%;">
        </a>
      </div>
    </div>

    <nav class="w3-sidebar w3-collapse w3-animate-left" style="z-index:3;" id="mySidebar">
      <div class="w3-container w3-row">

        <!-- PERFIL DO USUÁRIO -->
        <div style="padding: 24px 16px 16px; text-align:center;">
          <?php
          $foto_raw = $_SESSION['foto_usuarios'] ?? null;
          if ($foto_raw && !filter_var($foto_raw, FILTER_VALIDATE_URL) && !str_starts_with($foto_raw, '/img/')) {
            $foto_exibir = '/backend/upload/' . $foto_raw;
          } else {
            $foto_exibir = $foto_raw ?? '/img/logoperf.jpg';
          }
          ?>
          <img src="<?php echo htmlspecialchars($foto_exibir); ?>"
            style="width:80px; height:80px; object-fit:cover; border-radius:50%; border:2px solid var(--accent); display:block; margin:0 auto 10px;"
            alt="Foto de perfil"
            onerror="this.onerror=null; this.src='/img/logoperf.jpg';">
          <div style="font-size:14px; font-weight:700; color:var(--text-main);"><?= htmlspecialchars($session->get('usuario_nome')) ?></div>
          <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.08em;
                      color:var(--accent); margin-top:3px;"><?= strtoupper($session->get('usuario_tipo') ?? '') ?></div>
        </div>

        <div style="height:1px; background:var(--border-color); margin:0 16px 8px;"></div>

        <!-- NAVEGAÇÃO -->
        <div class="w3-bar-block" style="padding-bottom:24px;">

          <?php if (in_array($session->get('usuario_tipo'), ['admin', 'vendedor'])): ?>

            <!-- GERAL -->
            <div class="nav-section-label">Geral</div>
            <a href="/backend/admin/dashboard"
              class="w3-bar-item w3-button <?php echo isActive('/backend/admin/dashboard', $current_uri); ?>">
              <i class="fas fa-gauge-high fa-fw"></i> Painel
            </a>

            <?php if ($session->get('usuario_tipo') == 'admin'): ?>

              <!-- CADASTROS -->
              <div class="nav-section-label">Cadastros</div>
              <a href="/backend/usuario/listar"
                class="w3-bar-item w3-button <?php echo isActive('/backend/usuario/listar', $current_uri); ?>">
                <i class="fas fa-users fa-fw"></i> Usuários
              </a>
              <a href="/backend/produtos/listar"
                class="w3-bar-item w3-button <?php echo isActive('/backend/produtos/listar', $current_uri); ?>">
                <i class="fas fa-box fa-fw"></i> Produtos
              </a>

              <!-- PEDIDOS -->
              <div class="nav-section-label">Pedidos</div>
              <a href="/backend/pedido/listar"
                class="w3-bar-item w3-button <?php echo isActive('/backend/pedido/listar', $current_uri); ?>">
                <i class="fas fa-shopping-cart fa-fw"></i> Todos os Pedidos
              </a>
              <a href="/backend/itenspedidos/listar"
                class="w3-bar-item w3-button nav-sub-item <?php echo isActive('/backend/itenspedidos/listar', $current_uri); ?>">
                <i class="fas fa-list-ul fa-fw"></i> Itens dos Pedidos
              </a>

              <!-- CONTEÚDO -->
              <div class="nav-section-label">Conteúdo</div>
              <a href="/backend/avaliacao/listar"
                class="w3-bar-item w3-button <?php echo isActive('/backend/avaliacao/listar', $current_uri); ?>">
                <i class="fas fa-star fa-fw"></i> Avaliações
              </a>
              <a href="/backend/admin/newsletter"
                class="w3-bar-item w3-button <?php echo isActive('/backend/admin/newsletter', $current_uri); ?>">
                <i class="fas fa-envelope fa-fw"></i> Newsletter
              </a>

              <!-- SISTEMA -->
              <div class="nav-section-label">Sistema</div>
              <a href="/backend/configuracoes"
                class="w3-bar-item w3-button <?php echo isActive('/backend/configuracoes', $current_uri); ?>">
                <i class="fas fa-gear fa-fw"></i> Configurações
              </a>
              <a href="/backend/relatorios"
                class="w3-bar-item w3-button <?php echo isActive('/backend/relatorios', $current_uri); ?>">
                <i class="fas fa-chart-bar fa-fw"></i> Relatórios
              </a>

            <?php else: /* vendedor */ ?>

              <!-- VENDEDOR: apenas pedidos e clientes -->
              <div class="nav-section-label">Pedidos</div>
              <a href="/backend/pedido/listar"
                class="w3-bar-item w3-button <?php echo isActive('/backend/pedido/listar', $current_uri); ?>">
                <i class="fas fa-shopping-cart fa-fw"></i> Todos os Pedidos
              </a>
              <a href="/backend/cliente/listar"
                class="w3-bar-item w3-button <?php echo isActive('/backend/cliente/listar', $current_uri); ?>">
                <i class="fas fa-address-book fa-fw"></i> Clientes
              </a>

            <?php endif; ?>

          <?php else: /* cliente */ ?>

            <div class="nav-section-label">Minha Conta</div>
            <a href="/backend/cliente/dashboard"
              class="w3-bar-item w3-button <?php echo isActive('/backend/cliente/dashboard', $current_uri); ?>">
              <i class="fas fa-home fa-fw"></i> Início
            </a>
            <a href="/backend/cliente/meu-perfil/<?= htmlspecialchars($session->get('usuario_id') ?? '0') ?>"
              class="w3-bar-item w3-button <?php echo isActive('/backend/cliente/meu-perfil', $current_uri); ?>">
              <i class="fas fa-user-circle fa-fw"></i> Meu Perfil
            </a>
            <a href="/backend/cliente/avaliacoes"
              class="w3-bar-item w3-button <?php echo isActive('/backend/cliente/avaliacoes', $current_uri); ?>">
              <i class="fas fa-star fa-fw"></i> Minhas Avaliações
            </a>
            <a href="/"
              class="w3-bar-item w3-button">
              <i class="fas fa-shopping-bag fa-fw"></i> Continuar Comprando
            </a>

          <?php endif; ?>

          <!-- SAIR -->
          <div style="padding: 16px 10px 0;">
            <a href="/backend/logout"
              style="display:flex; align-items:center; gap:10px; padding:12px 16px;
                     background:linear-gradient(135deg,#ff6b6b,#ff3b3b); color:#000 !important;
                     font-weight:800; border-radius:10px; text-decoration:none;
                     box-shadow:0 4px 10px rgba(255,59,59,.25); transition:.2s; font-size:14px;"
              onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
              <i class="fas fa-right-from-bracket"></i> Sair
            </a>
          </div>

        </div>
      </div>
    </nav>

    <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer"
      title="close side menu" id="myOverlay"></div>

    <div class="w3-main" style="margin-left:260px;margin-top:80px;">

      <?php
  endif;
  $mensagem = Flash::get();
  if (isset($mensagem)) {
    foreach ($mensagem as $key => $value) {
      if ($key == "type") {
        $tipo = $value == "success" ? "alert-success" : "alert-danger";
        echo "<div class='alert $tipo' role='alert'>";
      } else {
        echo $value;
        echo "</div>";
      }
    }
  }
  ?>
