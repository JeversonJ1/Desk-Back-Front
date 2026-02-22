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

        // Imagem
        const mainImg = document.getElementById('main-product-img');
        if (mainImg) {
            mainImg.src = product.img;
            mainImg.alt = product.nome;
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
                        <input type="radio" name="size" id="${sizeId}" class="size-option" ${index === 0 ? 'checked' : ''}>
                        <label for="${sizeId}" class="size-label">${sizeLabel}</label>
                    `;
                }).join('');
            } else {
                // Caso não tenha tamanhos cadastrados, usa o padrão
                const defaultSizes = ['P', 'M', 'G', 'GG', 'XG'];
                sizeContainer.innerHTML = defaultSizes.map((size, index) => {
                    const sizeId = `size-${size.toLowerCase()}`;
                    return `
                        <input type="radio" name="size" id="${sizeId}" class="size-option" ${index === 1 ? 'checked' : ''}>
                        <label for="${sizeId}" class="size-label">${size}</label>
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
            for (const cat of data) {
                const p = cat.itens.find(i => i.id === productId);
                if (p && p.cores) {
                    productColors = p.cores;
                    break;
                }
            }

            if (productColors.length > 0) {
                colorContainer.innerHTML = productColors.map((cor, index) => {
                    const colorId = `color-${cor.toLowerCase().replace(/\s+/g, '-')}`;
                    return `
                        <input type="radio" name="color" id="${colorId}" class="color-option" ${index === 0 ? 'checked' : ''}>
                        <label for="${colorId}" class="color-label" title="${cor}">${cor}</label>
                    `;
                }).join('');
            } else {
                colorContainer.innerHTML = '<p class="small text-secondary m-0">Única cor disponível</p>';
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
            <div class="product-card">
                <a href="produto.html?id=${prod.id}" class="card-link">
                    <div class="card-img-container">
                        <img src="${prod.img}" class="card-main-img" alt="${prod.nome}" loading="lazy">
                    </div>
                    <div class="card-body">
                        <p class="card-category text-uppercase small opacity-50 mb-1">${prod.categoriaOrigem || 'Geral'}</p>
                        <h5 class="card-title">${prod.nome}</h5>
                        <div class="price-info">
                            <p class="original-price">R$ ${precoOriginal}</p>
                            <p class="main-price gold-text">R$ ${precoFormatado}</p>
                        </div>
                    </div>
                </a>
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
                    } else if (window.bootstrap) {
                        const miniCartEl = document.getElementById('miniCartOffcanvas');
                        if (miniCartEl) {
                            const bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(miniCartEl);
                            bsOffcanvas.show();
                        }
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

// Inicializa
document.addEventListener('DOMContentLoaded', ProductDetailManager.init);
