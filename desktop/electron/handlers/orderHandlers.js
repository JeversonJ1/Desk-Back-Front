const { ipcMain } = require('electron');
const Database = require('../../database/database');
const Logger = require('../services/logger');
const Validator = require('../services/validator');
const { requireAuth } = require('./authHandlers');

function mapItensPedido(itens) {
  return (itens || []).map(item => ({
    id: item.id_item || item.id || item.id_itens_pedidos,
    produto_id: item.id_produto,
    quantidade: item.quantidade,
    preco_unitario: item.preco_unitario
  }));
}

async function mapPedidoParaUI(pedido) {
  const itens = await Database.itensPedido.listarPorPedido(pedido.id);

  return {
    id: pedido.id,
    cliente_id: pedido.cliente_id,
    itens: mapItensPedido(itens),
    total: Number(pedido.total),
    status: pedido.status,
    criadoEm: pedido.data_pedido || pedido.criado_em || pedido.criadoEm,
    atualizadoEm: pedido.atualizado_em || pedido.atualizadoEm,
    data: pedido.data_pedido || pedido.data || pedido.criado_em
  };
}

function registerOrderHandlers() {
  ipcMain.handle('pedidos:listar', requireAuth(async () => {
    try {
      // Listar do Banco Local (agora único banco real MySQL)
      const pedidos = await Database.pedidos.listar();
      const pedidosComItens = await Promise.all((pedidos || []).map(mapPedidoParaUI));
      Logger.log('Pedidos listados (Banco Local)');
      return pedidosComItens;
    } catch (error) {
      Logger.error('Erro ao listar pedidos', error.message);
      throw error;
    }
  }));

  ipcMain.handle('pedidos:criar', requireAuth(async (e, pedido) => {
    try {
      const validation = Validator.validateOrder(pedido);
      if (!validation.isValid) {
        throw new Error(`Dados inválidos: ${validation.errors.join(', ')}`);
      }

      const totalCalculado = Array.isArray(pedido.itens)
        ? pedido.itens.reduce((sum, item) => sum + (item.preco_unitario || 0) * item.quantidade, 0)
        : 0;
      const total = typeof pedido.total === 'number' && !Number.isNaN(pedido.total)
        ? pedido.total
        : totalCalculado;

      const pedidoCriado = await Database.pedidos.criar({
        cliente_id: pedido.cliente_id || null,
        itens: pedido.itens,
        total: parseFloat(total.toFixed(2))
      });

      // ✅ CORRIGIDO: Verificar estoque antes de subtrair
      if (Array.isArray(pedido.itens)) {
        const produtos = await Database.produtos.listar();
        const produtoMap = new Map((produtos || []).map(p => [p.id, p]));

        for (const item of pedido.itens) {
          const produtoId = item.produto_id || item.id_produto;
          const qtd = parseInt(item.quantidade) || 0;
          if (produtoId && qtd > 0) {
            const produto = produtoMap.get(produtoId);
            if (!produto || produto.estoque < qtd) {
              throw new Error(`Estoque insuficiente para o produto "${produto?.nome || produtoId}" (disponível: ${produto?.estoque || 0}, solicitado: ${qtd})`);
            }
            await Database.produtos.subtrairEstoque(produtoId, qtd);
            Logger.log(`Estoque subtraído: produto ${produtoId} - ${qtd} unidade(s)`);
          }
        }
      }

      const pedidoResposta = {
        id: pedidoCriado.id,
        cliente_id: pedidoCriado.cliente_id,
        itens: pedido.itens,
        total: pedidoCriado.total || total,
        status: pedidoCriado.status,
        criadoEm: pedidoCriado.data_pedido,
        atualizadoEm: pedidoCriado.data_pedido
      };

      Logger.log(`Pedido criado com sucesso. ID: ${pedidoCriado.id}`, { total: pedidoResposta.total, itens: pedidoResposta.itens.length });
      return { sucesso: true, id: pedidoCriado.id, pedido: pedidoResposta };
    } catch (error) {
      Logger.error('Erro ao criar pedido', error.message);
      throw error;
    }
  }));

  ipcMain.handle('pedidos:atualizar', requireAuth(async (e, pedido) => {
    try {
      if (!Validator._validate('id', pedido.id)) {
        throw new Error('ID do pedido inválido');
      }

      const pedidoAtualizado = await Database.pedidos.atualizar(pedido.id, {
        status: pedido.status
      });

      Logger.log(`Pedido atualizado. ID: ${pedido.id}`, { novoStatus: pedidoAtualizado.status });
      return { sucesso: true, pedido: pedidoAtualizado };
    } catch (error) {
      Logger.error('Erro ao atualizar pedido', error.message);
      throw error;
    }
  }));

  ipcMain.handle('pedidos:excluir', requireAuth(async (e, id) => {
    try {
      if (!Validator._validate('id', id)) {
        throw new Error('ID inválido');
      }

      await Database.pedidos.excluir(id);
      Logger.log(`Pedido excluído. ID: ${id}`);
      return { sucesso: true };
    } catch (error) {
      Logger.error('Erro ao excluir pedido', error.message);
      throw error;
    }
  }));
}

module.exports = {
  registerOrderHandlers
};
