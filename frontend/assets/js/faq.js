/**
 * Gerenciador de FAQ
 * Responsável pela interatividade do accordion FAQ
 */

const FAQManager = (() => {
  const init = () => {
    const faqs = document.querySelectorAll('.faq-question');
    faqs.forEach(button => {
      button.addEventListener('click', handleFaqClick);
    });
  };
  
  const handleFaqClick = (e) => {
    const button = e.currentTarget;
    const parent = button.parentElement;
    
    // Fechar outras FAQs abertas
    document.querySelectorAll('.faq').forEach(faq => {
      if (faq !== parent) {
        faq.classList.remove('open');
      }
    });
    
    // Toggle da FAQ clicada
    parent.classList.toggle('open');
  };
  
  return {
    init
  };
})();

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
  FAQManager.init();
});
