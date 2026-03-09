// ================================
// VARIÁVEIS GLOBAIS
// ================================
let produtos = [];
let clientes = [];
let carrinho = [];
let pedidosRealizados = [];
let categorias = [];
let desconto = 0;
let carregandoClientes = false;
let carregandoProdutos = false;
let carregandoHistorico = false;
let ultimoCarregamentoProdutos = 0;
let ultimoCarregamentoHistorico = 0;
const INTERVALO_MINIMO_RECARREGAMENTO_MS = 2000;
let renderToken = 0;
let renderIndex = 0;
let renderListaAtual = [];
const RENDER_CHUNK_SIZE = 24;
let renderContainer = null;

const PLACEHOLDER_SVG = '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300">' +
  '<rect width="100%" height="100%" fill="#181818"/>' +
  '<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#b3b3b3" font-size="16">Sem imagem</text>' +
  '</svg>';
const PLACEHOLDER_IMAGE = `data:image/svg+xml;utf8,${encodeURIComponent(PLACEHOLDER_SVG)}`;

/**
 * Processa o caminho da imagem para garantir que funcione no Electron
 * @param {string} imagePath - Caminho original da imagem
 * @returns {string} - Caminho processado ou placeholder
 */
function processarCaminhoImagem(imagePath) {
  if (!imagePath || typeof imagePath !== 'string') return PLACEHOLDER_IMAGE;
  if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) return imagePath;
  if (imagePath.startsWith('data:image')) return imagePath;

  if (imagePath.startsWith('file://')) {
    let safePath = imagePath.replace('file:///', '').replace('file://', '');
    return `app://local/${safePath}`;
  }

  if (imagePath.includes('produtos/') || imagePath.includes('upload/')) {
    const base = 'http://localhost:8000/backend/upload/';
    const cleanPath = imagePath.replace(/^upload\//, '');
    return base + cleanPath;
  }

  if (imagePath.includes(':\\') || imagePath.startsWith('/')) {
    const normalizedPath = imagePath.replace(/\\/g, '/');
    return `app://local/${normalizedPath.replace(/^\//, '')}`;
  }

  console.warn(`⚠️ Formato de imagem não reconhecido: ${imagePath}`);
  return PLACEHOLDER_IMAGE;
}

function withTimeout(promise, ms, label) {
  return Promise.race([
    promise,
    new Promise((_, reject) =>
      setTimeout(() => reject(new Error(`${label} demorou mais de ${ms / 1000}s`)), ms)
    )
  ]);
}

function getSessionId() {
  return typeof AuthHelper !== 'undefined' ? AuthHelper.getSessionId() : localStorage.getItem('sessionId');
}

// Carregar carrinho do localStorage
const carrinhoSalvo = localStorage.getItem('carrinho_temp');
if (carrinhoSalvo) {
  try {
    carrinho = JSON.parse(carrinhoSalvo);
    console.log('✓ Carrinho restaurado:', carrinho.length, 'itens');
  } catch (e) {
    console.error('Erro ao restaurar carrinho:', e);
  }
}

// ================================
// FUNÇÕES AUXILIARES
// ================================

