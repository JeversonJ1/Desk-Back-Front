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
      // Fetch settings first to check for maintenance and promotional banner
      let config = null;
      try {
        const configRes = await fetch('/api/config.php');
        if (configRes.ok) {
          config = await configRes.json();
        }
      } catch (err) {
        console.error('Erro ao buscar configurações:', err);
      }

      // Check Maintenance Mode
      if (config && config.manutencao) {
        const auth = await checkAuth();
        const isAdmin = (auth && auth.authenticated && auth.user && auth.user.tipo === 'admin');
        if (!isAdmin) {
          // Replace body with beautiful maintenance view
          document.body.innerHTML = `
            <div style="background-color:#050505; color:#ffffff; font-family:'Outfit', sans-serif; display:flex; justify-content:center; align-items:center; height:100vh; text-align:center; margin:0; padding:20px; box-sizing:border-box;">
              <div style="max-width:550px; padding:60px 40px; background:rgba(255, 255, 255, 0.02); border-radius:32px; border:1px solid rgba(197, 160, 45, 0.1); box-shadow:0 30px 60px rgba(0,0,0,0.8);">
                <img src="/assets/img/logo2026.png" alt="Koketsu" style="height:60px; margin-bottom:40px; filter:drop-shadow(0 0 10px rgba(197, 160, 45, 0.3));">
                <div style="width:100px; height:100px; background:rgba(197, 160, 45, 0.1); border:1px solid rgba(197, 160, 45, 0.2); border-radius:30px; display:flex; align-items:center; justify-content:center; margin:0 auto 30px; animation: pulse 2s infinite ease-in-out;">
                  <i class="bi bi-gear-fill" style="font-size:40px; color:#c5a02d;"></i>
                </div>
                <h1 style="font-size:2.5em; margin-bottom:20px; font-weight:800; letter-spacing:-1px; background:linear-gradient(135deg, #fff 0%, #c5a02d 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">Estamos Evoluindo</h1>
                <p style="font-size:1.1em; color:#888; line-height:1.8; margin-bottom:40px;">A Koketsu Store está passando por uma atualização estética premium para melhor atendê-lo. Voltaremos em instantes com uma experiência renovada.</p>
                <div style="font-size:0.85em; color:#888; letter-spacing:1px; text-transform:uppercase; font-weight:700; opacity:0.5;">
                  &copy; ${new Date().getFullYear()} Koketsu Store • Estilo & Excelência
                </div>
              </div>
            </div>
          `;
          document.body.style.overflow = 'hidden';
          return;
        }
      }

      // Remover header estático caso exista e injetar no body
      const existingHeader = document.querySelector('header');
      const headerHtml = window.ComponentsTemplate.Header;
      if (existingHeader) {
        existingHeader.outerHTML = `<header id="global-header">${headerHtml}</header>`;
      } else {
        document.body.insertAdjacentHTML('afterbegin', `<header id="global-header">${headerHtml}</header>`);
      }

      // RENDER PROMOTIONAL BANNER IF ACTIVE (AS INFINITE CAROUSEL/MARQUEE)
      // Clean up old style tags and classes from previous versions
      const oldStyle = document.getElementById('promo-banner-styles');
      if (oldStyle) oldStyle.remove();
      document.body.classList.remove('has-promo-banner');

      // Check if we are on the homepage (possui .hero-swiper-container)
      const heroContainer = document.querySelector('.hero-swiper-container');

      if (config && config.banner_ativo && config.banner_texto && heroContainer) {
        // Remove existing marquee banner if any
        const existingBanner = document.querySelector('.promo-banner-marquee');
        if (existingBanner) existingBanner.remove();

        const bannerEl = document.createElement('div');
        bannerEl.className = `promo-banner-marquee bg-${config.banner_cor || 'dourado'}`;

        let trackHtml = '';
        for (let i = 0; i < 16; i++) {
          trackHtml += `<div class="promo-banner-marquee-item">${config.banner_texto}</div>`;
        }
        bannerEl.innerHTML = `<div class="promo-banner-marquee-track">${trackHtml}</div>`;

        // Home page: place it below the main hero swiper banner (relative flow)
        heroContainer.insertAdjacentElement('afterend', bannerEl);
        document.body.classList.remove('has-promo-banner-other');

        // Add dynamic CSS styles for the marquee effect and theme colors
        const styleId = 'promo-banner-marquee-styles';
        if (!document.getElementById(styleId)) {
          const style = document.createElement('style');
          style.id = styleId;
          style.innerHTML = `
            .promo-banner-marquee {
              width: 100%;
              overflow: hidden;
              white-space: nowrap;
              box-sizing: border-box;
              height: 44px;
              display: flex;
              align-items: center;
              position: relative;
              z-index: 100;
              box-shadow: 0 4px 15px rgba(0,0,0,0.5);
              border-top: 1px solid rgba(255,255,255,0.05);
              border-bottom: 1px solid rgba(0,0,0,0.2);
            }
            .promo-banner-marquee-track {
              display: inline-flex;
              animation: marquee-scroll 55s linear infinite;
              will-change: transform;
            }
            .promo-banner-marquee-item {
              display: inline-flex;
              align-items: center;
              font-family: 'Oswald', sans-serif;
              font-size: 13px;
              font-weight: 700;
              text-transform: uppercase;
              letter-spacing: 2px;
              padding: 0;
              margin: 0;
              white-space: nowrap;
            }
            .promo-banner-marquee-item::after {
              content: "✦";
              margin-left: 40px;
              margin-right: 40px;
              color: inherit;
              opacity: 0.6;
            }
            @keyframes marquee-scroll {
              0% { transform: translate3d(0, 0, 0); }
              100% { transform: translate3d(-50%, 0, 0); }
            }
            .promo-banner-marquee.bg-dourado {
              background: linear-gradient(135deg, #F2C84B, #b8880b);
              color: #000;
            }
            .promo-banner-marquee.bg-vermelho {
              background: linear-gradient(135deg, #e74c3c, #c0392b);
              color: #fff;
            }
            .promo-banner-marquee.bg-preto {
              background: #111;
              color: #fff;
              border-bottom: 1px solid rgba(242, 200, 75, 0.3);
            }
            @media (max-width: 768px) {
              .promo-banner-marquee {
                height: 36px;
              }
              .promo-banner-marquee-item {
                font-size: 11px;
                letter-spacing: 1px;
              }
              .promo-banner-marquee-item::after {
                margin-left: 25px;
                margin-right: 25px;
              }
            }
          `;
          document.head.appendChild(style);
        }
      } else {
        document.body.classList.remove('has-promo-banner-other');
        const existingBanner = document.querySelector('.promo-banner-marquee');
        if (existingBanner) existingBanner.remove();
      }

      // Remover footer estático caso exista e injetar
      const existingFooter = document.querySelector('footer');
      const footerHtml = window.ComponentsTemplate.Footer;
      if (existingFooter) {
        existingFooter.outerHTML = footerHtml; // O arquivo footer.html já tem a tag <footer>
      } else {
        document.body.insertAdjacentHTML('beforeend', footerHtml);
      }

      // UPDATE SEO AND SOCIAL SETTINGS DYNAMICALLY
      if (config) {
        // Update tab title and description tags
        if (config.seo_titulo) {
          document.title = config.seo_titulo;
        }
        if (config.seo_descricao) {
          let metaDesc = document.querySelector('meta[name="description"]');
          if (!metaDesc) {
            metaDesc = document.createElement('meta');
            metaDesc.name = 'description';
            document.head.appendChild(metaDesc);
          }
          metaDesc.content = config.seo_descricao;
        }

        // Update footer social media icons & redirection links
        const socialContainer = document.querySelector('.redes-sociais-icones');
        if (socialContainer) {
          let iconsHtml = '';
          if (config.social_instagram) {
            iconsHtml += `<a href="${config.social_instagram}" target="_blank" title="Instagram"><i class="bi bi-instagram me-3 text-white hover:text-[var(--brand-yellow)] transition"></i></a>`;
          }
          if (config.social_facebook) {
            iconsHtml += `<a href="${config.social_facebook}" target="_blank" title="Facebook"><i class="bi bi-facebook me-3 text-white hover:text-[var(--brand-yellow)] transition"></i></a>`;
          }
          if (config.social_tiktok) {
            iconsHtml += `<a href="${config.social_tiktok}" target="_blank" title="TikTok"><i class="bi bi-tiktok me-3 text-white hover:text-[var(--brand-yellow)] transition"></i></a>`;
          }
          if (config.social_youtube) {
            iconsHtml += `<a href="${config.social_youtube}" target="_blank" title="YouTube"><i class="bi bi-youtube text-white hover:text-[var(--brand-yellow)] transition"></i></a>`;
          }
          if (iconsHtml) {
            socialContainer.innerHTML = iconsHtml;
          }
        }

        // Update WhatsApp button in footer
        const whatsappBtn = document.querySelector('.btn-whatsapp-footer');
        if (whatsappBtn && config.whatsapp_numero) {
          whatsappBtn.href = `https://wa.me/${config.whatsapp_numero}`;
        }

        // Update SAC contact email in footer
        const emailSac = document.querySelector('.email-sac');
        if (emailSac && config.email_contato) {
          emailSac.textContent = config.email_contato;
        }

        // Update CNPJ and Address in the copyright area
        const innerCopyright = document.querySelector('.footer-copyright-inner');
        if (innerCopyright) {
          let additionalInfo = '';
          if (config.cnpj) additionalInfo += `CNPJ: ${config.cnpj} `;
          if (config.endereco) additionalInfo += `| Endereço: ${config.endereco} `;
          if (additionalInfo) {
            // Clear any old dynamically added info first
            const oldInfo = innerCopyright.querySelector('.dynamic-copyright-info');
            if (oldInfo) oldInfo.remove();
            const infoEl = document.createElement('p');
            infoEl.className = 'dynamic-copyright-info text-[10px] text-gray-500 mt-2 uppercase tracking-wider font-semibold';
            infoEl.textContent = additionalInfo;
            innerCopyright.appendChild(infoEl);
          }
        }
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
        <img src="/assets/img/logo2026.png" alt="Logo Koketsu Grife" class="max-h-[85px] w-auto object-contain" />
      </a>
    </div>

    <!-- Right Section: Admin & Cart -->
    <div class="flex items-center justify-end gap-3 flex-1">
      
      <!-- Auth Container (Logado) -->
      <div id="navUserContainer" class="hidden items-center group relative">
        <button id="userAvatarBtn" class="bg-black border border-white/10 rounded-full text-white py-2 px-6 flex items-center gap-3 text-[13px] font-semibold transition hover:bg-[var(--brand-yellow)] hover:text-black hover:scale-105 hover:shadow-[0_4px_15px_rgba(255,215,0,0.3)]" aria-label="Menu do usuário">
          <img id="userAvatarImg" src="" alt="Avatar" class="hidden w-5 h-5 rounded-full object-cover" onerror="this.style.display='none'; const fb = document.getElementById('userAvatarFallback'); if(fb) fb.style.display='inline-flex';">
          <i id="userAvatarFallback" class="bi bi-person-fill text-xl"></i>
          <span id="navUserName" class="font-bold uppercase hidden md:inline tracking-[0.1em] text-[0.75rem]"></span>
        </button>

        <!-- Dropdown do usuário (Logado) -->
        <div id="userDropdown" class="absolute top-full right-0 mt-3 w-64 bg-[#0d0d0d] rounded-2xl shadow-[0_20px_60px_rgba(0,0,0,0.8)] border border-[#F2C84B]/20 opacity-0 invisible group-hover:opacity-100 group-hover:visible translate-y-2 group-hover:translate-y-0 transition-all duration-300 z-[1000] overflow-hidden backdrop-blur-xl">
          <!-- Cabeçalho do dropdown -->
          <div class="bg-gradient-to-br from-[#1a1400] to-[#111] p-4 border-b border-[#F2C84B]/10 flex items-center gap-3">
            <div class="relative shrink-0">
              <img id="ddAvatarImg" src="" alt="Avatar" class="hidden w-11 h-11 rounded-full object-cover border-2 border-[#F2C84B]" onerror="this.style.display='none'; const fb = document.getElementById('ddAvatarFallback'); if(fb) fb.style.display='flex';">
              <div id="ddAvatarFallback" class="w-11 h-11 rounded-full flex items-center justify-center text-black font-black text-lg" style="background: linear-gradient(135deg, #F2C84B, #b8880b);"><i class="bi bi-person-fill"></i></div>
              <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-400 rounded-full border-2 border-[#0d0d0d]"></span>
            </div>
            <div class="min-w-0">
              <div id="ddUserName" class="font-black text-white text-sm truncate">Cliente</div>
              <span class="dd-badge inline-flex items-center gap-1 text-[9px] font-black text-black uppercase tracking-widest bg-[#F2C84B] px-2 py-0.5 rounded-full mt-0.5">
                <i class="bi bi-star-fill text-[7px]"></i> MEMBRO KOKETSU
              </span>
            </div>
          </div>
          <!-- Links -->
          <div class="user-dropdown-links p-2 flex flex-col gap-0.5">
            <a id="ddProfileLink" href="/backend/cliente/meu-perfil/0" class="flex items-center gap-3 px-3 py-2.5 text-[13px] text-gray-300 hover:text-[#F2C84B] hover:bg-[#F2C84B]/8 rounded-xl transition-all group/link">
              <i class="bi bi-person text-base text-gray-500 group-hover/link:text-[#F2C84B] transition-colors"></i>
              <span class="font-semibold">Meu Perfil</span>
            </a>
            <a href="/backend/cliente/pedidos" class="flex items-center gap-3 px-3 py-2.5 text-[13px] text-gray-300 hover:text-[#F2C84B] hover:bg-[#F2C84B]/8 rounded-xl transition-all group/link">
              <i class="bi bi-bag-check text-base text-gray-500 group-hover/link:text-[#F2C84B] transition-colors"></i>
              <span class="font-semibold">Meus Pedidos</span>
            </a>
            <a href="/backend/configuracoes" class="flex items-center gap-3 px-3 py-2.5 text-[13px] text-gray-300 hover:text-[#F2C84B] hover:bg-[#F2C84B]/8 rounded-xl transition-all group/link">
              <i class="bi bi-gear text-base text-gray-500 group-hover/link:text-[#F2C84B] transition-colors"></i>
              <span class="font-semibold">Preferências</span>
            </a>
            <div class="h-px bg-white/5 my-1 mx-2"></div>
            <a href="#" id="ddLogoutBtn" class="logout-link flex items-center gap-3 px-3 py-2.5 text-[13px] text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl transition-all group/link">
              <i class="bi bi-box-arrow-right text-base transition-colors"></i>
              <span class="font-semibold">Sair da conta</span>
            </a>
          </div>
        </div>
      </div>

      <!-- Login Button CompSupply Dropdown (Deslogado) -->
      <div id="navGuestContainer" class="relative group h-full flex items-center">
        <a href="#" id="navLoginBtn" class="bg-black border border-white/10 rounded-full text-white py-2 px-6 flex items-center gap-3 text-[13px] font-semibold transition hover:bg-[var(--brand-yellow)] hover:text-black hover:scale-105 hover:shadow-[0_4px_15px_rgba(255,215,0,0.3)]">
          <i class="bi bi-person text-xl"></i>
          <span class="font-bold uppercase hidden md:inline tracking-[0.1em] text-[0.75rem]">LOGIN</span>
        </a>
        
        <!-- Dropdown Hover Panel (Guest) -->
        <div class="absolute right-0 top-full pt-3 w-[280px] z-[1000] opacity-0 invisible group-hover:opacity-100 group-hover:visible translate-y-2 group-hover:translate-y-0 transition-all duration-300 pointer-events-none group-hover:pointer-events-auto">
          <div class="bg-[#0d0d0d] rounded-2xl p-5 shadow-[0_20px_60px_rgba(0,0,0,0.8)] border border-[#F2C84B]/20 backdrop-blur-xl">
            <!-- Cabeçalho -->
            <div class="flex items-center gap-2 mb-4">
              <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background:linear-gradient(135deg,#F2C84B,#b8880b)">
                <i class="bi bi-person-fill text-black text-sm"></i>
              </div>
              <div>
                <p class="text-white font-black uppercase text-[11px] tracking-[0.15em]">Acesse sua conta</p>
                <p class="text-gray-500 text-[10px]">Membro Koketsu</p>
              </div>
            </div>

            <a href="#" class="block w-full text-center bg-[#F2C84B] text-black hover:brightness-110 transition mb-2 font-black uppercase py-3 rounded-xl text-[0.72rem] tracking-[0.1em] shadow-[0_4px_15px_rgba(242,200,75,0.25)]" onclick="if(window.AuthManager) document.getElementById('navLoginBtn').click()">
              <i class="bi bi-lightning-fill mr-1"></i> Entrar na conta
            </a>

            <a href="/backend/register" class="block w-full text-center border border-white/15 text-white hover:border-[#F2C84B]/50 hover:text-[#F2C84B] transition mb-4 font-bold uppercase py-2.5 rounded-xl text-[0.72rem] tracking-[0.1em]">
              Criar nova conta
            </a>

            <div class="h-px bg-white/5 mb-4"></div>

            <a href="/backend/cliente/pedidos" class="group/trace flex items-center justify-between">
              <span class="font-bold uppercase text-gray-500 group-hover/trace:text-[#F2C84B] transition text-[0.68rem] tracking-[0.1em]">Rastrear meu pedido</span>
              <i class="bi bi-arrow-right text-gray-600 group-hover/trace:text-[#F2C84B] group-hover/trace:translate-x-1 transition-all"></i>
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
      <img src="/assets/img/logo2026.png" alt="Koketsu" class="login-modal-logo">
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

          <div class="flex flex-col flex-1 overflow-y-auto gap-10 w-full pb-10">
              <!-- Termos Mais Buscados -->
              <div class="w-full">
                  <h4 class="text-gray-400 font-bold uppercase mb-4 text-[10px] tracking-[0.1em]">Termos mais buscados</h4>
                  <div class="flex flex-wrap gap-3">
                      <button class="border border-white/20 hover:border-[#F2C84B] hover:text-[#F2C84B] text-white rounded-full font-bold px-5 py-2.5 transition text-[11px] tracking-wider flex items-center gap-2" onclick="if(window.SearchManager) SearchManager.setQuery('Camiseta')"><span class="bg-white/10 text-white/50 rounded-full w-4 h-4 flex items-center justify-center text-[8px]">1</span> Camiseta</button>
                      <button class="border border-white/20 hover:border-[#F2C84B] hover:text-[#F2C84B] text-white rounded-full font-bold px-5 py-2.5 transition text-[11px] tracking-wider flex items-center gap-2" onclick="if(window.SearchManager) SearchManager.setQuery('Moletom')"><span class="bg-white/10 text-white/50 rounded-full w-4 h-4 flex items-center justify-center text-[8px]">2</span> Moletom</button>
                      <button class="border border-white/20 hover:border-[#F2C84B] hover:text-[#F2C84B] text-white rounded-full font-bold px-5 py-2.5 transition text-[11px] tracking-wider flex items-center gap-2" onclick="if(window.SearchManager) SearchManager.setQuery('Cargo')"><span class="bg-white/10 text-white/50 rounded-full w-4 h-4 flex items-center justify-center text-[8px]">3</span> Cargo</button>
                      <button class="border border-white/20 hover:border-[#F2C84B] hover:text-[#F2C84B] text-white rounded-full font-bold px-5 py-2.5 transition text-[11px] tracking-wider flex items-center gap-2" onclick="if(window.SearchManager) SearchManager.setQuery('Oversized')"><span class="bg-white/10 text-white/50 rounded-full w-4 h-4 flex items-center justify-center text-[8px]">4</span> Oversized</button>
                      <button class="border border-white/20 hover:border-[#F2C84B] hover:text-[#F2C84B] text-white rounded-full font-bold px-5 py-2.5 transition text-[11px] tracking-wider flex items-center gap-2" onclick="if(window.SearchManager) SearchManager.setQuery('Calça')"><span class="bg-white/10 text-white/50 rounded-full w-4 h-4 flex items-center justify-center text-[8px]">5</span> Calça</button>
                  </div>
              </div>

              <!-- Resultados Relevantes -->
              <div class="w-full">
                  <h4 class="text-gray-400 font-bold mb-6 text-sm tracking-wide">Itens relevantes</h4>
                  <div id="overlayResults" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                      <p class="text-white/50 italic text-sm col-span-full">Aguardando sua busca...</p>
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
