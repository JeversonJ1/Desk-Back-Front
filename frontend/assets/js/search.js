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
        container.innerHTML = products.map(prod => `
            <div class="group relative flex flex-col h-full bg-[#050505] rounded-2xl overflow-hidden border border-white/5 transition-all duration-700 hover:shadow-[0_20px_50px_rgba(242,200,75,0.15)] hover:border-[#F2C84B]/30 cursor-pointer" onclick="window.location.href='produto.html?id=${prod.id}'">
                
                <!-- Imagem -->
                <div class="relative aspect-[3/4] overflow-hidden bg-[#111]">
                    <img src="${prod.img && !prod.img.endsWith('/backend/upload/') && !prod.img.endsWith('/backend/upload') ? prod.img : '/frontend/assets/img/LogoKoketsu.jpg'}" onerror="this.src='/frontend/assets/img/LogoKoketsu.jpg'" alt="${prod.nome}" class="w-full h-full object-cover transition-transform duration-1000 opacity-90 group-hover:opacity-100" loading="lazy">
                    <!-- Overlay sutil -->
                    <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-transparent to-transparent opacity-60 pointer-events-none"></div>
                </div>

                <!-- Conteúdo -->
                <div class="p-6 flex flex-col items-center text-center flex-1 relative z-10">
                    <p class="text-[9px] text-[#F2C84B] font-bold uppercase tracking-widest mb-2">${prod.category || 'Geral'}</p>
                    <h5 class="text-white font-bold text-sm uppercase tracking-[0.1em] mb-4 group-hover:text-[#F2C84B] transition-colors leading-snug">${prod.nome}</h5>
                    
                    <div class="mt-auto flex flex-col items-center w-full">
                        <div class="flex items-center justify-center gap-2 mb-1">
                            <span class="text-white font-black text-xl tracking-tighter group-hover:text-[#F2C84B] transition-colors">R$ ${prod.preco.toFixed(2).replace('.', ',')}</span>
                        </div>
                    </div>
                </div>
            </div>
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
