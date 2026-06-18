// ================================
// ESTADO GLOBAL
// ================================
let produtos = [];
let tamanhosCadastrados = new Set();
let categorias = new Set();
let produtoEditando = null;
let imagemAtual = null;
let tamanhosAtuais = []; // Array para armazenar tamanhos temporários
let produtosComTamanhos = {}; // Armazenar tamanhos por produto

let produtosRetryCount = 0;
const MAX_PRODUTOS_RETRY = 3;
let carregandoProdutos = false;
let ultimoCarregamentoProdutos = 0;
const INTERVALO_MINIMO_RECARREGAMENTO_MS = 2000;
let renderToken = 0;
const RENDER_CHUNK_SIZE = 24;

const PLACEHOLDER_SVG_PRODUTO = `<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"><rect width="200" height="200" fill="#1a1a1a"/><rect x="60" y="55" width="80" height="65" rx="4" fill="none" stroke="#444" stroke-width="2"/><circle cx="82" cy="78" r="8" fill="#444"/><polyline points="60,120 85,95 105,112 125,88 140,120" fill="none" stroke="#444" stroke-width="2"/><text x="100" y="155" text-anchor="middle" fill="#555" font-size="11" font-family="sans-serif">Sem imagem</text></svg>`;
const PLACEHOLDER_IMAGE = `data:image/svg+xml;utf8,${encodeURIComponent(PLACEHOLDER_SVG_PRODUTO)}`;

/**
 * Processa o caminho da imagem para garantir que funcione no Electron
 * @param {string} imagePath - Caminho original da imagem
 * @returns {string} - Caminho processado ou placeholder
 */
