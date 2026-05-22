/**
 * Gerenciador da Página de Detalhes do Produto
 * Versão 2.0 — com validações, feedback visual e galeria mobile
 */

const ProductDetailManager = (() => {
    const API_ENDPOINT = '/api/vitrine.php';
    let allProducts = [];
    let authStatus = { authenticated: false };

    /**
     * Inicializa a página
     */
    const init = async () => {
        const urlParams = new URLSearchParams(window.location.search);
        const productId = parseInt(urlParams.get('id'));

        if (!productId) {
            window.location.href = 'catalogo.html';
            return;
        }

        // Verifica autenticação
        if (window.Utils && window.Utils.checkAuth) {
            authStatus = await window.Utils.checkAuth();
        }

        allProducts = await fetchProducts();
        currentProduct = findProductById(productId);

        if (!currentProduct) {
            Utils.showNotification('Produto não encontrado!', 'error');
            setTimeout(() => window.location.href = 'catalogo.html', 1500);
            return;
        }

        renderProductDetails(currentProduct);
        renderRelatedProducts(currentProduct.categoriaOrigem, productId);
        setupEventListeners();
        setupLightbox();
    };

    /**
     * Busca todos os produtos da API
     */
    const fetchProducts = async () => {
        try {
            const response = await fetch(API_ENDPOINT + '?t=' + Date.now());
            if (!response.ok) throw new Error('Erro na API');
            return await response.json();
        } catch (e) {
            console.error('Erro ao carregar dados:', e);
            return [];
        }
    };

    /**
     * Localiza o produto específico pelo ID navegando pelas categorias
     */
    const findProductById = (id) => {
        for (const cat of allProducts) {
            if (cat.itens) {
                const item = cat.itens.find(p => p.id === id);
                if (item) {
                    return { ...item, categoriaOrigem: cat.categoria };
                }
            }
        }
        return null;
    };

    /**
     * Renderiza as informações no HTML
     */
    const renderProductDetails = (product) => {
        // Textos básicos
        document.title = `${product.nome} | Koketsu Grife`;
        const breadcrumb = document.getElementById('breadcrumb-current');
        if (breadcrumb) breadcrumb.textContent = product.nome;

        const prodName = document.getElementById('product-name');
        if (prodName) prodName.textContent = product.nome;

        const prodCat = document.getElementById('product-category');
        if (prodCat) prodCat.textContent = product.categoriaOrigem;

        const prodDesc = document.getElementById('product-long-desc');
        if (prodDesc) prodDesc.textContent = product.descricao || 'Nenhuma descrição disponível para este produto.';

        const prodShortDesc = document.getElementById('product-short-desc');
        if (prodShortDesc) prodShortDesc.textContent = product.descricao || 'Nenhuma descrição disponível para este produto.';

        // Imagem Principal — com cursor de zoom e aria
        const mainImg = document.getElementById('main-product-img');
        if (mainImg) {
            mainImg.src = product.img;
            mainImg.alt = product.nome;
            mainImg.style.cursor = 'zoom-in';
        }

        // Galeria de Miniaturas (desktop vertical + mobile horizontal)
        renderGallery(product);

        // Preços — destaque dourado no preço principal
        const priceCurrent = product.preco;
        const pCurrent = document.getElementById('price-current');
        if (pCurrent) {
            pCurrent.textContent = `R$ ${priceCurrent.toFixed(2).replace('.', ',')}`;
            pCurrent.classList.add('text-[var(--brand-yellow)]');
        }

        // Lógica do Botão de Ação — mantendo ícone
        const addCartBtn = document.getElementById('btn-add-to-cart');
        if (addCartBtn) {
            addCartBtn.innerHTML = '<i class="bi bi-bag-plus text-lg mr-2"></i> ADICIONAR AO CARRINHO';
            addCartBtn.classList.remove('btn-whatsapp-order', 'btn-login-to-order');
            addCartBtn.classList.add('btn-primary-gold');

            // Garantir que seletores estejam habilitados
            const qtyInput = document.getElementById('buy-qty');
            if (qtyInput) qtyInput.removeAttribute('disabled');
        }

        // Buscar e renderizar tamanhos e cores dinamicamente
        fetchAndRenderSizes(product.id);
        renderColors(product); // FIX: reutiliza allProducts já carregados
    };

    /**
     * Renderiza galeria desktop (miniaturas verticais) e mobile (carrossel horizontal)
     */
    const renderGallery = (product) => {
        let allMedia = [product.img];
        if (product.galeria && product.galeria.length > 0) {
            allMedia = [...allMedia, ...product.galeria];
        }

        // Helper para criar thumb HTML
        const makeThumbHtml = (mediaUrl, idx, extraClass = '') => {
            const isVideo = !!mediaUrl.match(/\.mp4$/i);
            const mediaContent = isVideo
                ? `<video src="${mediaUrl}" class="w-full h-full object-cover"></video>
                   <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                       <i class="bi bi-play-fill text-white text-xl"></i>
                   </div>`
                : `<img src="${mediaUrl}" class="w-full h-full object-cover" alt="Foto do produto">`;

            return `
            <div class="relative shrink-0 rounded-lg overflow-hidden cursor-pointer border-2 
                        ${idx === 0 ? 'border-[var(--brand-yellow)]' : 'border-transparent hover:border-gray-500'} 
                        transition ${extraClass}"
                 onclick="changeMainMedia(this, '${mediaUrl}', ${isVideo})">
                ${mediaContent}
            </div>`;
        };

        // Desktop: miniaturas verticais
        const galleryContainer = document.getElementById('product-gallery-thumbnails');
        if (galleryContainer) {
            galleryContainer.innerHTML = allMedia
                .map((url, idx) => makeThumbHtml(url, idx, 'w-full aspect-[3/4]'))
                .join('');
        }

        // Mobile: carrossel horizontal de miniaturas
        const mobileGallery = document.getElementById('product-gallery-mobile');
        if (mobileGallery) {
            mobileGallery.innerHTML = allMedia
                .map((url, idx) => makeThumbHtml(url, idx, 'w-20 h-20'))
                .join('');
        }
    };

    /**
     * Busca tamanhos originais do banco para o produto
     */
    const fetchAndRenderSizes = async (productId) => {
        const sizeContainer = document.querySelector('.size-selector');
        if (!sizeContainer) return;

        try {
            const response = await fetch(`/api/tamanhos.php?id_produto=${productId}`);
            const result = await response.json();

            if (result.success && result.data && result.data.length > 0) {
                sizeContainer.innerHTML = result.data.map((item, index) => {
                    const sizeLabel = item.tamanho_tamanhos.toUpperCase();
                    const sizeId = `size-${sizeLabel.toLowerCase()}`;
                    return `
                        <input type="radio" name="size" id="${sizeId}" class="hidden peer/${sizeId}" ${index === 0 ? 'checked' : ''}>
                        <label for="${sizeId}" class="w-12 h-12 flex items-center justify-center border border-gray-700 bg-black rounded-lg cursor-pointer text-sm font-bold text-gray-400 peer-checked/${sizeId}:border-[var(--brand-yellow)] peer-checked/${sizeId}:text-black peer-checked/${sizeId}:bg-[var(--brand-yellow)] hover:border-gray-500 transition shadow-[0_0_15px_rgba(248,211,70,0)] peer-checked/${sizeId}:shadow-[0_0_15px_rgba(248,211,70,0.3)]">${sizeLabel}</label>
                    `;
                }).join('');
            } else {
                // Caso não tenha tamanhos cadastrados, usa o padrão (nenhum selecionado por padrão)
                const defaultSizes = ['P', 'M', 'G', 'GG', 'XG'];
                sizeContainer.innerHTML = defaultSizes.map((size) => {
                    const sizeId = `size-${size.toLowerCase()}`;
                    return `
                        <input type="radio" name="size" id="${sizeId}" class="hidden peer/${sizeId}">
                        <label for="${sizeId}" class="w-12 h-12 flex items-center justify-center border border-gray-700 bg-black rounded-lg cursor-pointer text-sm font-bold text-gray-400 peer-checked/${sizeId}:border-[var(--brand-yellow)] peer-checked/${sizeId}:text-black peer-checked/${sizeId}:bg-[var(--brand-yellow)] hover:border-gray-500 transition shadow-[0_0_15px_rgba(248,211,70,0)] peer-checked/${sizeId}:shadow-[0_0_15px_rgba(248,211,70,0.3)]">${size}</label>
                    `;
                }).join('');
            }
        } catch (e) {
            console.error('Erro ao carregar tamanhos:', e);
        }
    };

    /**
     * Renderiza cores reutilizando allProducts já carregados (sem fetch extra)
     */
    const renderColors = (product) => {
        const colorContainer = document.querySelector('.color-selector');
        if (!colorContainer) return;

        const productColors = product.cores || [];
        const mainProductThumb = product.img;

        if (productColors.length > 0) {
            colorContainer.innerHTML = productColors.map((cor, index) => {
                const colorId = `color-${cor.toLowerCase().replace(/\s+/g, '-')}`;
                return `
                    <input type="radio" name="color" id="${colorId}" class="hidden peer/${colorId}" ${index === 0 ? 'checked' : ''}>
                    <label for="${colorId}" class="w-12 h-12 rounded-full cursor-pointer flex items-center justify-center border-2 border-transparent peer-checked/${colorId}:border-[var(--brand-yellow)] hover:border-gray-500 transition p-0.5" title="${cor}">
                       <span class="w-full h-full rounded-full bg-cover bg-center border border-white/10" style="background-image: url('${mainProductThumb}')"></span>
                    </label>
                `;
            }).join('');
        } else {
            // Pill estilizada para única cor
            colorContainer.innerHTML = `
                <div class="flex items-center gap-2 bg-white/5 border border-white/10 px-3 py-2 rounded-full">
                    <span class="w-4 h-4 rounded-full border border-white/20 inline-block" 
                          style="background-image: url('${mainProductThumb}'); background-size: cover; background-position: center;"></span>
                    <span class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Única Cor Disponível</span>
                </div>`;
        }
    };

    /**
     * Renderiza produtos relacionados (mesma categoria)
     */
    const renderRelatedProducts = (categoryName, currentId) => {
        const container = document.getElementById('related-products-grid');
        if (!container) return;

        const category = allProducts.find(c => c.categoria === categoryName);
        if (!category || !category.itens) return;

        const related = category.itens
            .filter(p => p.id !== currentId)
            .slice(0, 4);

        if (related.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-sm col-span-4 text-center">Nenhum produto relacionado encontrado.</p>';
            return;
        }

        container.innerHTML = '';
        related.forEach(prod => {
            const col = document.createElement('div');
            col.className = 'col';
            col.innerHTML = createMiniCard(prod);
            container.appendChild(col);
        });
    };

    /**
     * Helper para criar card premium para produtos relacionados
     */
    const createMiniCard = (prod) => {
        const precoFormatado = prod.preco.toFixed(2).replace('.', ',');

        return `
        <div class="group relative flex flex-col h-full bg-[#0a0a0a] rounded-2xl overflow-hidden border border-white/5 transition-all duration-500 hover:-translate-y-3 hover:shadow-[0_25px_60px_rgba(242,200,75,0.18)] hover:border-[#F2C84B]/40 cursor-pointer" onclick="window.location.href='produto.html?id=${prod.id}'">
            <!-- Imagem -->
            <div class="relative aspect-[3/4] overflow-hidden bg-[#111]">
                <img src="${prod.img}" alt="${prod.nome}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100" loading="lazy">
                <!-- Overlay gradiente -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-transparent to-transparent opacity-70 pointer-events-none"></div>
                <!-- Badge categoria -->
                <span class="absolute top-3 left-3 text-[10px] text-black font-black uppercase tracking-widest bg-[#F2C84B] px-2 py-1 rounded-md">${prod.categoriaOrigem || 'Geral'}</span>
            </div>

            <!-- Conteúdo -->
            <div class="p-5 flex flex-col items-center text-center flex-1 relative z-10">
                <h5 class="text-white font-bold text-sm md:text-base uppercase tracking-[0.08em] mb-3 group-hover:text-[#F2C84B] transition-colors leading-snug line-clamp-2">${prod.nome}</h5>
                
                <div class="mt-auto flex flex-col items-center w-full gap-3">
                    <span class="text-[#F2C84B] font-black text-2xl tracking-tight">R$ ${precoFormatado}</span>
                    <span class="w-full text-center text-[11px] font-bold uppercase tracking-widest text-black bg-[#F2C84B] py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">Ver Produto →</span>
                </div>
            </div>
        </div>
        `;
    };

    /**
     * Configura ouvintes de eventos
     */
    const setupEventListeners = () => {
        const addCartBtn = document.getElementById('btn-add-to-cart');
        if (addCartBtn) {
            addCartBtn.addEventListener('click', () => {
                const qtyInput = document.getElementById('buy-qty');
                const qty = parseInt(qtyInput.value);

                // VALIDAÇÃO: tamanho obrigatório
                const sizeInput = document.querySelector('input[name="size"]:checked');
                if (!sizeInput) {
                    // Destaca visualmente o seletor de tamanho
                    const sizeContainer = document.getElementById('size-selector-container');
                    if (sizeContainer) {
                        sizeContainer.classList.add('ring-2', 'ring-[var(--brand-yellow)]', 'ring-offset-2', 'ring-offset-black', 'rounded-lg', 'p-2');
                        setTimeout(() => {
                            sizeContainer.classList.remove('ring-2', 'ring-[var(--brand-yellow)]', 'ring-offset-2', 'ring-offset-black', 'rounded-lg', 'p-2');
                        }, 2000);
                    }
                    if (window.Utils && Utils.showNotification) {
                        Utils.showNotification('Por favor, selecione um tamanho!', 'error');
                    }
                    return;
                }

                const size = sizeInput.id.replace('size-', '').toUpperCase();
                const colorInput = document.querySelector('input[name="color"]:checked');
                const color = colorInput ? document.querySelector(`label[for="${colorInput.id}"]`).title : null;

                // Integração real com CartManager
                const cartManager = window.CartManager || CartManager;
                if (cartManager) {
                    cartManager.addToCart({
                        id: currentProduct.id,
                        nome: currentProduct.nome,
                        preco: currentProduct.preco,
                        img: currentProduct.img,
                    }, qty, size, color);

                    // FEEDBACK VISUAL no botão
                    const originalHtml = addCartBtn.innerHTML;
                    addCartBtn.innerHTML = '<i class="bi bi-check-circle-fill text-lg mr-2"></i> ADICIONADO!';
                    addCartBtn.classList.add('opacity-80');
                    addCartBtn.disabled = true;
                    setTimeout(() => {
                        addCartBtn.innerHTML = originalHtml;
                        addCartBtn.classList.remove('opacity-80');
                        addCartBtn.disabled = false;
                    }, 2000);

                    if (window.Utils && Utils.showNotification) {
                        Utils.showNotification(`${currentProduct.nome} adicionado ao carrinho!`, 'success');
                    }

                    // Abrir o mini-carrinho após adicionar
                    if (typeof cartManager.openDrawer === 'function') {
                        cartManager.openDrawer();
                    }
                } else {
                    console.error('CartManager não encontrado!');
                }
            });
        }
    };

    /**
     * Lightbox nativo para a imagem principal
     */
    const setupLightbox = () => {
        // Cria overlay do lightbox
        const overlay = document.createElement('div');
        overlay.id = 'product-lightbox';
        overlay.style.cssText = `
            display: none; position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,0.95); cursor: zoom-out;
            align-items: center; justify-content: center;
            backdrop-filter: blur(8px);
        `;
        overlay.innerHTML = `
            <button id="lightbox-close" style="position:absolute;top:20px;right:24px;color:#fff;font-size:2rem;background:none;border:none;cursor:pointer;line-height:1;opacity:0.7;" aria-label="Fechar">
                <i class="bi bi-x-lg"></i>
            </button>
            <img id="lightbox-img" src="" alt="Produto ampliado" 
                 style="max-width:90vw;max-height:90vh;object-fit:contain;border-radius:12px;box-shadow:0 0 60px rgba(0,0,0,0.8);">
        `;
        document.body.appendChild(overlay);

        // Função para abrir lightbox
        window.openLightbox = (src, alt) => {
            const img = overlay.querySelector('#lightbox-img');
            img.src = src;
            img.alt = alt || 'Produto';
            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        };

        // Fechar ao clicar no overlay ou no botão X
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay || e.target.closest('#lightbox-close')) {
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }
        });

        // Fechar com ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && overlay.style.display === 'flex') {
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }
        });

        // Vincular clique na imagem principal (event delegation, pois a imagem é trocada dinamicamente)
        const mainContainer = document.querySelector('.main-image-container');
        if (mainContainer) {
            mainContainer.style.cursor = 'zoom-in';
            mainContainer.addEventListener('click', () => {
                const img = mainContainer.querySelector('img');
                if (img) openLightbox(img.src, img.alt);
            });
        }
    };

    return { init };
})();

