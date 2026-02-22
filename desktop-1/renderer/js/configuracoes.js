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

function getSessionId() {
  return typeof AuthHelper !== 'undefined' ? AuthHelper.getSessionId() : localStorage.getItem('sessionId');
}

// Toggle de visibilidade de senha
function toggleSenhaVisibilidade() {
  const senhaInput = document.getElementById('novaSenha');
  const toggleBtn = document.getElementById('toggleSenha');

  if (senhaInput.type === 'password') {
    senhaInput.type = 'text';
    toggleBtn.textContent = '👁️';
  } else {
    senhaInput.type = 'password';
    toggleBtn.textContent = '👁️';
  }
}

// Verificar força da senha
function verificarForcaSenha(senha) {
  const forcaDiv = document.getElementById('forcaSenha');
  const forcaTexto = document.getElementById('forcaTexto');
  const barras = document.querySelectorAll('.forca-bar');

  if (!senha || senha.length === 0) {
    forcaDiv.style.display = 'none';
    return;
  }

  forcaDiv.style.display = 'block';

  let forca = 0;
  if (senha.length >= 6) forca++;
  if (senha.length >= 10) forca++;
  if (/[a-z]/.test(senha) && /[A-Z]/.test(senha)) forca++;
  if (/[0-9]/.test(senha)) forca++;
  if (/[^a-zA-Z0-9]/.test(senha)) forca++;

  // Calcular nível (0-4)
  const nivel = Math.min(Math.floor(forca / 1.25), 4);

  const cores = ['#ff6b6b', '#ffa94d', '#ffc107', '#51cf66', '#51cf66'];
  const textos = ['Muito fraca', 'Fraca', 'Média', 'Forte', 'Muito forte'];

  barras.forEach((barra, index) => {
    if (index < nivel) {
      barra.style.background = cores[nivel];
    } else {
      barra.style.background = '#333';
    }
  });

  forcaTexto.textContent = textos[nivel];
  forcaTexto.style.color = cores[nivel];
}

// Verificar correspondência de senhas
function verificarSenhasIguais() {
  const novaSenha = document.getElementById('novaSenha').value;
  const confirmarSenha = document.getElementById('confirmarSenha').value;
  const matchEl = document.getElementById('senhaMatch');

  if (!confirmarSenha) {
    matchEl.style.display = 'none';
    return;
  }

  matchEl.style.display = 'block';

  if (novaSenha === confirmarSenha) {
    matchEl.textContent = '✓ Senhas correspondem';
    matchEl.style.color = '#51cf66';
  } else {
    matchEl.textContent = '× Senhas não correspondem';
    matchEl.style.color = '#ff6b6b';
  }
}

// ================================
// MUDAR ABA
// ================================
function mudarAba(abaName) {
  // Ocultar todas as abas
  document.querySelectorAll('.tab-content').forEach(tab => {
    tab.style.display = 'none';
  });

  // Remover classe active dos botões
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.classList.remove('active');
    btn.style.borderBottomColor = 'transparent';
  });

  // Mostrar aba selecionada
  const abaElement = document.getElementById(abaName);
  if (abaElement) {
    abaElement.style.display = 'block';
  }

  // Marcar botão como ativo
  const botao = document.querySelector(`[data-tab="${abaName}"]`);
  if (botao) {
    botao.classList.add('active');
    botao.style.borderBottomColor = '#ffc107';
  }

  // Carregar dados ao abrir aba
  if (abaName === 'banners') {
    carregarBanners();
  }
}