function processarCaminhoImagem(imagePath) {
  // Se não houver imagem, retornar placeholder
  if (!imagePath || typeof imagePath !== 'string') {
    return PLACEHOLDER_IMAGE;
  }

  // Se já for URL completa (http/https), retornar como está
  if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
    return imagePath;
  }

  // Se for data:image, retornar como está
  if (imagePath.startsWith('data:image')) {
    return imagePath;
  }

  // Se for file://, substituir pelo protocolo seguro app:// para o Electron permitir leitura
  if (imagePath.startsWith('file://')) {
    // Remove o possível triplo /// do windows
    let safePath = imagePath.replace('file:///', '').replace('file://', '');
    return `app://local/${safePath}`;
  }

  // Se for caminho relativo de API (ex: "produtos/698b1ca4e86730.png")
  // Construir URL completa apontando para o servidor PHP local
  if (imagePath.includes('produtos/') || imagePath.includes('upload/')) {
    const base = 'http://localhost:8000/backend/upload/';
    const cleanPath = imagePath.replace(/^upload\//, '');
    return base + cleanPath;
  }

  // Se for path absoluto local sem file:// (raro, mas possível)
  if (imagePath.includes(':\\') || imagePath.startsWith('/')) {
    const normalizedPath = imagePath.replace(/\\/g, '/');
    return `app://local/${normalizedPath.replace(/^\//, '')}`;
  }

  // Fallback: retornar placeholder
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

let modal, btnNovo, cancelarModal, cancelarModalBtn, salvarProduto;
let gridProdutos, inputImagem, preview, searchProduto, filterCategoria;
let filterTamanho, filterEstoque, limparFiltros, ordenarPor;
let modalTitulo, btnAdicionarTamanho, listaTamanhos;

// ================================
// INICIALIZAÇÃO
// ================================
document.addEventListener('DOMContentLoaded', () => {
  // Inicializar elementos do DOM
  modal = document.getElementById('modalProduto');
  btnNovo = document.getElementById('btnNovoProduto');
  cancelarModal = document.getElementById('cancelarModal');
  cancelarModalBtn = document.getElementById('cancelarModalBtn');
  salvarProduto = document.getElementById('salvarProduto');
  gridProdutos = document.getElementById('gridProdutos');
  inputImagem = document.getElementById('imagem');
  preview = document.getElementById('preview');
  searchProduto = document.getElementById('searchProduto');
  filterCategoria = document.getElementById('filterCategoria');
  filterTamanho = document.getElementById('filterTamanho');
  filterEstoque = document.getElementById('filterEstoque');
  limparFiltros = document.getElementById('limparFiltros');
  ordenarPor = document.getElementById('ordenarPor');
  modalTitulo = document.getElementById('modalTitulo');
  btnAdicionarTamanho = document.getElementById('btnAdicionarTamanho');
  listaTamanhos = document.getElementById('listaTamanhos');

  // Configurar event listeners
  configurarEventListeners();

  // Carregar produtos
  carregarProdutos();

  // Recarregar quando a aba ficar visível novamente
  document.addEventListener('visibilitychange', () => {
    if (!document.hidden) {
      carregarProdutos();
    }
  });

  // Sincronização visual automática a cada 15 segundos
  setInterval(() => {
    // Só atualiza se a aba estiver ativa e não houver modais abertos que impeçam a atualização silenciosa
    if (!document.hidden && (!modal || modal.style.display !== 'flex')) {
      carregarProdutos();
    }
  }, 15000);
});

function configurarEventListeners() {
  // Modal
  if (btnNovo) {
    btnNovo.onclick = () => {
      limparFormulario();
      abrirModalProduto();
    };
  }

  if (cancelarModal) {
    cancelarModal.onclick = () => {
      limparFormulario();
      fecharModalProduto();
    };
  }

  if (cancelarModalBtn) {
    cancelarModalBtn.onclick = () => {
      limparFormulario();
      fecharModalProduto();
    };
  }

  // Fechar ao clicar fora do modal
  window.onclick = (e) => {
    if (e.target === modal) {
      limparFormulario();
      fecharModalProduto();
    }
  };

  // Salvar produto
  if (salvarProduto) {
    salvarProduto.onclick = salvarProdutoHandler;
  }

  // Tamanhos
  if (btnAdicionarTamanho) {
    btnAdicionarTamanho.onclick = adicionarTamanhoHandler;
  }

  // Preview de imagem
  if (inputImagem) {
    inputImagem.onchange = previewImagemHandler;
  }

  // Remover imagem atual
  const btnRemoverImagem = document.getElementById('btnRemoverImagem');
  if (btnRemoverImagem) {
    btnRemoverImagem.onclick = (e) => {
      e.stopPropagation(); // não abre o file picker
      imagemAtual = null;
      inputImagem.value = '';
      atualizarPreviewImagem(null);
    };
  }

  // Formatação automática de preço
  const precoInput = document.getElementById('preco');
  if (precoInput) {
    precoInput.addEventListener('input', (e) => {
      let valor = e.target.value.replace(/\D/g, '');
      if (valor) {
        valor = (parseInt(valor) / 100).toFixed(2);
        e.target.value = valor.replace('.', ',');
      }
    });
  }

  // Busca e filtro
  if (searchProduto) {
    searchProduto.oninput = filtrarProdutos;
  }

  if (filterCategoria) {
    filterCategoria.onchange = filtrarProdutos;
  }

  if (filterTamanho) {
    filterTamanho.onchange = filtrarProdutos;
  }

  if (filterEstoque) {
    filterEstoque.onchange = filtrarProdutos;
  }

  if (ordenarPor) {
    ordenarPor.onchange = filtrarProdutos;
  }

  if (limparFiltros) {
    limparFiltros.onclick = () => {
      searchProduto.value = '';
      filterCategoria.value = '';
      filterTamanho.value = '';
      filterEstoque.value = '';
      ordenarPor.value = 'nome-az';
      filtrarProdutos();
    };
  }
}

function abrirModalProduto() {
  modal.style.display = 'flex';
  setTimeout(() => {
    const nomeInput = document.getElementById('nome');
    if (nomeInput) nomeInput.focus();
  }, 0);
}

function fecharModalProduto() {
  modal.style.display = 'none';
  setTimeout(() => {
    if (searchProduto) searchProduto.focus();
  }, 0);
}

// ================================
// CARREGAR PRODUTOS
// ================================
async function carregarProdutos() {
  try {
    const agora = Date.now();
    if (carregandoProdutos || (agora - ultimoCarregamentoProdutos) < INTERVALO_MINIMO_RECARREGAMENTO_MS) {
      return;
    }

    carregandoProdutos = true;
    const sessionId = getSessionId();
    produtos = await withTimeout(window.api.listarProdutos(sessionId), 8000, 'Carregar produtos');
    console.log('Produtos carregados:', produtos);

    produtosRetryCount = 0;

    // Extrair categorias e carregar tamanhos
    categorias = new Set();
    tamanhosCadastrados = new Set();

    let tamanhosTodos = null;
    try {
      tamanhosTodos = await window.api.listarTamanhosTodos(sessionId);
    } catch (err) {
      console.warn('Falha ao listar tamanhos em lote, usando fallback por produto:', err);
    }

    if (Array.isArray(tamanhosTodos)) {
      const mapaTamanhos = {};
      tamanhosTodos.forEach((tamanho) => {
        const produtoId = tamanho.produto_id;
        if (!mapaTamanhos[produtoId]) {
          mapaTamanhos[produtoId] = [];
        }
        mapaTamanhos[produtoId].push(tamanho);
        if (tamanho.tamanho) tamanhosCadastrados.add(tamanho.tamanho);
      });

      for (const p of produtos) {
        if (p.categoria) categorias.add(p.categoria);
        produtosComTamanhos[p.id] = mapaTamanhos[p.id] || [];
      }
    } else {
      for (const p of produtos) {
        if (p.categoria) categorias.add(p.categoria);

        try {
          const tamanhos = await window.api.listarTamanhos(sessionId, p.id);
          produtosComTamanhos[p.id] = tamanhos || [];
          tamanhos.forEach(t => tamanhosCadastrados.add(t.tamanho));
        } catch (err) {
          console.error(`Erro ao carregar tamanhos do produto ${p.id}:`, err);
          produtosComTamanhos[p.id] = [];
        }
      }
    }

    atualizarFilterCategoria();
    atualizarFilterTamanho();
    renderizarProdutos(produtos);
  } catch (err) {
    console.error('Erro ao carregar produtos:', err);
    produtosRetryCount += 1;
    if (produtosRetryCount <= MAX_PRODUTOS_RETRY) {
      gridProdutos.innerHTML = '<p class="text-danger col-12">Erro ao carregar produtos. Tentando novamente...</p>';
      setTimeout(carregarProdutos, 2000);
      return;
    }

    gridProdutos.innerHTML = `
      <div class="col-12 text-center py-4">
        <p class="text-warning mb-2">Falha ao carregar produtos.</p>
        <button class="btn btn-sm btn-outline-warning" onclick="recarregarProdutos()">Tentar novamente</button>
      </div>
    `;
  } finally {
    carregandoProdutos = false;
    ultimoCarregamentoProdutos = Date.now();
  }
}

window.recarregarProdutos = function () {
  produtosRetryCount = 0;
  carregarProdutos();
};

// ================================
// RENDERIZAR GRID DE PRODUTOS
// ================================
function renderizarProdutos(lista) {
  if (!lista || lista.length === 0) {
    gridProdutos.innerHTML = '<p class="text-muted col-12 text-center py-5">Nenhum produto encontrado</p>';
    return;
  }
  const token = ++renderToken;
  let index = 0;
  gridProdutos.innerHTML = '';

  const renderChunk = () => {
    if (token !== renderToken) return;
    const slice = lista.slice(index, index + RENDER_CHUNK_SIZE);
    if (slice.length === 0) return;

    const html = slice.map(p => {
      const tamanhos = produtosComTamanhos[p.id] || [];
      const tamanhosBadges = tamanhos.length > 0
        ? tamanhos.map(t => `<span class="tamanho-badge">${t.tamanho}</span>`).join('')
        : '<span class="tamanho-sem-dados">Sem tamanhos</span>';

      // Processar caminho da imagem para garantir formato correto
      const imagemSrc = processarCaminhoImagem(p.imagem);

      return `
      <div class="card-estoque-produto" style="${p.ativo === false ? 'opacity: 0.6; filter: grayscale(80%);' : ''}">
        <div class="imagem-container">
          ${p.ativo === false ? '<div style="position: absolute; top: 10px; right: 10px; background: #dc3545; color: white; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; z-index: 10;">INATIVO</div>' : ''}
          <img src="${imagemSrc}" 
               alt="${p.nome}"
               loading="lazy"
               decoding="async"
               onerror="this.src='${PLACEHOLDER_IMAGE}'">
          <div class="badge-estoque ${p.estoque > 10 ? 'badge-ok' : p.estoque > 0 ? 'badge-warning' : 'badge-critical'}">
            ${p.estoque} un
          </div>
        </div>
        <div class="info-produto">
          <h5>${p.nome}</h5>
          <p class="categoria">${(p.categoria && isNaN(p.categoria)) ? p.categoria : 'Sem categoria'}</p>
          <div class="tamanhos-disponiveis">
            ${tamanhosBadges}
          </div>
          <div class="preco-info">
            <span class="preco">R$ ${parseFloat(p.preco).toFixed(2)}</span>
          </div>
        </div>
        <div class="acoes-produto">
          <button class="btn btn-sm btn-gold" onclick="editarProduto(${p.id})">Editar</button>
          <button class="btn btn-sm btn-danger" onclick="confirmarExclusao(${p.id}, '${p.nome}')">Excluir</button>
        </div>
      </div>
    `;
    }).join('');

    gridProdutos.insertAdjacentHTML('beforeend', html);
    index += RENDER_CHUNK_SIZE;
    if (index < lista.length) {
      requestAnimationFrame(renderChunk);
    }
  };

  renderChunk();
}

// ================================
// EDITAR PRODUTO (Global para onclick)
// ================================
window.editarProduto = async function (id) {
  const produto = produtos.find(p => p.id === id);
  if (!produto) return;

  produtoEditando = id;
  imagemAtual = produto.imagem || null;

  document.getElementById('nome').value = produto.nome;
  document.getElementById('descricao').value = produto.descricao || '';
  document.getElementById('categoria').value = produto.categoria || '';
  document.getElementById('preco').value = produto.preco;
  document.getElementById('qtd').value = produto.estoque;

  // Mostrar imagem atual no preview (pode ser Base64 ou URL)
  atualizarPreviewImagem(imagemAtual);

  // Carregar tamanhos do produto
  try {
    const sessionId = getSessionId();
    tamanhosAtuais = await window.api.listarTamanhos(sessionId, id);
    renderizarTamanhos();
  } catch (err) {
    console.error('Erro ao carregar tamanhos:', err);
    tamanhosAtuais = [];
  }

  inputImagem.value = '';
  modalTitulo.textContent = 'Editar Produto';
  abrirModalProduto();
}

/**
 * Atualiza o preview da imagem no modal.
 * Aceita Base64, URL ou null.
 */
function atualizarPreviewImagem(src) {
  const placeholder = document.getElementById('uploadPlaceholder');
  const btnRemover = document.getElementById('btnRemoverImagem');

  if (src) {
    preview.src = processarCaminhoImagem(src);
    preview.style.display = 'block';
    if (placeholder) placeholder.style.display = 'none';
    if (btnRemover) btnRemover.style.display = 'inline-flex';
  } else {
    preview.src = '';
    preview.style.display = 'none';
    if (placeholder) placeholder.style.display = 'block';
    if (btnRemover) btnRemover.style.display = 'none';
  }
}

// ================================
// SALVAR PRODUTO
// ================================
async function salvarProdutoHandler() {
  const nome = document.getElementById('nome').value.trim();
  const descricao = document.getElementById('descricao').value.trim();
  const categoria = document.getElementById('categoria').value.trim();
  const precoBruto = document.getElementById('preco').value.trim();
  const preco = parseFloat(precoBruto.replace(/\./g, '').replace(',', '.'));
  const qtd = parseInt(document.getElementById('qtd').value);

  if (!nome || preco <= 0 || qtd < 0 || isNaN(preco) || isNaN(qtd)) {
    alert('❌ Preencha os campos obrigatórios corretamente');
    return;
  }

  try {
    salvarProduto.disabled = true;
    salvarProduto.textContent = '⏳ Salvando...';

    const dados = {
      nome,
      descricao,
      categoria,
      preco,
      estoque: qtd,
      imagem: imagemAtual
    };

    let produtoId;
    if (produtoEditando) {
      const sessionId = getSessionId();
      await window.api.atualizarProduto(sessionId, { id: produtoEditando, ...dados });
      produtoId = produtoEditando;
      console.log('Produto atualizado:', produtoEditando);
    } else {
      const sessionId = getSessionId();
      const resultado = await window.api.criarProduto(sessionId, dados);
      produtoId = resultado.id;
      console.log('Novo produto criado com ID:', produtoId);
    }

    // Salvar tamanhos
    if (produtoEditando) {
      // Excluir tamanhos antigos ao editar
      const sessionId = getSessionId();
      await window.api.excluirTamanhosPorProduto(sessionId, produtoId);
    }

    // Adicionar novos tamanhos
    for (const tamanho of tamanhosAtuais) {
      const sessionId = getSessionId();
      await window.api.criarTamanho(sessionId, {
        produto_id: produtoId,
        tamanho: tamanho.tamanho,
        quantidade: tamanho.quantidade
      });
    }

    alert('✅ Produto salvo com sucesso!');
    limparFormulario();
    modal.style.display = 'none';
    carregarProdutos();
  } catch (err) {
    console.error('Erro ao salvar produto:', err);
    alert('❌ Erro ao salvar produto: ' + err.message);
  } finally {
    salvarProduto.disabled = false;
    salvarProduto.textContent = 'Salvar Produto';
  }
}

// ================================
// GERENCIAR TAMANHOS
// ================================
function adicionarTamanhoHandler() {
  const tamanho = document.getElementById('novoTamanho').value.trim().toUpperCase();
  const quantidade = parseInt(document.getElementById('novaQuantidade').value);

  if (!tamanho || isNaN(quantidade) || quantidade < 0) {
    alert('❌ Preencha o tamanho e quantidade corretamente');
    return;
  }

  // Verificar se o tamanho já foi adicionado
  if (tamanhosAtuais.some(t => t.tamanho === tamanho)) {
    alert('⚠️ Este tamanho já foi adicionado');
    return;
  }

  tamanhosAtuais.push({ tamanho, quantidade });
  renderizarTamanhos();

  // Limpar campos
  document.getElementById('novoTamanho').value = '';
  document.getElementById('novaQuantidade').value = '';
}

function renderizarTamanhos() {
  if (tamanhosAtuais.length === 0) {
    listaTamanhos.innerHTML = '<div style="text-align: center; padding: 20px; color: #666; font-size: 13px;"><svg width="40" height="40" style="opacity: 0.3; margin-bottom: 8px;" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/></svg><br>Nenhum tamanho adicionado ainda</div>';
    return;
  }

  const html = tamanhosAtuais.map((t, index) => `
    <div class="d-flex justify-content-between align-items-center mb-2 p-3" style="background: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%); border-radius: 8px; border: 1px solid #3a3a3a; color: #fff; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.2);" onmouseover="this.style.borderColor='#F2C84B'; this.style.transform='translateX(4px)';" onmouseout="this.style.borderColor='#3a3a3a'; this.style.transform='translateX(0)';">
      <div style="display: flex; align-items: center; gap: 12px;">
        <div style="background: #F2C84B; color: #000; width: 36px; height: 36px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; box-shadow: 0 2px 6px rgba(255,216,77,0.4);">${t.tamanho}</div>
        <div>
          <div style="font-size: 13px; color: #fff; font-weight: 500;">${t.quantidade} unidade${t.quantidade !== 1 ? 's' : ''}</div>
          <div style="font-size: 11px; color: #888;">em estoque</div>
        </div>
      </div>
      <button type="button" class="btn btn-sm" onclick="removerTamanho(${index})" style="background: #ff4444; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 14px; transition: all 0.2s; box-shadow: 0 2px 4px rgba(255,68,68,0.3);" onmouseover="this.style.background='#ff0000'; this.style.transform='scale(1.05)';" onmouseout="this.style.background='#ff4444'; this.style.transform='scale(1)';">✕</button>
    </div>
  `).join('');

  listaTamanhos.innerHTML = html;
}

// Tornar função global para onclick
window.removerTamanho = function (index) {
  tamanhosAtuais.splice(index, 1);
  renderizarTamanhos();
}

// ================================
// CONFIRMAR EXCLUSÃO (Global para onclick)
// ================================
window.confirmarExclusao = function (id, nome) {
  if (confirm(`Tem certeza que deseja excluir "${nome}"?`)) {
    excluirProduto(id);
  }
}

// ================================
// EXCLUIR PRODUTO
// ================================
async function excluirProduto(id) {
  try {
    const sessionId = getSessionId();
    await window.api.excluirProduto(sessionId, id);
    console.log('Produto excluído:', id);
    alert('✅ Produto excluído com sucesso!');
    carregarProdutos();
  } catch (err) {
    console.error('Erro ao excluir produto:', err);
    alert('❌ Erro ao excluir: ' + err.message);
  }
}

// ================================
// PREVIEW DE IMAGEM
// ================================
async function previewImagemHandler() {
  const file = inputImagem.files[0];
  if (!file) return;

  const tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
  if (!tiposPermitidos.includes(file.type)) {
    alert('❌ Tipo de arquivo inválido! Use JPG, PNG ou WebP');
    inputImagem.value = '';
    return;
  }

  try {
    const wasLarge = file.size > 5 * 1024 * 1024;
    const dataUrl = await compressImageToDataUrl(file, 5);
    if (wasLarge) {
      alert('ℹ️ Imagem acima de 5MB foi otimizada automaticamente.');
    }

    imagemAtual = dataUrl;
    atualizarPreviewImagem(imagemAtual);
  } catch (err) {
    console.error('Erro ao otimizar imagem:', err);
    alert('❌ Não foi possível otimizar a imagem.');
    inputImagem.value = '';
  }
}

function readFileAsDataUrl(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = (e) => resolve(e.target.result);
    reader.onerror = reject;
    reader.readAsDataURL(file);
  });
}

