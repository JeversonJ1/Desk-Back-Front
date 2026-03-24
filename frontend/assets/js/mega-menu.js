/**
 * MegaMenu - Sistema de menu estilo CompSupply para Koketsu Grife
 * Carrega categorias e produtos via API REST
 */
const MegaMenu = (() => {
  const API_BASE = '/api';
  let isOpen = false;
  let currentCategoryId = null;
  let categoriesCache = null;
  let productsCache = {};

  // ── DOM refs (resolved lazily) ────────────────────────────
  const el = (id) => document.getElementById(id);

  // ── Open / Close ──────────────────────────────────────────
  const open = async () => {
    const overlay = el('megaMenuOverlay');
    const container = el('megaMenuContainer');
    if (!overlay || !container) return;

    overlay.classList.add('is-open');
    container.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    isOpen = true;

    // Load categories if not yet cached
    await loadCategories();
  };

  const close = () => {
    const overlay = el('megaMenuOverlay');
    const container = el('megaMenuContainer');
    if (!overlay || !container) return;

    overlay.classList.remove('is-open');
    container.classList.remove('is-open');
    document.body.style.overflow = '';
    isOpen = false;
  };

  const toggle = () => {
    isOpen ? close() : open();
  };

  // ── Categories ────────────────────────────────────────────
  const loadCategories = async () => {
    if (categoriesCache) {
      renderCategories(categoriesCache);
      return;
    }

    showCategoriesSkeleton();

    try {
      const res = await fetch(`${API_BASE}/categorias`);
      const json = await res.json();
      // API returns { data: [...] } or directly an array
      const cats = Array.isArray(json) ? json : (json.data || json.categorias || []);
      categoriesCache = cats;
      renderCategories(cats);

      // Auto-load first category products
      if (cats.length > 0) {
        selectCategory(cats[0].id_categorias, cats[0].nome_categorias);
      }
    } catch (err) {
      console.error('[MegaMenu] Failed to load categories:', err);
      showCategoriesError();
    }
  };

  const renderCategories = (categories) => {
    const list = el('megaMenuCategoryList');
    if (!list) return;

    if (!categories || categories.length === 0) {
      list.innerHTML = '<p style="padding:12px 28px;color:#9ca3af;font-size:12px;">Nenhuma categoria disponível.</p>';
      return;
    }

    let html = '<div class="menu-brand-label">Koketsu Grife</div>';
    html += '<div class="menu-divider"></div>';

    categories.forEach((cat) => {
      const id = cat.id_categorias;
      const nome = cat.nome_categorias || 'Categoria';
      const slug = encodeURIComponent(nome);
      html += `
        <button
          class="menu-category-item"
          data-cat-id="${id}"
          data-cat-nome="${nome}"
          onclick="MegaMenu.selectCategory(${id}, '${nome.replace(/'/g, "\\'")}')"
        >
          <span>${nome}</span>
          <i class="bi bi-chevron-right cat-arrow"></i>
        </button>
      `;
    });

    html += '<div class="menu-divider"></div>';
    html += `
      <a href="/frontend/pages/catalogo.html" class="menu-ver-todos" onclick="MegaMenu.close()">
        <i class="bi bi-grid-3x3-gap-fill" style="font-size:13px;"></i>
        Ver Catálogo Completo
      </a>
    `;

    list.innerHTML = html;
  };

  const showCategoriesSkeleton = () => {
    const list = el('megaMenuCategoryList');
    if (!list) return;
    let html = '<div class="menu-skeleton">';
    for (let i = 0; i < 8; i++) {
      html += `<div class="menu-skeleton-item" style="width:${60 + Math.random() * 30}%"></div>`;
    }
    html += '</div>';
    list.innerHTML = html;
  };

  const showCategoriesError = () => {
    const list = el('megaMenuCategoryList');
    if (!list) return;
    list.innerHTML = '<p style="padding:16px 28px;color:#ef4444;font-size:12px;">Erro ao carregar categorias.</p>';
  };

  // ── Category selection / Products ─────────────────────────
  const selectCategory = async (catId, catNome) => {
    currentCategoryId = catId;

    // Update active state in left panel
    document.querySelectorAll('.menu-category-item').forEach((btn) => {
      btn.classList.toggle('is-active', btn.dataset.catId == catId);
    });

    // Update right panel title
    const titleEl = el('megaMenuProductsTitle');
    if (titleEl) {
      titleEl.innerHTML = `Produtos <span>${catNome}</span>`;
    }

    await loadProducts(catId);
  };

  const loadProducts = async (catId) => {
    if (productsCache[catId]) {
      renderProducts(productsCache[catId]);
      return;
    }

    showProductsSkeleton();

    try {
      // Try fetching products filtered by category
      const res = await fetch(`${API_BASE}/produtos?categoria_id=${catId}&limit=6`);
      const json = await res.json();
      const products = Array.isArray(json) ? json : (json.data || json.produtos || []);

      // Store in cache
      productsCache[catId] = products;
      renderProducts(products);
    } catch (err) {
      console.error('[MegaMenu] Failed to load products:', err);
      showProductsError();
    }
  };

  const renderProducts = (products) => {
    const grid = el('megaMenuProductsGrid');
    if (!grid) return;

    if (!products || products.length === 0) {
      grid.innerHTML = `
        <div class="menu-no-products" style="grid-column:1/-1;">
          <i class="bi bi-bag-x" style="font-size:32px;opacity:0.3;"></i>
          <span>Nenhum produto nesta categoria.</span>
        </div>
      `;
      return;
    }

    const shown = products.slice(0, 6);
    grid.innerHTML = shown.map((p) => {
      const id = p.id_produtos || p.id;
      const nome = p.nome_produtos || p.nome || 'Produto';
      const preco = parseFloat(p.preco_produtos || p.preco || 0);
      const precoFormatted = preco.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
      const pixDesconto = (preco * 0.97).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
      const avaliacoes = p.total_avaliacoes || p.avaliacoes || 0;
      const nota = parseFloat(p.nota_media || p.nota || 5);
      const stars = renderStars(nota);

      // Build image URL
      let imgSrc = '/frontend/assets/img/placeholder-product.png';
      if (p.imagem_produtos) imgSrc = `/backend/upload/${p.imagem_produtos}`;
      else if (p.imagem) imgSrc = p.imagem.startsWith('http') ? p.imagem : `/backend/upload/${p.imagem}`;
      else if (p.url_imagem) imgSrc = p.url_imagem;

      return `
        <a href="/frontend/pages/produto.html?id=${id}" class="menu-product-card" onclick="MegaMenu.close()">
          <div class="menu-product-img-wrapper">
            <img src="${imgSrc}" alt="${nome}" loading="lazy" onerror="this.src='/frontend/assets/img/logo2026.png'">
          </div>
          <div class="menu-product-info">
            <div class="menu-product-name">${nome}</div>
            <div class="menu-product-stars">
              ${stars}
              ${avaliacoes > 0 ? `<span class="star-count">(${avaliacoes})</span>` : ''}
            </div>
            <div class="menu-product-price">
              ${precoFormatted}
              <span class="pix-badge">3% PIX</span>
            </div>
          </div>
        </a>
      `;
    }).join('');
  };

  const renderStars = (nota) => {
    const full = Math.floor(nota);
    const half = nota % 1 >= 0.5 ? 1 : 0;
    const empty = 5 - full - half;
    let html = '';
    for (let i = 0; i < full; i++) html += '<i class="bi bi-star-fill star"></i>';
    if (half) html += '<i class="bi bi-star-half star"></i>';
    for (let i = 0; i < empty; i++) html += '<i class="bi bi-star star" style="opacity:0.3;"></i>';
    return html;
  };

  const showProductsSkeleton = () => {
    const grid = el('megaMenuProductsGrid');
    if (!grid) return;
    let html = '';
    for (let i = 0; i < 6; i++) {
      html += `
        <div class="menu-product-skeleton">
          <div class="skel-img"></div>
          <div class="skel-text">
            <div class="skel-line"></div>
            <div class="skel-line w-2-3"></div>
            <div class="skel-line w-1-3"></div>
          </div>
        </div>
      `;
    }
    grid.innerHTML = html;
  };

  const showProductsError = () => {
    const grid = el('megaMenuProductsGrid');
    if (!grid) return;
    grid.innerHTML = `
      <div class="menu-no-products" style="grid-column:1/-1;">
        <i class="bi bi-exclamation-triangle" style="font-size:28px;color:#ef4444;opacity:0.5;"></i>
        <span style="color:#ef4444;">Erro ao carregar produtos.</span>
      </div>
    `;
  };

  // ── Init ──────────────────────────────────────────────────
  const init = () => {
    // Wire open button(s)
    document.querySelectorAll('[data-mega-menu-open]').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        open();
      });
    });

    // Wire close button
    const closeBtn = el('megaMenuCloseBtn');
    if (closeBtn) closeBtn.addEventListener('click', close);

    // Close on overlay click
    const overlay = el('megaMenuOverlay');
    if (overlay) overlay.addEventListener('click', close);

    // Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && isOpen) close();
    });
  };

  document.addEventListener('DOMContentLoaded', init);

  return { open, close, toggle, selectCategory };
})();

window.MegaMenu = MegaMenu;
