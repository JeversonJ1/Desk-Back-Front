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
            container.innerHTML = '<p class="text-white/40 text-sm">Nenhum resultado encontrado.</p>';
            return;
        }
        container.innerHTML = products.map(p => `
            <a href="/frontend/pages/produto.html?id=${p.id}" class="flex items-center gap-4 group text-decoration-none bg-white/5 p-3 rounded-xl border border-white/5 hover:border-[var(--brand-yellow)] transition">
                <img src="${p.img}" alt="${p.nome}" class="w-16 h-16 object-cover rounded-lg group-hover:scale-105 transition duration-500">
                <div class="flex-grow">
                    <div class="text-white text-xs font-bold uppercase tracking-widest group-hover:text-[var(--brand-yellow)] transition">${p.nome}</div>
                    <div class="text-[var(--brand-yellow)] text-[10px] font-black mt-1">R$ ${p.preco.toFixed(2).replace('.', ',')}</div>
                </div>
                <span class="bi bi-arrow-right-short text-white/20 group-hover:text-[var(--brand-yellow)] transition text-xl"></span>
            </a>
        `).join('');
    };

    const openOverlay = () => {
        const overlay = document.getElementById('searchOverlay');
        const input = document.getElementById('overlaySearchInput');
        if (overlay) {
            overlay.classList.remove('invisible', 'opacity-0');
            setTimeout(() => input?.focus(), 100);
            document.body.style.overflow = 'hidden';
        }
    };

    const closeOverlay = () => {
        const overlay = document.getElementById('searchOverlay');
        if (overlay) {
            overlay.classList.add('invisible', 'opacity-0');
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
