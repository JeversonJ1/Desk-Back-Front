const CartManager = (() => {
  const CART_STORAGE_KEY = 'koketsu_cart';

  /**
   * Obtém e migra o carrinho do localStorage
   */
  const getCart = () => {
    try {
      const stored = localStorage.getItem(CART_STORAGE_KEY);
      let cart = stored ? JSON.parse(stored) : [];

      // MIGRATION: Garante que todos os itens tenham cartItemId e ID string
      let migrated = false;
      cart = cart.map(item => {
        if (!item.cartItemId) {
          const pid = String(item.id || item.productId);
          item.id = pid;
          item.cartItemId = `${pid}-${item.size || 'M'}`;
          migrated = true;
        }
        // Garante que quantidade seja número
        if (typeof item.quantidade !== 'number') {
          item.quantidade = parseInt(item.quantidade) || 1;
          migrated = true;
        }
        return item;
      });

      if (migrated) {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
      }
      return cart;
    } catch (error) {
      console.error('Erro ao obter carrinho:', error);
      return [];
    }
  };

  const saveCart = (cart) => {
    try {
      localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
      window.dispatchEvent(new Event('cartUpdated'));
    } catch (error) {
      console.error('Erro ao salvar carrinho:', error);
    }
  };

  /**
   * Adiciona um produto ao carrinho com suporte a atributos
   */
  const addToCart = (product, quantity = 1, size = 'M', color = null) => {
    const cart = getCart();

    // Converte ID para string para consistência
    const pid = String(product.id);
    // ID único considerando ID + Tamanho + Cor
    const cartItemId = `${pid}-${size}-${color || 'default'}`;

    // Procura por ID ÚNICO da variação
    const existingItem = cart.find(item => item.cartItemId === cartItemId);

    if (existingItem) {
      existingItem.quantidade += quantity;
    } else {
      cart.push({
        id: pid,
        cartItemId: cartItemId, // ID Único (Produto + Tamanho + Cor)
        nome: product.nome,
        preco: product.preco,
        img: product.img,
        quantidade: quantity,
        size: size,
        color: color,
        parcelas: product.parcelas || (product.preco / 6)
      });
    }

    saveCart(cart);
    showToast(`${product.nome} (${size}${color ? ` - ${color}` : ''}) adicionado!`);
    return cart;
  };

  const removeFromCart = (cartItemId) => {
    console.log('Removendo item:', cartItemId);
    const cart = getCart();
    const filteredCart = cart.filter(item => item.cartItemId !== cartItemId);
    saveCart(filteredCart);
    return filteredCart;
  };

  const updateQuantity = (cartItemId, quantity) => {
    const cart = getCart();
    const item = cart.find(item => item.cartItemId === cartItemId);

    if (item) {
      if (quantity <= 0) {
        return removeFromCart(cartItemId);
      }
      item.quantidade = quantity;
      saveCart(cart);
    }
    return cart;
  };

  const getItemCount = () => {
    const cart = getCart();
    return cart.reduce((acc, item) => acc + (item.quantidade || 0), 0);
  };

  /**
   * Sistema de Notificação Toast
   */
  const showToast = (message) => {
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
      toastContainer = document.createElement('div');
      toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
      toastContainer.style.zIndex = '9999';
      document.body.appendChild(toastContainer);
    }

    const toastId = 'toast-' + Date.now();
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true" style="border: 1px solid var(--color-primary) !important;">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle-fill text-primary me-2"></i> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = document.getElementById(toastId);
    if (window.bootstrap) {
      const bsToast = new bootstrap.Toast(toastElement, { delay: 3000 });
      bsToast.show();
    }
    toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
  };

  /**
   * Sistema de Mini-Carrinho (Drawer)
   */
  const injectMiniCartHTML = () => {
    if (document.getElementById('miniCartOffcanvas')) return;

    const html = `
      <div class="offcanvas offcanvas-end mini-cart-drawer" tabindex="-1" id="miniCartOffcanvas" aria-labelledby="miniCartLabel">
        <div class="offcanvas-header border-bottom border-dark">
          <h5 class="offcanvas-title fw-bold text-white" id="miniCartLabel">MEU CARRINHO</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0 d-flex flex-column">
          <div id="miniCartItems" class="flex-grow-1 overflow-auto p-3">
            <!-- Itens injetados via JS -->
          </div>
          <div class="mini-cart-footer border-top border-dark p-4 bg-dark-secondary">
            <div class="d-flex justify-content-between mb-3 text-white">
              <span class="text-uppercase small">Subtotal</span>
              <span id="miniCartSubtotal" class="fw-bold gold-text">R$ 0,00</span>
            </div>
            <a href="carrinho.html" class="btn btn-outline-light w-100 py-3 fw-bold">VER CARRINHO</a>
            <button id="btn-finalizar-pedido" class="btn btn-primary-gold w-100 py-3 mt-2 fw-bold">
              <i class="bi bi-check-circle-fill me-2"></i> FINALIZAR PEDIDO
            </button>
          </div>
        </div>
      </div>
    `;
    document.body.insertAdjacentHTML('beforeend', html);
  };

  const renderMiniCart = () => {
    const itemsContainer = document.getElementById('miniCartItems');
    const subtotalEl = document.getElementById('miniCartSubtotal');
    if (!itemsContainer) return;

    const cart = getCart();
    if (cart.length === 0) {
      itemsContainer.innerHTML = '<div class="text-center py-5 opacity-50"><i class="bi bi-bag-x fs-1 mb-3 d-block"></i>Carrinho vazio</div>';
      if (subtotalEl) subtotalEl.textContent = 'R$ 0,00';
      return;
    }

    let subtotal = 0;
    itemsContainer.innerHTML = cart.map(item => {
      const itemSubtotal = item.preco * item.quantidade;
      subtotal += itemSubtotal;
      return `
        <div class="mini-cart-item d-flex gap-3 mb-4">
          <div class="item-img-mini">
            <img src="${item.img}" alt="${item.nome}" width="70" height="90" style="object-fit: cover; border-radius: 4px;">
          </div>
          <div class="item-info-mini flex-grow-1">
            <h6 class="text-white small fw-bold mb-1">${item.nome}</h6>
            <p class="text-secondary small mb-2">Tam: ${item.size} ${item.color ? `| Cor: ${item.color}` : ''} | Qtd: ${item.quantidade}</p>
            <div class="d-flex justify-content-between align-items-center">
              <span class="gold-text small fw-bold">R$ ${item.preco.toFixed(2).replace('.', ',')}</span>
              <button class="btn-remove-mini text-danger bg-transparent p-0" onclick="CartManager.removeFromCart('${item.cartItemId}')">
                <i class="bi bi-trash small"></i>
              </button>
            </div>
          </div>
        </div>
      `;
    }).join('');

    if (subtotalEl) subtotalEl.textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
  };

  const openDrawer = () => {
    const offcanvasEl = document.getElementById('miniCartOffcanvas');
    if (offcanvasEl && window.bootstrap) {
      const bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
      bsOffcanvas.show();
    }
  };

  const initAddToCartButtons = () => {
    document.addEventListener('click', (e) => {
      const quickBtn = e.target.closest('.btn-quick-buy');
      if (quickBtn) {
        const card = quickBtn.closest('.product-card');
        if (card) {
          const product = {
            id: quickBtn.dataset.productId,
            nome: card.querySelector('.card-title').textContent.trim(),
            preco: parseFloat(card.querySelector('.main-price').textContent.replace('R$', '').replace('.', '').replace(',', '.').trim()),
            img: card.querySelector('.card-main-img').src
          };
          addToCart(product, 1, 'M');
          openDrawer();
        }
      }
    });
  };

  const updateCartBadge = () => {
    const count = getItemCount();
    const badges = document.querySelectorAll('.cart-badge');
    badges.forEach(badge => {
      badge.textContent = count;
      badge.style.display = count > 0 ? 'inline-flex' : 'none';
    });
    renderMiniCart();
  };

  // URL da API de Perfil
  const PERFIL_API = '/api/perfil_api.php';

  /**
   * Verifica e solicita dados do perfil (telefone/endereço)
   */
  const checkProfile = async (userId) => {
    try {
      const response = await fetch(PERFIL_API);
      const result = await response.json();

      if (result.success && result.data) {
        // CORREÇÃO: As chaves retornadas pela API são 'telefone' e 'endereco', não 'telefone_perfil'
        const { id_perfil, telefone, endereco } = result.data;

        // Validação simples: não nulo e não vazio
        if (telefone && endereco && telefone.trim() !== '' && endereco.trim() !== '') {
          return { ok: true, id_perfil: id_perfil };
        }
      }

      // Se faltar dados
      // Substituindo Alert nativo por Modal Bootstrap
      const modalId = 'profileIncompleteModal';
      let modalEl = document.getElementById(modalId);
      if (modalEl) modalEl.remove();

      const modalHtml = `
          <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
              <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content bg-dark text-white border-secondary">
                      <div class="modal-header border-secondary">
                          <h5 class="modal-title fw-bold text-warning"><i class="bi bi-exclamation-triangle me-2"></i>Falta Pouco!</h5>
                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body text-center py-4">
                          <p class="mb-3">Para garantir que seu pedido chegue certinho, precisamos que você preencha seu <strong>Telefone</strong> e <strong>Endereço</strong>.</p>
                          <p class="text-secondary small">Você será redirecionado para completar seu perfil.</p>
                          <button id="btnRedirectProfile" class="btn btn-primary-gold w-100 fw-bold mt-3">PREENCHER AGORA</button>
                      </div>
                  </div>
              </div>
          </div>
      `;
      document.body.insertAdjacentHTML('beforeend', modalHtml);

      const bsModal = new bootstrap.Modal(document.getElementById(modalId));
      bsModal.show();

      document.getElementById('btnRedirectProfile').addEventListener('click', () => {
        bsModal.hide();
        if (userId) {
          window.location.href = `/backend/cliente/meu-perfil/${userId}`;
        } else {
          window.location.href = '/backend/cliente/dashboard';
        }
      });

      return { ok: false };

    } catch (error) {
      console.error('Erro ao verificar perfil:', error);
      showToast('Erro ao verificar dados do perfil. Tente novamente.');
      return { ok: false };
    }
  };

  // Flag de controle para prevenir duplicação de pedidos
  let isProcessingCheckout = false;

  const createOrder = async (idPerfil, cart, total) => {
    try {
      // Mapear itens para o formato esperado pelo controller
      // item.id deve ser o ID do Produto no banco
      const itensPayload = cart.map(item => ({
        id_produto: item.id,
        quantidade: item.quantidade,
        preco_unitario: item.preco,
        tamanho: item.size,
        cor: item.color
      }));

      const payload = {
        id_perfil: idPerfil,
        data_pedido: new Date().toISOString().slice(0, 19).replace('T', ' '),
        total_pedido: total,
        status_pedido: 'pendente', // Status inicial
        itens: itensPayload
      };

      const response = await fetch('/api/pedidos.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      const result = await response.json();
      if (response.ok) {
        return result.id_pedido; // Pode vir em result.id_pedido ou result.data dependendo da API
      } else {
        console.error('Erro ao criar pedido:', result);
        return null;
      }
    } catch (error) {
      console.error('Erro na requisição do pedido:', error);
      return null;
    }
  };

  const handleCheckout = async () => {
    // PROTEÇÃO CONTRA DUPLICAÇÃO: Verificar se já está processando
    if (isProcessingCheckout) {
      console.warn('Checkout já está sendo processado. Aguarde...');
      showToast('Processando pedido, aguarde...');
      return;
    }

    const cart = getCart();
    if (cart.length === 0) {
      showToast('Seu carrinho está vazio!');
      return;
    }

    // Marcar como processando
    isProcessingCheckout = true;

    // Desabilitar botão e mostrar feedback visual
    const checkoutBtn = document.getElementById('btn-finalizar-pedido');
    const originalBtnContent = checkoutBtn ? checkoutBtn.innerHTML : '';

    if (checkoutBtn) {
      checkoutBtn.disabled = true;
      checkoutBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> PROCESSANDO...';
      checkoutBtn.style.opacity = '0.7';
      checkoutBtn.style.cursor = 'not-allowed';
    }


    try {
      // Verifica autenticação com implementação LOCAL
      const checkAuthLocal = async () => {
        try {
          console.log(`[${new Date().toLocaleTimeString()}] Iniciando checkAuthLocal...`);
          const response = await fetch('/api/check_auth.php', { credentials: 'same-origin' });
          if (!response.ok) {
            console.error('CheckAuth falhou HTTP:', response.status);
            return { authenticated: false };
          }
          const data = await response.json();
          console.log('CheckAuth Resposta:', data);
          return data;
        } catch (err) {
          console.error('Erro no checkAuthLocal:', err);
          return { authenticated: false };
        }
      };

      let authStatus = await checkAuthLocal();

      if (!authStatus.authenticated) {
        console.warn('Usuário não autenticado pelo checkAuthLocal.');
        showToast('Você precisa estar logado para finalizar a compra.');

        // Resetar estado antes de redirecionar
        isProcessingCheckout = false;
        if (checkoutBtn) {
          checkoutBtn.disabled = false;
          checkoutBtn.innerHTML = originalBtnContent;
          checkoutBtn.style.opacity = '1';
          checkoutBtn.style.cursor = 'pointer';
        }

        setTimeout(() => {
          const isPages = window.location.pathname.includes('/pages/');
          window.location.href = isPages ? '../backend/login' : '/backend/login';
        }, 1500);
        return;
      }

      // Verifica Perfil
      const profileCheck = await checkProfile(authStatus.user.id);
      if (!profileCheck.ok) {
        // Resetar estado se perfil incompleto
        isProcessingCheckout = false;
        if (checkoutBtn) {
          checkoutBtn.disabled = false;
          checkoutBtn.innerHTML = originalBtnContent;
          checkoutBtn.style.opacity = '1';
          checkoutBtn.style.cursor = 'pointer';
        }
        return;
      }

      const idPerfil = profileCheck.id_perfil;
      if (!idPerfil) {
        showToast('Erro técnico: ID do Perfil não identificado.');
        console.error('ID Perfil missing from checkProfile response', profileCheck);

        // Resetar estado
        isProcessingCheckout = false;
        if (checkoutBtn) {
          checkoutBtn.disabled = false;
          checkoutBtn.innerHTML = originalBtnContent;
          checkoutBtn.style.opacity = '1';
          checkoutBtn.style.cursor = 'pointer';
        }
        return;
      }

      // Calcular Total
      const total = cart.reduce((acc, item) => acc + (item.preco * item.quantidade), 0);

      // Salvar Pedido no Banco
      showToast('Salvando pedido...');
      const pedidoId = await createOrder(idPerfil, cart, total);

      if (!pedidoId) {
        // Erro detalhado já logado no console
        showToast('Erro ao salvar pedido no sistema. Verifique o console ou contate suporte.');

        // Resetar estado para permitir nova tentativa
        isProcessingCheckout = false;
        if (checkoutBtn) {
          checkoutBtn.disabled = false;
          checkoutBtn.innerHTML = originalBtnContent;
          checkoutBtn.style.opacity = '1';
          checkoutBtn.style.cursor = 'pointer';
        }
        return;
      }

      // Sucesso: Limpar Carrinho e Redirecionar
      localStorage.removeItem(CART_STORAGE_KEY);
      updateCartBadge(); // Zera badge visualmente

      showToast('Pedido realizado com sucesso! Redirecionando...');

      // Redireciona para lista de pedidos do cliente (SEM WHATSAPP)
      setTimeout(() => {
        window.location.href = '/backend/cliente/pedidos';
      }, 2000); // 2s delay para ler o toast

    } catch (error) {
      console.error('Erro inesperado no checkout:', error);
      showToast('Erro ao processar pedido. Tente novamente.');

      // Resetar estado em caso de erro
      isProcessingCheckout = false;
      if (checkoutBtn) {
        checkoutBtn.disabled = false;
        checkoutBtn.innerHTML = originalBtnContent;
        checkoutBtn.style.opacity = '1';
        checkoutBtn.style.cursor = 'pointer';
      }
    }
  };

  document.addEventListener('DOMContentLoaded', () => {
    injectMiniCartHTML();
    initAddToCartButtons();
    updateCartBadge();

    // Listener para botão checkout
    document.body.addEventListener('click', (e) => {
      const btn = e.target.closest('#btn-finalizar-pedido');
      if (btn) {
        handleCheckout();
      }
    });

    window.addEventListener('cartUpdated', updateCartBadge);
  });

  const instance = { getCart, addToCart, removeFromCart, updateQuantity, getItemCount, handleCheckout };
  window.CartManager = instance;
  return instance;
})();
