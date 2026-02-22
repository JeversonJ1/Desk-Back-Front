const { ipcMain } = require('electron');
const Database = require('../../database/database');
const Logger = require('../services/logger');
const { requireAuth } = require('./authHandlers');

function calcularValorEstoque(produtos) {
  return (produtos || []).reduce((sum, produto) => {
    const preco = Number(produto.preco) || 0;
    const estoque = Number(produto.estoque) || 0;
    return sum + (preco * estoque);
  }, 0);
}

function registerDashboardHandlers() {
  ipcMain.handle('dashboard:obter', requireAuth(async () => {
    try {
      const produtos = await Database.produtos.listar();
      const pedidos = await Database.pedidos.listar();
      const clientes = await Database.clientes.listar();

      const dashboard = {
        totalProdutos: produtos ? produtos.length : 0,
        estoqueTotal: produtos ? produtos.reduce((sum, p) => sum + (Number(p.estoque) || 0), 0) : 0,
        totalPedidos: pedidos ? pedidos.length : 0,
        totalClientes: clientes ? clientes.length : 0,
        valorEstoque: calcularValorEstoque(produtos)
      };

      Logger.log('Dashboard carregado');
      return dashboard;
    } catch (error) {
      Logger.error('Erro ao obter dashboard', error.message);
      throw error;
    }
  }));

  ipcMain.handle('dashboard:vendas-mes', requireAuth(async () => {
    try {
      const pedidos = await Database.pedidos.listar();
      const hoje = new Date();
      const mesAtual = hoje.getMonth();
      const anoAtual = hoje.getFullYear();

      const pedidosMes = (pedidos || []).filter(pedido => {
        const dataPedido = new Date(pedido.data_pedido || pedido.criado_em || pedido.data);
        return dataPedido.getMonth() === mesAtual && dataPedido.getFullYear() === anoAtual;
      });

      const resultado = {
        totalVendas: pedidosMes.length,
        valorTotal: pedidosMes.reduce((sum, p) => sum + (Number(p.total) || 0), 0)
      };

      Logger.log('Vendas do mês calculadas');
      return resultado;
    } catch (error) {
      Logger.error('Erro ao obter vendas do mês', error.message);
      throw error;
    }
  }));

  ipcMain.handle('dashboard:estoque', requireAuth(async () => {
    try {
      const produtos = await Database.produtos.listar();
      const resultado = {
        totalProdutos: produtos ? produtos.length : 0,
        totalEstoque: produtos ? produtos.reduce((sum, p) => sum + (Number(p.estoque) || 0), 0) : 0,
        valorTotal: calcularValorEstoque(produtos)
      };

      Logger.log('Estoque calculado');
      return resultado;
    } catch (error) {
      Logger.error('Erro ao obter estoque', error.message);
      throw error;
    }
  }));
}

module.exports = {
  registerDashboardHandlers
};
