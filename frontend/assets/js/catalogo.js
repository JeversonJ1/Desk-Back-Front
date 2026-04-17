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

        // Calcula preço máximo real para o slider
        if (flatProducts.length > 0) {
            const maxPriceInCatalog = Math.ceil(Math.max(...flatProducts.map(p => p.preco)));
            activeFilters.maxPrice = maxPriceInCatalog;
            
            // Atualiza o DOM do slider se existir
            const priceRange = document.getElementById('rangePreco');
            if (priceRange) {
                priceRange.max = maxPriceInCatalog;
                priceRange.value = maxPriceInCatalog;
                const maxLabel = document.getElementById('maxPriceLabel');
                if (maxLabel) maxLabel.textContent = `R$ ${maxPriceInCatalog}`;
            }
        }

        // Setup de filtros (conecta os listeners antes de aplicar para evitar conflitos)
        setupFilters();

        // Verifica parâmetro de categoria na URL (suporta 'cat' e 'categoria')
        const params = new URLSearchParams(window.location.search);
        const urlCat = params.get('cat') || params.get('categoria');

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
        // Se ainda não existirem inputs, forçamos a renderização base
        const catContainer = document.getElementById('category-filter-container');
        if (catContainer && catContainer.querySelector('.small') && flatProducts.length > 0) {
            renderCategoryFilters();
            renderSizeFilters();
            renderColorFilters();
        }

        const catCheckboxes = document.querySelectorAll('.cat-filter-input');
        let found = false;

        catCheckboxes.forEach(cb => {
            const label = document.querySelector(`label[for="${cb.id}"]`).textContent.trim();
            const normalizedLabel = normalizeText(label);
            const normalizedCat = normalizeText(catName);

            if (normalizedLabel === normalizedCat ||
                normalizedLabel.includes(normalizedCat) ||
                normalizedCat.includes(normalizedLabel)) {
                
                cb.checked = true;
                
                // activeFilters.categories armazena o raw value, entao comparamos tbm normalizado
                const alreadyExists = activeFilters.categories.some(c => normalizeText(c) === normalizeText(cb.value));
                if (!alreadyExists) {
                    activeFilters.categories.push(cb.value);
                }
                found = true;
            } else {
                cb.checked = false; 
            }
        });

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
    const renderCatalog = (products) => {
        const container = document.getElementById('vitrine-catalogo');
        if (!container) return;

        // Limpa conteúdo estático
        container.innerHTML = '';

        // Atualiza contador
        const countEl = document.getElementById('catalogCount') || document.querySelector('.product-count b') || document.querySelector('[data-product-count]');
        if (countEl) countEl.textContent = products.length;

        if (products.length === 0) {
            container.innerHTML = `
                <div class="col-span-full text-center py-16 flex flex-col items-center">
                    <i class="bi bi-search text-6xl text-gray-700 mb-4 opacity-50"></i>
                    <p class="text-gray-400 tracking-wider">Nenhum produto encontrado com os filtros selecionados.</p>
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

        // Grid responsivo e moderno (CSS Grid no pai lida com a largura)
        productsToShow.forEach((prod) => {
            const html = createCatalogCard(prod);
            const col = document.createElement('div');
            col.className = 'w-full h-full'; 
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
        // Encontra o container da paginação
        const nav = document.querySelector('nav ul'); 
        if (!nav) return;

        nav.innerHTML = '';

        // Se não houver itens, não renderiza nada
        if (!totalItems || totalItems === 0) {
            return;
        }

        const calculatedPages = Math.ceil(totalItems / ITEMS_PER_PAGE);
        const totalPages = calculatedPages || 1; 

        // Prev Button
        const prevLi = document.createElement('li');
        prevLi.innerHTML = `<a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-800 ${currentPage === 1 ? 'text-gray-700 cursor-not-allowed' : 'text-gray-400 hover:text-(--brand-yellow) hover:border-(--brand-yellow) transition'}"><i class="bi bi-arrow-left"></i></a>`;
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
            if (i === currentPage) {
                a.className = 'w-10 h-10 flex items-center justify-center rounded-lg bg-(--brand-yellow) text-black font-bold';
            } else {
                a.className = 'w-10 h-10 flex items-center justify-center rounded-lg border border-gray-800 text-white hover:border-(--brand-yellow) transition';
            }

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
        nextLi.innerHTML = `<a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-800 ${currentPage >= totalPages ? 'text-gray-700 cursor-not-allowed' : 'text-gray-400 hover:text-(--brand-yellow) hover:border-(--brand-yellow) transition'}"><i class="bi bi-arrow-right"></i></a>`;
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
     * Cria o HTML do card para o catálogo com tratamento premium
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
            badges += `<span class="bg-[#F2C84B] text-black font-black px-3 py-1 text-[8px] rounded-sm tracking-widest uppercase shadow-lg shadow-yellow-500/20">${prod.oferta}</span>`;
        }
        if (prod.desconto) {
            badges += `<span class="bg-red-600 text-white font-black px-3 py-1 text-[8px] rounded-sm tracking-widest uppercase shadow-lg shadow-red-500/20">${prod.desconto}</span>`;
        }

        return `
        <div class="group relative flex flex-col h-full bg-[#050505] rounded-2xl overflow-hidden border border-white/5 transition-all duration-700 hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(242,200,75,0.15)] hover:border-[#F2C84B]/30 cursor-pointer" onclick="window.location.href='produto.html?id=${prod.id}'">
            
            <!-- Imagem -->
            <div class="relative aspect-[3/4] overflow-hidden bg-[#111]">
                ${badges ? `<div class="absolute top-4 left-4 flex flex-col gap-2 z-10">${badges}</div>` : ''}
                <img src="${prod.img}" alt="${prod.nome}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-90 group-hover:opacity-100" loading="lazy">
                <!-- Overlay sutil -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-transparent to-transparent opacity-60 pointer-events-none"></div>
            </div>

            <!-- Conteúdo -->
            <div class="p-6 flex flex-col items-center text-center flex-1 relative z-10">
                <p class="text-[9px] text-[#F2C84B] font-bold uppercase tracking-widest mb-2">${prod.categoriaOrigem || 'Geral'}</p>
                <h5 class="text-white font-bold text-sm uppercase tracking-[0.1em] mb-4 group-hover:text-[#F2C84B] transition-colors leading-snug">${prod.nome}</h5>
                
                <div class="mt-auto flex flex-col items-center w-full">
                    <div class="flex items-center justify-center gap-2 mb-1">
                        <span class="text-white font-black text-xl tracking-tighter group-hover:text-[#F2C84B] transition-colors">R$ ${precoFormatado}</span>
                    </div>
                    <p class="text-[10px] text-white/50 uppercase tracking-widest mb-6">6x de <span class="text-white/80 font-bold">R$ ${parcelasFormatadas}</span> s/ juros</p>
                    
                    <!-- Botão Ver Detalhes animado -->
                    <button class="w-full bg-transparent border border-white/20 text-white font-bold py-3 text-[10px] uppercase tracking-widest text-center rounded-lg transition-all duration-500 group-hover:bg-[#F2C84B] group-hover:text-black group-hover:border-[#F2C84B] shadow-[0_0_15px_rgba(0,0,0,0.5)]">
                        VER DETALHES
                    </button>
                </div>
            </div>
        </div>
        `;
    };

    const setupFilters = () => {
        // As lógicas de listeners individuais (Categorias, Tamanhos, Cores) 
        // agora são gerenciadas logo após sua respectiva inserção DOM
        // nos métodos renderCategoryFilters(), renderSizeFilters() e renderColorFilters()

        // Contudo, nós podemos escutar globalmente delegando eventos ou escutar o Preço

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

                // Desmarcar tudo via query selector genérico
                document.querySelectorAll('.cat-filter-input, .size-input, .color-filter-input').forEach(input => {
                    input.checked = false;
                });

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
     * Renderiza filtros de categorias baseados nos produtos carregados
     */
    const renderCategoryFilters = () => {
        const catContainer = document.getElementById('category-filter-container');
        if (!catContainer) return;

        // Extrair todas as categorias originais únicas
        const allCategories = new Set();
        flatProducts.forEach(p => {
            if (p.categoriaOrigem) {
                allCategories.add(p.categoriaOrigem);
            }
        });

        if (allCategories.size === 0) {
            catContainer.innerHTML = '<p class="small text-secondary m-0">Nenhuma categoria encontrada</p>';
            return;
        }

        catContainer.innerHTML = Array.from(allCategories).sort().map((cat, index) => {
            const id = `cat-${index}`;
            return `
                <div class="form-check custom-check">
                    <input class="form-check-input cat-filter-input" type="checkbox" id="${id}" value="${cat}">
                    <label class="form-check-label" for="${id}">${cat}</label>
                </div>
            `;
        }).join('');

        // Adicionar listeners para as categorias
        const catCheckboxes = document.querySelectorAll('.cat-filter-input');
        catCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                const label = document.querySelector(`label[for="${cb.id}"]`).textContent.trim();
                const normalizedValue = normalizeText(cb.value);
                
                if (cb.checked) {
                    if (!activeFilters.categories.some(c => normalizeText(c) === normalizedValue)) {
                        activeFilters.categories.push(cb.value);
                    }
                } else {
                    activeFilters.categories = activeFilters.categories.filter(c => normalizeText(c) !== normalizedValue);
                }
                applyFilters();
            });
        });
    };

    /**
     * Renderiza a grade de filtros de tamanhos
     */
    const renderSizeFilters = () => {
        const sizeContainer = document.getElementById('size-filter-container');
        if (!sizeContainer) return;

        // Extrair todos os tamanhos
        const allSizes = new Set();
        flatProducts.forEach(p => {
            if (p.tamanhos && Array.isArray(p.tamanhos)) {
                p.tamanhos.forEach(t => allSizes.add(t));
            }
        });

        if (allSizes.size === 0) {
            sizeContainer.innerHTML = '<p class="small text-secondary m-0">Nenhum tamanho disponível</p>';
            return;
        }

        const sortedSizes = Array.from(allSizes).sort((a, b) => {
            const order = { 'PP': 1, 'P': 2, 'M': 3, 'G': 4, 'GG': 5, 'XG': 6 };
            return (order[a.toUpperCase()] || 99) - (order[b.toUpperCase()] || 99);
        });

        sizeContainer.innerHTML = sortedSizes.map(size => {
            const id = `s-${size.toLowerCase()}`;
            return `
                <div class="form-check custom-check-btn">
                    <input type="checkbox" name="size" id="${id}" class="size-input" value="${size}">
                    <label for="${id}" class="size-label">${size}</label>
                </div>
            `;
        }).join('');

        // Adicionar listeners
        const sizeInputs = document.querySelectorAll('.size-input');
        sizeInputs.forEach(input => {
            input.addEventListener('change', () => {
                const val = input.value.toUpperCase();
                if (input.checked) {
                    if (!activeFilters.sizes.includes(val)) {
                        activeFilters.sizes.push(val);
                    }
                } else {
                    activeFilters.sizes = activeFilters.sizes.filter(s => s !== val);
                }
                applyFilters();
            });
        });
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

        // Na primeira execução REAL (após fetch), renderiza cores, categorias e tamanhos
        const colorContainer = document.getElementById('color-filter-container');
        if (colorContainer && !colorContainer.querySelector('.form-check') && flatProducts.length > 0) {
            renderCategoryFilters();
            renderSizeFilters();
            renderColorFilters();
        }

        let filtered = [...flatProducts];

        // Filtro de Categoria
        if (activeFilters.categories.length > 0) {
            filtered = filtered.filter(p => {
                if (!p.categoriaOrigem) return false;
                const pCatNormalized = normalizeText(p.categoriaOrigem);
                return activeFilters.categories.some(catLabel => {
                    const labelNormalized = normalizeText(catLabel);
                    return pCatNormalized === labelNormalized;
                });
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