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
    let toastContainer = document.querySelector('#tw-toast-container');
    if (!toastContainer) {
      toastContainer = document.createElement('div');
      toastContainer.id = 'tw-toast-container';
      toastContainer.className = 'fixed bottom-4 right-4 z-[9999] flex flex-col gap-2 pointer-events-none';
      document.body.appendChild(toastContainer);
    }

    const toast = document.createElement('div');
    toast.className = 'pointer-events-auto flex items-center gap-3 bg-black text-white px-4 py-3 rounded-lg border border-(--brand-yellow) shadow-lg shadow-black/50 transform transition-all duration-300 translate-y-4 opacity-0';
    toast.innerHTML = `
        <i class="bi bi-check-circle-fill text-(--brand-yellow)"></i>
        <span class="text-sm font-medium">${message}</span>
        <button class="ml-auto text-gray-400 hover:text-white transition" onclick="this.parentElement.remove()">
            <i class="bi bi-x-lg"></i>
        </button>
    `;

    toastContainer.appendChild(toast);
    
    // Animate in
    requestAnimationFrame(() => {
      toast.classList.remove('translate-y-4', 'opacity-0');
    });

    // Auto remove
    setTimeout(() => {
      toast.classList.add('translate-y-4', 'opacity-0');
      toast.addEventListener('transitionend', () => toast.remove());
    }, 3000);
  };

  /**
   * Sistema de Mini-Carrinho (Drawer)
   */
  const injectMiniCartHTML = () => {
    if (document.getElementById('miniCartDrawer')) return;

    const html = `
      <div id="miniCartBackdrop" class="fixed inset-0 bg-black/50 z-[1040] hidden backdrop-blur-sm transition-opacity opacity-0" onclick="CartManager.closeDrawer()"></div>
      <div id="miniCartDrawer" class="fixed top-0 right-0 h-full w-[350px] max-w-full bg-[#0a0a0a] border-l border-white/10 z-[1050] transform translate-x-full transition-transform duration-300 flex flex-col shadow-2xl">
        <div class="p-4 border-b border-white/10 flex justify-between items-center bg-black">
          <h5 class="text-white font-bold tracking-widest uppercase m-0 text-sm">MEU CARRINHO</h5>
          <button type="button" class="text-white/70 hover:text-white transition p-2" onclick="CartManager.closeDrawer()" aria-label="Close">
             <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>
        <div class="flex-1 overflow-y-auto p-4 custom-scrollbar" id="miniCartItems">
          <!-- Itens injetados via JS -->
        </div>
        <div class="p-4 border-t border-white/10 bg-[#111]">
          <div class="flex justify-between items-center mb-4 text-white">
            <span class="uppercase text-xs tracking-wider text-gray-400">Subtotal</span>
            <span id="miniCartSubtotal" class="font-bold text-(--brand-yellow) text-lg">R$ 0,00</span>
          </div>
          <a href="/pages/carrinho.html" class="block w-full py-3 px-4 border border-white/20 text-white text-center rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-white/10 transition mb-3">VER CARRINHO</a>
          <button id="btn-finalizar-pedido" class="w-full py-3 px-4 bg-(--brand-yellow) text-black rounded-lg text-xs font-bold uppercase tracking-widest hover:brightness-110 transition flex items-center justify-center gap-2 shadow-lg shadow-yellow-500/20">
            <i class="bi bi-check-circle-fill"></i> FINALIZAR PEDIDO
          </button>
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
      itemsContainer.innerHTML = '<div class="text-center py-10 opacity-50 flex flex-col items-center justify-center h-full"><i class="bi bi-bag-x text-4xl mb-3 text-white"></i><span class="text-white text-sm uppercase tracking-wider">Carrinho vazio</span></div>';
      if (subtotalEl) subtotalEl.textContent = 'R$ 0,00';
      return;
    }

    let subtotal = 0;
    itemsContainer.innerHTML = cart.map(item => {
      const itemSubtotal = item.preco * item.quantidade;
      subtotal += itemSubtotal;
      return `
        <div class="flex gap-4 mb-6 relative group">
          <div class="w-[80px] h-[100px] shrink-0 rounded-md overflow-hidden border border-white/5 bg-[#f5f5f5] flex items-center justify-center">
            <img src="${item.img}" alt="${item.nome}" class="w-full h-auto object-cover max-h-[100px]">
          </div>
          <div class="flex-1 flex flex-col justify-center">
            <h6 class="text-white text-xs font-bold mb-1 uppercase tracking-wider pr-6 leading-tight">${item.nome}</h6>
            <p class="text-gray-400 text-[10px] mb-2 uppercase tracking-wide">Tam: ${item.size} ${item.color ? `| Cor: ${item.color}` : ''} | Qtd: ${item.quantidade}</p>
            <div class="flex justify-between items-center mt-auto">
              <span class="text-(--brand-yellow) text-sm font-bold">R$ ${item.preco.toFixed(2).replace('.', ',')}</span>
            </div>
            <button class="absolute top-0 right-0 text-gray-500 hover:text-red-500 transition p-1 opacity-50 group-hover:opacity-100" onclick="CartManager.removeFromCart('${item.cartItemId}')">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      `;
    }).join('');

    if (subtotalEl) subtotalEl.textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
  };

  const openDrawer = () => {
    const drawer = document.getElementById('miniCartDrawer');
    const backdrop = document.getElementById('miniCartBackdrop');
    if (drawer && backdrop) {
      backdrop.classList.remove('hidden');
      // small delay to allow display:block before opacity transition
      requestAnimationFrame(() => {
        backdrop.classList.remove('opacity-0');
        drawer.classList.remove('translate-x-full');
      });
      document.body.style.overflow = 'hidden';
    }
  };

  const closeDrawer = () => {
    const drawer = document.getElementById('miniCartDrawer');
    const backdrop = document.getElementById('miniCartBackdrop');
    if (drawer && backdrop) {
      drawer.classList.add('translate-x-full');
      backdrop.classList.add('opacity-0');
      setTimeout(() => {
        backdrop.classList.add('hidden');
        document.body.style.overflow = '';
      }, 300);
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
      const modalId = 'profileIncompleteModal';
      let modalEl = document.getElementById(modalId);
      if (modalEl) modalEl.remove();

      const modalHtml = `
          <div id="${modalId}" class="fixed inset-0 z-[2000] hidden items-center justify-center p-4 sm:p-0">
              <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" id="${modalId}-backdrop"></div>
              <div class="bg-[#111] border border-white/10 rounded-2xl shadow-2xl w-full max-w-md p-6 relative z-10 transform scale-95 opacity-0 transition-all duration-300 pointer-events-auto flex flex-col items-center text-center" id="${modalId}-content">
                  <div class="w-full flex justify-between items-start mb-6">
                      <h5 class="text-(--brand-yellow) font-bold uppercase tracking-wider text-xl flex items-center gap-2">
                          <i class="bi bi-exclamation-triangle"></i> Falta Pouco!
                      </h5>
                      <button type="button" class="text-gray-400 hover:text-white transition" id="btnCloseProfileModal">
                          <i class="bi bi-x-lg"></i>
                      </button>
                  </div>
                  <div class="text-center w-full">
                      <p class="text-gray-300 text-sm mb-4 leading-relaxed">Para garantir que seu pedido chegue certinho, precisamos que você preencha seu <strong class="text-white">Telefone</strong> e <strong class="text-white">Endereço</strong>.</p>
                      <p class="text-gray-500 text-xs mb-6">Você será redirecionado para completar seu perfil no painel de cliente.</p>
                      <button id="btnRedirectProfile" class="w-full bg-(--brand-yellow) text-black py-4 rounded-xl font-bold uppercase tracking-widest text-sm hover:brightness-110 transition shadow-lg shadow-yellow-500/20">PREENCHER AGORA</button>
                  </div>
              </div>
          </div>
      `;
      document.body.insertAdjacentHTML('beforeend', modalHtml);

      const modalWrap = document.getElementById(modalId);
      const content = document.getElementById(`${modalId}-content`);
      const btnClose = document.getElementById('btnCloseProfileModal');
      const btnRedirect = document.getElementById('btnRedirectProfile');

      modalWrap.classList.remove('hidden');
      modalWrap.classList.add('flex');
      
      requestAnimationFrame(() => {
          content.classList.remove('scale-95', 'opacity-0');
          content.classList.add('scale-100', 'opacity-100');
      });

      const hideModal = () => {
          content.classList.remove('scale-100', 'opacity-100');
          content.classList.add('scale-95', 'opacity-0');
          setTimeout(() => {
              if(modalWrap) modalWrap.remove();
          }, 300);
      };

      btnClose.addEventListener('click', hideModal);

      btnRedirect.addEventListener('click', () => {
        hideModal();
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
          window.location.href = '/pages/login.html';
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

  const init = () => {
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
  };

  document.addEventListener('DOMContentLoaded', init);

  const instance = { getCart, addToCart, removeFromCart, updateQuantity, getItemCount, handleCheckout, init, openDrawer, closeDrawer };
  window.CartManager = instance;
  return instance;
})();