/**
 * Função global para ajuste de quantidade
 */
function adjustQty(amount) {
    const input = document.getElementById('buy-qty');
    let val = parseInt(input.value) + amount;
    if (val < 1) val = 1;
    if (val > 99) val = 99;
    input.value = val;
}

/**
 * Função global para alterar a mídia principal (imagem/vídeo)
 * Atualiza tanto a galeria desktop quanto mobile
 */
window.changeMainMedia = function(thumbElement, url, isVideo) {
    const container = document.querySelector('.main-image-container');
    if (!container) return;

    // Atualiza bordas de TODAS as miniaturas (desktop + mobile)
    document.querySelectorAll('#product-gallery-thumbnails > div, #product-gallery-mobile > div').forEach(el => {
        el.classList.remove('border-[var(--brand-yellow)]');
        el.classList.add('border-transparent');
    });

    // Ativa a miniatura clicada e seu par (desktop/mobile) pelo mesmo URL
    document.querySelectorAll(`[onclick*="${url}"]`).forEach(el => {
        el.classList.remove('border-transparent');
        el.classList.add('border-[var(--brand-yellow)]');
    });

    // Troca o conteúdo principal
    if (isVideo) {
        container.innerHTML = `<video src="${url}" class="w-full h-full object-cover" autoplay loop muted controls></video>`;
    } else {
        container.innerHTML = `<img id="main-product-img" src="${url}" class="w-full h-full object-cover fade-in" alt="Produto" style="cursor:zoom-in;">`;
    }
};

// Inicializa
document.addEventListener('DOMContentLoaded', ProductDetailManager.init);