// ================================
// CARREGAR CONFIGURAÇÕES
// ================================
async function carregarConfiguracoes() {
  try {
    const sessionId = getSessionId();
    const config = await window.api.obterConfig(sessionId);

    // App
    if (config.app) {
      document.getElementById('appTheme').value = config.app.theme || 'dark';
      document.getElementById('appAutoStart').checked = config.app.autoStart || false;
      document.getElementById('appShowInTray').checked = config.app.showInTray !== false;
      document.getElementById('appCloseToTray').checked = config.app.closeToTray || false;
    }

    // Backup
    if (config.backup) {
      const backupEnabled = document.getElementById('backupEnabled');
      if (backupEnabled) backupEnabled.checked = config.backup.enabled !== false;

      const backupAutoBackup = document.getElementById('backupAutoBackup');
      if (backupAutoBackup) backupAutoBackup.checked = config.backup.autoBackup !== false;

      const backupFrequency = document.getElementById('backupFrequency');
      if (backupFrequency) backupFrequency.value = config.backup.frequency || 'daily';

      const backupMaxBackups = document.getElementById('backupMaxBackups');
      if (backupMaxBackups) backupMaxBackups.value = config.backup.maxBackups || 7;

      const backupPath = document.getElementById('backupPath');
      if (backupPath) backupPath.value = config.backup.path || '';
    }
  } catch (err) {
    console.error('Erro ao carregar configurações:', err);
    mostrarNotificacao('Erro ao carregar configurações', 'erro');
  }
}

// ================================
// RESETAR CONFIGURAÇÕES
// ================================
async function resetarConfiguracoes() {
  if (!confirm('Tem certeza que deseja resetar TODAS as configurações para os valores padrão?\n\nIsso NÃO afetará suas credenciais de login.')) {
    return;
  }

  try {
    const sessionId = getSessionId();
    await window.api.resetarConfig(sessionId);
    mostrarNotificacao('Configurações resetadas com sucesso', 'sucesso');
    carregarConfiguracoes();
  } catch (err) {
    console.error('Erro ao resetar configurações:', err);
    mostrarNotificacao('Erro ao resetar: ' + err.message, 'erro');
  }
}

// Expor função globalmente
window.resetarConfiguracoes = resetarConfiguracoes;

// ================================
// CARREGAR BANNERS EXISTENTES
// ================================
async function carregarBanners() {
  try {
    const sessionId = getSessionId();
    const banners = await window.api.obterBanners(sessionId);
    renderizarBanners(banners || []);
  } catch (err) {
    console.error('Erro ao carregar banners:', err);
  }
}

// ================================
// RENDERIZAR LISTA DE BANNERS
// ================================
function renderizarBanners(banners) {
  const container = document.getElementById('bannersList');

  if (!banners || banners.length === 0) {
    container.innerHTML = '<p style="color: #888; text-align: center; padding: 20px;">Nenhum banner cadastrado ainda</p>';
    return;
  }

  container.innerHTML = banners.map((banner, index) => `
    <div class="banner-card">
      <img src="${banner.imagem}" alt="Thumb do banner" class="banner-thumb">
      <div class="banner-info">
        <div class="banner-title">${banner.nome || `Banner ${index + 1}`}</div>
        <div class="banner-meta">Criado em: ${new Date(banner.dataCriacao).toLocaleDateString('pt-BR')}${banner.dataAtualizacao ? ` • Atualizado: ${new Date(banner.dataAtualizacao).toLocaleDateString('pt-BR')}` : ''}</div>
      </div>
      <div class="banner-actions">
        <button type="button" class="btn btn-sm" onclick="editarBanner(${index})" style="background: #ffc107; color: #000; border: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; cursor: pointer;">Editar</button>
        <button type="button" class="btn btn-sm" onclick="excluirBanner(${index})" style="background: #ff4444; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; cursor: pointer;">Excluir</button>
      </div>
    </div>
  `).join('');
}

