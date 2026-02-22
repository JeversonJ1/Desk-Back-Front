const { ipcMain } = require('electron');
const fs = require('fs');
const path = require('path');
const Logger = require('../services/logger');
const ProdutoSyncService = require('../services/produtoSyncService');
const { requireAuth } = require('./authHandlers');

function registerSyncHandlers() {

    // =========================================================
    // CONVERTER ARQUIVO file:// PARA BASE64
    // =========================================================
    ipcMain.handle('sync:converterFileParaBase64', requireAuth(async (e, filePath) => {
        try {
            let cleanPath = filePath.replace(/^file:\/\/\//i, '').replace(/^file:\/\//i, '');
            try { cleanPath = decodeURIComponent(cleanPath); } catch (_) { }

            if (!fs.existsSync(cleanPath)) {
                Logger.warn(`Arquivo de imagem não encontrado: ${cleanPath}`);
                return null;
            }

            const buffer = fs.readFileSync(cleanPath);
            const ext = path.extname(cleanPath).substring(1).toLowerCase();
            const mimeMap = { jpg: 'image/jpeg', jpeg: 'image/jpeg', png: 'image/png', gif: 'image/gif', webp: 'image/webp' };
            const mimeType = mimeMap[ext] || 'image/jpeg';

            const base64 = `data:${mimeType};base64,${buffer.toString('base64')}`;
            Logger.log(`Arquivo convertido para Base64: ${path.basename(cleanPath)}`);
            return base64;
        } catch (error) {
            Logger.error('Erro ao converter file para Base64', error.message);
            return null;
        }
    }));

    // =========================================================
    // SINCRONIZAR UM PRODUTO ESPECÍFICO PARA API
    // Usado pelo botão "Sincronizar" na tela de Configurações
    // Payload esperado: objeto no formato da API
    // =========================================================
    ipcMain.handle('sync:sincronizarProdutoParaApi', requireAuth(async (e, produtoApi) => {
        try {
            Logger.log(`SyncHandler: Sincronizando produto "${produtoApi.nome_produtos}" via IPC...`);
            const resultado = await ProdutoSyncService.sincronizarUm(produtoApi);

            if (!resultado.sucesso) {
                throw new Error(resultado.erro || 'Falha ao sincronizar produto');
            }

            return { sucesso: true, data: resultado.data };
        } catch (error) {
            Logger.error('SyncHandler: Erro ao sincronizar produto', error.message);
            throw error;
        }
    }));

    // =========================================================
    // SINCRONIZAR TODOS OS PRODUTOS DO BANCO LOCAL → API
    // Rota: sync:sincronizarTodosProdutos
    // Retorna relatório: { total, enviados, erros, falhas }
    // =========================================================
    ipcMain.handle('sync:sincronizarTodosProdutos', requireAuth(async () => {
        try {
            Logger.log('SyncHandler: Iniciando sincronização em lote de todos os produtos...');

            const resultado = await ProdutoSyncService.sincronizarTodos();

            Logger.log(`SyncHandler: Sincronização concluída — ${resultado.enviados}/${resultado.total} produtos enviados.`);

            return {
                sucesso: true,
                total: resultado.total,
                enviados: resultado.enviados,
                erros: resultado.erros,
                falhas: resultado.falhas
            };
        } catch (error) {
            Logger.error('SyncHandler: Erro na sincronização em lote', error.message);
            throw error;
        }
    }));

    // =========================================================
    // VERIFICAR CONEXÃO COM A API REMOTA
    // Rota: sync:verificarConexao
    // =========================================================
    ipcMain.handle('sync:verificarConexao', requireAuth(async () => {
        try {
            const online = await ProdutoSyncService.verificarConexao();
            Logger.log(`SyncHandler: API remota ${online ? 'ONLINE ✅' : 'OFFLINE ❌'}`);
            return { online };
        } catch (error) {
            Logger.error('SyncHandler: Erro ao verificar conexão', error.message);
            return { online: false };
        }
    }));
}

module.exports = { registerSyncHandlers };
