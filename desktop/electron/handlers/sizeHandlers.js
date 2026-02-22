const { ipcMain } = require('electron');
const Database = require('../../database/database');
const Logger = require('../services/logger');
const { requireAuth } = require('./authHandlers');

function mapTamanho(tamanho) {
  return {
    id: tamanho.id_tamanhos || tamanho.id,
    produto_id: tamanho.id_produto,
    tamanho: tamanho.tamanho_tamanhos || tamanho.tamanho,
    quantidade: tamanho.quantidade_tamanhos ?? tamanho.quantidade
  };
}

function registerSizeHandlers() {
  ipcMain.handle('tamanhos:listar', requireAuth(async (e, produtoId) => {
    try {
      const tamanhos = await Database.tamanhos.listarPorProduto(produtoId);
      const tamanhosMapeados = (tamanhos || []).map(mapTamanho);
      Logger.log(`Tamanhos listados para produto ${produtoId}`);
      return tamanhosMapeados;
    } catch (error) {
      Logger.error('Erro ao listar tamanhos', error.message);
      throw error;
    }
  }));

  ipcMain.handle('tamanhos:listar-todos', requireAuth(async () => {
    try {
      const tamanhos = await Database.tamanhos.listar();
      const tamanhosMapeados = (tamanhos || []).map(mapTamanho);
      Logger.log('Tamanhos listados em lote');
      return tamanhosMapeados;
    } catch (error) {
      Logger.error('Erro ao listar tamanhos em lote', error.message);
      throw error;
    }
  }));

  ipcMain.handle('tamanhos:criar', requireAuth(async (e, tamanho) => {
    try {
      const novoTamanho = await Database.tamanhos.criar({
        id_produto: tamanho.produto_id,
        tamanho: tamanho.tamanho,
        quantidade: parseInt(tamanho.quantidade) || 0
      });

      const tamanhoResposta = mapTamanho(novoTamanho);
      Logger.log(`Tamanho criado. ID: ${tamanhoResposta.id}, Produto: ${tamanho.produto_id}`);
      return { sucesso: true, id: tamanhoResposta.id, tamanho: tamanhoResposta };
    } catch (error) {
      Logger.error('Erro ao criar tamanho', error.message);
      throw error;
    }
  }));

  ipcMain.handle('tamanhos:excluir', requireAuth(async (e, id) => {
    try {
      await Database.tamanhos.excluir(id);
      Logger.log(`Tamanho excluído. ID: ${id}`);
      return { sucesso: true };
    } catch (error) {
      Logger.error('Erro ao excluir tamanho', error.message);
      throw error;
    }
  }));

  ipcMain.handle('tamanhos:excluir-por-produto', requireAuth(async (e, produtoId) => {
    try {
      await Database.tamanhos.excluirPorProduto(produtoId);
      Logger.log(`Tamanhos excluídos do produto ${produtoId}`);
      return { sucesso: true };
    } catch (error) {
      Logger.error('Erro ao excluir tamanhos por produto', error.message);
      throw error;
    }
  }));
}

module.exports = {
  registerSizeHandlers
};
