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
      loop: false,
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
          <div class="product-card group bg-transparent flex flex-col h-full w-full transition-all duration-500 cursor-pointer" onclick="window.location.href='/pages/produto.html?id=${product.id}'">
            <div class="aspect-3/4 overflow-hidden relative rounded-2xl bg-[#111]">
              ${product.oferta || product.desconto ? `<span class="absolute top-4 left-4 bg-(--brand-yellow) text-black font-black px-3 py-1 text-[8px] z-10 rounded-full tracking-widest uppercase shadow-lg shadow-yellow-500/20">${product.oferta || product.desconto}</span>` : ''}
              <img alt="${product.nome}" class="card-main-img w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-90 group-hover:opacity-100" src="${product.img ? product.img : 'assets/img/placeholder.png'}" loading="lazy"/>
            </div>
            <div class="pt-6 flex flex-col flex-1">
              <h4 class="card-title font-sans text-xs font-bold uppercase tracking-[0.2em] text-white/40 group-hover:text-white transition-colors mb-2 leading-tight">${product.nome}</h4>
              
              <div class="mt-auto">
                <div class="flex flex-col">
                  <span class="text-white font-black text-xl tracking-tighter group-hover:text-[#F2C84B] transition-colors">R$ ${precoFormatado}</span>
                  <div class="mt-1 mb-4">
                    <span class="text-[9px] text-white/40 uppercase tracking-widest font-medium">10x de <span class="text-white/60 font-bold">R$ ${(product.preco / 10).toFixed(2).replace('.', ',')}</span> s/ juros</span>
                  </div>
                  <div class="w-full bg-transparent border border-white/20 text-white font-bold py-2 text-[10px] uppercase tracking-widest text-center rounded-lg group-hover:bg-[#F2C84B] group-hover:text-black group-hover:border-transparent transition-colors shadow-xl">
                    MAIS DETALHES
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;
    }).join('');

    return `
      <section class="py-24 bg-(--brand-dark) border-t border-white/5 relative z-10">
        <div class="container mx-auto px-6">
          <div class="text-center mb-16 relative">
             <div class="relative z-10 flex flex-col items-center">
                <h3 class="shimmer-title text-center">${catName}</h3>
                <span class="text-[#F2C84B] font-bold tracking-[0.5em] text-[10px] uppercase block mt-2">${tag}</span>
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
      let tag = 'EXCLUSIVIDADE URBANA';
      
      if (catLower.includes('cami') || catLower.includes('polo') || catLower.includes('t-shirt')) {
          tag = 'ESSENCIAIS DO DIA A DIA';
      } else if (catLower.includes('calça') || catLower.includes('calca') || catLower.includes('bermuda') || catLower.includes('short')) {
          tag = 'DOMÍNIO DO STREETSTYLE';
      } else if (catLower.includes('moletom') || catLower.includes('jaqueta') || catLower.includes('casaco')) {
          tag = 'CONFORTO E ESTILO ABSOLUTO';
      } else if (catLower.includes('tenis') || catLower.includes('tênis') || catLower.includes('calcado')) {
          tag = 'PASSOS DE ATITUDE';
      } else if (catLower.includes('acessorio') || catLower.includes('acessório') || catLower.includes('bone') || catLower.includes('boné')) {
          tag = 'O DETALHE QUE FAZ A DIFERENÇA';
      }
      
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

  const renderDynamicCategoriesCards = (productsData) => {
    const gridContainer = document.getElementById('dynamic-categories-grid');
    if (!gridContainer || !productsData || productsData.length === 0) return;

    // Shuffle and pick up to 4 categories
    const shuffledCategories = [...productsData].sort(() => 0.5 - Math.random()).slice(0, 4);
    
    let html = '';
    shuffledCategories.forEach(category => {
      const catLower = category.categoria.toLowerCase();
      let tag = 'EXCLUSIVIDADE URBANA';
      
      if (catLower.includes('cami') || catLower.includes('polo') || catLower.includes('t-shirt')) {
          tag = 'ESSENCIAIS';
      } else if (catLower.includes('calça') || catLower.includes('calca') || catLower.includes('bermuda') || catLower.includes('short')) {
          tag = 'STREETWEAR';
      } else if (catLower.includes('moletom') || catLower.includes('jaqueta') || catLower.includes('casaco')) {
          tag = 'INVERNO';
      } else if (catLower.includes('tenis') || catLower.includes('tênis') || catLower.includes('calcado')) {
          tag = 'EXCLUSIVO';
      } else if (catLower.includes('acessorio') || catLower.includes('acessório') || catLower.includes('bone') || catLower.includes('boné')) {
          tag = 'ACESSÓRIOS';
      }

      // Pick a random item from this category for the image
      const randomItem = category.itens[Math.floor(Math.random() * category.itens.length)];
      const imgSrc = (randomItem && randomItem.img) ? randomItem.img : 'assets/img/placeholder.png';
      const catUrl = `/pages/catalogo.html?categoria=${encodeURIComponent(category.categoria.toLowerCase())}`;

      html += `
        <div class="relative rounded-2xl overflow-hidden flex flex-col group cursor-pointer transition-all duration-500 hover:-translate-y-2 shadow-2xl hover:shadow-[0_10px_40px_rgba(242,200,75,0.15)] border border-[#F2C84B]/10 hover:border-[#F2C84B]/40 bg-[#0a0a0a]" onclick="window.location.href='${catUrl}'">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(242,200,75,0.08)_0%,transparent_70%)] opacity-50 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
            <div class="absolute top-6 left-6 z-10">
                <span class="text-[#111] bg-[#F2C84B] px-3 py-1 text-[8px] font-bold uppercase tracking-widest rounded-full shadow-lg">
                    ${tag}
                </span>
            </div>
            <div class="m-2 rounded-2xl bg-[#111] p-4 pt-12 flex-1 flex items-center justify-center overflow-hidden relative z-0">
                <img src="${imgSrc}" alt="${category.categoria}" class="w-full h-auto object-contain transition-transform duration-700 group-hover:scale-110 drop-shadow-xl" style="max-height: 200px;">
            </div>
            <div class="p-6 pt-4 pb-10 text-center relative z-10 flex flex-col items-center">
                <h3 class="text-white font-black text-sm uppercase tracking-[0.15em] m-0 group-hover:text-[#F2C84B] transition-transform duration-300 group-hover:-translate-y-2">${category.categoria}</h3>
                <div class="absolute bottom-4 left-0 right-0 opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center gap-1 translate-y-2 group-hover:translate-y-0">
                    <span class="text-[#F2C84B] text-[9px] font-bold uppercase tracking-widest">Explorar</span>
                    <i class="bi bi-arrow-right text-[#F2C84B] text-[10px]"></i>
                </div>
            </div>
        </div>
      `;
    });

    gridContainer.innerHTML = html;
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
    renderDynamicCategoriesCards(products);
    renderProducts(products);
  };

  return { init };
})();

document.addEventListener('DOMContentLoaded', ProductManager.init);