// Notificações Toast
function mostrarNotificacao(mensagem, tipo = 'info') {
  const cores = {
    sucesso: '#51cf66',
    erro: '#ff6b6b',
    aviso: '#ffa94d',
    info: '#4dabf7'
  };

  const toast = document.createElement('div');
  toast.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background: ${cores[tipo] || cores.info};
    color: #000;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    z-index: 10000;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    animation: slideIn 0.3s ease;
  `;
  toast.textContent = mensagem;
  document.body.appendChild(toast);

  setTimeout(() => {
    toast.style.animation = 'slideOut 0.3s ease';
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

// Salvar carrinho no localStorage
function salvarCarrinho() {
  try {
    localStorage.setItem('carrinho_temp', JSON.stringify(carrinho));
  } catch (e) {
    console.error('Erro ao salvar carrinho:', e);
  }
}

// Atualizar select de categorias
function atualizarSelectCategorias() {
  const select = document.getElementById('filterCategoria');
  if (select && categorias.length > 0) {
    select.innerHTML = '<option value="">Todas categorias</option>' +
      categorias.map(cat => `<option value="${cat}">${cat}</option>`).join('');
  }
}

// ================================
// CARREGAR CLIENTES
// ================================
async function carregarClientes() {
  try {
    if (carregandoClientes) return;
    carregandoClientes = true;
    const sessionId = getSessionId();
    clientes = await withTimeout(window.api.listarClientes(sessionId), 8000, 'Carregar clientes');
    const select = document.getElementById('clientePedido');

    select.innerHTML = '<option value="">Selecione um cliente...</option>' +
      clientes.map(c => `<option value="${c.id_cliente}">${c.nome_clientes}</option>`).join('');
  } catch (erro) {
    console.error('Erro ao carregar clientes:', erro);
    const select = document.getElementById('clientePedido');
    if (select) {
      select.innerHTML = '<option value="">Erro ao carregar clientes</option>';
    }
  } finally {
    carregandoClientes = false;
  }
}

// ================================
// CARREGAR PRODUTOS
// ================================
async function carregarProdutos(filtro = '', categoria = '', estoque = 'todos', forcarReload = false) {
  try {
    const agora = Date.now();
    if (carregandoProdutos || (!forcarReload && (agora - ultimoCarregamentoProdutos) < INTERVALO_MINIMO_RECARREGAMENTO_MS)) {
      return;
    }

    carregandoProdutos = true;

    // Usar cache se disponível
    if (produtos.length === 0 || forcarReload) {
      const sessionId = getSessionId();
      let allProducts = await withTimeout(window.api.listarProdutos(sessionId), 8000, 'Carregar produtos');
      produtos = allProducts.filter(p => p.ativo !== false);
      console.log('✓ Produtos carregados:', produtos.length);

      // Extrair categorias únicas
      categorias = [...new Set(produtos.map(p => p.categoria).filter(Boolean))];
      atualizarSelectCategorias();
    }

    // Aplicar filtros
    let produtosFiltrados = produtos;

    // Filtro de busca
    if (filtro) {
      const termoLower = filtro.toLowerCase();
      produtosFiltrados = produtosFiltrados.filter(p =>
        p.nome.toLowerCase().includes(termoLower) ||
        (p.categoria && p.categoria.toLowerCase().includes(termoLower))
      );
    }

    // Filtro de categoria
    if (categoria) {
      produtosFiltrados = produtosFiltrados.filter(p => p.categoria === categoria);
    }

    // Filtro de estoque
    if (estoque === 'disponivel') {
      produtosFiltrados = produtosFiltrados.filter(p => p.estoque > 0);
    } else if (estoque === 'baixo') {
      produtosFiltrados = produtosFiltrados.filter(p => p.estoque > 0 && p.estoque <= 10);
    }

    renderizarProdutos(produtosFiltrados);
  } catch (erro) {
    console.error('Erro ao carregar produtos:', erro);
    mostrarNotificacao('Erro ao carregar produtos', 'erro');
    const container = document.getElementById('listaProdutos');
    if (container) {
      container.innerHTML = `
        <div class="col-12 text-center py-4">
          <p class="text-warning mb-2">Falha ao carregar produtos.</p>
          <button class="btn btn-sm btn-outline-warning" onclick="carregarProdutos()">Tentar novamente</button>
        </div>
      `;
    }
  } finally {
    carregandoProdutos = false;
    ultimoCarregamentoProdutos = Date.now();
  }
}

// ================================
// RENDERIZAR PRODUTOS
// ================================
function renderizarProdutos(lista) {
  const container = document.getElementById('listaProdutos');
  renderContainer = container;

  if (lista.length === 0) {
    container.innerHTML = '<p class="text-muted col-12 text-center py-5">Nenhum produto encontrado</p>';
    return;
  }

  const token = ++renderToken;
  renderIndex = 0;
  renderListaAtual = lista;
  container.innerHTML = '';

  if (!container._scrollHandlerBound) {
    container.addEventListener('scroll', () => {
      const nearBottom = container.scrollTop + container.clientHeight >= container.scrollHeight - 200;
      if (nearBottom) {
        renderizarProximoChunk(token);
      }
    });
    container._scrollHandlerBound = true;
  }

  renderizarProximoChunk(token);
}

function renderizarProximoChunk(token) {
  if (token !== renderToken) return;
  if (!renderContainer) return;
  const slice = renderListaAtual.slice(renderIndex, renderIndex + RENDER_CHUNK_SIZE);
  if (slice.length === 0) return;

  const html = slice.map(p => {
    const emEstoque = p.estoque > 0;
    const corEstoque = p.estoque > 10 ? '#51cf66' : p.estoque > 0 ? '#ffa94d' : '#ff6b6b';
    const simboloEstoque = p.estoque > 10 ? '✓' : p.estoque > 0 ? '!' : '×';
    const imagemSrc = processarCaminhoImagem(p.imagem);

    return `
    <div class="produto-card" style="opacity: ${emEstoque ? '1' : '0.5'}; position: relative;">
      ${p.estoque <= 10 && p.estoque > 0 ? '<div style="position: absolute; top: 8px; right: 8px; background: #ffa94d; color: #000; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700;">BAIXO</div>' : ''}
       <img src="${imagemSrc}" 
           class="produto-imagem" 
           loading="lazy"
           decoding="async"
           onerror="this.src='${PLACEHOLDER_IMAGE}'"
           alt="${p.nome}">
      <div class="nome">${p.nome}</div>
      <div class="categoria" style="font-size: 11px; color: #888; margin-bottom: 4px;">${p.categoria || 'Sem categoria'}</div>
      <div class="preco">R$ ${parseFloat(p.preco).toFixed(2)}</div>
      <div class="estoque" style="color: ${corEstoque}; font-weight: 600;">
        ${simboloEstoque} ${emEstoque ? `${p.estoque} disponível` : 'Esgotado'}
      </div>
      ${emEstoque ? `
        <input type="number" id="qtd-${p.id}" min="1" max="${p.estoque}" value="1" placeholder="Qtd">
        <button class="btn-add" onclick="adicionarCarrinho(${p.id}, '${p.nome.replace(/'/g, "\\'")}', ${p.preco}, ${p.estoque})">
          Adicionar
        </button>
      ` : `
        <button class="btn-add" disabled style="background: #666; cursor: not-allowed;">
          Esgotado
        </button>
      `}
    </div>
  `;
  }).join('');


  renderContainer.insertAdjacentHTML('beforeend', html);
  renderIndex += RENDER_CHUNK_SIZE;
}

// ================================
// ADICIONAR AO CARRINHO
// ================================
function adicionarCarrinho(id, nome, preco, estoqueDisponivel) {
  try {
    const input = document.getElementById(`qtd-${id}`);
    const quantidade = parseInt(input.value);

    if (!quantidade || quantidade < 1) {
      mostrarNotificacao('Digite uma quantidade válida', 'aviso');
      return;
    }

    if (quantidade > estoqueDisponivel) {
      mostrarNotificacao(`Estoque disponível: ${estoqueDisponivel}`, 'aviso');
      return;
    }

    // Verificar se já está no carrinho
    const existente = carrinho.find(item => item.id === id);

    if (existente) {
      const novaQtd = existente.quantidade + quantidade;
      if (novaQtd > estoqueDisponivel) {
        mostrarNotificacao(`Máximo disponível: ${estoqueDisponivel}`, 'aviso');
        return;
      }
      existente.quantidade = novaQtd;
      mostrarNotificacao(`${nome} atualizado (${novaQtd}x)`, 'sucesso');
    } else {
      carrinho.push({ id, nome, preco, quantidade });
      mostrarNotificacao(`${nome} adicionado (${quantidade}x)`, 'sucesso');
    }

    input.value = '1'; // Resetar input
    atualizarCarrinho();
    salvarCarrinho();

    // Feedback visual no botão
    const btn = input.nextElementSibling;
    const textoOriginal = btn.textContent;
    btn.textContent = '✓ Adicionado';
    btn.style.background = '#51cf66';
    setTimeout(() => {
      btn.textContent = textoOriginal;
      btn.style.background = '';
    }, 1000);
  } catch (erro) {
    console.error('Erro ao adicionar:', erro);
    mostrarNotificacao('Erro ao adicionar produto', 'erro');
  }
}

// ================================
// ATUALIZAR EXIBIÇÃO CARRINHO
// ================================
function atualizarCarrinho() {
  const container = document.getElementById('carrinhoItems');
  const totalItens = document.getElementById('totalItens');
  const subtotal = document.getElementById('subtotal');
  const totalPedido = document.getElementById('totalPedido');
  const descontoValor = document.getElementById('descontoValor');

  if (carrinho.length === 0) {
    container.innerHTML = '<p class="text-muted text-center py-5">Nenhum item adicionado</p>';
    totalItens.textContent = '0';
    subtotal.textContent = 'R$ 0.00';
    totalPedido.textContent = 'R$ 0.00';
    if (descontoValor) descontoValor.textContent = '- R$ 0.00';
    return;
  }

  // Renderizar itens
  container.innerHTML = carrinho.map(item => `
    <div class="carrinho-item">
      <div class="carrinho-item-info">
        <div class="carrinho-item-nome">${item.nome}</div>
        <div class="carrinho-item-preco">R$ ${parseFloat(item.preco).toFixed(2)} × ${item.quantidade}</div>
      </div>
      <input type="number" class="carrinho-item-qtd" value="${item.quantidade}" min="1"
        onchange="atualizarQuantidade(${item.id}, this.value)">
      <button class="carrinho-item-remover" onclick="removerCarrinho(${item.id})" title="Remover item">×</button>
    </div>
  `).join('');

  // Calcular totais
  const subtotalValor = carrinho.reduce((sum, item) => sum + (item.preco * item.quantidade), 0);
  const qtdTotal = carrinho.reduce((sum, item) => sum + item.quantidade, 0);

  // Aplicar desconto
  const descontoInput = document.getElementById('desconto');
  desconto = descontoInput ? parseFloat(descontoInput.value) || 0 : 0;
  const descontoEmReais = subtotalValor * (desconto / 100);
  const totalComDesconto = subtotalValor - descontoEmReais;

  totalItens.textContent = qtdTotal;
  subtotal.textContent = 'R$ ' + subtotalValor.toFixed(2);
  if (descontoValor) descontoValor.textContent = '- R$ ' + descontoEmReais.toFixed(2);
  totalPedido.textContent = 'R$ ' + totalComDesconto.toFixed(2);
}

// ================================
// ATUALIZAR QUANTIDADE
// ================================
function atualizarQuantidade(id, novaQtd) {
  novaQtd = parseInt(novaQtd);

  if (novaQtd < 1) {
    removerCarrinho(id);
    return;
  }

  const item = carrinho.find(i => i.id === id);
  if (item) {
    const produtoOriginal = produtos.find(p => p.id === id);
    if (novaQtd > produtoOriginal.estoque) {
      mostrarNotificacao(`Máximo disponível: ${produtoOriginal.estoque}`, 'aviso');
      atualizarCarrinho();
      return;
    }
    item.quantidade = novaQtd;
    atualizarCarrinho();
    salvarCarrinho();
  }
}

// ================================
// REMOVER DO CARRINHO
// ================================
function removerCarrinho(id) {
  const item = carrinho.find(i => i.id === id);
  carrinho = carrinho.filter(item => item.id !== id);
  atualizarCarrinho();
  salvarCarrinho();
  if (item) {
    mostrarNotificacao(`${item.nome} removido`, 'info');
  }
}

// ================================
// FINALIZAR PEDIDO
// ================================
document.getElementById('btnFinalizar').addEventListener('click', async () => {
  try {
    if (carrinho.length === 0) {
      mostrarNotificacao('Adicione produtos ao pedido', 'aviso');
      return;
    }

    // Cliente é opcional - pode ser null para clientes não cadastrados
    const clienteIdValue = document.getElementById('clientePedido').value;
    const clienteId = clienteIdValue ? parseInt(clienteIdValue) : null;

    // Calcular total com desconto
    const subtotalValor = carrinho.reduce((sum, item) => sum + (item.preco * item.quantidade), 0);
    const descontoEmReais = subtotalValor * (desconto / 100);
    const total = subtotalValor - descontoEmReais;

    // Validar estoque antes de finalizar
    for (const item of carrinho) {
      const produto = produtos.find(p => p.id === item.id);
      if (!produto || produto.estoque < item.quantidade) {
        mostrarNotificacao(`Estoque insuficiente: ${item.nome}`, 'erro');
        carregarProdutos('', '', 'todos', true); // Recarregar estoque atualizado
        return;
      }
    }

    // Preparar itens para o pedido
    const itens = carrinho.map(item => ({
      produto_id: item.id,
      quantidade: item.quantidade,
      preco_unitario: item.preco
    }));

    // Desabilitar botão durante processamento
    const btnFinalizar = document.getElementById('btnFinalizar');
    const textoOriginal = btnFinalizar.textContent;
    btnFinalizar.disabled = true;
    btnFinalizar.textContent = 'Processando...';

    console.log('Finalizando pedido...', { clienteId, itens, total, desconto });

    const sessionId = getSessionId();
    const resultado = await window.api.criarPedido(sessionId, {
      cliente_id: clienteId,
      itens: itens,
      total: total,
      desconto: desconto
    });

    // Adicionar ao histórico local
    const cliente = clienteId ? clientes.find(c => c.id_cliente === clienteId) : null;
    pedidosRealizados.unshift({
      id: resultado.id,
      criadoEm: new Date().toISOString(),
      cliente_id: clienteId,
      cliente: cliente ? cliente.nome_clientes : 'Não cadastrado',
      itens: carrinho.length,
      total: total
    });

    // Limpar carrinho e formulário
    carrinho = [];
    desconto = 0;
    document.getElementById('clientePedido').value = '';
    document.getElementById('desconto').value = '0';
    localStorage.removeItem('carrinho_temp');
    atualizarCarrinho();
    carregarHistorico(true);
    carregarProdutos('', '', 'todos', true); // Recarregar para atualizar estoque

    // Reabilitar botão
    btnFinalizar.disabled = false;
    btnFinalizar.textContent = textoOriginal;

    mostrarNotificacao(`Pedido #${resultado.id} realizado! Total: R$ ${total.toFixed(2)}`, 'sucesso');
  } catch (erro) {
    console.error('Erro ao finalizar pedido:', erro);
    mostrarNotificacao('Erro ao finalizar pedido: ' + erro.message, 'erro');
    document.getElementById('btnFinalizar').disabled = false;
    document.getElementById('btnFinalizar').textContent = 'Finalizar Pedido';
  }
});