function loadImageFromFile(file) {
  return new Promise((resolve, reject) => {
    const img = new Image();
    const url = URL.createObjectURL(file);
    img.onload = () => {
      URL.revokeObjectURL(url);
      resolve(img);
    };
    img.onerror = (e) => {
      URL.revokeObjectURL(url);
      reject(e);
    };
    img.src = url;
  });
}

function canvasToBlob(canvas, type, quality) {
  return new Promise((resolve) => canvas.toBlob(resolve, type, quality));
}

async function compressImageToDataUrl(file, maxSizeMB = 5) {
  const maxBytes = maxSizeMB * 1024 * 1024;
  if (file.size <= maxBytes) {
    return readFileAsDataUrl(file);
  }

  const img = await loadImageFromFile(file);
  let width = img.naturalWidth;
  let height = img.naturalHeight;
  let quality = 0.9;
  let scale = 1;
  const outputType = file.type === 'image/png' ? 'image/webp' : 'image/jpeg';

  const canvas = document.createElement('canvas');
  const ctx = canvas.getContext('2d');

  const render = () => {
    canvas.width = Math.round(width * scale);
    canvas.height = Math.round(height * scale);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
  };

  render();
  let blob = await canvasToBlob(canvas, outputType, quality);

  while (blob && blob.size > maxBytes && quality > 0.6) {
    quality -= 0.1;
    blob = await canvasToBlob(canvas, outputType, quality);
  }

  while (blob && blob.size > maxBytes && scale > 0.6) {
    scale -= 0.1;
    quality = 0.85;
    render();
    blob = await canvasToBlob(canvas, outputType, quality);
  }

  if (!blob) {
    throw new Error('Falha ao gerar blob da imagem');
  }

  return readFileAsDataUrl(new File([blob], file.name, { type: outputType }));
}

