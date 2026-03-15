const ProductManager = (() => {
  const API_ENDPOINT = '/api/vitrine.php';
  let authStatus = { authenticated: false };

  const formatPrice = (value) => {
    return parseFloat(value).toFixed(2).replace('.', ',');
  };

  /**
   * Inicializa o Swiper para um container específico
   */
  const initSwiper = (selector) => {
    if (!window.Swiper) return;
    new Swiper(selector, {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      breakpoints: {
        640: { slidesPerView: 2, spaceBetween: 20 },
        1024: { slidesPerView: 3, spaceBetween: 30 },
        1280: { slidesPerView: 4, spaceBetween: 30 }
      }
    });
  };

  /**
   * Cria SECTION de carrossel para Tailwind
   */
  const createTailwindCarouselSection = (category) => {
    if (!category || !category.itens || category.itens.length === 0) return '';
    const catName = category.categoria || 'Coleção';
    const tag = category.tag || 'Lançamento';
    const safeId = catName.toLowerCase().replace(/\s+/g, '-');

    const slidesHtml = category.itens.map(product => {
      const precoFormatado = formatPrice(product.preco);
      return `
        <div class="swiper-slide h-auto">
          <div class="product-card group bg-[var(--brand-grey)] border border-white/5 flex flex-col h-full w-full">
            <div class="aspect-[3/4] overflow-hidden relative">
              ${product.oferta || product.desconto ? `<span class="absolute top-2 left-2 gold-badge px-2 py-0.5 text-[7px] z-10">${product.oferta || product.desconto}</span>` : ''}
              <img alt="${product.nome}" class="card-main-img w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="${product.img}" loading="lazy"/>
              <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center gap-4">
                 <a href="pages/produto.html?id=${product.id}" class="w-10 h-10 bg-white text-black flex items-center justify-center rounded-full hover:bg-[var(--brand-yellow)] transition">
                    <i class="bi bi-eye"></i>
                 </a>
              </div>
            </div>
            <div class="p-4 flex flex-col flex-1 bg-black/50 backdrop-blur-sm">
              <h4 class="card-title font-heading text-[18px] font-black uppercase tracking-tight text-white mb-4 leading-tight">${product.nome}</h4>
              
              <div class="mt-auto">
                <div class="flex flex-col mb-4">
                  <span class="text-[var(--brand-yellow)] font-bold text-2xl tracking-tighter">R$ ${precoFormatado}</span>
                  <div class="mt-1">
                    <span class="text-[11px] text-white uppercase tracking-wider font-medium">4x de <span class="text-[var(--brand-yellow)] font-bold">R$ ${(product.preco / 4).toFixed(2).replace('.', ',')}</span></span>
                    <span class="text-[9px] text-white/40 uppercase ml-1">sem juros</span>
                  </div>
                </div>
                
                <button class="btn-quick-buy w-full bg-[var(--brand-yellow)] text-black font-bold py-3 text-[10px] uppercase tracking-[0.2em] hover:bg-white transition-all duration-300 transform active:scale-95" data-product-id="${product.id}">
                  ADICIONAR AO CARRINHO
                </button>
              </div>
            </div>
          </div>
        </div>
      `;
    }).join('');

    return `
      <section class="py-24 bg-[var(--brand-dark)] border-t border-white/5 relative z-10">
        <div class="container mx-auto px-6">
          <div class="text-center mb-16 relative">
             <h2 class="font-heading text-6xl md:text-8xl font-bold uppercase tracking-tighter opacity-5 absolute left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none w-full">${catName}</h2>
             <div class="relative z-10">
                <span class="text-[var(--brand-yellow)] font-bold tracking-[0.5em] text-[10px] uppercase mb-4 block">${tag}</span>
                <h3 class="font-heading text-3xl md:text-5xl font-bold uppercase tracking-tight text-white">${catName}</h3>
             </div>
          </div>
          
          <div class="swiper swiper-${safeId}">
            <div class="swiper-wrapper">
              ${slidesHtml}
            </div>
            <!-- Navigation -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
          </div>
        </div>
      </section>
    `;
  };

  const renderProducts = (productsData) => {
    const container = document.getElementById('vitrineTailwind');
    if (!container) return;

    let html = '';
    productsData.forEach(category => {
      const catLower = category.categoria.toLowerCase();
      let tag = 'KOKETSU';
      
      if (catLower.includes('camiseta')) tag = 'ESSENTIALS';
      else if (catLower.includes('calça')) tag = 'STREETSTYLE';
      else if (catLower.includes('moletom')) tag = 'WINTER';
      
      html += createTailwindCarouselSection({ ...category, tag });
    });

    container.innerHTML = html;

    // Inicializar carrosséis
    productsData.forEach(category => {
      const safeId = category.categoria.toLowerCase().replace(/\s+/g, '-');
      initSwiper(`.swiper-${safeId}`);
    });
  };

  const fetchProducts = async () => {
    try {
      const response = await fetch(API_ENDPOINT + '?t=' + Date.now());
      if (!response.ok) throw new Error(`HTTP: ${response.status}`);
      const data = await response.json();
      return Array.isArray(data) ? data : [];
    } catch (error) {
      console.error('Erro ao buscar produtos:', error);
      return [];
    }
  };

  const initHeroSwiper = () => {
    if (!window.Swiper) return;
    new Swiper('.heroSwiper', {
      effect: 'fade',
      fadeEffect: { crossFade: true },
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
    });
  };

  const init = async () => {
    if (window.Utils && window.Utils.checkAuth) {
      authStatus = await window.Utils.checkAuth();
    }
    initHeroSwiper();
    const products = await fetchProducts();
    renderProducts(products);
  };

  return { init };
})();

document.addEventListener('DOMContentLoaded', ProductManager.init);
