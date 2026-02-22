/**
 * Gerenciador de Accordion (Menu)
 * Responsável pela lógica do accordion do menu offcanvas
 */

const AccordionManager = (() => {
  const init = () => {
    const buttons = document.querySelectorAll('.accordion-button');
    buttons.forEach(button => {
      button.addEventListener('click', handleAccordionClick);
    });
  };
  
  const handleAccordionClick = (e) => {
    // Bootstrap já gerencia a lógica do accordion
    // Esta função está aqui para futuros eventos customizados
    const button = e.target.closest('.accordion-button');
    if (button) {
      console.log('Accordion clicado:', button.textContent.trim());
    }
  };
  
  return {
    init
  };
})();

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
  AccordionManager.init();
});