// ================================
// LIMPAR FORMULÁRIO
// ================================
function limparFormulario() {
  document.getElementById('nome').value = '';
  document.getElementById('descricao').value = '';
  document.getElementById('categoria').value = '';
  document.getElementById('preco').value = '';
  document.getElementById('qtd').value = '';
  document.getElementById('novoTamanho').value = '';
  document.getElementById('novaQuantidade').value = '';
  inputImagem.value = '';
  imagemAtual = null;
  atualizarPreviewImagem(null);
  produtoEditando = null;
  tamanhosAtuais = [];
  renderizarTamanhos();
  modalTitulo.textContent = 'Novo Produto';
}

// ================================
// ================================
// ATUALIZAR FILTRO DE CATEGORIAS
// ================================
function atualizarFilterCategoria() {
  const select = document.getElementById('filterCategoria');

  // Limpar opções antigas (mantendo apenas "Todas as categorias")
  while (select.options.length > 1) {
    select.remove(1);
  }

  // Contar quantos produtos tem cada categoria
  const categoriasComProdutos = new Set();
  produtos.forEach(p => {
    // Adicionar apenas se for uma string válida (nome da categoria) e não um número (ID órfão)
    if (p.categoria && isNaN(p.categoria)) {
      categoriasComProdutos.add(p.categoria);
    }
  });

  // Adicionar apenas categorias que têm produtos
  const opcoes = Array.from(categoriasComProdutos).sort();
  opcoes.forEach(cat => {
    const opt = document.createElement('option');
    opt.value = cat;
    opt.textContent = cat;
    select.appendChild(opt);
  });
}

