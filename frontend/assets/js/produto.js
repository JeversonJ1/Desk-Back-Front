/**
 * Gerenciador da Página de Detalhes do Produto
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

        // Imagem Principal
        const mainImg = document.getElementById('main-product-img');
        if (mainImg) {
            mainImg.src = product.img;
            mainImg.alt = product.nome;
        }

        // Galeria de Miniaturas
        const galleryContainer = document.getElementById('product-gallery-thumbnails');
        if (galleryContainer) {
            let allMedia = [product.img];
            if (product.galeria && product.galeria.length > 0) {
                allMedia = [...allMedia, ...product.galeria];
            }
            
            galleryContainer.innerHTML = allMedia.map((mediaUrl, idx) => {
                const isVideo = !!mediaUrl.match(/\.mp4$/i);
                const mediaHtml = isVideo ? 
                    `<video src="${mediaUrl}" class="w-full h-full object-cover"></video><div class="absolute inset-0 flex items-center justify-center bg-black/30"><i class="bi bi-play-fill text-white text-xl"></i></div>` : 
                    `<img src="${mediaUrl}" class="w-full h-full object-cover" alt="Thumb ${idx}">`;
                    
                return `
                <div class="relative w-full aspect-[3/4] shrink-0 rounded-lg overflow-hidden cursor-pointer border-2 ${idx === 0 ? 'border-[var(--brand-yellow)]' : 'border-transparent hover:border-gray-500'} transition" 
                     onclick="changeMainMedia(this, '${mediaUrl}', ${isVideo})">
                    ${mediaHtml}
                </div>`;
            }).join('');
            
            // Renderiza apenas uma imagem grande na view principal (removida lógica de 2ª imagem)
        }

        // Preços
        const priceCurrent = product.preco;
        const priceOld = priceCurrent * 1.25;

        const pOld = document.getElementById('price-old');
        if (pOld) pOld.textContent = `R$ ${priceOld.toFixed(2).replace('.', ',')}`;

        const pCurrent = document.getElementById('price-current');
        if (pCurrent) pCurrent.textContent = `R$ ${priceCurrent.toFixed(2).replace('.', ',')}`;

        const installmentsValue = (priceCurrent / 6).toFixed(2).replace('.', ',');
        const pInstall = document.getElementById('price-installments');
        if (pInstall) pInstall.textContent = `em até 6x de R$ ${installmentsValue} sem juros`;

        // Lógica do Botão de Ação
        const addCartBtn = document.getElementById('btn-add-to-cart');
        if (addCartBtn) {
            addCartBtn.innerHTML = 'ADICIONAR AO CARRINHO';
            addCartBtn.classList.remove('btn-whatsapp-order', 'btn-login-to-order');
            addCartBtn.classList.add('btn-primary-gold');

            // Garantir que seletores estejam habilitados
            const qtyInput = document.getElementById('buy-qty');
            if (qtyInput) qtyInput.removeAttribute('disabled');
        }

        // Buscar e renderizar tamanhos e cores dinamicamente
        fetchAndRenderSizes(product.id);
        fetchAndRenderColors(product.id);
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
                // Caso não tenha tamanhos cadastrados, usa o padrão
                const defaultSizes = ['P', 'M', 'G', 'GG', 'XG'];
                sizeContainer.innerHTML = defaultSizes.map((size, index) => {
                    const sizeId = `size-${size.toLowerCase()}`;
                    return `
                        <input type="radio" name="size" id="${sizeId}" class="hidden peer/${sizeId}" ${index === 1 ? 'checked' : ''}>
                        <label for="${sizeId}" class="w-12 h-12 flex items-center justify-center border border-gray-700 bg-black rounded-lg cursor-pointer text-sm font-bold text-gray-400 peer-checked/${sizeId}:border-[var(--brand-yellow)] peer-checked/${sizeId}:text-black peer-checked/${sizeId}:bg-[var(--brand-yellow)] hover:border-gray-500 transition shadow-[0_0_15px_rgba(248,211,70,0)] peer-checked/${sizeId}:shadow-[0_0_15px_rgba(248,211,70,0.3)]">${size}</label>
                    `;
                }).join('');
            }
        } catch (e) {
            console.error('Erro ao carregar tamanhos:', e);
        }
    };

    /**
     * Busca cores do banco para o produto
     */
    const fetchAndRenderColors = async (productId) => {
        const colorContainer = document.querySelector('.color-selector');
        if (!colorContainer) return;

        try {
            const response = await fetch(`/api/vitrine.php`);
            const data = await response.json();

            let productColors = [];
            let mainProductThumb = '';
            for (const cat of data) {
                const p = cat.itens.find(i => i.id === productId);
                if (p && p.cores) {
                    productColors = p.cores;
                    mainProductThumb = p.img;
                    break;
                }
            }

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
                colorContainer.innerHTML = '<p class="text-[11px] text-gray-500 m-0">Única cor disponível</p>';
            }
        } catch (e) {
            console.error('Erro ao carregar cores:', e);
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

        // Filtra o atual e pega até 4 itens
        const related = category.itens
            .filter(p => p.id !== currentId)
            .slice(0, 4);

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
        const precoOriginal = (prod.preco * 1.25).toFixed(2).replace('.', ',');

        return `
        <div class="group relative flex flex-col h-full bg-[#050505] rounded-2xl overflow-hidden border border-white/5 transition-all duration-700 hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(242,200,75,0.15)] hover:border-[#F2C84B]/30 cursor-pointer" onclick="window.location.href='produto.html?id=${prod.id}'">
            <!-- Imagem -->
            <div class="relative aspect-[3/4] overflow-hidden bg-[#111]">
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
                </div>
            </div>
        </div>
        `;
    };

    /**
     * Configura ouvintes de eventos
     */
    const setupEventListeners = () => {
        // Botão de adicionar ao carrinho
        const addCartBtn = document.getElementById('btn-add-to-cart');
        if (addCartBtn) {
            addCartBtn.addEventListener('click', () => {
                const qtyInput = document.getElementById('buy-qty');
                const qty = parseInt(qtyInput.value);

                const sizeInput = document.querySelector('input[name="size"]:checked');
                const size = sizeInput ? sizeInput.id.replace('size-', '').toUpperCase() : 'M';

                const colorInput = document.querySelector('input[name="color"]:checked');
                const color = colorInput ? document.querySelector(`label[for="${colorInput.id}"]`).textContent : null;

                // Integração real com CartManager
                const cartManager = window.CartManager || CartManager;
                if (cartManager) {
                    cartManager.addToCart({
                        id: currentProduct.id,
                        nome: currentProduct.nome,
                        preco: currentProduct.preco,
                        img: currentProduct.img,
                    }, qty, size, color);

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

    return { init };
})();

/**
 * Função global para ajuste de quantidade (chamada via onclick no HTML)
 */
function adjustQty(amount) {
    const input = document.getElementById('buy-qty');
    let val = parseInt(input.value) + amount;
    if (val < 1) val = 1;
    input.value = val;
}

/**
 * Função global para alterar a mídia principal (imagem/vídeo)
 */
window.changeMainMedia = function(thumbElement, url, isVideo) {
    const container = document.querySelector('.main-image-container');
    if (!container) return;
    
    // Atualiza bordas das miniaturas
    document.querySelectorAll('#product-gallery-thumbnails > div').forEach(el => {
        el.classList.remove('border-[var(--brand-yellow)]');
        el.classList.add('border-transparent');
    });
    thumbElement.classList.remove('border-transparent');
    thumbElement.classList.add('border-[var(--brand-yellow)]');

    // Troca o conteúdo principal da primeira div
    if (isVideo) {
        container.innerHTML = `<video src="${url}" class="w-full h-full object-cover" autoplay loop muted controls></video>`;
    } else {
        container.innerHTML = `<img id="main-product-img" src="${url}" class="w-full h-full object-cover fade-in" alt="Produto">`;
    }
};

// Inicializa
document.addEventListener('DOMContentLoaded', ProductDetailManager.init);
