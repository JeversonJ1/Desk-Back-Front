/**
 * Gerenciador de Navbar
 * Responsável pela interatividade da barra de navegação
 */

const NavbarManager = (() => {
  const init = () => {
    window.addEventListener('scroll', handleScroll);
  };
  
  const handleScroll = () => {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 50) {
      navbar?.classList.add('scrolled');
    } else {
      navbar?.classList.remove('scrolled');
    }
  };
  
  return {
    init,
    handleScroll // Expose handleScroll so we can trigger it immediately to init state
  };
})();

window.NavbarManager = NavbarManager;

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
  NavbarManager.init();
});
