const { ipcMain } = require('electron');
const Database = require('../../database/database');
const Logger = require('../services/logger');
const { requireAuth } = require('./authHandlers');

function mapBanner(banner) {
  return {
    id: banner.id,
    nome: banner.titulo || banner.nome,
    imagem: banner.imagem,
    link: banner.link || '',
    ordem: banner.ordem || 0,
    ativo: banner.ativo,
    dataCriacao: banner.criado_em || banner.dataCriacao,
    dataAtualizacao: banner.atualizado_em || banner.dataAtualizacao
  };
}

function registerBannersHandlers() {
  // Obter todos os banners
  ipcMain.handle('banners:obter', requireAuth(async () => {
    try {
      const banners = await Database.banners.listar();
      const bannersMapeados = (banners || []).map(mapBanner);
      Logger.log(`Banners carregados: ${bannersMapeados.length}`);
      return bannersMapeados;
    } catch (error) {
      Logger.error('Erro ao obter banners', error.message);
      throw error;
    }
  }));

  // Criar novo banner
  ipcMain.handle('banners:criar', requireAuth(async (event, banner) => {
    try {
      const novoBanner = await Database.banners.criar({
        titulo: banner.nome || banner.titulo,
        imagem: banner.imagem,
        link: banner.link || '',
        ordem: banner.ordem || 0,
        ativo: banner.ativo !== false
      });

      const bannerResposta = mapBanner({
        ...novoBanner,
        titulo: banner.nome || banner.titulo,
        criado_em: new Date().toISOString()
      });

      Logger.log(`Banner criado: ${bannerResposta.nome} (ID: ${bannerResposta.id})`);
      return bannerResposta;
    } catch (error) {
      Logger.error('Erro ao criar banner', error.message);
      throw error;
    }
  }));

  // ✅ CORRIGIDO: usar ID em vez de índice
  ipcMain.handle('banners:atualizar', requireAuth(async (event, id, dadosAtualizados) => {
    try {
      if (!id) throw new Error('ID do banner inválido');

      const bannerAtualizado = await Database.banners.atualizar(id, {
        titulo: dadosAtualizados.nome || dadosAtualizados.titulo,
        imagem: dadosAtualizados.imagem,
        link: dadosAtualizados.link || '',
        ordem: dadosAtualizados.ordem || 0,
        ativo: dadosAtualizados.ativo !== false ? 1 : 0
      });

      const bannerResposta = mapBanner({
        ...bannerAtualizado,
        titulo: dadosAtualizados.nome || dadosAtualizados.titulo,
        atualizado_em: new Date().toISOString()
      });

      Logger.log(`Banner atualizado: ID ${id} — ${bannerResposta.nome}`);
      return bannerResposta;
    } catch (error) {
      Logger.error('Erro ao atualizar banner', error.message);
      throw error;
    }
  }));

  // ✅ CORRIGIDO: usar ID em vez de índice
  ipcMain.handle('banners:excluir', requireAuth(async (event, id) => {
    try {
      if (!id) throw new Error('ID do banner inválido');

      await Database.banners.excluir(id);

      Logger.log(`Banner excluído: ID ${id}`);
      return { success: true };
    } catch (error) {
      Logger.error('Erro ao excluir banner', error.message);
      throw error;
    }
  }));

  Logger.log('Handlers de banners registrados');
}

module.exports = { registerBannersHandlers };
