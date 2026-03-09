import 'bootstrap';

// Utilitário de autenticação para uso em outras páginas
class AuthHelper {
  static getSessionId() {
    return localStorage.getItem('sessionId');
  }

  static getUsername() {
    return localStorage.getItem('username');
  }

  static isAuthenticated() {
    return !!this.getSessionId();
  }

  static getRole() {
    return localStorage.getItem('role') || 'admin';
  }

  static applyRoleRestrictions() {
    const role = this.getRole().toLowerCase();
    
    if (role === 'vendedor') {
      // Ocultar menus não permitidos
      const restrictedLinks = document.querySelectorAll('a[href="produtos.html"], a[href="configuracoes.html"]');
      restrictedLinks.forEach(link => {
        link.style.display = 'none';
      });

      // Bloquear acesso direto via URL
      const path = window.location.pathname;
      if (path.includes('produtos.html') || path.includes('configuracoes.html')) {
        alert('Acesso negado. Restrito para Administradores.');
        window.location.href = 'dashboard.html';
      }
      
      // Ocultar cards no dashboard que sejam de estoque ou valor total
      const cardsToHide = document.querySelectorAll('.metric-produtos, .metric-estoque, .metric-valor, .estoque-section');
      cardsToHide.forEach(el => el.style.display = 'none');
    }
  }

  static async validateAndRedirect() {
    const sessionId = this.getSessionId();

    if (!sessionId) {
      window.location.href = 'login.html';
      return false;
    }

    try {
      const result = await window.api.validateSession(sessionId);
      if (!result.valido) {
        this.logout();
        return false;
      }
      return true;
    } catch (error) {
      console.error('Erro ao validar sessão:', error);
      this.logout();
      return false;
    }
  }

  static logout() {
    const sessionId = this.getSessionId();
    if (sessionId) {
      window.api.logout(sessionId).catch(err => console.error('Erro ao fazer logout:', err));
    }
    localStorage.removeItem('sessionId');
    localStorage.removeItem('username');
    window.location.href = 'login.html';
  }

  static async changePassword(oldPassword, newPassword) {
    const sessionId = this.getSessionId();
    if (!sessionId) {
      throw new Error('Não autenticado');
    }

    return window.api.changePassword(sessionId, oldPassword, newPassword);
  }

  static showUserInfo() {
    const username = this.getUsername();
    const userElements = document.querySelectorAll('[data-user-name]');
    if (userElements.length > 0 && username) {
      userElements.forEach(el => el.textContent = username);
    }
  }
}

// Expor AuthHelper globalmente (necessário com type="module")
window.AuthHelper = AuthHelper;

// Validar autenticação ao carregar páginas protegidas
document.addEventListener('DOMContentLoaded', () => {
  // Só valida se a página atual não é login.html
  if (window.location.pathname.includes('login.html') === false &&
    window.location.pathname.includes('.html')) {

    // Verificar se está autenticado
    if (!AuthHelper.isAuthenticated()) {
      console.log('Usuário não autenticado, redirecionando...');
      window.location.href = 'login.html';
      return;
    }

    // Validar sessão
    AuthHelper.validateAndRedirect().catch(err => {
      console.error('Erro na validação:', err);
      // Não força logout se der erro, pode ser problema de rede
    });
    
    AuthHelper.applyRoleRestrictions();
  }

  AuthHelper.showUserInfo();
});

// Adicionar botão de logout se existir
document.addEventListener('DOMContentLoaded', () => {
  const logoutButtons = document.querySelectorAll('[data-logout]');
  logoutButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      AuthHelper.logout();
    });
  });
});

// Sidebar toggle (mobile)
document.addEventListener('DOMContentLoaded', () => {
  const toggleButtons = document.querySelectorAll('[data-sidebar-toggle]');
  const backdrop = document.querySelector('[data-sidebar-backdrop]');
  const menuLinks = document.querySelectorAll('.sidebar .menu a');

  const closeSidebar = () => document.body.classList.remove('sidebar-open');
  const toggleSidebar = () => document.body.classList.toggle('sidebar-open');

  toggleButtons.forEach(btn => btn.addEventListener('click', toggleSidebar));
  if (backdrop) backdrop.addEventListener('click', closeSidebar);

  menuLinks.forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 768) closeSidebar();
    });
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth > 768) closeSidebar();
  });
});
