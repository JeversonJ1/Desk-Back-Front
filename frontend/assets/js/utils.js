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
      
      // Reinicializa scripts dependentes do header (Navbar, Search, Auth)
      if (window.NavbarManager && typeof window.NavbarManager.handleScroll === 'function') window.NavbarManager.handleScroll();
      if (window.SearchManager && typeof window.SearchManager.init === 'function') window.SearchManager.init();
      if (window.AuthManager && typeof window.AuthManager.init === 'function') window.AuthManager.init();
      if (window.CartManager && typeof window.CartManager.init === 'function') window.CartManager.init();
      if (window.NewsletterManager && typeof window.NewsletterManager.init === 'function') window.NewsletterManager.init();
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
<nav id="navbar" class="navbar d-flex align-items-center px-4 sticky-top d-flex">
  <div class="navbar-section d-flex align-items-center">
    <button class="btn btn-link text-white d-flex align-items-center p-0 me-4" data-bs-toggle="offcanvas"
      data-bs-target="#menuOffcanvas" aria-label="Abrir menu">
      <i class="bi bi-list fs-4"></i> <span class="ms-1 d-none d-md-inline">Menu</span>
    </button>
    <div class="search-container d-none d-lg-flex position-relative">
      <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
      <input type="text" id="globalSearch" class="search-input ps-5" placeholder="Buscar na Koketsu...">
      <div id="searchResults" class="search-dropdown d-none"></div>
    </div>
  </div>

  <div class="navbar-center">
    <a href="/frontend/index.html" aria-label="Página inicial">
      <img src="/frontend/assets/img/logo2026.png" alt="Logo Koketsu Grife" class="logo" />
    </a>
  </div>

  <div class="navbar-end d-flex justify-content-end align-items-center">
    <!-- Estado: Deslogado -->
    <a href="#" id="navLoginBtn" class="icon-button" aria-label="Fazer login">
      <i class="bi bi-person"></i> <span class="d-none d-md-inline">Login</span>
    </a>

    <!-- Estado: Logado -->
    <div id="navUserContainer" class="user-nav-container" style="display: none;">
      <button id="userAvatarBtn" class="user-avatar-btn" aria-label="Menu do usuário">
        <img id="userAvatarImg" src="" alt="Avatar" style="display: none;">
        <i id="userAvatarFallback" class="bi bi-person-fill avatar-fallback"></i>
      </button>
      <span id="navUserName" class="user-nav-name d-none d-md-inline"></span>

      <!-- Dropdown do usuário -->
      <div id="userDropdown" class="user-dropdown">
        <div class="user-dropdown-header">
          <img id="ddAvatarImg" src="" alt="Avatar" style="display: none;">
          <div id="ddAvatarFallback" class="avatar-fallback-lg"><i class="bi bi-person-fill"></i></div>
          <div class="user-dropdown-info">
            <div id="ddUserName" class="dd-name">Cliente</div>
            <span class="dd-badge">Membro Koketsu</span>
          </div>
        </div>
        <div class="user-dropdown-links">
          <a id="ddProfileLink" href="/backend/cliente/meu-perfil/0"><i class="bi bi-person"></i> Meu Perfil</a>
          <a href="/backend/cliente/pedidos"><i class="bi bi-bag-check"></i> Meus Pedidos</a>
          <a href="/backend/configuracoes"><i class="bi bi-gear"></i> Preferências</a>
          <div class="user-dropdown-divider"></div>
          <a href="#" id="ddLogoutBtn" class="logout-link"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </div>
      </div>
    </div>

    <a href="/frontend/pages/carrinho.html" class="icon-button ms-3">
      <i class="bi bi-bag"></i> <span class="d-none d-md-inline">Carrinho</span>
      <span class="cart-badge" style="display: none;">0</span>
    </a>
  </div>
</nav>

