const { contextBridge, ipcRenderer } = require('electron');

contextBridge.exposeInMainWorld('api', {
  // AUTENTICAÇÃO
  login: (username, password) => ipcRenderer.invoke('auth:login', username, password),
  logout: (sessionId) => ipcRenderer.invoke('auth:logout', sessionId),
  validateSession: (sessionId) => ipcRenderer.invoke('auth:validate', sessionId),
  changePassword: (sessionId, oldPassword, newPassword) =>
    ipcRenderer.invoke('auth:changePassword', sessionId, oldPassword, newPassword),
  resetPassword: (email, novaSenha) =>
    ipcRenderer.invoke('auth:resetPassword', email, novaSenha),

  // PRODUTOS
  listarProdutos: (sessionId) => ipcRenderer.invoke('produtos:listar', sessionId),
  criarProduto: (sessionId, p) => ipcRenderer.invoke('produtos:criar', sessionId, p),
  excluirProduto: (sessionId, id) => ipcRenderer.invoke('produtos:excluir', sessionId, id),
  atualizarProduto: (sessionId, dados) => ipcRenderer.invoke('produtos:atualizar', sessionId, dados),

  // TAMANHOS
  listarTamanhos: (sessionId, produtoId) => ipcRenderer.invoke('tamanhos:listar', sessionId, produtoId),
  listarTamanhosTodos: (sessionId) => ipcRenderer.invoke('tamanhos:listar-todos', sessionId),
  criarTamanho: (sessionId, tamanho) => ipcRenderer.invoke('tamanhos:criar', sessionId, tamanho),
  excluirTamanho: (sessionId, id) => ipcRenderer.invoke('tamanhos:excluir', sessionId, id),
  excluirTamanhosPorProduto: (sessionId, produtoId) => ipcRenderer.invoke('tamanhos:excluir-por-produto', sessionId, produtoId),

  // CLIENTES
  listarClientes: (sessionId) => ipcRenderer.invoke('clientes:listar', sessionId),
  criarCliente: (sessionId, c) => ipcRenderer.invoke('clientes:criar', sessionId, c),
  atualizarCliente: (sessionId, dados) => ipcRenderer.invoke('clientes:atualizar', sessionId, dados),
  excluirCliente: (sessionId, id) => ipcRenderer.invoke('clientes:excluir', sessionId, id),

  // PEDIDOS
  listarPedidos: (sessionId) => ipcRenderer.invoke('pedidos:listar', sessionId),
  criarPedido: (sessionId, pedido) => ipcRenderer.invoke('pedidos:criar', sessionId, pedido),
  atualizarPedido: (sessionId, pedido) => ipcRenderer.invoke('pedidos:atualizar', sessionId, pedido),
  excluirPedido: (sessionId, id) => ipcRenderer.invoke('pedidos:excluir', sessionId, id),

  // DASHBOARD
  obterDashboard: (sessionId) => ipcRenderer.invoke('dashboard:obter', sessionId),
  vendasMes: (sessionId) => ipcRenderer.invoke('dashboard:vendas-mes', sessionId),
  estoqueDashboard: (sessionId) => ipcRenderer.invoke('dashboard:estoque', sessionId),

  // BANNERS
  obterBanners: (sessionId) => ipcRenderer.invoke('banners:obter', sessionId),
  criarBanner: (sessionId, banner) => ipcRenderer.invoke('banners:criar', sessionId, banner),
  atualizarBanner: (sessionId, index, dados) => ipcRenderer.invoke('banners:atualizar', sessionId, index, dados),
  excluirBanner: (sessionId, index) => ipcRenderer.invoke('banners:excluir', sessionId, index),

  // CONFIGURAÇÕES
  alterarCredenciais: (sessionId, dados) => ipcRenderer.invoke('config:alterar-credenciais', sessionId, dados),
  obterConfig: (sessionId) => ipcRenderer.invoke('config:obter', sessionId),
  atualizarConfigApp: (sessionId, dados) => ipcRenderer.invoke('config:atualizar-app', sessionId, dados),
  atualizarConfigApi: (sessionId, dados) => ipcRenderer.invoke('config:atualizar-api', sessionId, dados),
  atualizarConfigLogs: (sessionId, dados) => ipcRenderer.invoke('config:atualizar-logs', sessionId, dados),
  atualizarConfigBackup: (sessionId, dados) => ipcRenderer.invoke('config:atualizar-backup', sessionId, dados),
  resetarConfig: (sessionId) => ipcRenderer.invoke('config:resetar', sessionId),

  // SINCRONIZAÇÃO DE PRODUTOS
  converterFileParaBase64: (sessionId, filePath) => ipcRenderer.invoke('sync:converterFileParaBase64', sessionId, filePath),
  sincronizarProdutoParaApi: (sessionId, produtoApi) => ipcRenderer.invoke('sync:sincronizarProdutoParaApi', sessionId, produtoApi),
  sincronizarTodosProdutos: (sessionId) => ipcRenderer.invoke('sync:sincronizarTodosProdutos', sessionId),
  verificarConexaoApi: (sessionId) => ipcRenderer.invoke('sync:verificarConexao', sessionId),

  // CATEGORIAS (para buscar ID por nome)
  listarCategorias: (sessionId) => ipcRenderer.invoke('categorias:listar', sessionId)
});
