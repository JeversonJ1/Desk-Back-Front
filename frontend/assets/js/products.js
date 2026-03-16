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
          <div class="product-card group bg-transparent flex flex-col h-full w-full transition-all duration-500">
            <div class="aspect-[3/4] overflow-hidden relative rounded-2xl bg-[#111]">
              ${product.oferta || product.desconto ? `<span class="absolute top-4 left-4 bg-[var(--brand-yellow)] text-black font-black px-3 py-1 text-[8px] z-10 rounded-full tracking-widest uppercase shadow-lg shadow-yellow-500/20">${product.oferta || product.desconto}</span>` : ''}
              <img alt="${product.nome}" class="card-main-img w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-90 group-hover:opacity-100" src="${product.img}" loading="lazy"/>
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-end p-6">
                 <a href="/frontend/pages/produto.html?id=${product.id}" class="w-full bg-white text-black font-bold py-3 text-[10px] uppercase tracking-widest text-center rounded-lg hover:bg-[var(--brand-yellow)] transition-colors shadow-xl">
                    Ver Detalhes
                 </a>
              </div>
            </div>
            <div class="pt-6 flex flex-col flex-1">
              <h4 class="card-title font-sans text-xs font-bold uppercase tracking-[0.2em] text-white/40 group-hover:text-white transition-colors mb-2 leading-tight">${product.nome}</h4>
              
              <div class="mt-auto">
                <div class="flex flex-col">
                  <span class="text-white font-black text-xl tracking-tighter group-hover:text-[var(--brand-yellow)] transition-colors">R$ ${precoFormatado}</span>
                  <div class="mt-1">
                    <span class="text-[9px] text-white/20 uppercase tracking-widest font-medium">10x de <span class="text-white/40">R$ ${(product.preco / 10).toFixed(2).replace('.', ',')}</span> s/ juros</span>
                  </div>
                </div>
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
