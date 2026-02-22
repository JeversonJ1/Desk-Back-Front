/**
 * Gerenciador da Página de Catálogo
 */

const CatalogManager = (() => {
    const API_ENDPOINT = '../api/vitrine.php';
    let allProducts = [];
    let flatProducts = [];
    const ITEMS_PER_PAGE = 12;
    let currentPage = 1;
    let activeFilters = {
        categories: [],
        sizes: [],
        colors: [], // Suporte a múltiplas cores
        maxPrice: 1000,
        sort: 'Lançamentos'
    };

    let authStatus = { authenticated: false };

    /**
     * Remove acentos e caracteres especiais para comparação
     */
    const normalizeText = (text) => {
        return text ? text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '') : '';
    };





    /**
     * Inicializa o catálogo
     */
    const init = async () => {
        // Verifica autenticação
        if (window.Utils && window.Utils.checkAuth) {
            authStatus = await window.Utils.checkAuth();
        }

        // Busca produtos
        allProducts = await fetchProducts();

        // Flatten a estrutura de categorias para uma lista única de produtos
        flatProducts = flattenProducts(allProducts);



        // Setup de filtros (conecta os listeners antes de aplicar para evitar conflitos)
        setupFilters();

        // Verifica parâmetro de categoria na URL
        const params = new URLSearchParams(window.location.search);
        const urlCat = params.get('cat');

        if (urlCat) {
            handleURLCategory(urlCat);
        } else {
            // Renderiza padrão se não houver categoria na URL
            applyFilters();
        }
    };



    /**
     * Trata a categoria vinda da URL
     */
    const handleURLCategory = (catName) => {
        const catCheckboxes = document.querySelectorAll('#collapseCat .form-check-input');
        let found = false;

        catCheckboxes.forEach(cb => {
            const label = document.querySelector(`label[for="${cb.id}"]`).textContent.trim();
            const normalizedLabel = normalizeText(label);
            const normalizedCat = normalizeText(catName);

            if (normalizedLabel === normalizedCat ||
                normalizedLabel.includes(normalizedCat) ||
                normalizedCat.includes(normalizedLabel)) {
                cb.checked = true;
                if (!activeFilters.categories.includes(label)) {
                    activeFilters.categories.push(label);
                }
                found = true;
            } else {
                cb.checked = false; // Desmarca outros se vier da URL
            }
        });

        // Se encontrou, aplica os filtros (que agora incluem a categoria)
        // Se não encontrou, renderiza tudo normalmente
        applyFilters();
    };

    /**
     * Busca produtos da API
     */
    const fetchProducts = async () => {
        try {
            const response = await fetch(API_ENDPOINT);
            if (!response.ok) throw new Error('Erro na API');
            const data = await response.json();

            if (Array.isArray(data) && data.length > 0) return data;

            console.error('API retornou lista vazia ou inválida.');
            return [];
        } catch (e) {
            console.error('Erro ao carregar catálogo:', e);
            return [];
        }
    };

    /**
     * Transforma a lista de categorias em lista de itens
     */
    const flattenProducts = (categories) => {
        let items = [];
        categories.forEach(cat => {
            if (cat.itens && Array.isArray(cat.itens)) {
                // Adiciona a categoria ao item se não tiver
                const itemsWithCat = cat.itens.map(item => ({
                    ...item,
                    categoriaOrigem: cat.categoria
                }));
                items = items.concat(itemsWithCat);
            }
        });
        return items;
    };

    /**
     * Renderiza o grid de produtos
     */
    /**
     * Renderiza o grid de produtos
     */
    const renderCatalog = (products) => {
        const container = document.getElementById('vitrine-catalogo');
        if (!container) return;

        // Limpa conteúdo estático
        container.innerHTML = '';

        // Atualiza contador
        const countEl = document.querySelector('.product-count b');
        if (countEl) countEl.textContent = products.length;

        if (products.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search display-1 text-secondary opacity-25"></i>
                    <p class="mt-3 text-secondary">Nenhum produto encontrado com os filtros selecionados.</p>
                </div>
            `;
            renderPagination(0); // Limpa paginação ou mostra vazia
            return;
        }

        // Paginação
        const totalItems = products.length;
        const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
        const endIndex = startIndex + ITEMS_PER_PAGE;
        const productsToShow = products.slice(startIndex, endIndex);

        // Grid responsivo e moderno
        productsToShow.forEach((prod) => {
            const html = createCatalogCard(prod);
            const col = document.createElement('div');
            col.className = 'col-6 col-md-4 col-lg-3';
            col.innerHTML = html;
            container.appendChild(col);
        });

        renderPagination(totalItems);

        if (window.AnimationManager) {
            window.AnimationManager.init();
        }
    };

    /**
     * Renderiza a paginação
     */
    const renderPagination = (totalItems) => {
        const nav = document.querySelector('nav ul.pagination-premium');
        if (!nav) return;

        nav.innerHTML = '';

        const minPages = 3;
        const calculatedPages = Math.ceil(totalItems / ITEMS_PER_PAGE);
        // Garante pelo menos 3 páginas na visualização (pedido do usuário)
        // Mas se tiver mais, usa o calculado.
        // Se calculatedPages for 0 (sem produtos), ainda mostra 3?
        // Sim, o usuário pediu "mesmo que nao tenha produto suficiente".
        const totalPages = Math.max(minPages, calculatedPages || 1);

        // Prev Button
        const prevLi = document.createElement('li');
        prevLi.innerHTML = `<a href="#" class="${currentPage === 1 ? 'disabled-link' : ''}"><i class="bi bi-arrow-left"></i></a>`;
        prevLi.onclick = (e) => {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                applyFilters(false);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        };
        nav.appendChild(prevLi);

        // Page Numbers
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = "#";
            a.textContent = i;
            if (i === currentPage) a.className = 'active';

            a.onclick = (e) => {
                e.preventDefault();
                currentPage = i;
                applyFilters(false);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };
            li.appendChild(a);
            nav.appendChild(li);
        }

        // Next Button
        const nextLi = document.createElement('li');
        nextLi.innerHTML = `<a href="#" class="${currentPage >= totalPages ? 'disabled-link' : ''}"><i class="bi bi-arrow-right"></i></a>`;
        nextLi.onclick = (e) => {
            e.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                applyFilters(false);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        };
        nav.appendChild(nextLi);
    };

    /**
     * Cria o HTML do card para o catálogo
     */
    const createCatalogCard = (prod) => {
        const precoFormatado = prod.preco.toFixed(2).replace('.', ',');
        const precoOriginal = (prod.preco * 1.2).toFixed(2).replace('.', ',');
        const parcelasFormatadas = (prod.preco / 6).toFixed(2).replace('.', ',');

        const WHATSAPP_NUMBER = '5511999999999';
        const userName = authStatus.user ? authStatus.user.nome : 'Cliente';
        const message = `Olá! Me chamo ${userName} e tenho interesse no produto:\n\n*${prod.nome}*\nPreço: R$ ${precoFormatado}\n\n(Vim pelo catálogo Koketsu Grife)`;
        const wsLink = `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(message)}`;

        let badges = '';
        if (prod.oferta) {
            badges += `<span class="badge sale-badge">${prod.oferta}</span>`;
        }
        if (prod.desconto) {
            badges += `<span class="badge sale-badge-alt">${prod.desconto}</span>`;
        }

        const actionButton = `
            <button class="btn-quick-buy" onclick="event.preventDefault(); window.location.href='produto.html?id=${prod.id}'">
                VER DETALHES
            </button>
        `;

        return `
        <div class="product-card">
            <a href="produto.html?id=${prod.id}" class="card-link">
                <div class="card-img-container">
                    ${badges ? `<div class="card-badges">${badges}</div>` : ''}
                    <img src="${prod.img}" class="card-main-img" alt="${prod.nome}" loading="lazy">
                </div>
                <div class="card-body">
                    <p class="card-category text-uppercase small opacity-50 mb-1">${prod.categoriaOrigem || 'Geral'}</p>
                    <h5 class="card-title">${prod.nome}</h5>
                    <div class="price-info">
                        <p class="original-price">R$ ${precoOriginal}</p>
                        <p class="main-price gold-text">R$ ${precoFormatado}</p>
                        <p class="card-installments">6x de <span class="installments-value">R$ ${parcelasFormatadas}</span></p>
                    </div>

                </div>
            </a>
            <div class="card-actions">
                ${actionButton}
            </div>
        </div>
      `;
    };

    const setupFilters = () => {
        // Categoria Checkboxes
        const catCheckboxes = document.querySelectorAll('#collapseCat .form-check-input');
        catCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                const label = document.querySelector(`label[for="${cb.id}"]`).textContent.trim();
                if (cb.checked) {
                    if (!activeFilters.categories.includes(label)) {
                        activeFilters.categories.push(label);
                    }
                } else {
                    activeFilters.categories = activeFilters.categories.filter(c => c !== label);
                }
                applyFilters();
            });
        });

        // Tamanho Checkboxes (Ajustado para IDs corretos e comportamento de array)
        const sizeInputs = document.querySelectorAll('.size-input');
        sizeInputs.forEach(input => {
            input.addEventListener('change', () => {
                const label = document.querySelector(`label[for="${input.id}"]`).textContent.trim().toUpperCase();
                if (input.checked) {
                    if (!activeFilters.sizes.includes(label)) {
                        activeFilters.sizes.push(label);
                    }
                } else {
                    activeFilters.sizes = activeFilters.sizes.filter(s => s !== label);
                }
                applyFilters();
            });
        });



        // Preço Range
        const priceRange = document.getElementById('rangePreco');
        const rangeTooltip = document.getElementById('rangeTooltip');

        if (priceRange) {
            priceRange.addEventListener('input', (e) => {
                const value = e.target.value;
                const maxLabel = document.getElementById('maxPriceLabel');
                if (maxLabel) maxLabel.textContent = `R$ ${value}`;

                // Tooltip Update
                if (rangeTooltip) {
                    rangeTooltip.textContent = `R$ ${value}`;
                    const percent = (value - priceRange.min) / (priceRange.max - priceRange.min) * 100;
                    rangeTooltip.style.left = `${percent}%`;
                    priceRange.parentElement.classList.add('active');
                }

                activeFilters.maxPrice = parseInt(value);
                applyFilters();
            });

            priceRange.addEventListener('change', () => {
                priceRange.parentElement.classList.remove('active');
            });

            priceRange.parentElement.classList.remove('active');
        }

        // Limpar Tudo
        const clearBtn = document.querySelector('.filter-header button');

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                activeFilters = {
                    categories: [],
                    sizes: [],
                    colors: [],
                    maxPrice: 1000,
                    sort: 'Lançamentos'
                };

                catCheckboxes.forEach(cb => cb.checked = false);
                sizeInputs.forEach(input => input.checked = false);

                if (priceRange) {
                    priceRange.value = 1000;
                    const maxLabel = document.getElementById('maxPriceLabel');
                    if (maxLabel) maxLabel.textContent = `R$ 1000`;
                }

                const sortSelect = document.querySelector('.sort-select');
                if (sortSelect) sortSelect.value = 'Lançamentos';

                applyFilters();
            });
        }

        // Ordenação
        const sortSelect = document.querySelector('.sort-select');
        if (sortSelect) {
            sortSelect.addEventListener('change', (e) => {
                activeFilters.sort = e.target.value;
                applyFilters();
            });
        }
    };

    /**
     * Renderiza filtros de cores baseados nos produtos carregados
     */
    const renderColorFilters = () => {
        const colorContainer = document.getElementById('color-filter-container');
        if (!colorContainer) return;

        // Extrair todas as cores únicas dos produtos
        const allCores = new Set();
        flatProducts.forEach(p => {
            if (p.cores && Array.isArray(p.cores)) {
                p.cores.forEach(c => allCores.add(c));
            }
        });

        if (allCores.size === 0) {
            colorContainer.innerHTML = '<p class="small text-secondary m-0">Nenhuma variação de cor</p>';
            return;
        }

        colorContainer.innerHTML = Array.from(allCores).sort().map(cor => {
            const id = `cor-${cor.toLowerCase().replace(/\s+/g, '-')}`;
            return `
                <div class="form-check custom-check">
                    <input class="form-check-input color-filter-input" type="checkbox" value="${cor}" id="${id}">
                    <label class="form-check-label" for="${id}">${cor}</label>
                </div>
            `;
        }).join('');

        // Adicionar listeners para as cores
        const colorCheckboxes = document.querySelectorAll('.color-filter-input');
        colorCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                if (cb.checked) {
                    activeFilters.colors.push(cb.value);
                } else {
                    activeFilters.colors = activeFilters.colors.filter(c => c !== cb.value);
                }
                applyFilters();
            });
        });
    };

    const applyFilters = (resetPage = true) => {
        if (resetPage) {
            currentPage = 1;
        }

        // Na primeira execução REAL (após fetch), renderiza cores
        const colorContainer = document.getElementById('color-filter-container');
        if (colorContainer && colorContainer.querySelector('.small') && flatProducts.length > 0) {
            renderColorFilters();
        }

        let filtered = [...flatProducts];

        // Filtro de Categoria
        if (activeFilters.categories.length > 0) {
            filtered = filtered.filter(p => {
                if (!p.categoriaOrigem) return false;
                return activeFilters.categories.some(catLabel =>
                    p.categoriaOrigem.toLowerCase().includes(catLabel.replace('Camisetas ', '').toLowerCase()) ||
                    catLabel.toLowerCase().includes(p.categoriaOrigem.toLowerCase())
                );
            });
        }

        // Filtro de Preço
        filtered = filtered.filter(p => p.preco <= activeFilters.maxPrice);

        // Filtro de Tamanho (Real)
        if (activeFilters.sizes.length > 0) {
            filtered = filtered.filter(p => {
                if (!p.tamanhos || !Array.isArray(p.tamanhos)) return false;
                return activeFilters.sizes.some(s => p.tamanhos.includes(s));
            });
        }

        // Filtro de Cor
        if (activeFilters.colors && activeFilters.colors.length > 0) {
            filtered = filtered.filter(p => {
                if (!p.cores || !Array.isArray(p.cores)) return false;
                return activeFilters.colors.some(c => p.cores.includes(c));
            });
        }



        // Ordenação
        if (activeFilters.sort === 'Menor Preço') {
            filtered.sort((a, b) => a.preco - b.preco);
        } else if (activeFilters.sort === 'Maior Preço') {
            filtered.sort((a, b) => b.preco - a.preco);
        } else if (activeFilters.sort === 'Lançamentos') {
            filtered.sort((a, b) => b.id - a.id);
        }

        renderCatalog(filtered);
    };

    return { init };
})();

document.addEventListener('DOMContentLoaded', CatalogManager.init);