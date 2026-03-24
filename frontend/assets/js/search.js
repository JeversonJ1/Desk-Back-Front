const SearchManager = (() => {
    const API_URL = '/api/vitrine.php';
    let allProducts = [];

    const init = async () => {
        const input = document.getElementById('overlaySearchInput');
        if (!input) return;

        allProducts = await fetchProducts();

        input.addEventListener('input', Utils.debounce((e) => {
            const term = e.target.value.toLowerCase().trim();
            const resultsContainer = document.getElementById('overlayResults');
            
            if (term.length < 2) {
                resultsContainer.innerHTML = '<p class="text-white/20 text-xs italic">Aguardando sua busca...</p>';
                return;
            }

            const filtered = filterProducts(term);
            renderResults(filtered, resultsContainer);
        }, 300));
    };

    const fetchProducts = async () => {
        try {
            const resp = await fetch(API_URL);
            const data = await resp.json();
            if (!Array.isArray(data)) return [];
            let flat = [];
            data.forEach(cat => {
                if (cat?.itens) {
                    cat.itens.forEach(item => {
                        flat.push({ ...item, category: cat.categoria || '' });
                    });
                }
            });
            return flat;
        } catch (e) {
            console.error('Search fetch error:', e);
            return [];
        }
    };

    const filterProducts = (term) => {
        return allProducts.filter(p =>
            p.nome.toLowerCase().includes(term) ||
            p.category.toLowerCase().includes(term)
        ).slice(0, 10);
    };

    const renderResults = (products, container) => {
        if (products.length === 0) {
            container.innerHTML = '<p class="text-white-50 small">Nenhum resultado encontrado.</p>';
            return;
        }
        container.innerHTML = products.map(p => `
            <a href="/pages/produto.html?id=${p.id}" class="d-flex align-items-center gap-3 text-decoration-none bg-dark p-3 rounded-3 border border-secondary transition-all" style="border-color: rgba(255,255,255,0.1) !important;" onmouseover="this.style.borderColor='var(--brand-yellow)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'">
                <img src="${p.img}" alt="${p.nome}" class="rounded shadow-sm" style="width: 64px; height: 64px; object-fit: cover;">
                <div class="grow">
                    <div class="text-white small fw-bold text-uppercase" style="letter-spacing: 0.1em; transition: color 0.3s;" onmouseover="this.style.color='var(--brand-yellow)'" onmouseout="this.style.color='white'">${p.nome}</div>
                    <div class="text-warning small fw-bolder mt-1">R$ ${p.preco.toFixed(2).replace('.', ',')}</div>
                </div>
                <i class="bi bi-arrow-right-short text-white-50 fs-4 transition-all" onmouseover="this.style.color='var(--brand-yellow)'" onmouseout="this.style.color='rgba(255,255,255,0.5)'"></i>
            </a>
        `).join('');
    };

    const openOverlay = () => {
        const overlay = document.getElementById('searchOverlay');
        const input = document.getElementById('overlaySearchInput');
        if (overlay) {
            // Tailwind compat
            overlay.classList.remove('invisible', 'opacity-0');
            // Global Bootstrap compat
            overlay.style.visibility = 'visible';
            overlay.style.opacity = '1';
            
            setTimeout(() => input?.focus(), 100);
            document.body.style.overflow = 'hidden';
        }
    };

    const closeOverlay = () => {
        const overlay = document.getElementById('searchOverlay');
        if (overlay) {
             // Tailwind compat
            overlay.classList.add('invisible', 'opacity-0');
             // Global Bootstrap compat
            overlay.style.visibility = 'hidden';
            overlay.style.opacity = '0';
            
            document.body.style.overflow = '';
        }
    };

    const setQuery = (query) => {
        const input = document.getElementById('overlaySearchInput');
        if (input) {
            input.value = query;
            input.dispatchEvent(new Event('input'));
        }
    };

    return { init, openOverlay, closeOverlay, setQuery };
})();

window.SearchManager = SearchManager;
document.addEventListener('DOMContentLoaded', SearchManager.init);
