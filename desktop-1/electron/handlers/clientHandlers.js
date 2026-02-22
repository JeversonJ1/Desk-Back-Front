const { ipcMain } = require('electron');
const Database = require('../../database/database');
const Validator = require('../services/validator');
const Logger = require('../services/logger');
const { requireAuth } = require('./authHandlers');

function registerClientHandlers() {
  ipcMain.handle('clientes:listar', requireAuth(async () => {
    try {
      const ApiService = require('../services/apiService');

      // 1. Sincronizar Clientes da API (se houver endpoint e table)
      try {
        const json = await ApiService.getClientes();
        // A API DatabaseController retorna { status: 'sucesso', dados: [...] }
        if (json.dados && Array.isArray(json.dados)) {
          Logger.log(`Encontrados ${json.dados.length} clientes na API. Sincronizando...`);
          for (const item of json.dados) {
            // O objeto deve ter id_cliente e outros campos.
            // Se vier como id, mapear para id_cliente se necessario?
            // DatabaseController faz "SELECT * FROM tbl_clientes", entao deve vir com nomes de colunas do DB remoto.
            // Assumindo que sao iguais ao local.
            await Database.clientes.sincronizar(item, false);
          }
          Database.salvar();
        }
      } catch (apiError) {
        Logger.warn('Falha ao sincronizar clientes com API (GET)', apiError.message);
      }

      // 2. Listar do Banco Local
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
      if (!id) throw new Error('ID inválido');
      await Database.clientes.excluir(id);
      Logger.log(`Cliente ID ${id} excluído com sucesso.`);
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
