const { ipcMain } = require('electron');
const Database = require('../../database/database');
const Validator = require('../services/validator');
const Logger = require('../services/logger');
const { requireAuth } = require('./authHandlers');

function registerClientHandlers() {
  ipcMain.handle('clientes:listar', requireAuth(async () => {
    try {
      // Listar do Banco Local (MySQL direto)
      const clientes = await Database.clientes.listar();
      return clientes.map(c => ({
        ...c,
        id: c.id_cliente // Compatibilidade frontend
      }));
    } catch (error) {
      Logger.error('Erro ao listar clientes', error.message);
      throw error;
    }
  }));

  ipcMain.handle('clientes:criar', requireAuth(async (e, cliente) => {
    try {
      // ✅ CORRIGIDO: usar null para deixar o SQLite gerar o ID por AUTOINCREMENT
      const novoCliente = {
        id_cliente: null,
        nome_clientes: cliente.nome_clientes,
        email_clientes: cliente.email_clientes,
        telefone_clientes: cliente.telefone_clientes,
        cpf_cnpj_clientes: cliente.cpf_cnpj_clientes,
        data_nascimento_clientes: cliente.data_nascimento_clientes,
        cep_clientes: cliente.cep_clientes,
        logradouro_clientes: cliente.logradouro_clientes || cliente.endereco_clientes, // Frontend usa endereco ou logradouro? html ids: endereco
        numero_clientes: cliente.numero_clientes,
        bairro_clientes: cliente.bairro_clientes,
        cidade_clientes: cliente.cidade_clientes,
        observacoes_clientes: cliente.observacoes_clientes,
        sincronizado: 0
      };

      await Database.clientes.criar(novoCliente);

      Logger.log(`Cliente criado localmente. ID: ${novoCliente.id_cliente}`);
      return { sucesso: true, id: novoCliente.id_cliente, cliente: novoCliente };
    } catch (error) {
      Logger.error('Erro ao criar cliente', error.message);
      throw error;
    }
  }));

  ipcMain.handle('clientes:atualizar', requireAuth(async (e, cliente) => {
    try {
      if (!cliente.id && !cliente.id_cliente) throw new Error('ID inválido');

      const dadosAtualizados = {
        ...cliente,
        id_cliente: cliente.id || cliente.id_cliente,
        sincronizado: 0 // Marcar para re-sync se editado
      };

      // Ajuste campos se necessario (ex: logradouro vs endereco)
      if (cliente.endereco_clientes) dadosAtualizados.logradouro_clientes = cliente.endereco_clientes;

      await Database.clientes.sincronizar(dadosAtualizados);
      return { sucesso: true };
    } catch (error) {
      Logger.error('Erro ao atualizar cliente', error.message);
      throw error;
    }
  }));

  ipcMain.handle('clientes:excluir', requireAuth(async (e, id) => {
    try {
      const parsedId = parseInt(id, 10);
      if (isNaN(parsedId) || parsedId <= 0) {
        throw new Error('ID inválido');
      }
      await Database.clientes.excluir(parsedId);
      Logger.log(`Cliente ID ${parsedId} excluído com sucesso.`);
      return { sucesso: true };
    } catch (error) {
      Logger.error('Erro ao excluir cliente', error.message);
      throw error;
    }
  }));
}

module.exports = {
  registerClientHandlers
};