// ================================
// BOTÕES AUXILIARES
// ================================
document.getElementById('btnLimparCarrinho').addEventListener('click', () => {
  if (carrinho.length === 0) {
    mostrarNotificacao('Carrinho já está vazio', 'info');
    return;
  }
  if (confirm('Deseja limpar o carrinho? (' + carrinho.length + ' itens)')) {
    carrinho = [];
    localStorage.removeItem('carrinho_temp');
    atualizarCarrinho();
    mostrarNotificacao('Carrinho limpo', 'info');
  }
});

document.getElementById('btnCancelar').addEventListener('click', () => {
  if (carrinho.length === 0) {
    mostrarNotificacao('Carrinho já está vazio', 'info');
    return;
  }
  if (confirm('Deseja cancelar este pedido? (' + carrinho.length + ' itens)')) {
    carrinho = [];
    desconto = 0;
    document.getElementById('desconto').value = '0';
    document.getElementById('clientePedido').value = '';
    localStorage.removeItem('carrinho_temp');
    atualizarCarrinho();
    mostrarNotificacao('Pedido cancelado', 'info');
  }
});

// ================================
// BUSCA E FILTROS DE PRODUTOS
// ================================
document.getElementById('searchProduto').addEventListener('input', (e) => {
  const categoria = document.getElementById('filterCategoria')?.value || '';
  const estoque = document.getElementById('filterEstoque')?.value || 'todos';
  carregarProdutos(e.target.value, categoria, estoque);
});