// ================================
// ATUALIZAR FILTRO DE TAMANHOS
// ================================
function atualizarFilterTamanho() {
  const select = document.getElementById('filterTamanho');
  const opcoes = Array.from(tamanhosCadastrados).sort();

  opcoes.forEach(tamanho => {
    if (!select.querySelector(`option[value="${tamanho}"]`)) {
      const opt = document.createElement('option');
      opt.value = tamanho;
      opt.textContent = tamanho;
      select.appendChild(opt);
    }
  });
}

// ================================
// BUSCA E FILTRO
// ================================
function filtrarProdutos() {
  const termo = searchProduto.value.toLowerCase();
  const categoria = filterCategoria.value;
  const tamanho = filterTamanho.value;
  const estoqueFilter = filterEstoque.value;
  const sortValue = ordenarPor.value || 'nome-az';

  const filtrados = produtos.filter(p => {
    // Filtro por nome/categoria
    const matchNome = p.nome.toLowerCase().includes(termo) ||
      (p.categoria && String(p.categoria).toLowerCase().includes(termo));
    const matchCategoria = !categoria || p.categoria === categoria;

    // Filtro por tamanho
    let matchTamanho = true;
    if (tamanho) {
      const tamanhosProduto = produtosComTamanhos[p.id] || [];
      matchTamanho = tamanhosProduto.some(t => t.tamanho === tamanho);
    }

    // Filtro por estoque
    let matchEstoque = true;
    if (estoqueFilter) {
      if (estoqueFilter === 'em-falta') {
        matchEstoque = p.estoque === 0;
      } else if (estoqueFilter === 'baixo') {
        matchEstoque = p.estoque > 0 && p.estoque <= 10;
      } else if (estoqueFilter === 'medio') {
        matchEstoque = p.estoque > 10 && p.estoque <= 30;
      } else if (estoqueFilter === 'alto') {
        matchEstoque = p.estoque > 30;
      }
    }

    return matchNome && matchCategoria && matchTamanho && matchEstoque;
  });

  // Aplicar ordenação
  switch (sortValue) {
    case 'nome-az':
      filtrados.sort((a, b) => a.nome.localeCompare(b.nome, 'pt-BR'));
      break;
    case 'nome-za':
      filtrados.sort((a, b) => b.nome.localeCompare(a.nome, 'pt-BR'));
      break;
    case 'preco-menor':
      filtrados.sort((a, b) => a.preco - b.preco);
      break;
    case 'preco-maior':
      filtrados.sort((a, b) => b.preco - a.preco);
      break;
    case 'estoque-menor':
      filtrados.sort((a, b) => a.estoque - b.estoque);
      break;
    case 'estoque-maior':
      filtrados.sort((a, b) => b.estoque - a.estoque);
      break;
  }

  renderizarProdutos(filtrados);
}

// ================================
// ATUALIZAR RESUMO
// ================================
function atualizarResumo() {
  document.getElementById('totalProdutos').textContent = produtos.length;
  document.getElementById('totalEstoque').textContent =
    produtos.reduce((sum, p) => sum + (p.estoque || 0), 0);
  document.getElementById('valorTotal').textContent =
    'R$ ' + produtos.reduce((sum, p) => sum + (p.preco * p.estoque), 0).toFixed(2);
}
