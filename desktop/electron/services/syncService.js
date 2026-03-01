const fs = require('fs');
const path = require('path');
const Database = require('../../database/database');
const ApiService = require('./apiService');
const Logger = require('./logger');

class SyncService {
    constructor() {
        this.interval = null;
        this.isRunning = false;
        // Intervalo de 1 minuto
        this.checkIntervalMs = 60 * 1000;
    }

    saveBase64Image(base64String, prefix = 'img') {
        if (!base64String || typeof base64String !== 'string') return null;

        // Se já for um path ou URL, retorna como está (mas se for relativo 'img/', ajustar se necessário)
        if (!base64String.startsWith('data:image')) {
            return base64String;
        }

        try {
            // Extrair extensão e dados
            const matches = base64String.match(/^data:image\/([a-zA-Z0-9]+);base64,(.+)$/);
            if (!matches) return null;

            const ext = matches[1] === 'jpeg' ? 'jpg' : matches[1];
            const data = matches[2];

            // Diretório de armazenamento
            const storageDir = path.join(__dirname, '../../storage/images');
            if (!fs.existsSync(storageDir)) {
                fs.mkdirSync(storageDir, { recursive: true });
            }

            // Nome do arquivo
            const filename = `${prefix}_${Date.now()}_${Math.floor(Math.random() * 1000)}.${ext}`;
            const filePath = path.join(storageDir, filename);

            // Salvar arquivo
            fs.writeFileSync(filePath, data, 'base64');

            // Retorna caminho absoluto com protocolo file:// para o Electron
            return `file://${filePath.replace(/\\/g, '/')}`;
        } catch (error) {
            Logger.error('SyncService: Erro ao salvar imagem base64', error.message);
            return null;
        }
    }

    async downloadImage(url, prefix = 'img') {
        try {
            if (!url) return null;
            Logger.log(`Baixando imagem de URL: ${url}`);

            const response = await fetch(url);
            if (!response.ok) {
                Logger.warn(`SyncService: Erro ao baixar imagem: Status ${response.status} - URL: ${url}`);
                return null;
            }

            const buffer = await response.arrayBuffer();
            const data = Buffer.from(buffer);

            // Determine extension from content-type or url
            let ext = 'jpg'; // Default
            const contentType = response.headers.get('content-type');

            if (contentType) {
                if (contentType.includes('jpeg')) ext = 'jpg';
                else if (contentType.includes('png')) ext = 'png';
                else if (contentType.includes('gif')) ext = 'gif';
                else if (contentType.includes('webp')) ext = 'webp';
            } else if (url.lastIndexOf('.') > -1) {
                const potentialExt = url.split('.').pop().split('?')[0];
                if (potentialExt && potentialExt.length <= 4) ext = potentialExt;
            }

            const storageDir = path.join(__dirname, '../../storage/images');
            if (!fs.existsSync(storageDir)) {
                fs.mkdirSync(storageDir, { recursive: true });
            }

            const filename = `${prefix}_${Date.now()}_${Math.floor(Math.random() * 1000)}.${ext}`;
            const filePath = path.join(storageDir, filename);

            fs.writeFileSync(filePath, data);

            return `file://${filePath.replace(/\\/g, '/')}`;
        } catch (error) {
            Logger.error('SyncService: Erro ao baixar imagem da URL', error.message);
            return null;
        }
    }

    startAutoSync() {
        if (this.interval) return;

        Logger.log('Iniciando serviço de sincronização automática...');

        // Download inicial de dados
        this.downloadAllData();

        // Inicia loop de upload
        this.syncPendingData();
        this.interval = setInterval(() => this.syncPendingData(), this.checkIntervalMs);
    }