// Listener para filtro de categoria
const filterCategoria = document.getElementById('filterCategoria');
if (filterCategoria) {
  filterCategoria.addEventListener('change', (e) => {
    const busca = document.getElementById('searchProduto').value;
    const estoque = document.getElementById('filterEstoque')?.value || 'todos';
    carregarProdutos(busca, e.target.value, estoque);
  });
}

// Listener para filtro de estoque
const filterEstoque = document.getElementById('filterEstoque');
if (filterEstoque) {
  filterEstoque.addEventListener('change', (e) => {
    const busca = document.getElementById('searchProduto').value;
    const categoria = document.getElementById('filterCategoria')?.value || '';
    carregarProdutos(busca, categoria, e.target.value);
  });
}

// Listener para campo de desconto
const descontoInput = document.getElementById('desconto');
if (descontoInput) {
  descontoInput.addEventListener('input', (e) => {
    let valor = parseFloat(e.target.value) || 0;
    if (valor < 0) valor = 0;
    if (valor > 100) valor = 100;
    e.target.value = valor;
    desconto = valor;
    atualizarCarrinho();
  });
}

// ================================
// ATALHOS DE TECLADO
// ================================
document.addEventListener('keydown', (e) => {
  // F2: Focar no campo de busca
  if (e.key === 'F2') {
    e.preventDefault();
    document.getElementById('searchProduto').focus();
  }

  // F3: Focar no select de cliente
  if (e.key === 'F3') {
    e.preventDefault();
    document.getElementById('clientePedido').focus();
  }

  // F9: Finalizar pedido
  if (e.key === 'F9') {
    e.preventDefault();
    document.getElementById('btnFinalizar').click();
  }

  // Esc: Limpar carrinho
  if (e.key === 'Escape' && carrinho.length > 0) {
    e.preventDefault();
    document.getElementById('btnCancelar').click();
  }
});

