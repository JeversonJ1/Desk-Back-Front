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
        <div class="swiper-slide h-auto pb-8 pt-4"> <!-- Added padding for shadows/hover effects to not be clipped by swiper -->
          <div class="group relative flex flex-col h-full bg-[#050505] rounded-2xl overflow-hidden border border-white/5 transition-all duration-700 hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(242,200,75,0.22)] hover:border-[#F2C84B]/30 cursor-pointer" onclick="window.location.href='/pages/produto.html?id=${product.id}'">
            
            <!-- Imagem -->
            <div class="relative aspect-[3/4] overflow-hidden bg-[#111]">
              ${product.oferta || product.desconto ? `<div class="absolute top-4 left-4 flex flex-col gap-2 z-10"><span class="bg-[#F2C84B] text-black font-black px-3 py-1 text-[8px] rounded-sm tracking-widest uppercase shadow-lg shadow-yellow-500/20">${product.oferta || product.desconto}</span></div>` : ''}
              <img alt="${product.nome}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-90 group-hover:opacity-100" src="${product.img ? product.img : 'assets/img/placeholder.png'}" loading="lazy"/>
              <!-- Overlay sutil -->
              <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-transparent to-transparent opacity-60 pointer-events-none"></div>
            </div>
            
            <!-- Conteúdo -->
            <div class="p-6 flex flex-col items-center text-center flex-1 relative z-10 border-t border-white/5 bg-gradient-to-b from-[#0a0a0a] to-[#050505]">
              <p class="text-[9px] text-[#F2C84B] font-bold uppercase tracking-widest mb-2">${catName}</p>
              <h5 class="text-white font-bold text-sm uppercase tracking-[0.1em] mb-4 group-hover:text-[#F2C84B] transition-colors leading-snug line-clamp-2">${product.nome}</h5>
              
              <div class="mt-auto flex flex-col items-center w-full">
                <div class="flex items-center justify-center gap-2 mb-6">
                  <span class="text-white font-black text-xl tracking-tighter group-hover:text-[#F2C84B] transition-colors">R$ ${precoFormatado}</span>
                </div>
                
                <!-- Botão Ver Detalhes animado -->
                <div class="w-full bg-transparent border border-white/20 text-white font-bold py-3 text-[10px] uppercase tracking-widest text-center rounded-lg transition-all duration-500 group-hover:bg-[#F2C84B] group-hover:text-black group-hover:border-[#F2C84B] shadow-[0_0_15px_rgba(0,0,0,0.5)]">
                  MAIS DETALHES
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

    // Se não há dados, remove o spinner silenciosamente
    if (!productsData || productsData.length === 0) {
      container.innerHTML = '';
      return;
    }

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

  /**
   * Agrupa lista plana de produtos por nome da categoria
   */
  const groupByCategory = (produtos) => {
    const map = {};
    produtos.forEach(p => {
      const cat = p.nome_categorias || p.categoria_nome || 'Outros';
      if (!map[cat]) map[cat] = { categoria: cat, itens: [] };
      const imgPrincipal = p.imagem_produtos
        ? (p.imagem_produtos.startsWith('http') || p.imagem_produtos.startsWith('/') ? p.imagem_produtos : '/backend/upload/' + p.imagem_produtos)
        : 'assets/img/placeholder.png';
      map[cat].itens.push({
        id: parseInt(p.id_produto),
        nome: p.nome_produtos,
        descricao: p.descricao_produtos || '',
        preco: parseFloat(p.preco_produtos),
        img: imgPrincipal,
        galeria: [],
        oferta: null,
        desconto: null
      });
    });
    return Object.values(map);
  };

  const fetchProducts = async () => {
    try {
      // Tenta a vitrine especializada primeiro
      const response = await fetch(API_ENDPOINT + '?t=' + Date.now());
      if (!response.ok) throw new Error(`HTTP: ${response.status}`);
      const data = await response.json();
      if (Array.isArray(data) && data.length > 0) return data;

      // Fallback: busca todos os produtos e agrupa por categoria
      console.warn('Vitrine sem dados, usando fallback de produtos gerais...');
      const fbRes = await fetch('/api/produtos?t=' + Date.now());
      if (!fbRes.ok) throw new Error(`Fallback HTTP: ${fbRes.status}`);
      const fbData = await fbRes.json();
      const lista = (fbData.data || fbData);
      if (Array.isArray(lista) && lista.length > 0) {
        return groupByCategory(lista);
      }
      return [];
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
        <div class="relative rounded-2xl overflow-hidden flex flex-col group cursor-pointer transition-all duration-700 hover:-translate-y-2 shadow-[0_10px_30px_rgba(0,0,0,0.5)] hover:shadow-[0_20px_50px_rgba(242,200,75,0.2)] h-[400px]" onclick="window.location.href='${catUrl}'">
            <!-- Imagem de Fundo (Edge to Edge) -->
            <img src="${imgSrc}" alt="${category.categoria}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
            
            <!-- Gradiente Escuro no Rodapé para Leitura -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-[#050505]/60 to-transparent opacity-90 group-hover:opacity-100 transition-opacity duration-500"></div>
            
            <!-- Conteúdo (Rodapé) -->
            <div class="absolute bottom-0 left-0 right-0 p-6 z-10 flex flex-col items-center transform transition-transform duration-500 translate-y-4 group-hover:translate-y-0">
                <h3 class="text-white font-black text-xl uppercase tracking-[0.2em] mb-2 group-hover:text-[#F2C84B] transition-colors duration-300 drop-shadow-md text-center">${category.categoria}</h3>
                <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-500 delay-100">
                    <span class="text-white/80 text-[10px] font-bold uppercase tracking-[0.2em]">Ver Coleção</span>
                    <i class="bi bi-arrow-right text-[#F2C84B] text-[12px]"></i>
                </div>
            </div>
            
            <!-- Borda Decorativa -->
            <div class="absolute inset-0 border border-white/10 rounded-2xl pointer-events-none group-hover:border-[#F2C84B]/30 transition-colors duration-700"></div>
        </div>
      `;
    });

    gridContainer.innerHTML = html;
  };

  const loadHeroBanners = async () => {
    const swiperWrapper = document.querySelector('.heroSwiper .swiper-wrapper');
    if (!swiperWrapper) return;
    
    try {
      const response = await fetch('/api/banners?t=' + Date.now());
      if (!response.ok) throw new Error('Network response was not ok');
      const json = await response.json();
      
      if (json.status === 'success' && Array.isArray(json.data) && json.data.length > 0) {
        const activeBanners = json.data.filter(b => b.ativo);
        if (activeBanners.length > 0) {
          let slidesHtml = '';
          activeBanners.forEach(banner => {
            const imgSrc = (banner.imagem.startsWith('http') || banner.imagem.startsWith('/')) 
              ? banner.imagem 
              : '/backend/upload/' + banner.imagem;
            
            const linkTagOpen = banner.link ? `<a href="${banner.link}" class="absolute inset-0 z-10">` : '';
            const linkTagClose = banner.link ? `</a>` : '';
            slidesHtml += `
              <div class="swiper-slide relative">
                ${linkTagOpen}
                <img src="${imgSrc}" class="absolute inset-0 w-full h-full object-cover" alt="${banner.titulo || 'Banner'}">
                ${linkTagClose}
              </div>
            `;
          });
          swiperWrapper.innerHTML = slidesHtml;
          return;
        }
      }
    } catch (error) {
      console.error('Error loading dynamic banners:', error);
    }
    
    console.log('Using default fallback banners.');
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
    await loadHeroBanners();
    initHeroSwiper();
    const products = await fetchProducts();
    renderDynamicCategoriesCards(products);
    renderProducts(products);
  };

  return { init };
})();

document.addEventListener('DOMContentLoaded', ProductManager.init);
