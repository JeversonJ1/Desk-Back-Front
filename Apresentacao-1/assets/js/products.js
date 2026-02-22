/**
 * Gerenciador de Produtos e Vitrine
 * Responsável por renderizar e gerenciar a exibição de produtos
 */

const ProductManager = (() => {
  const PRODUCTS_PER_SLIDE = 4;
  const API_ENDPOINT = '/api/vitrine.php';
  const WHATSAPP_NUMBER = '5511999999999'; // Substitua pelo número real da Koketsu
  let authStatus = { authenticated: false };

  /**
   * Formata valor para moeda
   */
  const formatPrice = (value) => {
    return parseFloat(value).toFixed(2).replace('.', ',');
  };

  /**
   * Gera o link do WhatsApp com a mensagem pré-preenchida
   */
  const getWhatsAppLink = (product) => {
    const userName = authStatus.user ? authStatus.user.nome : 'Cliente';
    const message = `Olá! Me chamo ${userName} e tenho interesse no produto:\n\n*${product.nome}*\nPreço: R$ ${formatPrice(product.preco)}\n\n(Vim pelo site Koketsu Grife)`;
    return `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(message)}`;
  };

  /**
   * Cria o HTML de um card de produto
   */
  const createProductCard = (product) => {
    const precoFormatado = formatPrice(product.preco);
    const parcelasFormatadas = formatPrice(
      product.parcelas || product.preco / 6
    );

    // Preço original (20% a mais para simular desconto)
    const precoOriginal = (product.preco * 1.2).toFixed(2);
    const precoOriginalFormatado = formatPrice(precoOriginal);

    let badges = '';
    if (product.desconto) {
      badges += `<span class="badge sale-badge">${product.desconto}</span>`;
    }
    if (product.oferta) {
      badges += `<span class="badge sale-badge-alt">${product.oferta}</span>`;
    }

    const actionButton = `
      <button class="btn-quick-buy" data-product-id="${product.id}">
        Adicionar ao Carrinho
      </button>
    `;

    return `
      <div class="product-card">
        <a href="pages/produto.html?id=${product.id}" class="card-link">
          <div class="card-img-container">
            ${badges ? `<div class="card-badges">${badges}</div>` : ''}
            <img 
              src="${product.img}" 
              alt="${product.alt}" 
              class="card-main-img"
              loading="lazy"
            >
          </div>
          <div class="card-body">
            <h5 class="card-title">${product.nome}</h5>
            <div class="price-info">
              <p class="original-price">R$ ${precoOriginalFormatado}</p>
              <p class="main-price">R$ ${precoFormatado}</p>
              <p class="card-installments">ou <span class="installments-value">6x de R$ ${parcelasFormatadas}</span> sem juros</p>
            </div>
          </div>
        </a>
        <div class="card-actions">
          ${actionButton}
        </div>
      </div>
    `;
  };

  /**
   * Cria uma seção de carrossel de produtos
   */
  const createCarouselSection = (category) => {
    if (!category || !category.itens || category.itens.length === 0) return document.createElement('div');
    const catName = category.categoria || 'Coleção';
    const section = document.createElement('section');
    section.className = 'category-section py-5';

    const titleHtml = `
      <h2 class="h2-titulo">${catName}</h2>
      <p class="category-tag">${category.tag || 'KOKETSU GRIFE - PREMIUM'}</p>
      <div id="carousel-${catName.replace(/\s+/g, '-')}" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner"></div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carousel-${catName.replace(/\s+/g, '-')}" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carousel-${catName.replace(/\s+/g, '-')}" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Próximo</span>
        </button>
      </div>
    `;

    const carouselContainer = document.createElement('div');
    carouselContainer.innerHTML = titleHtml;
    section.appendChild(carouselContainer);

    const inner = carouselContainer.querySelector('.carousel-inner');

    // Criar slides
    for (let i = 0; i < category.itens.length; i += PRODUCTS_PER_SLIDE) {
      const carouselItem = document.createElement('div');
      carouselItem.classList.add('carousel-item');
      if (i === 0) carouselItem.classList.add('active');

      const row = document.createElement('div');
      row.className = 'row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4';

      const slideItems = category.itens.slice(i, i + PRODUCTS_PER_SLIDE);

      slideItems.forEach(product => {
        const col = document.createElement('div');
        col.classList.add('col');
        col.innerHTML = createProductCard(product);
        row.appendChild(col);
      });

      carouselItem.appendChild(row);
      inner.appendChild(carouselItem);
    }

    return section;
  };

  /**
   * Renderiza todos os produtos na vitrine
   */
  const renderProducts = (productsData) => {
    const container = document.getElementById('vitrine');
    if (!container) return;

    container.innerHTML = '';

    productsData.forEach(category => {
      // Pula categoria de lançamentos se estiver vazia ou se explicitamente solicitado
      const catLower = category.categoria.toLowerCase();
      // Pula categorias específicas solicitadas pelo usuário para a HOME
      if (catLower.includes('lançamentos') ||
        catLower.includes('lancamentos') ||
        catLower.includes('vestidos') ||
        catLower.includes('saias') ||
        catLower.includes('shorts') ||
        catLower.includes('polos') ||
        catLower.includes('acessórios') ||
        catLower.includes('acessorios') ||
        catLower.includes('bonés') ||
        catLower.includes('bones')) {
        return;
      }

      const carouselSection = createCarouselSection(category);
      container.appendChild(carouselSection);
    });

    // Inicializar carrosséis do Bootstrap
    initializeCarousels();

    // Re-inicializa observadores de animação para novos elementos
    if (window.AnimationManager) {
      window.AnimationManager.init();
    }
  };

  /**
   * Inicializa os carrosséis do Bootstrap
   */
  const initializeCarousels = () => {
    const carousels = document.querySelectorAll('.carousel');
    carousels.forEach(carouselEl => {
      const carousel = new bootstrap.Carousel(carouselEl, {
        interval: false,
        wrap: false,
        keyboard: false
      });

      // Remover listeners de teclado automáticos
      carouselEl.removeEventListener('keydown.bs.carousel');
    });
  };

  /**
   * Busca produtos da API
   */
  const fetchProducts = async () => {
    try {
      const response = await fetch(API_ENDPOINT + '?t=' + Date.now());

      if (!response.ok) {
        throw new Error(`Erro HTTP: ${response.status}`);
      }

      const data = await response.json();

      // Se a API retornar dados válidos
      if (Array.isArray(data) && data.length > 0) {
        return data;
      }

      console.error('API retornou dados vazios.');
      return [];
    } catch (error) {
      console.error('Erro ao buscar produtos:', error);
      return [];
    }
  };

  /**
   * Inicializa o gerenciador de produtos
   */
  const init = async () => {
    // Verifica autenticação antes de renderizar
    if (window.Utils && window.Utils.checkAuth) {
      authStatus = await window.Utils.checkAuth();
    }

    const products = await fetchProducts();
    renderProducts(products);
  };

  return {
    init,
    renderProducts,
    fetchProducts
  };
})();

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
  ProductManager.init();
});