// ================================
// EDITAR BANNER
// ================================
window.editarBanner = async function (index) {
  try {
    const sessionId = getSessionId();
    const banners = await window.api.obterBanners(sessionId);
    const banner = banners[index];

    if (!banner) {
      mostrarNotificacao('Banner não encontrado', 'erro');
      return;
    }

    // Preencher form com dados do banner
    document.getElementById('nomeBanner').value = banner.nome || '';

    // Mostrar preview da imagem atual
    const preview = document.getElementById('previewNovo');
    preview.src = banner.imagem;
    preview.style.display = 'block';
    atualizarInfoImagem(banner.imagem);
    const simg = document.getElementById('simulacaoImg');
    const sbox = document.getElementById('simulacaoBanner');
    if (simg && sbox) { simg.src = banner.imagem; sbox.style.display = 'block'; }

    // Guardar índice para atualização
    document.getElementById('formBanners').dataset.bannerIndex = index;
    document.getElementById('formBanners').dataset.modoEdicao = 'true';

    // Scroll para formulário
    document.getElementById('formBanners').scrollIntoView({ behavior: 'smooth' });
    mostrarNotificacao('Modo de edição ativado', 'info');
  } catch (err) {
    console.error('Erro ao editar banner:', err);
    mostrarNotificacao('Erro ao editar banner: ' + err.message, 'erro');
  }
}

// ================================
// EXCLUIR BANNER
// ================================
window.excluirBanner = async function (index) {
  if (!confirm('Tem certeza que deseja excluir este banner? Esta ação não pode ser desfeita.')) return;

  try {
    const sessionId = getSessionId();
    await window.api.excluirBanner(sessionId, index);
    mostrarNotificacao('Banner excluído com sucesso', 'sucesso');
    carregarBanners();
  } catch (err) {
    console.error('Erro ao excluir banner:', err);
    mostrarNotificacao('Erro ao excluir banner: ' + err.message, 'erro');
  }
}