// ================================
// HISTÓRICO DE PEDIDOS
// ================================
async function carregarHistorico(forcarReload = false) {
  const container = document.getElementById('historicoPedidos');

  try {
    const agora = Date.now();
    if (carregandoHistorico || (!forcarReload && (agora - ultimoCarregamentoHistorico) < INTERVALO_MINIMO_RECARREGAMENTO_MS)) {
      return;
    }

    carregandoHistorico = true;
    container.innerHTML = '<p class="text-muted text-center py-3"><span class="spinner-border spinner-border-sm me-2"></span>Carregando...</p>';

    const sessionId = getSessionId();
    const pedidosDB = await withTimeout(window.api.listarPedidos(sessionId), 15000, 'Carregar histórico');
    const clientesDB = clientes.length > 0 ? clientes : await withTimeout(window.api.listarClientes(sessionId), 8000, 'Carregar clientes');

    console.log('✓ Pedidos carregados:', pedidosDB.length);

    // Ordenar por data decrescente e pegar últimos 10
    const ultimosPedidos = pedidosDB
      .sort((a, b) => new Date(b.data) - new Date(a.data))
      .slice(0, 10);

    if (ultimosPedidos.length === 0) {
      container.innerHTML = '<p class="text-muted text-center py-4">Nenhum pedido realizado ainda</p>';
      return;
    }

    container.innerHTML = ultimosPedidos.map(pedido => {
      const cliente = clientesDB.find(c => c.id === pedido.cliente_id);
      const nomeCliente = pedido.cliente_id ? (cliente ? cliente.nome_clientes : 'Cliente não encontrado') : 'Não cadastrado';
      const data = new Date(pedido.criadoEm).toLocaleString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });

      return `
        <div class="pedido-item">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <strong style="color: #fff; font-size: 14px;">Pedido #${pedido.id}</strong>
              <div class="pedido-item-data">${data}</div>
            </div>
            <span class="badge bg-success" style="font-size: 11px;">✓ Finalizado</span>
          </div>
          <div style="color: #bbb; font-size: 13px; margin-bottom: 6px;">
            ${nomeCliente}
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <span style="color: #999; font-size: 12px;">
              ${pedido.itens?.length || 0} ${pedido.itens?.length === 1 ? 'item' : 'itens'}
            </span>
            <span class="pedido-item-valor" style="font-size: 16px;">
              R$ ${parseFloat(pedido.total).toFixed(2)}
            </span>
          </div>
        </div>
      `;
    }).join('');

  } catch (erro) {
    console.error('Erro ao carregar histórico:', erro);
    container.innerHTML = '<p class="text-warning text-center py-3" style="font-size: 13px;">⚠ Erro ao carregar histórico<br><small style="font-size: 11px; color: #888;">' + erro.message + '</small></p>';
  } finally {
    carregandoHistorico = false;
    ultimoCarregamentoHistorico = Date.now();
  }
}

