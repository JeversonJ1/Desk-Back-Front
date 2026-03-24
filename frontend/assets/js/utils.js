/**
 * Utilitários Gerais
 * Funções auxiliares reutilizáveis em toda a aplicação
 */

window.Utils = (() => {
  /**
   * Valida um endereço de email
   */
  const validateEmail = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  };

  /**
   * Debounce para otimizar eventos que disparam frequentemente
   */
  const debounce = (func, delay = 300) => {
    let timeoutId;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeoutId);
        func(...args);
      };
      clearTimeout(timeoutId);
      timeoutId = setTimeout(later, delay);
    };
  };

  /**
   * Throttle para limitar a frequência de execução
   */
  const throttle = (func, limit = 300) => {
    let inThrottle;
    return function executedFunction(...args) {
      if (!inThrottle) {
        func.apply(this, args);
        inThrottle = true;
        setTimeout(() => inThrottle = false, limit);
      }
    };
  };

  /**
   * Formata um valor em moeda brasileira
   */
  const formatCurrency = (value) => {
    return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL'
    }).format(value);
  };

  /**
   * Armazena dados no localStorage
   */
  const setLocalStorage = (key, value) => {
    try {
      localStorage.setItem(key, JSON.stringify(value));
    } catch (error) {
      console.error('Erro ao salvar no localStorage:', error);
    }
  };

  /**
   * Recupera dados do localStorage
   */
  const getLocalStorage = (key, defaultValue = null) => {
    try {
      const item = localStorage.getItem(key);
      return item ? JSON.parse(item) : defaultValue;
    } catch (error) {
      console.error('Erro ao ler do localStorage:', error);
      return defaultValue;
    }
  };

  /**
   * Remove dados do localStorage
   */
  const removeLocalStorage = (key) => {
    try {
      localStorage.removeItem(key);
    } catch (error) {
      console.error('Erro ao remover do localStorage:', error);
    }
  };

  /**
   * Mostra uma notificação (toast)
   */
  const showNotification = (message, type = 'info', duration = 3000) => {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
      notification.classList.add('show');
    }, 100);

    setTimeout(() => {
      notification.classList.remove('show');
      setTimeout(() => notification.remove(), 300);
    }, duration);
  };

  /**
   * Scroll suave para um elemento
   */
  const scrollToElement = (element, offset = 0) => {
    const elementPosition = element.getBoundingClientRect().top + window.scrollY - offset;
    window.scrollTo({
      top: elementPosition,
      behavior: 'smooth'
    });
  };

  /**
   * Verifica se o usuário está logado
   */
  const checkAuth = async () => {
    try {
      const response = await fetch('/api/check_auth.php', { credentials: 'same-origin' });
      if (!response.ok) return { authenticated: false };
      return await response.json();
    } catch (error) {
      console.error('Erro ao verificar autenticação:', error);
      return { authenticated: false };
    }
  };

  /**
   * Carrega e injeta componentes globais (Header e Footer)
   */
  const loadPartials = async () => {
    try {
      // Remover header estático caso exista e injetar no body
      const existingHeader = document.querySelector('header');
      
      const headerHtml = window.ComponentsTemplate.Header;
      if (existingHeader) {
         existingHeader.outerHTML = `<header id="global-header">${headerHtml}</header>`;
      } else {
         document.body.insertAdjacentHTML('afterbegin', `<header id="global-header">${headerHtml}</header>`);
      }

      // Remover footer estático caso exista e injetar
      const existingFooter = document.querySelector('footer');
      const footerHtml = window.ComponentsTemplate.Footer;
      if (existingFooter) {
         existingFooter.outerHTML = footerHtml; // O arquivo footer.html já tem a tag <footer>
      } else {
         document.body.insertAdjacentHTML('beforeend', footerHtml);
      }
      
      // Reinicializa scripts dependentes do header (Navbar, Search, Auth, MegaMenu)
      if (window.NavbarManager && typeof window.NavbarManager.handleScroll === 'function') window.NavbarManager.handleScroll();
      if (window.SearchManager && typeof window.SearchManager.init === 'function') window.SearchManager.init();
      if (window.AuthManager && typeof window.AuthManager.init === 'function') window.AuthManager.init();
      if (window.CartManager && typeof window.CartManager.init === 'function') window.CartManager.init();
      if (window.NewsletterManager && typeof window.NewsletterManager.init === 'function') window.NewsletterManager.init();
      // Re-bind MegaMenu after header re-injection
      if (window.MegaMenu && typeof window.MegaMenu.open === 'function') {
        document.querySelectorAll('[data-mega-menu-open]').forEach(btn => {
          btn.addEventListener('click', e => { e.preventDefault(); window.MegaMenu.open(); });
        });
        const closeBtn = document.getElementById('megaMenuCloseBtn');
        if (closeBtn) closeBtn.addEventListener('click', () => window.MegaMenu.close());
        const overlay = document.getElementById('megaMenuOverlay');
        if (overlay) overlay.addEventListener('click', () => window.MegaMenu.close());
      }
    } catch (error) {
      console.error('Erro ao carregar componentes globais:', error);
    }
  };

  return {
    validateEmail,
    debounce,
    throttle,
    formatCurrency,
    setLocalStorage,
    getLocalStorage,
    removeLocalStorage,
    showNotification,
    scrollToElement,
    checkAuth,
    loadPartials
  };
})();
const Components = {
    Header: `
<!-- NAVBAR -->
<nav id="navbar" class="fixed top-0 w-full h-[96px] z-[2000] bg-black/40 backdrop-blur-md border-b border-[#F2C84B]/15 transition-all duration-300 flex items-center">
  <div class="w-full px-4 lg:px-8 flex items-center justify-between h-full">
    
    <!-- Left Section: Menu & Search -->
    <div class="flex items-center gap-3 lg:gap-4 flex-1">
      <button class="text-white hover:text-[var(--brand-yellow)] transition flex items-center gap-2 p-0" onclick="window.MenuManager && window.MenuManager.openMenu()" aria-label="Abrir menu">
        <i class="bi bi-list text-3xl"></i>
        <span class="hidden md:inline font-bold uppercase tracking-[0.2em] text-xs">Catálogo</span>
      </button>

      <!-- CompSupply Style Search (Opens Overlay) -->
      <div class="hidden lg:flex items-center gap-3 bg-white/5 border border-white/10 rounded-full py-2 px-4 cursor-pointer hover:border-[var(--brand-yellow)] transition" onclick="if(window.SearchManager){ SearchManager.openOverlay(); }">
        <i class="bi bi-search text-white/50"></i>
        <span class="text-white/50 font-bold text-[0.75rem] tracking-[0.1em] mt-px">PESQUISAR...</span>
      </div>
    </div>

    <!-- Center Section: Logo -->
    <div class="flex justify-center flex-1">
      <a href="/" aria-label="Página inicial" class="hover:scale-110 transition-transform duration-300 filter drop-shadow-[0_2px_4px_rgba(248,211,70,0.2)] hover:drop-shadow-[0_4px_8px_rgba(248,211,70,0.4)]">
        <img src="/frontend/assets/img/logo2026.png" alt="Logo Koketsu Grife" class="max-h-[85px] w-auto object-contain" />
      </a>
    </div>

    <!-- Right Section: Admin & Cart -->
    <div class="flex items-center justify-end gap-3 flex-1">
      
      <!-- Auth Container (Logado) -->
      <div id="navUserContainer" class="hidden items-center group relative">
        <button id="userAvatarBtn" class="bg-black border border-white/10 rounded-full text-white py-1.5 px-4 flex items-center gap-3 text-[13px] font-semibold transition hover:bg-[var(--brand-yellow)] hover:text-black hover:scale-105 shadow-sm" aria-label="Menu do usuário">
          <img id="userAvatarImg" src="" alt="Avatar" class="hidden w-6 h-6 rounded-full object-cover">
          <i id="userAvatarFallback" class="bi bi-person-fill text-xl"></i>
        </button>
        <span id="navUserName" class="hidden lg:inline font-bold uppercase ml-2 text-[0.75rem] tracking-[0.1em]"></span>

        <!-- Dropdown do usuário (Logado) -->
        <div id="userDropdown" class="absolute top-full right-0 mt-3 w-64 bg-white rounded-xl shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible translate-y-2 group-hover:translate-y-0 transition-all duration-300 z-[1000] overflow-hidden">
          <div class="bg-gray-50 p-4 border-b border-gray-100 flex items-center gap-3">
            <img id="ddAvatarImg" src="" alt="Avatar" class="hidden w-10 h-10 rounded-full object-cover border-2 border-[var(--brand-yellow)]">
            <div id="ddAvatarFallback" class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-xl"><i class="bi bi-person-fill"></i></div>
            <div>
              <div id="ddUserName" class="font-bold text-gray-900 text-sm">Cliente</div>
              <span class="dd-badge text-[10px] font-bold text-[var(--brand-yellow)] uppercase tracking-wider bg-yellow-50 px-2 py-0.5 rounded-full border border-yellow-200">Membro Koketsu</span>
            </div>
          </div>
          <div class="user-dropdown-links p-2 flex flex-col">
            <a id="ddProfileLink" href="/backend/cliente/meu-perfil/0" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-600 hover:text-[var(--brand-yellow)] hover:bg-gray-50 rounded-lg transition"><i class="bi bi-person"></i> Meu Perfil</a>
            <a href="/backend/cliente/pedidos" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-600 hover:text-[var(--brand-yellow)] hover:bg-gray-50 rounded-lg transition"><i class="bi bi-bag-check"></i> Meus Pedidos</a>
            <a href="/backend/configuracoes" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-600 hover:text-[var(--brand-yellow)] hover:bg-gray-50 rounded-lg transition"><i class="bi bi-gear"></i> Preferências</a>
            <div class="h-px bg-gray-100 my-1 mx-2"></div>
            <a href="#" id="ddLogoutBtn" class="logout-link flex items-center gap-3 px-3 py-2 text-sm text-red-500 hover:bg-red-50 rounded-lg transition"><i class="bi bi-box-arrow-right"></i> Sair</a>
          </div>
        </div>
      </div>

      <!-- Login Button CompSupply Dropdown (Deslogado) -->
      <div id="navGuestContainer" class="relative group h-full flex items-center">
        <a href="#" id="navLoginBtn" class="bg-black border border-white/10 rounded-full text-white py-2 px-6 flex items-center gap-3 text-[13px] font-semibold transition hover:bg-[var(--brand-yellow)] hover:text-black hover:scale-105 hover:shadow-[0_4px_15px_rgba(255,215,0,0.3)]">
          <i class="bi bi-person text-xl"></i>
          <span class="font-bold uppercase hidden md:inline tracking-[0.1em] text-[0.75rem]">LOGIN</span>
        </a>
        
        <!-- Dropdown Hover Panel -->
        <div class="absolute right-0 top-full pt-3 w-[290px] z-[1000] opacity-0 invisible group-hover:opacity-100 group-hover:visible translate-y-2 group-hover:translate-y-0 transition-all duration-300 pointer-events-none group-hover:pointer-events-auto">
          <div class="bg-white rounded-xl p-6 shadow-2xl border border-gray-100">
            <p class="text-gray-500 font-bold uppercase mb-4 text-[0.65rem] tracking-[0.15em]">Acesse sua conta</p>
            
            <a href="#" class="block w-full text-center bg-black text-white hover:bg-gray-800 transition mb-2 font-bold uppercase py-3 rounded-lg text-[0.75rem] tracking-[0.1em] shadow-md" onclick="if(window.AuthManager) document.getElementById('navLoginBtn').click()">
              Entrar na conta
            </a>
            
            <a href="/backend/register" class="block w-full text-center border-2 border-black text-black hover:bg-gray-50 transition mb-5 font-bold uppercase py-2.5 rounded-lg text-[0.75rem] tracking-[0.1em]">
              Criar nova conta
            </a>
            
            <hr class="border-gray-200">
            
            <a href="/backend/cliente/pedidos" class="group/trace flex items-center justify-between text-decoration-none mt-4">
              <span class="font-bold uppercase text-gray-500 group-hover/trace:text-black transition text-[0.7rem] tracking-[0.1em]">Rastrear meu pedido</span>
              <i class="bi bi-arrow-right text-gray-400 group-hover/trace:text-black group-hover/trace:translate-x-1 transition-all"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Carrinho -->
      <a href="#" onclick="if(window.CartManager) { CartManager.openDrawer(); return false; }" class="bg-black border border-white/10 rounded-full text-white py-2 px-6 flex items-center gap-3 text-[13px] font-semibold transition hover:bg-[var(--brand-yellow)] hover:text-black hover:scale-105 hover:shadow-[0_4px_15px_rgba(255,215,0,0.3)]">
        <i class="bi bi-bag text-xl"></i>
        <span class="font-bold uppercase hidden lg:inline tracking-[0.1em] text-[0.75rem]">CARRINHO</span>
        <span class="cart-badge bg-[var(--brand-yellow)] text-black rounded-full w-5 h-5 items-center justify-center text-[10px] font-bold ml-1 hidden">0</span>
      </a>
    </div>
  </div>
</nav>

<!-- ============================================================
     MEGA MENU — Estilo CompSupply
     ============================================================ -->

<!-- Backdrop overlay -->
<div id="megaMenuOverlay" class="mega-menu-overlay"></div>

<!-- Menu container -->
<div id="megaMenuContainer" class="mega-menu-container">

  <!-- Header: tabs Menu / Fechar -->
  <div class="mega-menu-header">
    <span class="mega-menu-tab is-active">Menu</span>
    <button id="megaMenuCloseBtn" class="mega-menu-tab tab-close">Fechar</button>
  </div>

  <!-- Body: left categories + right products -->
  <div class="mega-menu-body">

    <!-- Left: Category list -->
    <div class="mega-menu-left">
      <div id="megaMenuCategoryList">
        <div class="menu-skeleton">
          <div class="menu-skeleton-item" style="width:70%"></div>
          <div class="menu-skeleton-item" style="width:55%"></div>
          <div class="menu-skeleton-item" style="width:80%"></div>
          <div class="menu-skeleton-item" style="width:60%"></div>
          <div class="menu-skeleton-item" style="width:75%"></div>
          <div class="menu-skeleton-item" style="width:50%"></div>
        </div>
      </div>
    </div>

    <!-- Right: Featured products -->
    <div class="mega-menu-right">
      <div class="menu-products-title" id="megaMenuProductsTitle">
        Produtos <span>em Destaque</span>
      </div>
      <div class="menu-products-grid" id="megaMenuProductsGrid">
        <div class="menu-product-skeleton"><div class="skel-img"></div><div class="skel-text"><div class="skel-line"></div><div class="skel-line w-2-3"></div><div class="skel-line w-1-3"></div></div></div>
        <div class="menu-product-skeleton"><div class="skel-img"></div><div class="skel-text"><div class="skel-line"></div><div class="skel-line w-2-3"></div><div class="skel-line w-1-3"></div></div></div>
        <div class="menu-product-skeleton"><div class="skel-img"></div><div class="skel-text"><div class="skel-line"></div><div class="skel-line w-2-3"></div><div class="skel-line w-1-3"></div></div></div>
        <div class="menu-product-skeleton"><div class="skel-img"></div><div class="skel-text"><div class="skel-line"></div><div class="skel-line w-2-3"></div><div class="skel-line w-1-3"></div></div></div>
        <div class="menu-product-skeleton"><div class="skel-img"></div><div class="skel-text"><div class="skel-line"></div><div class="skel-line w-2-3"></div><div class="skel-line w-1-3"></div></div></div>
        <div class="menu-product-skeleton"><div class="skel-img"></div><div class="skel-text"><div class="skel-line"></div><div class="skel-line w-2-3"></div><div class="skel-line w-1-3"></div></div></div>
      </div>
    </div>

  </div>

  <!-- Footer links -->
  <div class="mega-menu-footer">
    <a href="/pages/sobre.html" onclick="window.MegaMenu&&window.MegaMenu.close()">Sobre a Koketsu</a>
    <div class="footer-sep"></div>
    <a href="/pages/politica.html" onclick="window.MegaMenu&&window.MegaMenu.close()">Privacidade</a>
    <div class="footer-sep"></div>
    <a href="/pages/duvidas.html" onclick="window.MegaMenu&&window.MegaMenu.close()">Suporte</a>
    <div class="footer-sep"></div>
    <a href="/pages/catalogo.html" onclick="window.MegaMenu&&window.MegaMenu.close()" style="color:#F2C84B;">Ver Catálogo Completo →</a>
  </div>

</div>
`,
    Footer: `
<div class="footer-grid">
      <div class="coluna-footer newsletter">
        <h3 class="titulo">Faça parte da nossa família e receba acessos exclusivos e novidades.</h3>
        <div class="redes-sociais-icones mt-4">
          <a href="#"><i class="bi bi-instagram me-3"></i></a>
          <a href="#"><i class="bi bi-facebook me-3"></i></a>
          <a href="#"><i class="bi bi-tiktok"></i></a>
        </div>
      </div>

      <div class="coluna-footer">
        <h3 class="titulo">INSTITUCIONAL</h3>
        <a href="/pages/sobre.html">Sobre a Koketsu</a>
        <a href="/pages/politica.html">Política de Privacidade</a>
      </div>

      <div class="coluna-footer">
        <h3 class="titulo">AJUDA</h3>
        <a href="/pages/duvidas.html">Dúvidas Frequentes</a>
        <a href="/pages/Trocas.html">Trocas e Devoluções</a>
        <a href="/pages/frete.html">Frete e Entrega</a>
      </div>

      <div class="coluna-footer">
        <h3 class="titulo">FALE CONOSCO</h3>
        <p class="small">Seg a Sáb (exceto feriados)<br>09h às 19h</p>
        <p class="email-sac">sac@koketsu.com.br</p>
        <a href="https://WhatsApp.com" target="_blank" class="btn-whatsapp-footer">
          WHATSAPP <i class="bi bi-whatsapp"></i>
        </a>
      </div>
    </div>

    <div class="copyright-container">
      <div class="footer-copyright-inner">
        <p>© 2026 KOKETSU GRIFE. TODOS OS DIREITOS RESERVADOS.</p>
        <div class="pagamentos">
          <i class="bi bi-credit-card-2-back mx-1"></i>
          <i class="bi bi-qr-code mx-1"></i>
          <i class="bi bi-bank mx-1"></i>
        </div>
      </div>
    </div>
  
  <!-- Modal de Login (Original classes preserved as they are custom controlled by auth.css and auth.js) -->
  <div id="loginModalOverlay" class="login-modal-overlay">
    <div class="login-modal-card">
      <button id="loginModalClose" class="login-modal-close" aria-label="Fechar">&times;</button>
      <img src="/frontend/assets/img/logo2026.png" alt="Koketsu" class="login-modal-logo">
      <h2 class="login-modal-title">BEM-VINDO</h2>
      <p class="login-modal-subtitle">Acesse sua área exclusiva</p>

      <div id="loginModalError" class="login-modal-error"></div>

      <form id="loginModalForm" class="login-modal-form">
        <div class="form-group-auth">
          <input type="email" id="loginModalEmail" placeholder="E-mail de acesso" required autocomplete="email">
          <i class="bi bi-envelope"></i>
        </div>
        <div class="form-group-auth">
          <input type="password" id="loginModalSenha" placeholder="Sua senha secreta" required
            autocomplete="current-password">
          <i class="bi bi-lock"></i>
        </div>
        <button type="submit" id="loginModalSubmit" class="login-modal-btn">
          <span class="spinner"></span> Entrar
        </button>
      </form>

      <div class="login-modal-links">
        Não faz parte da elite? <a href="/backend/register">Cadastre-se</a>
      </div>
    </div>
  </div>

  <!-- Full-screen Search Overlay Tailwind Rewrite -->
  <div id="searchOverlay" class="fixed inset-0 z-[2060] bg-black/95 backdrop-blur-md invisible opacity-0 transition-all duration-500 flex flex-col p-4 md:p-8">
      <div class="container mx-auto flex flex-col h-full max-w-4xl">
          <div class="flex justify-between items-center mb-10 w-full">
              <span class="text-[var(--brand-yellow)] font-bold italic text-3xl tracking-[-0.05em]">KOKETSU<span class="text-white">SEARCH</span></span>
              <button class="text-white/50 hover:text-white transition text-3xl p-2" onclick="if(window.SearchManager){ window.SearchManager.closeOverlay(); }">
                  <i class="bi bi-x-lg"></i>
              </button>
          </div>
          
          <div class="relative mb-10 w-full">
              <input type="text" id="overlaySearchInput" class="w-full bg-transparent border-0 border-b-2 border-white/20 text-white uppercase font-bold focus:ring-0 focus:outline-none focus:border-[var(--brand-yellow)] transition-colors py-4 text-3xl md:text-5xl" placeholder="O QUE VOCÊ BUSCA?">
              <div class="absolute right-0 top-1/2 -translate-y-1/2 text-white/50 text-3xl pointer-events-none">
                  <i class="bi bi-search"></i>
              </div>
          </div>

          <div class="flex flex-col md:flex-row flex-1 overflow-y-auto gap-8 w-full">
              <div class="w-full md:w-1/2">
                  <h4 class="text-[var(--brand-yellow)] font-bold uppercase mb-6 text-[0.65rem] tracking-[0.3em]">Sugestões de Termos</h4>
                  <div class="flex flex-wrap gap-3">
                      <button class="border border-white/20 hover:border-white/50 hover:bg-white/10 text-white rounded-full font-bold uppercase px-4 py-2 transition text-[0.65rem] tracking-[0.1em]" onclick="if(window.SearchManager) SearchManager.setQuery('Camiseta')">Camiseta</button>
                      <button class="border border-white/20 hover:border-white/50 hover:bg-white/10 text-white rounded-full font-bold uppercase px-4 py-2 transition text-[0.65rem] tracking-[0.1em]" onclick="if(window.SearchManager) SearchManager.setQuery('Moletom')">Moletom</button>
                      <button class="border border-white/20 hover:border-white/50 hover:bg-white/10 text-white rounded-full font-bold uppercase px-4 py-2 transition text-[0.65rem] tracking-[0.1em]" onclick="if(window.SearchManager) SearchManager.setQuery('Cargo')">Cargo</button>
                      <button class="border border-white/20 hover:border-white/50 hover:bg-white/10 text-white rounded-full font-bold uppercase px-4 py-2 transition text-[0.65rem] tracking-[0.1em]" onclick="if(window.SearchManager) SearchManager.setQuery('Oversized')">Oversized</button>
                  </div>
              </div>
              <div class="w-full md:w-1/2">
                  <h4 class="text-[var(--brand-yellow)] font-bold uppercase mb-6 text-[0.65rem] tracking-[0.3em]">Resultados Relevantes</h4>
                  <div id="overlayResults" class="flex flex-col gap-4">
                      <p class="text-white/50 italic text-sm">Aguardando sua busca...</p>
                  </div>
              </div>
          </div>
      </div>
  </div>
`
};

window.MenuManager = (() => {
    const openMenu = () => {
        if (window.MegaMenu && typeof window.MegaMenu.open === 'function') {
            window.MegaMenu.open();
        }
    };
    const closeMenu = () => {
        if (window.MegaMenu && typeof window.MegaMenu.close === 'function') {
            window.MegaMenu.close();
        }
    };
    return { openMenu, closeMenu };
})();

window.ComponentsTemplate = Components;
