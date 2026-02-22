/**
 * Sistema de Busca Global em Tempo Real
 */
const SearchManager = (() => {
    const API_URL = '/api/vitrine.php';
    let allProducts = [];

    const init = async () => {
        const searchInput = document.getElementById('globalSearch');
        const resultsContainer = document.getElementById('searchResults');
        if (!searchInput || !resultsContainer) return;

        // Carrega produtos para cache local
        allProducts = await fetchProducts();

        searchInput.addEventListener('input', Utils.debounce((e) => {
            const term = e.target.value.toLowerCase().trim();

            if (term.length < 2) {
                resultsContainer.classList.add('d-none');
                return;
            }

            const filtered = filterProducts(term);
            renderResults(filtered, resultsContainer);
        }, 300));

        // Fecha ao clicar fora
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.classList.add('d-none');
            }
        });
    };

    const fetchProducts = async () => {
        try {
            const resp = await fetch(API_URL);
            const data = await resp.json();
            if (!Array.isArray(data)) return [];
            let flat = [];
            data.forEach(cat => {
                if (cat && cat.itens && Array.isArray(cat.itens)) {
                    cat.itens.forEach(item => {
                        flat.push({ ...item, category: cat.categoria || '' });
                    });
                }
            });
            return flat;
        } catch (e) {
            console.error('Erro ao buscar produtos para busca:', e);
            return [];
        }
    };

    const filterProducts = (term) => {
        return allProducts.filter(p =>
            p.nome.toLowerCase().includes(term) ||
            p.category.toLowerCase().includes(term)
        ).slice(0, 6); // Limite de 6 resultados
    };

    const renderResults = (products, container) => {
        if (products.length === 0) {
            container.innerHTML = '<div class="p-4 text-center small text-secondary">Nenhum produto encontrado.</div>';
        } else {
            container.innerHTML = products.map(p => `
        <a href="/pages/produto.html?id=${p.id}" class="search-result-item text-decoration-none">
          <img src="${p.img}" alt="${p.nome}" class="mini-thumb">
          <div>
            <div class="text-white small fw-bold">${p.nome}</div>
            <div class="gold-text x-small">R$ ${p.preco.toFixed(2).replace('.', ',')}</div>
          </div>
        </a>
      `).join('');
        }
        container.classList.remove('d-none');
    };

    return { init };
})();

document.addEventListener('DOMContentLoaded', SearchManager.init);