// ================================
// INICIALIZAÇÃO
// ================================
document.addEventListener('DOMContentLoaded', () => {
  console.log('📦 Página de pedidos carregando...');
  carregarClientes();
  carregarProdutos();
  carregarHistorico();

  // Restaurar carrinho se houver
  if (carrinho.length > 0) {
    atualizarCarrinho();
    mostrarNotificacao('Carrinho restaurado (' + carrinho.length + ' itens)', 'info');
  }

  console.log('✓ Inicialização completa');
  console.log('Atalhos: F2=Busca | F3=Cliente | F9=Finalizar | Esc=Cancelar');

  // Sincronização visual automática silenciosa a cada 15 segundos
  setInterval(() => {
    if (!document.hidden) {
      // Atualiza produtos (estoque) silenciosamente
      carregarProdutos('', document.getElementById('filterCategoria')?.value || '', document.getElementById('filterEstoque')?.value || 'todos');
      // Atualiza histórico de pedidos silenciosamente
      carregarHistorico();
    }
  }, 15000);
});

// ================================
// EXPOR FUNÇÕES NO ESCOPO GLOBAL
// Scripts type="module" têm escopo isolado — funções chamadas por onclick
// inline no HTML precisam ser expostas no window.
// ================================
window.adicionarCarrinho = adicionarCarrinho;
window.removerCarrinho = removerCarrinho;
window.atualizarQuantidade = atualizarQuantidade;
window.carregarProdutos = carregarProdutos;
window.carregarHistorico = carregarHistorico;
