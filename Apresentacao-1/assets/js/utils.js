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
    checkAuth
  };
})();
