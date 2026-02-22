<?php
use App\Koketsu\Core\Flash;
use App\Koketsu\Core\Session;

// Função auxiliar para determinar a classe ativa do menu
function isActive($link_uri, $current_uri) {
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
    (function() {
        const savedTheme = localStorage.getItem('theme') || 'dark';
        if (savedTheme === 'light' || (savedTheme === 'system' && window.matchMedia('(prefers-color-scheme: light)').matches)) {
            document.documentElement.classList.add('theme-light');
        }
    })();
</script>
<style>
:root {
    --bg-main: #0a0a0b;
    --bg-sidebar: #121214;
    --bg-top: #000000;
    --bg-card: #161618;
    --text-main: #f1f1f1;
    --text-muted: #8a8a8e;
    --border-color: #242428;
    --accent: #dfd155;
    --accent-hover: #ffcc00;
    --shadow-sm: 0 2px 4px rgba(0,0,0,0.3);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.4);
    --glass-bg: rgba(22, 22, 24, 0.7);
    --glass-border: rgba(255, 215, 0, 0.1);
}

.theme-light {
    --bg-main: #f4f7f9;
    --bg-sidebar: #ffffff;
    --bg-top: #e8e8e8; /* Cinza acinzentado solicitado */
    --bg-card: #ffffff;
    --text-main: #1a1a1c;
    --text-muted: #5d666e;
    --border-color: #d1d9e6; /* Borda um pouco mais visível no claro */
    --accent: #c5a02d;
    --accent-hover: #a68421;
    --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
    --shadow-md: 0 8px 24px rgba(149, 157, 165, 0.2);
    --glass-bg: rgba(255, 255, 255, 0.8);
    --glass-border: rgba(197, 160, 45, 0.2);
}


/* ======= RESET GERAL ======= */
html, body {
  height: 100%;
  margin: 0;
  padding: 0;
  background-color: var(--bg-main) !important;
  color: var(--text-main) !important;
  font-family: "Segoe UI", sans-serif;
  display: flex;
  flex-direction: column;
}

/* ======= TOPO ======= */
.w3-top, .w3-bar.w3-top {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background-color: var(--bg-top) !important;
  color: var(--text-main) !important;
  z-index: 1000;
  height: 80px;
  line-height: 60px;
  border-bottom: 1px solid var(--border-color);
}

/* ====== MENU LATERAL ====== */
.w3-sidebar {
  /* Fundo mais claro para contraste */
  background-color: var(--bg-sidebar) !important; 
  color: var(--text-main) !important;
  width: 260px !important;
  position: fixed !important;
  top: 0;
  left: 0;
  height: 100vh !important;
  overflow-y: auto;
  padding-top: 70px; /* Ajuste para melhor visual de perfil */
  border-right: 1px solid var(--border-color);
}

.w3-sidebar a {
  display: flex;
  align-items: center;
  gap: 10px;
  color: var(--text-main) !important;
  background-color: transparent !important;
  padding: 12px 20px !important;
  font-size: 15px;
  font-weight: 500;
  border-radius: 8px;
  transition: all 0.25s ease-in-out;
  margin: 4px 12px;
}

/* Estilo do link ativo/selecionado */
.w3-sidebar a.active-link {
    background-color: var(--accent) !important;
    color: var(--bg-sidebar) !important;
    font-weight: 700;
    transform: translateX(0); 
    box-shadow: 0 4px 12px var(--accent);
}

/* Hover nos links não ativos */
.w3-sidebar a:hover:not(.active-link) {
  background-color: #ffcc0044 !important; /* Cor mais sutil no hover */
  color: #fff !important;
  transform: translateX(4px);
  box-shadow: none;
}

/* Ícones do menu */
.w3-sidebar a i {
  font-size: 18px;
  width: 24px;
  text-align: center;
}



/* ====== BOTÃO SAIR ====== */
.logout-btn {
  display: inline-block;
  background-color: #e74c3c !important;
  color: #fff !important;
  padding: 10px 16px;
  border-radius: 6px;
  font-weight: 600;
  text-decoration: none;
  transition: background-color 0.2s ease;
}

.logout-btn:hover {
  background-color: #ff6b5a !important;
  color: #fff !important;
  text-decoration: none;
  box-shadow: 0 0 10px #ff6b5a66;
}

/* ====== CONTEÚDO ====== */