// ================================
// PREVIEW DE BANNER
// ================================
async function previewBanner(input, previewId) {
  if (input.files && input.files[0]) {
    const file = input.files[0];
    const tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!tiposPermitidos.includes(file.type)) {
      mostrarNotificacao('Formato inválido. Use JPG, PNG, GIF ou WebP', 'erro');
      input.value = '';
      return;
    }

    try {
      const wasLarge = file.size > 5 * 1024 * 1024;
      const dataUrl = await compressImageToDataUrl(file, 5);
      if (wasLarge) {
        mostrarNotificacao('Imagem acima de 5MB foi otimizada automaticamente.', 'aviso');
      }

      const preview = document.getElementById(previewId);
      preview.src = dataUrl;
      preview.style.display = 'block';
      atualizarInfoImagem(dataUrl);
      const simg = document.getElementById('simulacaoImg');
      const sbox = document.getElementById('simulacaoBanner');
      if (simg && sbox) { simg.src = dataUrl; sbox.style.display = 'block'; }
    } catch (err) {
      console.error('Erro ao otimizar banner:', err);
      mostrarNotificacao('Não foi possível otimizar a imagem.', 'erro');
      input.value = '';
    }
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
// INFO DE ESCALA E SIMULAÇÃO
// ================================
const RATIO_RECOMENDADO = 8 / 3; // ~2.6667
const LARGURA_RECOMENDADA = 1920;
const ALTURA_RECOMENDADA = 720;

function atualizarInfoImagem(src) {
  const img = new Image();
  img.onload = () => {
    const w = img.naturalWidth;
    const h = img.naturalHeight;
    const ratio = (w / h);
    const info = document.getElementById('previewInfo');
    if (!info) return;
    const diff = Math.abs(ratio - RATIO_RECOMENDADO);
    let ajuste = 'sem cortes';
    if (diff > 0.06) {
      ajuste = ratio > RATIO_RECOMENDADO ? 'corte lateral (largura maior)' : 'corte superior/inferior (altura maior)';
    }
    info.innerHTML = `Recomendado: <strong>${LARGURA_RECOMENDADA}×${ALTURA_RECOMENDADA}</strong> (≈${RATIO_RECOMENDADO.toFixed(2)}:1) • Sua imagem: <strong>${w}×${h}</strong> (≈${ratio.toFixed(2)}:1) → <span style="color:#ffc107;">${ajuste}</span>`;
    info.style.display = 'block';
  };
  img.src = src;
}

// ================================
// DRAG AND DROP BANNERS
// ================================
document.addEventListener('DOMContentLoaded', () => {
  const dropZone = document.getElementById('dropZone');
  const fileInput = document.getElementById('imagemBanner');

  if (dropZone && fileInput) {
    dropZone.addEventListener('dragover', (e) => {
      e.preventDefault();
      dropZone.style.borderColor = '#ffc107';
      dropZone.style.background = 'rgba(255,193,7,0.15)';
    });

    dropZone.addEventListener('dragleave', () => {
      dropZone.style.borderColor = '#ffc107';
      dropZone.style.background = 'rgba(255,193,7,0.05)';
    });

    dropZone.addEventListener('drop', (e) => {
      e.preventDefault();
      if (e.dataTransfer.files.length > 0) {
        fileInput.files = e.dataTransfer.files;
        previewBanner(fileInput, 'previewNovo');
      }
      dropZone.style.borderColor = '#ffc107';
      dropZone.style.background = 'rgba(255,193,7,0.05)';
    });
  }
});

// ================================
// FORMULÁRIO CREDENCIAIS
// ================================
const formCredenciais = document.getElementById('formCredenciais');
if (formCredenciais) {
  // Listeners para validação em tempo real
  const novaSenhaInput = document.getElementById('novaSenha');
  const confirmarSenhaInput = document.getElementById('confirmarSenha');

  if (novaSenhaInput) {
    novaSenhaInput.addEventListener('input', (e) => {
      verificarForcaSenha(e.target.value);
      verificarSenhasIguais();
    });
  }

  if (confirmarSenhaInput) {
    confirmarSenhaInput.addEventListener('input', verificarSenhasIguais);
  }

  formCredenciais.addEventListener('submit', async (e) => {
    e.preventDefault();

    const loginAtual = document.getElementById('loginAtual').value.trim();
    const senhaAtual = document.getElementById('senhaAtual').value.trim();
    const novoLogin = document.getElementById('novoLogin').value.trim();
    const novaSenha = document.getElementById('novaSenha').value.trim();
    const confirmarSenha = document.getElementById('confirmarSenha').value.trim();

    // Validações
    if (!loginAtual || !senhaAtual || !novoLogin || !novaSenha || !confirmarSenha) {
      mostrarNotificacao('Preencha todos os campos', 'aviso');
      return;
    }

    if (novaSenha !== confirmarSenha) {
      mostrarNotificacao('As senhas não correspondem', 'erro');
      return;
    }

    if (novaSenha.length < 6) {
      mostrarNotificacao('A senha deve ter no mínimo 6 caracteres', 'aviso');
      return;
    }

    if (novoLogin.length < 3) {
      mostrarNotificacao('O login deve ter no mínimo 3 caracteres', 'aviso');
      return;
    }

    if (!confirm('Confirma a alteração das credenciais? Você precisará fazer login novamente.')) {
      return;
    }

    try {
      const sessionId = getSessionId();
      await window.api.alterarCredenciais(sessionId, {
        loginAtual,
        senhaAtual,
        novoLogin,
        novaSenha
      });

      mostrarNotificacao('Credenciais alteradas com sucesso!', 'sucesso');
      formCredenciais.reset();

      // Resetar indicadores
      document.getElementById('forcaSenha').style.display = 'none';
      document.getElementById('senhaMatch').style.display = 'none';

      // Redirecionar para login após 2 segundos
      setTimeout(() => {
        window.location.href = 'login.html';
      }, 2000);
    } catch (err) {
      console.error('Erro ao alterar credenciais:', err);
      mostrarNotificacao('Erro ao alterar credenciais: ' + err.message, 'erro');
    }
  });
}

// ================================
// FORMULÁRIO BANNERS
// ================================
const formBanners = document.getElementById('formBanners');
if (formBanners) {
  formBanners.addEventListener('submit', async (e) => {
    e.preventDefault();

    const nomeBanner = document.getElementById('nomeBanner').value.trim();
    const imagemFile = document.getElementById('imagemBanner').files[0];
    const modoEdicao = formBanners.dataset.modoEdicao === 'true';
    const bannerIndex = parseInt(formBanners.dataset.bannerIndex);

    if (!nomeBanner) {
      mostrarNotificacao('Digite o nome do banner', 'aviso');
      return;
    }

    if (!imagemFile && !modoEdicao) {
      mostrarNotificacao('Selecione uma imagem para o banner', 'aviso');
      return;
    }

    // Validar tipo de arquivo
    if (imagemFile && !['image/jpeg', 'image/png', 'image/gif', 'image/webp'].includes(imagemFile.type)) {
      mostrarNotificacao('Formato inválido. Use JPG, PNG, GIF ou WebP', 'erro');
      return;
    }

    try {
      const sessionId = getSessionId();
      if (imagemFile) {
        const imagemData = await compressImageToDataUrl(imagemFile, 5);

        if (modoEdicao) {
          await window.api.atualizarBanner(sessionId, bannerIndex, {
            nome: nomeBanner,
            imagem: imagemData
          });
          mostrarNotificacao('Banner atualizado com sucesso', 'sucesso');
        } else {
          await window.api.criarBanner(sessionId, {
            nome: nomeBanner,
            imagem: imagemData
          });
          mostrarNotificacao('Banner criado com sucesso', 'sucesso');
        }

        limparFormBanner();
        carregarBanners();
      }
    } catch (err) {
      console.error('Erro ao salvar banner:', err);
      mostrarNotificacao('Erro ao salvar banner: ' + err.message, 'erro');
    }
  });
}

// Função auxiliar para limpar formulário de banner
function limparFormBanner() {
  formBanners.reset();
  document.getElementById('previewNovo').style.display = 'none';
  const sbox = document.getElementById('simulacaoBanner');
  const info = document.getElementById('previewInfo');
  if (sbox) sbox.style.display = 'none';
  if (info) info.style.display = 'none';
  delete formBanners.dataset.modoEdicao;
  delete formBanners.dataset.bannerIndex;
}

// ================================
// FORMULÁRIO APP
// ================================
const formApp = document.getElementById('formApp');
if (formApp) {
  formApp.addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
      const dados = {
        theme: document.getElementById('appTheme').value,
        autoStart: document.getElementById('appAutoStart').checked,
        showInTray: document.getElementById('appShowInTray').checked,
        closeToTray: document.getElementById('appCloseToTray').checked
      };

      const sessionId = getSessionId();
      await window.api.atualizarConfigApp(sessionId, dados);
      mostrarNotificacao('Configurações do aplicativo salvas', 'sucesso');
    } catch (err) {
      console.error('Erro ao salvar config app:', err);
      mostrarNotificacao('Erro: ' + err.message, 'erro');
    }
  });
}

