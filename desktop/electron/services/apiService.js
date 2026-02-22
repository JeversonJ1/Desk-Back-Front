const config = require('../config/config');
const Logger = require('./logger');

class ApiService {
    constructor() {
        this.baseUrl = config.api.baseUrl;
        this.timeout = config.api.timeout || 5000;
    }

    /**
     * Helper interno para executar chamadas fetch com timeout e tratamento de erros
     */
    async _fetch(endpoint, options = {}) {
        const url = `${this.baseUrl}${endpoint}`;
        const controller = new AbortController();
        const id = setTimeout(() => controller.abort(), this.timeout);

        try {
            Logger.debug(`API Request: ${url}`);
            const response = await fetch(url, {
                ...options,
                signal: controller.signal
            });
            clearTimeout(id);

            if (!response.ok) {
                // Tenta ler erro do corpo ou usa status text
                throw new Error(`Erro API ${response.status}: ${response.statusText}`);
            }

            const json = await response.json();
            // console.log(json);
            return json;
        } catch (error) {
            clearTimeout(id);
            if (error.name === 'AbortError') {
                Logger.warn(`API Timeout: ${url}`);
                throw new Error(`Timeout na requisição para ${endpoint}`);
            }
            Logger.warn(`API Error (${endpoint}): ${error.message}`);
            // Retorna null ou lança, dependendo da estratégia. 
            // Aqui vamos lançar para quem chama decidir, mas logamos antes.
            throw error;
        }
    }

    // Métodos Específicos para cada Entidade

    async getUsuarios() {
        return this._fetch('/usuarios');
    }

    async getClientes() {
        return this._fetch('/api/database/clientes');
    }

    async getPerfis() {
        return this._fetch('/perfis');
    }

    async getCategorias() {
        return this._fetch('/categorias');
    }

    async getProdutos() {
        return this._fetch('/produtos');
    }

    async getImagens() {
        return this._fetch('/imagens');
    }

    async getCores() {
        return this._fetch('/cores');
    }

    async getEstoque() {
        return this._fetch('/estoque');
    }

    async getPedidos() {
        return this._fetch('/pedidos');
    }

    async getItensPedidos() {
        return this._fetch('/itens_pedidos'); // endpoint pode nao existir mas metodo fica pronto
    }

    // Métodos de Envio (POST)
    async createUsuario(usuarioData) {
        return this._fetch('/usuarios', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(usuarioData)
        });
    }

    async createCliente(clienteData) {
        return this._fetch('/clientes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(clienteData)
        });
    }

    async createProduto(produtoData) {
        return this._fetch('/produtos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(produtoData)
        });
    }

    async createPedido(pedidoData) {
        // Pedido structure may need adjustment depending on backend expectation (itens flat vs nested)
        // Backend 'PedidosController@salvarPedido' likely expects specific payload.
        return this._fetch('/pedidos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(pedidoData)
        });
    }

    async createEstoqueMovimentacao(movimentacaoData) {
        return this._fetch('/estoque', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(movimentacaoData)
        });
    }

    async deleteCliente(id) {
        return this._fetch(`/clientes/${id}`, {
            method: 'DELETE'
        });
    }

    async deleteProduto(id) {
        return this._fetch(`/produtos/${id}`, {
            method: 'DELETE'
        });
    }

    // Health Check
    async healthCheck() {
        return this._fetch('/health');
    }

    // Autenticação Desktop (endpoint JSON exclusivo)
    async authDesktop(email, senha) {
        return this._fetch('/auth/desktop', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, senha })
        });
    }

    // Banners
    async getBanners() {
        return this._fetch('/banners');
    }

    async createBanner(bannerData) {
        return this._fetch('/banners', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(bannerData)
        });
    }

    async updateBanner(id, bannerData) {
        return this._fetch(`/banners/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(bannerData)
        });
    }

    async deleteBanner(id) {
        return this._fetch(`/banners/${id}/deletar`, {
            method: 'POST'
        });
    }

    // Tamanhos
    async getTamanhos() {
        return this._fetch('/tamanhos');
    }

    async getTamanhosPorProduto(produtoId) {
        return this._fetch(`/tamanhos/${produtoId}`);
    }
}

module.exports = new ApiService();
