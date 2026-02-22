/**
 * Gerenciador de Navbar
 * Responsável pela interatividade da barra de navegação
 */

const NavbarManager = (() => {
  const navbar = document.getElementById('navbar');
  
  const init = () => {
    window.addEventListener('scroll', handleScroll);
  };
  
  const handleScroll = () => {
    if (window.scrollY > 50) {
      navbar?.classList.add('scrolled');
    } else {
      navbar?.classList.remove('scrolled');
    }
  };
  
  return {
    init
  };
})();

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
  NavbarManager.init();
});