// ================================
// FORMULÁRIO BACKUP
// ================================
const formBackup = document.getElementById('formBackup');
if (formBackup) {
  formBackup.addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
      const dados = {
        enabled: document.getElementById('backupEnabled').checked,
        autoBackup: document.getElementById('backupAutoBackup').checked,
        frequency: document.getElementById('backupFrequency').value,
        maxBackups: parseInt(document.getElementById('backupMaxBackups').value)
      };

      const sessionId = getSessionId();
      await window.api.atualizarConfigBackup(sessionId, dados);
      mostrarNotificacao('Configurações de backup salvas', 'sucesso');
    } catch (err) {
      console.error('Erro ao salvar config backup:', err);
      mostrarNotificacao('Erro: ' + err.message, 'erro');
    }
  });
}

// ================================
// CARREGAR CONFIGURAÇÕES AO INICIAR
// ================================
document.addEventListener('DOMContentLoaded', () => {
  carregarConfiguracoes();
});

// ================================
// SINCRONIZAÇÃO DE PRODUTOS → API
// ================================
async function sincronizarProdutos() {
  const btnSincronizar = document.getElementById('btnSincronizar');
  const syncStatus = document.getElementById('syncStatus');
  const syncIcon = document.getElementById('syncIcon');
  const syncMessage = document.getElementById('syncMessage');
  const syncDetails = document.getElementById('syncDetails');
  const syncProgress = document.getElementById('syncProgress');
  const syncProgressBar = document.getElementById('syncProgressBar');

  try {
    // Desabilitar botão e mostrar status
    btnSincronizar.disabled = true;
    btnSincronizar.innerHTML = '<span style="font-size: 20px;">⏳</span><span>Sincronizando...</span>';
    syncStatus.style.display = 'block';
    syncProgress.style.display = 'block';
    syncIcon.textContent = '⏳';
    syncMessage.textContent = 'Verificando conexão com a API...';
    syncDetails.textContent = '';
    syncProgressBar.style.width = '10%';

    const sessionId = getSessionId();

    // 1. Verificar se API está acessível
    const conexao = await window.api.verificarConexaoApi(sessionId);
    if (!conexao.online) {
      throw new Error('API remota não está acessível em http://localhost:8000. Verifique se o servidor está rodando.');
    }

    syncMessage.textContent = 'Coletando produtos do banco local...';
    syncProgressBar.style.width = '20%';

    // 2. Disparar sincronização em lote no processo Electron
    syncMessage.textContent = 'Enviando produtos para a API...';
    syncProgressBar.style.width = '40%';

    const resultado = await window.api.sincronizarTodosProdutos(sessionId);

    // 3. Exibir resultado
    syncProgressBar.style.width = '100%';

    if (resultado.erros === 0) {
      syncIcon.textContent = '✅';
      syncMessage.textContent = `Sincronização concluída com sucesso!`;
      syncDetails.textContent = `${resultado.enviados} produto(s) enviado(s) para a API.`;
      mostrarNotificacao(`✅ ${resultado.enviados} produtos sincronizados com sucesso!`, 'sucesso');
    } else if (resultado.enviados > 0) {
      syncIcon.textContent = '⚠️';
      syncMessage.textContent = 'Sincronização concluída com avisos';
      syncDetails.textContent = `${resultado.enviados} enviados, ${resultado.erros} com erro de ${resultado.total} total.`;
      mostrarNotificacao(`⚠️ ${resultado.enviados} enviados, ${resultado.erros} com erro.`, 'aviso');
    } else {
      syncIcon.textContent = '❌';
      syncMessage.textContent = 'Falha na sincronização';
      const primeiroErro = resultado.falhas?.[0]?.erro || 'Verifique os logs.';
      syncDetails.textContent = `Nenhum produto foi enviado. ${primeiroErro}`;
      mostrarNotificacao('❌ Nenhum produto foi sincronizado.', 'erro');
    }

    // Esconder barra de progresso após 5 segundos
    setTimeout(() => {
      syncProgress.style.display = 'none';
    }, 5000);

  } catch (err) {
    console.error('Erro na sincronização:', err);
    if (syncIcon) syncIcon.textContent = '❌';
    if (syncMessage) syncMessage.textContent = 'Erro na sincronização';
    if (syncDetails) syncDetails.textContent = err.message;
    if (syncProgress) syncProgress.style.display = 'none';
    mostrarNotificacao('❌ Erro: ' + err.message, 'erro');
  } finally {
    btnSincronizar.disabled = false;
    btnSincronizar.innerHTML = '<span style="font-size: 20px;">🔄</span><span>Sincronizar Produtos Agora</span>';
  }
}


// Expor funções globalmente (necessário com type="module")
window.sincronizarProdutos = sincronizarProdutos;
window.mudarAba = mudarAba;
window.toggleSenhaVisibilidade = toggleSenhaVisibilidade;
window.previewBanner = previewBanner;
window.editarBanner = window.editarBanner; // já definido acima
window.excluirBanner = window.excluirBanner; // já definido acima