    stopAutoSync() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
            Logger.log('Serviço de sincronização automática parado.');
        }
    }

    async downloadAllData() {
        await this.downloadUsersFromApi();
        await this.downloadClientesFromApi();
        await this.downloadProdutosFromApi();
        await this.downloadPedidosFromApi();
        await this.downloadEstoqueFromApi(); // New
    }

    async syncPendingData() {
        if (this.isRunning) return;
        this.isRunning = true;

        try {
            await this.checkAndSendUsers();
            await this.checkAndSendClientes();
            await this.checkAndSendProdutos();
            await this.checkAndSendPedidos();
            await this.checkAndSendEstoque();
            await this.checkAndSendDeletions(); // New
        } catch (error) {
            Logger.error('SyncService: Erro geral no loop de sincronização', error.message);
        } finally {
            this.isRunning = false;
        }
    }

    // ... (keep existing user/client/product logic unchanged if not shown) ...


    // --- USERS ---

    async downloadUsersFromApi() {
        try {
            Logger.log('SyncService: Baixando usuários da API...');
            const json = await ApiService.getUsuarios();
            const isSuccess = json.status === 'success' || !json.status;

            if (isSuccess && Array.isArray(json.data)) {
                Logger.log(`SyncService: ${json.data.length} usuários encontrados.`);
                for (const item of json.data) {
                    await Database.usuarios.sincronizar(item, false);
                }
                Database.salvar();
            }
        } catch (error) {
            Logger.error('SyncService: Erro ao baixar usuários', error.message);
        }
    }

    async checkAndSendUsers() {
        const pendentes = await Database.usuarios.listarPendentes();
        if (pendentes && pendentes.length > 0) {
            Logger.log(`SyncService: Encontrados ${pendentes.length} usuários pendentes.`);
            for (const usuario of pendentes) {
                try {
                    const payload = {
                        nome_usuarios: usuario.nome_usuarios,
                        email_usuarios: usuario.email_usuarios,
                        nivel_acesso: usuario.nivel_acesso,
                        senha_usuarios: usuario.senha_usuarios,
                        foto_usuarios: usuario.foto_usuarios
                    };

                    Logger.log(`SyncService: Enviando usuário ${usuario.nome_usuarios}...`);
                    const response = await ApiService.createUsuario(payload);

                    if (response.status === 'success' || response.sucesso) {
                        const apiId = response.data ? (response.data.id_usuarios || response.data.id) : null;
                        const novoId = apiId || usuario.id_usuarios;
                        await Database.usuarios.marcarSincronizado(usuario.id_usuarios, novoId);
                        Logger.log(`SyncService: Usuário sincronizado (ID: ${novoId})`);
                    }
                } catch (err) {
                    Logger.error(`SyncService: Erro ao enviar usuário ${usuario.nome_usuarios}`, err.message);
                }
            }
        }
    }

    // --- CLIENTS ---

    async downloadClientesFromApi() {
        try {
            Logger.log('SyncService: Baixando clientes da API...');
            const json = await ApiService.getClientes();
            if (json.dados && Array.isArray(json.dados)) {
                Logger.log(`SyncService: ${json.dados.length} clientes encontrados.`);
                for (const item of json.dados) {
                    await Database.clientes.sincronizar(item, false);
                }
                Database.salvar();
            }
        } catch (error) {
            Logger.error('SyncService: Erro ao baixar clientes', error.message);
        }
    }

    async checkAndSendClientes() {
        const clientesPendentes = await Database.clientes.listarPendentes();
        if (clientesPendentes && clientesPendentes.length > 0) {
            Logger.log(`SyncService: Encontrados ${clientesPendentes.length} clientes pendentes.`);
            for (const cliente of clientesPendentes) {
                try {
                    Logger.log(`SyncService: Enviando cliente ${cliente.nome_clientes}...`);
                    const response = await ApiService.createCliente(cliente);

                    if (response.status === 'sucesso' || response.status === 'success' || response.sucesso) {
                        const apiId = response.id || (response.dados && response.dados.id) || (response.data && response.data.id);
                        const novoId = apiId || cliente.id_cliente;
                        await Database.clientes.marcarSincronizado(cliente.id_cliente, novoId);
                        Logger.log(`SyncService: Cliente sincronizado (ID: ${novoId})`);
                    } else {
                        Logger.warn(`SyncService: Falha ao enviar cliente. Status: ${response.status}`);
                    }
                } catch (err) {
                    Logger.error(`SyncService: Erro ao enviar cliente ${cliente.nome_clientes}`, err.message);
                }
            }
        }
    }

    // --- PRODUCTS ---

    async downloadProdutosFromApi() {
        try {
            Logger.log('SyncService: Baixando produtos da API...');
            const json = await ApiService.getProdutos();

            // Check for different response structures based on logs
            const produtosData = json.data || json.dados || (Array.isArray(json) ? json : []);

            if (produtosData && Array.isArray(produtosData)) {
                Logger.log(`SyncService: ${produtosData.length} produtos encontrados.`);

                for (const item of produtosData) {
                    try {
                        const id = item.id_produto || item.id;
                        let processedImage = false;

                        // Prioridade 1: Base64 já presente
                        if (item.caminho_imagem && typeof item.caminho_imagem === 'string' && item.caminho_imagem.startsWith('data:image')) {
                            Logger.log(`Salvando imagem base64 (caminho_imagem) para produto ${id}`);
                            const localPath = await this.saveBase64Image(item.caminho_imagem, `prod_${id}`);
                            if (localPath) {
                                item.imagem_produtos = localPath;
                                item.imagem = localPath;
                                processedImage = true;
                                Logger.log(`Imagem salva como: ${localPath}`);
                            }
                        } else if (item.imagem_produtos && typeof item.imagem_produtos === 'string' && item.imagem_produtos.startsWith('data:image')) {
                            Logger.log(`Salvando imagem base64 (imagem_produtos) para produto ${id}`);
                            const localPath = await this.saveBase64Image(item.imagem_produtos, `prod_${id}`);
                            if (localPath) {
                                item.imagem_produtos = localPath;
                                item.imagem = localPath;
                                processedImage = true;
                                Logger.log(`Imagem salva como: ${localPath}`);
                            }
                        }

                        // Prioridade 2: URL/Path - Download e salva como arquivo local
                        if (!processedImage) {
                            let imageUrl = null;

                            if (item.imagem_produtos && item.imagem_produtos.length > 5 && !item.imagem_produtos.startsWith('file://')) {
                                imageUrl = item.imagem_produtos;
                            } else if (item.caminho_imagem && item.caminho_imagem.length > 5 && !item.caminho_imagem.startsWith('file://')) {
                                imageUrl = item.caminho_imagem;
                            }

                            if (imageUrl) {
                                // Construir URL completa para download
                                let fullImageUrl = imageUrl;

                                if (!imageUrl.startsWith('http')) {
                                    // Se for path relativo (ex: "produtos/698b1ca4e86730.38470274.png")
                                    // Tentar construir URL absoluta
                                    const apiBase = ApiService.baseUrl || 'http://localhost:8000/backend/api';

                                    // Remover /api do final e adicionar /storage
                                    const baseUrl = apiBase.replace('/api', '');

                                    // Normalizar o path da imagem (remover prefixo "produtos/" se houver)
                                    let imagePath = imageUrl;
                                    if (imagePath.startsWith('produtos/')) {
                                        imagePath = imagePath.substring('produtos/'.length);
                                    }

                                    // Construir URL final: http://localhost:8000/backend/storage/produtos/{filename}
                                    fullImageUrl = `${baseUrl}/storage/produtos/${imagePath}`;
                                    Logger.log(`Convertido path relativo "${imageUrl}" para URL absoluta: ${fullImageUrl}`);
                                }

                                Logger.log(`Tentando baixar imagem de: ${fullImageUrl} (Prod ${id})`);
                                const localPath = await this.downloadImage(fullImageUrl, `prod_${id}`);

                                if (localPath) {
                                    item.imagem = localPath;
                                    item.imagem_produtos = localPath;
                                    processedImage = true;
                                    Logger.log(`✅ Imagem baixada e salva como: ${localPath}`);
                                } else {
                                    Logger.warn(`❌ Falha ao baixar imagem para Prod ${id} de: ${fullImageUrl}`);
                                }
                            }
                        }

                        await Database.produtos.sincronizar(item, false);
                    } catch (err) {
                        Logger.error(`Erro ao processar produto ${item.id}`, err.message);
                    }
                }
                Database.salvar();
            } else {
                Logger.warn('SyncService: Formato de resposta de produtos inesperado ou vazio');
            }
        } catch (error) {
            Logger.error('SyncService: Erro ao baixar produtos', error.message);
        }
    }

    async saveBase64Image(base64String, prefix = 'img') {
        try {
            if (!base64String || !base64String.startsWith('data:image')) return null;

            const parts = base64String.split(';');
            const contentType = parts[0].split(':')[1];
            const base64Data = parts[1].split(',')[1];

            let ext = 'jpg';
            if (contentType.includes('jpeg')) ext = 'jpg';
            else if (contentType.includes('png')) ext = 'png';
            else if (contentType.includes('gif')) ext = 'gif';
            else if (contentType.includes('webp')) ext = 'webp';

            const buffer = Buffer.from(base64Data, 'base64');

            const storageDir = path.join(__dirname, '../../storage/images');
            if (!fs.existsSync(storageDir)) {
                fs.mkdirSync(storageDir, { recursive: true });
            }

            const filename = `${prefix}_${Date.now()}_${Math.floor(Math.random() * 1000)}.${ext}`;
            const filePath = path.join(storageDir, filename);

            fs.writeFileSync(filePath, buffer);

            return `file://${filePath.replace(/\\/g, '/')}`;
        } catch (error) {
            Logger.error('SyncService: Erro ao salvar imagem Base64', error.message);
            return null;
        }
    }

    async downloadImage(url, prefix = 'img') {
        try {
            if (!url) return null;

            const response = await fetch(url);
            if (!response.ok) {
                Logger.warn(`SyncService: Erro ao baixar imagem: Status ${response.status} - URL: ${url}`);
                return null;
            }

            const buffer = await response.arrayBuffer();
            const data = Buffer.from(buffer);

            // Determine extension from content-type or url
            let ext = 'jpg';
            const contentType = response.headers.get('content-type');

            if (contentType) {
                if (contentType.includes('jpeg')) ext = 'jpg';
                else if (contentType.includes('png')) ext = 'png';
                else if (contentType.includes('gif')) ext = 'gif';
                else if (contentType.includes('webp')) ext = 'webp';
            } else if (url.lastIndexOf('.') > -1) {
                const potentialExt = url.split('.').pop().split('?')[0];
                if (potentialExt && potentialExt.length <= 4) ext = potentialExt;
            }

            const storageDir = path.join(__dirname, '../../storage/images');
            if (!fs.existsSync(storageDir)) {
                fs.mkdirSync(storageDir, { recursive: true });
            }

            const filename = `${prefix}_${Date.now()}_${Math.floor(Math.random() * 1000)}.${ext}`;
            const filePath = path.join(storageDir, filename);

            fs.writeFileSync(filePath, data);

            return `file://${filePath.replace(/\\/g, '/')}`;
        } catch (error) {
            Logger.error('SyncService: Erro ao baixar imagem da URL', error.message);
            return null;
        }
    }

    async checkAndSendProdutos() {
        const produtosPendentes = await Database.produtos.listarPendentes();
        if (produtosPendentes && produtosPendentes.length > 0) {
            Logger.log(`SyncService: Encontrados ${produtosPendentes.length} produtos pendentes.`);
            for (const prod of produtosPendentes) {
                try {
                    Logger.log(`SyncService: Enviando produto ${prod.nome}...`);
                    // Map fields for API
                    const payload = {
                        nome_produtos: prod.nome,
                        descricao_produtos: prod.descricao,
                        preco_produtos: prod.preco,
                        estoque_produtos: prod.estoque,
                        imagem_produtos: prod.imagem,
                        id_categoria: prod.categoria
                    };

                    const response = await ApiService.createProduto(payload);

                    if (response.status === 'sucesso' || response.status === 'success' || response.sucesso) {
                        const apiId = response.id || (response.dados && response.dados.id) || (response.data && response.data.id);
                        if (apiId) {
                            await Database.produtos.marcarSincronizado(prod.id, apiId);
                            Logger.log(`SyncService: Produto sincronizado (ID: ${apiId})`);
                        }
                    } else {
                        Logger.warn(`SyncService: Falha ao enviar produto. Status: ${response.status}`);
                    }
                } catch (err) {
                    Logger.error(`SyncService: Erro ao enviar produto ${prod.nome}`, err.message);
                }
            }
        }
    }
    // --- ORDERS ---

    async downloadPedidosFromApi() {
        try {
            Logger.log('SyncService: Baixando pedidos e itens...');
            const json = await ApiService.getPedidos();
            if (json.dados && Array.isArray(json.dados)) {
                Logger.log(`SyncService: ${json.dados.length} pedidos encontrados.`);
                for (const item of json.dados) {
                    await Database.pedidos.sincronizar(item, false);
                }
                Database.salvar();
            }

            try {
                const jsonItens = await ApiService.getItensPedidos();
                if (jsonItens.status === 'success' && Array.isArray(jsonItens.data)) {
                    for (const item of jsonItens.data) {
                        await Database.itensPedido.sincronizar(item, false);
                    }
                    Database.salvar();
                }
            } catch (e) {
                // Ignore if endpoint fails or doesn't exist yet
            }

        } catch (error) {
            Logger.error('SyncService: Erro ao baixar pedidos', error.message);
        }
    }

    async checkAndSendPedidos() {
        const pedidosPendentes = await Database.pedidos.listarPendentes();
        if (pedidosPendentes && pedidosPendentes.length > 0) {
            Logger.log(`SyncService: Encontrados ${pedidosPendentes.length} pedidos pendentes.`);
            for (const p of pedidosPendentes) {
                try {
                    Logger.log(`SyncService: Enviando pedido ${p.id_pedido}...`);
                    // Map fields
                    const payload = {
                        id_cliente: p.id_perfil, // map perfil to client
                        data_pedido: p.data_pedido,
                        total_pedido: p.total_pedido,
                        status_pedido: p.status_pedido,
                        itens: p.itens ? p.itens.map(i => ({
                            id_produto: i.id_produto,
                            quantidade: i.quantidade,
                            preco_unitario: i.preco_unitario
                        })) : []
                    };

                    const response = await ApiService.createPedido(payload);

                    if (response.status === 'sucesso' || response.status === 'success' || response.sucesso) {
                        const apiId = response.id || (response.dados && response.dados.id) || (response.data && response.data.id);
                        if (apiId) {
                            await Database.pedidos.marcarSincronizado(p.id_pedido, apiId);
                            Logger.log(`SyncService: Pedido sincronizado (ID: ${apiId})`);
                        }
                    } else {
                        Logger.warn(`SyncService: Falha ao enviar pedido ${p.id_pedido}. Status: ${response.status}`);
                    }
                } catch (err) {
                    Logger.error(`SyncService: Erro ao enviar pedido ${p.id_pedido}`, err.message);
                }
            }
        }
    }
    // --- STOCK MOVEMENTS ---

    async downloadEstoqueFromApi() {
        try {
            Logger.log('SyncService: Baixando movimentações de estoque...');
            const json = await ApiService.getEstoque();
            if (json.dados && Array.isArray(json.dados)) {
                Logger.log(`SyncService: ${json.dados.length} movimentações encontradas.`);
                for (const item of json.dados) {
                    await Database.estoque.sincronizar(item, false);
                }
                Database.salvar();
            }
        } catch (error) {
            Logger.error('SyncService: Erro ao baixar estoque', error.message);
        }
    }

    async checkAndSendEstoque() {
        const estoquePendente = await Database.estoque.listarPendentes();
        if (estoquePendente && estoquePendente.length > 0) {
            Logger.log(`SyncService: Encontrados ${estoquePendente.length} movimentações de estoque pendentes.`);
            for (const mov of estoquePendente) {
                try {
                    Logger.log(`SyncService: Enviando movimentação ${mov.id_estoque_movimentacao}...`);
                    // Map fields
                    const payload = {
                        id_produto: mov.id_produto,
                        descricao_estoque_movimentacao: mov.descricao_estoque_movimentacao,
                        quantidade_estoque_movimentacao: mov.quantidade_estoque_movimentacao,
                        data_estoque_movimentacao: mov.data_estoque_movimentacao,
                        tipo_estoque_movimentacao: mov.tipo_estoque_movimentacao
                    };

                    const response = await ApiService.createEstoqueMovimentacao(payload);

                    if (response.status === 'sucesso' || response.status === 'success' || response.sucesso) {
                        const apiId = response.id || (response.dados && response.dados.id) || (response.data && response.data.id);
                        const novoId = apiId || mov.id_estoque_movimentacao;
                        await Database.estoque.marcarSincronizado(mov.id_estoque_movimentacao, novoId);
                        Logger.log(`SyncService: Movimentação sincronizada (ID: ${novoId})`);
                    } else {
                        Logger.warn(`SyncService: Falha ao enviar movimentação. Status: ${response.status}`);
                    }
                } catch (err) {
                    Logger.error(`SyncService: Erro ao enviar movimentação`, err.message);
                }
            }
        }
    }

    async checkAndSendDeletions() {
        // Produtos
        if (Database.produtos.listarPendentesDelecao) {
            const produtosDeletados = await Database.produtos.listarPendentesDelecao();
            if (produtosDeletados && produtosDeletados.length > 0) {
                Logger.log(`SyncService: Encontrados ${produtosDeletados.length} produtos para exclusão.`);
                for (const prod of produtosDeletados) {
                    try {
                        if (prod.id_produto_api) {
                            Logger.log(`SyncService: Excluindo produto remoto ${prod.id_produto_api}...`);
                            await ApiService.deleteProduto(prod.id_produto_api);
                        }
                        // Marca como sincronizado (após excluir na API, mantemos deletado localmente mas sincronizado)
                        await Database.produtos.marcarSincronizado(prod.id, prod.id_produto_api || prod.id);
                    } catch (e) {
                        Logger.error(`SyncService: Erro ao excluir produto ${prod.nome}`, e.message);
                    }
                }
            }
        }
    }
}

module.exports = new SyncService();