.w3-main {
  flex: 1; 
  margin-left: 260px !important;
  margin-top: 85px !important; 
  padding: 50px !important;
  background-color: var(--bg-main) !important;
  color: var(--text-main) !important;
  transition: all 0.3s ease-in-out;
}

/* ======= SEPARADOR DE PERFIL ======= */
hr {
    border-color: #333 !important;
}


/* ======= BOTÕES/TABELAS (Estilos de tema escuro) ======= */
.w3-table tr:nth-child(even) {
    background-color: var(--bg-sidebar) !important;
}
.w3-tag {
    padding: 4px 8px;
    font-size: 12px;
    font-weight: bold;
}
.w3-table .w3-button {
    font-size: 11px;
    padding: 8px 12px; 
    margin: 2px;
    text-transform: uppercase;
}
.w3-table {
    color: #f1f1f1;
}
.w3-table thead tr {
    background-color: #333 !important;
    color: white;
}
.w3-card, .w3-white, .w3-light-grey {
    background-color: var(--bg-card) !important;
    color: var(--text-main) !important;
    border: 1px solid var(--border-color) !important;
}

/* ======= INPUTS ======= */
input, select, textarea {
  background-color: var(--bg-card) !important;
  color: var(--text-main) !important;
  border: 1px solid var(--border-color) !important;
  border-radius: 4px;
  padding: 8px;
}

/* ======= SCROLLBAR (opcional) ======= */
::-webkit-scrollbar {
  width: 8px;
}
::-webkit-scrollbar-thumb {
  background-color: #333;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background-color: #555;
}

/* ======= RESPONSIVIDADE DASHBOARD ======= */
@media (max-width: 992px) {
    .w3-main {
        margin-left: 0 !important;
        padding: 20px !important;
        margin-top: 70px !important;
    }

    .w3-sidebar {
        width: 260px !important;
        display: none; /* Esconde por padrão no mobile */
        z-index: 1001 !important;
    }

    #main-logo {
        height: 50px !important;
    }

    .w3-top, .w3-bar.w3-top {
        height: 70px;
        line-height: normal;
        display: flex;
        align-items: center;
    }
}

@media (max-width: 600px) {
    .w3-main {
        padding: 15px !important;
    }
    
    .w3-container.w3-row {
        padding: 0 !important;
    }

    .w3-top {
        padding: 0 10px;
    }
}

/* Utilitário para tabelas responsivas globais */
.w3-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
</style>
</head>
<body class="">

  <?php
    $session = new Session();
    if($session->has('usuario_id')):
     
  ?>

<div class="w3-bar w3-top w3-theme w3-large" style="z-index:4">
  <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-black" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button>
  <div class="w3-bar" style="background-color: var(--bg-top) !important; height:80px; display:flex; align-items:center; justify-content:center; border-bottom: 1px solid var(--border-color);">
    <a href="/">
      <img id="main-logo" src="/assets/img/logo2026.png" alt="Koketsu Logo" style="height: 70px; width: auto; max-width: 100%;">
    </a>
  </div>
</div>

<nav class="w3-sidebar w3-collapse w3-animate-left" style="z-index:3;" id="mySidebar"><br>
  <div class="w3-container w3-row">
    <div class="w3-container w3-center w3-padding">
  <div class="w3-container w3-center w3-padding">
    <?php
    // Verifica se a foto existe na sessão, senão usa a padrão
    $foto_raw = $_SESSION['foto_usuarios'] ?? null;
    if ($foto_raw && !filter_var($foto_raw, FILTER_VALIDATE_URL)) {
        // Se não é uma URL, constrói o caminho completo
        $foto_exibir = '/backend/upload/' . $foto_raw;
    } else {
        $foto_exibir = $foto_raw ?? '/img/logoperf.jpg';
    }
    ?>
    
    <img src="<?php echo htmlspecialchars($foto_exibir); ?>" 
         class="w3-circle" 
         style="width:120px; height:120px; object-fit: cover; border:2px solid #ffcc00;"
         alt="Foto de perfil"
         onerror="this.src='/img/logoperf.jpg';">
</div>
  <h5 class="w3-margin-top">Bem-vindo, <strong><?= htmlspecialchars($session->get('usuario_nome')); ?></strong></h5>
