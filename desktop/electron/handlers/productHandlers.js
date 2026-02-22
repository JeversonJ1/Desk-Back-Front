const { ipcMain } = require('electron');
const Database = require('../../database/database');
const Validator = require('../services/validator');
const Logger = require('../services/logger');
const { requireAuth } = require('./authHandlers');

async function mapProdutosComCategorias(produtos) {
  const categorias = await Database.categorias.listar();
  const categoriasMap = new Map();

  // Mapear tanto pelo ID local quanto pelo ID da API
  (categorias || []).forEach(c => {
    if (c.id) categoriasMap.set(c.id, c.nome);
    if (c.id_categoria_api) categoriasMap.set(c.id_categoria_api, c.nome);
  });

  return (produtos || []).map(produto => ({
    ...produto,
    categoria: produto.categoria ? (categoriasMap.get(produto.categoria) || produto.categoria) : '',
    criadoEm: produto.criado_em || produto.criadoEm,
    atualizadoEm: produto.atualizado_em || produto.atualizadoEm
  }));
}

function registerProductHandlers() {
  ipcMain.handle('produtos:listar', requireAuth(async () => {
    try {
      // 1. Retornar imediatamente do MySQL (fonte de verdade)
      const produtos = await Database.produtos.listar();
      const produtosComCategorias = await mapProdutosComCategorias(produtos);
      Logger.log(`Produtos carregados do SQLite: ${produtos.length}`);

      // 2. Sincronização com API em background (best-effort, não bloqueia)
      setImmediate(async () => {
        try {
          const ApiService = require('../services/apiService');
          const jsonCat = await ApiService.getCategorias();
          if (jsonCat?.status === 'success' && Array.isArray(jsonCat.data)) {
            for (const cat of jsonCat.data) await Database.categorias.sincronizar(cat, false);
          }
        } catch (e) { /* Ignore: API offline */ }

        try {
          const ApiService = require('../services/apiService');
          const jsonProd = await ApiService.getProdutos();
          if (jsonProd?.status === 'success' && Array.isArray(jsonProd.data)) {
            Logger.log(`Sync background: ${jsonProd.data.length} produtos da API.`);
            for (const item of jsonProd.data) {
              await Database.produtos.sincronizar(item, false);
            }
          }
        } catch (e) { Logger.warn('Sync background falhou: ' + e.message); }
      });

      return produtosComCategorias;
    } catch (error) {
      Logger.error('Erro ao listar produtos', error.message);
      throw error;
    }
  }));

  ipcMain.handle('produtos:criar', requireAuth(async (e, produto) => {
    try {
      const validation = Validator.validateProduct(produto);
      if (!validation.isValid) {
        throw new Error(`Dados inválidos: ${validation.errors.join(', ')}`);
      }

      const categoriaTexto = produto.categoria?.trim() || '';
      const categoriaId = categoriaTexto ? await Database.categorias.buscarOuCriar(categoriaTexto) : null;

      const newProduct = await Database.produtos.criar({
        nome: produto.nome.trim(),
        descricao: produto.descricao || '',
        preco: parseFloat(produto.preco),
        estoque: parseInt(produto.estoque),
        imagem: produto.imagem || produto.caminho_imagem || null,
        categoria: categoriaId
      });

      const produtoResposta = {
        ...newProduct,
        categoria: categoriaTexto
      };

      Logger.log(`Produto criado com sucesso. ID: ${newProduct.id}`, { nome: produtoResposta.nome });
      return { sucesso: true, id: newProduct.id, produto: produtoResposta };
    } catch (error) {
      Logger.error('Erro ao criar produto', error.message);
      throw error;
    }
  }));

  ipcMain.handle('produtos:atualizar', requireAuth(async (e, produto) => {
    try {
      if (!Validator._validate('id', produto.id)) {
        throw new Error('ID do produto inválido');
      }

      const validation = Validator.validateProduct(produto);
      if (!validation.isValid) {
        throw new Error(`Dados inválidos: ${validation.errors.join(', ')}`);
      }

      const categoriaTexto = produto.categoria?.trim() || '';
      const categoriaId = categoriaTexto ? await Database.categorias.buscarOuCriar(categoriaTexto) : null;

      const produtoAtualizado = await Database.produtos.atualizar(produto.id, {
        nome: produto.nome.trim(),
        descricao: produto.descricao || '',
        preco: parseFloat(produto.preco),
        estoque: parseInt(produto.estoque),
        imagem: produto.imagem || produto.caminho_imagem || null,
        categoria: categoriaId
      });

      const produtoResposta = {
        ...produtoAtualizado,
        categoria: categoriaTexto
      };

      Logger.log(`Produto atualizado. ID: ${produto.id}`, { nome: produtoResposta.nome });
      return { sucesso: true, produto: produtoResposta };
    } catch (error) {
      Logger.error('Erro ao atualizar produto', error.message);
      throw error;
    }
  }));

  ipcMain.handle('produtos:excluir', requireAuth(async (e, id) => {
    try {
      if (!Validator._validate('id', id)) {
        throw new Error('ID inválido');
      }

      await Database.produtos.excluir(id);
      Logger.log(`Produto excluído. ID: ${id}`);
      return { sucesso: true };
    } catch (error) {
      Logger.error('Erro ao excluir produto', error.message);
      throw error;
    }
  }));

  // Handler para listar categorias (usado na sincronização)
  ipcMain.handle('categorias:listar', requireAuth(async () => {
    try {
      const categorias = await Database.categorias.listar();
      Logger.log('Categorias listadas');
      return categorias || [];
    } catch (error) {
      Logger.error('Erro ao listar categorias', error.message);
      throw error;
    }
  }));
}

module.exports = {
  registerProductHandlers
};