<div class="offcanvas offcanvas-start" tabindex="-1" id="menuOffcanvas" aria-labelledby="menuOffcanvasLabel">
  <div class="offcanvas-header border-bottom border-dark">
    <h5 class="offcanvas-title fw-bold text-yellow" id="menuOffcanvasLabel">CATEGORIAS</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body p-0">
    <div class="accordion accordion-flush" id="accordionMenu">
      <div class="accordion-item bg-transparent">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#itemsCamisetas">
            CAMISETAS
          </button>
        </h2>
        <div id="itemsCamisetas" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
          <div class="accordion-body">
            <a href="/frontend/pages/catalogo.html?cat=Camisetas Oversized">Oversized</a>
            <a href="/frontend/pages/catalogo.html?cat=Camisetas Streetwear">Streetwear</a>
            <a href="/frontend/pages/catalogo.html?cat=Camisetas">Ver Todas</a>
          </div>
        </div>
      </div>
      <div class="accordion-item bg-transparent">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#itemsMoletons">
            MOLETONS
          </button>
        </h2>
        <div id="itemsMoletons" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
          <div class="accordion-body">
            <a href="/frontend/pages/catalogo.html?cat=Moletons">Todos os Moletons</a>
          </div>
        </div>
      </div>
      <div class="accordion-item bg-transparent">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#itemsCalcas">
            CALÇAS
          </button>
        </h2>
        <div id="itemsCalcas" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
          <div class="accordion-body">
            <a href="/frontend/pages/catalogo.html?cat=Calças">Todas as Calças</a>
          </div>
        </div>
      </div>
      <div class="accordion-item bg-transparent">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#itemsAcessorios">
            CHAPÉUS & BONÉS
          </button>
        </h2>
        <div id="itemsAcessorios" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
          <div class="accordion-body">
            <a href="/frontend/pages/catalogo.html?cat=Chapéus e Bonés">Chapéus e Bonés</a>
            <a href="/frontend/pages/catalogo.html?cat=Chapéus e Bonés">Bonés Exclusivos</a>
          </div>
        </div>
      </div>
      <div class="accordion-item bg-transparent">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#itemsTenis">
            TÊNIS
          </button>
        </h2>
        <div id="itemsTenis" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
          <div class="accordion-body">
            <a href="/frontend/pages/catalogo.html?cat=Tenis">Coleção Tênis</a>
          </div>
        </div>
      </div>
      <div class="accordion-item bg-transparent">
        <h2 class="accordion-header">
          <a href="/frontend/pages/catalogo.html" class="accordion-button no-arrow text-warning">CATÁLOGO COMPLETO</a>
        </h2>
      </div>
    </div>
    <div class="menu-footer mt-auto p-4">
      <a href="/frontend/pages/sobre.html" class="d-block text-secondary small py-1 text-decoration-none">Sobre a Koketsu</a>
      <a href="/frontend/pages/politica.html" class="d-block text-secondary small py-1 text-decoration-none">Privacidade</a>
      <a href="/frontend/pages/duvidas.html" class="d-block text-secondary small py-1 text-decoration-none">Suporte</a>
    </div>
  </div>
</div>
`,
    Footer: `
<div class="container">
      <div class="coluna-footer newsletter">
        <h3 class="titulo">Faça parte da nossa família e receba acessos exclusivos e novidades.</h3>
        <p>Inscreva-se para ter acesso a exclusivo á venda antecipada, novidades promoções.</p>
        <form class="form-email" id="formNewsletter">
          <div class="input-group">
            <input type="email" id="newsletterEmail" name="email_newsletter" placeholder="Seu e-mail" required />
            <button type="submit" id="btnNewsletter">OK</button>
          </div>
        </form>
        <div class="redes-sociais-icones mt-4">
          <a href="#"><i class="bi bi-instagram me-3"></i></a>
          <a href="#"><i class="bi bi-facebook me-3"></i></a>
          <a href="#"><i class="bi bi-tiktok"></i></a>
        </div>
      </div>

      <div class="coluna-footer">
        <h3 class="titulo">INSTITUCIONAL</h3>
        <a href="/frontend/pages/sobre.html">Sobre a Koketsu</a>
        <a href="/frontend/pages/politica.html">Política de Privacidade</a>
        <a href="/frontend/pages/fidelidade.html">Programa de Fidelidade</a>
      </div>

      <div class="coluna-footer">
        <h3 class="titulo">AJUDA</h3>
        <a href="/frontend/pages/duvidas.html">Dúvidas Frequentes</a>
        <a href="/frontend/pages/Trocas.html">Trocas e Devoluções</a>
        <a href="/frontend/pages/frete.html">Frete e Entrega</a>
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
      <div class="container text-center">
        <p>© 2026 KOKETSU GRIFE. TODOS OS DIREITOS RESERVADOS.</p>
        <div class="pagamentos">
          <i class="bi bi-credit-card-2-back mx-1"></i>
          <i class="bi bi-qr-code mx-1"></i>
          <i class="bi bi-bank mx-1"></i>
        </div>
      </div>
    </div>
  

  <!-- Modal de Login -->
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
`
};
window.ComponentsTemplate = Components;
