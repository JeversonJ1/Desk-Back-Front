/**
 * Gerenciador de Animações (Scroll Reveal)
 */
const AnimationManager = (() => {
    const initReveal = () => {
        const observerOptions = {
            threshold: 0.15,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    // Opcional: parar de observar após animar
                    // observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const revealElements = document.querySelectorAll('.reveal');
        revealElements.forEach(el => observer.observe(el));
    };

    return {
        init: initReveal
    };
})();

document.addEventListener('DOMContentLoaded', AnimationManager.init);