</div>
<hr style="border-color:#333;"> 
  <div class="w3-container">
    <h5>Painel Koketsu</h5>
  </div>
  <div class="w3-bar-block">
    <?php if ($session->get('usuario_tipo') == 'admin'): ?>
        <a href="/backend/admin/dashboard" class="w3-bar-item w3-button w3-padding <?php echo isActive('/backend/admin/dashboard', $current_uri); ?>">
            <i class="fas fa-home fa-fw"></i> Início (Admin)
        </a>
        <a href="/backend/usuario/listar" class="w3-bar-item w3-button w3-padding <?php echo isActive('/backend/usuario/listar', $current_uri); ?>">
            <i class="fas fa-users fa-fw"></i> Usuários
        </a>
        <a href="/backend/produtos/listar" class="w3-bar-item w3-button w3-padding <?php echo isActive('/backend/produtos/listar', $current_uri); ?>">
            <i class="fas fa-tags fa-fw"></i> Produtos
        </a>
        <a href="/backend/pedido/listar" class="w3-bar-item w3-button w3-padding <?php echo isActive('/backend/pedido/listar', $current_uri); ?>">
            <i class="fas fa-shopping-cart fa-fw"></i> Todos Pedidos
        </a>
        <a href="/backend/itenspedidos/listar" class="w3-bar-item w3-button w3-padding <?php echo isActive('/backend/itenspedidos/listar', $current_uri); ?>">
            <i class="fab fa-dropbox fa-fw"></i> Itens Pedidos
        </a>
        <a href="/backend/cliente/listar" class="w3-bar-item w3-button w3-padding <?php echo isActive('/backend/cliente/listar', $current_uri); ?>">
            <i class="fas fa-address-book fa-fw"></i> Clientes
        </a>
        <a href="/backend/avaliacao/listar" class="w3-bar-item w3-button w3-padding <?php echo isActive('/backend/avaliacao/listar', $current_uri); ?>">
            <i class="fas fa-star fa-fw"></i> Avaliações
        </a>
        <a href="/backend/admin/newsletter" class="w3-bar-item w3-button w3-padding <?php echo isActive('/backend/admin/newsletter', $current_uri); ?>">
            <i class="fas fa-envelope fa-fw"></i> Newsletter
        </a>
    <?php else: ?>
        <a href="/backend/cliente/dashboard" class="w3-bar-item w3-button w3-padding <?php echo isActive('/backend/cliente/dashboard', $current_uri); ?>">
            <i class="fas fa-home fa-fw"></i> Início
        </a>
        <a href="/backend/cliente/avaliacoes" class="w3-bar-item w3-button w3-padding <?php echo isActive('/backend/cliente/avaliacoes', $current_uri); ?>">
            <i class="fas fa-star fa-fw"></i> Avaliações
        </a>
    <?php endif; ?>

    <?php if ($session->get('usuario_tipo') != 'admin'): ?>
    <a href="/backend/cliente/meu-perfil/<?= htmlspecialchars($session->get('usuario_id') ?? '0') ?>" class="w3-bar-item w3-button w3-padding <?php echo isActive('/backend/cliente/meu-perfil', $current_uri); ?>">
        <i class="fas fa-user-circle fa-fw"></i> Perfil
    </a>
    <?php endif; ?>

    <a href="/backend/configuracoes" class="w3-bar-item w3-button w3-padding <?php echo isActive('/backend/configuracoes', $current_uri); ?>">
        <i class="fas fa-cog fa-fw"></i> Configurações
    </a>
    <?php if ($session->get('usuario_tipo') != 'admin'): ?>
    <a href="/" class="w3-bar-item w3-button w3-padding">
        <i class="fas fa-shopping-bag fa-fw"></i> Continuar Comprando
    </a>
    <?php endif; ?>
    <br>
    <a href="/backend/logout" class="w3-bar-item w3-button w3-padding" style="color: #e74c3c !important;">
        <i class="fas fa-sign-out-alt fa-fw"></i> Sair
    </a>
  </div>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<div class="w3-main" style="margin-left:260px;margin-top:80px;">
    
    <?php
    endif;
$mensagem = Flash::get();
if(isset($mensagem)){
foreach($mensagem as $key => $value){
    if($key == "type"){
        $tipo = $value == "success" ? "alert-success" : "alert-danger";
    echo "<div class='alert $tipo' role='alert'>";
    }else{
        echo $value;
        echo "</div>";
    }
}
}
?>
