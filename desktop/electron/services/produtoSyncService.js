const fs = require('fs');
const path = require('path');
const Database = require('../../database/database');
const Logger = require('./logger');

// URL do endpoint remoto conforme definido pelo usuário
const API_URL = 'http://localhost:8000/backend/api/produtos';
const TIMEOUT_MS = 30000; // 30 segundos por requisição

/**
 * ProdutoSyncService
 * Módulo dedicado para sincronizar produtos do banco local para a API remota.
 *
 * Formato esperado pela API:
 * {
 *   nome_produtos: string,
 *   descricao_produtos: string,
 *   preco_produtos: number,
 *   estoque_produtos: number,
 *   id_categoria: number | null,
 *   imagem_produtos: string (Base64 ou URL)
 * }
 */
class ProdutoSyncService {

    // =========================================================
    // HELPERS INTERNOS
    // =========================================================

    /**
     * Faz uma requisição POST para a API remota com timeout configurado.
     * @param {object} payload - Dados do produto no formato da API
     * @returns {Promise<object>} Resposta da API
     */
    async _postParaApi(payload) {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), TIMEOUT_MS);

        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload),
                signal: controller.signal
            });

            clearTimeout(timeoutId);

            // Tenta parsear o JSON da resposta
            let data;
            try {
                data = await response.json();
            } catch {
                data = { status: response.ok ? 'success' : 'error', message: response.statusText };
            }

            if (!response.ok) {
                const msg = data?.message || data?.error || `HTTP ${response.status}: ${response.statusText}`;
                throw new Error(msg);
            }

            return data;
        } catch (error) {
            clearTimeout(timeoutId);
            if (error.name === 'AbortError') {
                throw new Error(`Timeout após ${TIMEOUT_MS / 1000}s. Verifique se a API está online.`);
            }
            throw error;
        }
    }

    /**
     * Converte imagem file:// para Base64.
     * Retorna null se o arquivo não existir ou em caso de erro.
     * @param {string} filePath - Caminho com protocolo file://
     * @returns {string|null}
     */
    _fileParaBase64(filePath) {
        try {
            if (!filePath || typeof filePath !== 'string') return null;

            // Já é Base64, retorna como está
            if (filePath.startsWith('data:image')) return filePath;

            // Remove protocolo file:// e decodifica URI
            let cleanPath = filePath
                .replace(/^file:\/\/\//i, '')
                .replace(/^file:\/\//i, '');
            try { cleanPath = decodeURIComponent(cleanPath); } catch (_) { }

            if (!fs.existsSync(cleanPath)) {
                Logger.warn(`ProdutoSync: Arquivo de imagem não encontrado: ${cleanPath}`);
                return null;
            }

            const buffer = fs.readFileSync(cleanPath);
            const ext = path.extname(cleanPath).substring(1).toLowerCase();
            const mimeMap = { jpg: 'image/jpeg', jpeg: 'image/jpeg', png: 'image/png', gif: 'image/gif', webp: 'image/webp' };
            const mimeType = mimeMap[ext] || 'image/jpeg';

            return `data:${mimeType};base64,${buffer.toString('base64')}`;
        } catch (err) {
            Logger.error('ProdutoSync: Erro ao converter file para Base64', err.message);
            return null;
        }
    }

    /**
     * Mapeia um produto do banco local para o formato esperado pela API.
     * Converte imagem file:// → Base64 automaticamente.
     * @param {object} produto - Produto do banco local
     * @param {Map} categoriasMap - Mapa de nome de categoria → id_categoria_api
     * @returns {object} Payload no formato da API
     */
    _mapearParaApi(produto, categoriasMap = new Map()) {
        // Resolver id_categoria: se o campo categoria for número, usa direto.
        // Se for texto (nome), busca no mapa de categorias.
        let idCategoria = null;
        if (produto.categoria !== null && produto.categoria !== undefined) {
            if (typeof produto.categoria === 'number') {
                idCategoria = produto.categoria;
            } else if (typeof produto.categoria === 'string') {
                idCategoria = categoriasMap.get(produto.categoria.toLowerCase()) || null;
            }
        }

        // Resolver imagem
        let imagemFinal = null;
        const imgField = produto.imagem || produto.imagem_produtos || produto.caminho_imagem;
        if (imgField) {
            if (imgField.startsWith('file://')) {
                imagemFinal = this._fileParaBase64(imgField);
            } else {
                // URL ou Base64 — envia como está
                imagemFinal = imgField;
            }
        }

        return {
            nome_produtos: String(produto.nome || produto.nome_produtos || '').trim(),
            descricao_produtos: String(produto.descricao || produto.descricao_produtos || ''),
            preco_produtos: parseFloat(produto.preco || produto.preco_produtos || 0),
            estoque_produtos: parseInt(produto.estoque || produto.estoque_produtos || 0),
            id_categoria: idCategoria,
            imagem_produtos: imagemFinal
        };
    }

    // =========================================================
    // MÉTODOS PÚBLICOS
    // =========================================================

    /**
     * Sincroniza UM produto específico para a API remota.
     * @param {object} produto - Produto do banco local
     * @param {Map} [categoriasMap] - Mapa opcional de categorias
     * @returns {Promise<{sucesso: boolean, data?: object, erro?: string}>}
     */
    async sincronizarUm(produto, categoriasMap = new Map()) {
        try {
            const payload = this._mapearParaApi(produto, categoriasMap);

            Logger.log(`ProdutoSync: Enviando "${payload.nome_produtos}" → ${API_URL}`);
            const resposta = await this._postParaApi(payload);

            // Aceita múltiplos formatos de resposta de sucesso
            const sucesso = resposta?.status === 'success'
                || resposta?.status === 'sucesso'
                || resposta?.sucesso === true
                || resposta?.id !== undefined
                || resposta?.id_produto !== undefined;

            if (sucesso) {
                const apiId = resposta?.id || resposta?.id_produto || resposta?.data?.id;
                Logger.log(`ProdutoSync: ✅ "${payload.nome_produtos}" sincronizado (API ID: ${apiId || '?'})`);
                return { sucesso: true, data: resposta, apiId };
            } else {
                const msg = resposta?.message || resposta?.mensagem || 'Resposta inesperada da API';
                Logger.warn(`ProdutoSync: ⚠️ Falha ao enviar "${payload.nome_produtos}": ${msg}`);
                return { sucesso: false, erro: msg };
            }
        } catch (err) {
            Logger.error(`ProdutoSync: ❌ Erro ao enviar produto "${produto.nome}"`, err.message);
            return { sucesso: false, erro: err.message };
        }
    }

    /**
     * Sincroniza TODOS os produtos do banco local para a API.
     * Retorna relatório completo com progresso.
     *
     * @param {function} [onProgresso] - Callback opcional(atual, total, nomeProduto)
     * @returns {Promise<{total, enviados, erros, falhas: Array}>}
     */
    async sincronizarTodos(onProgresso = null) {
        Logger.log('ProdutoSync: ============ INÍCIO DA SINCRONIZAÇÃO ============');

        const resultado = {
            total: 0,
            enviados: 0,
            erros: 0,
            falhas: []  // [{nome, erro}]
        };

        try {
            // 1. Buscar todos os produtos do banco local
            const produtos = await Database.produtos.listar();

            if (!produtos || produtos.length === 0) {
                Logger.warn('ProdutoSync: Nenhum produto encontrado no banco local.');
                return resultado;
            }

            resultado.total = produtos.length;
            Logger.log(`ProdutoSync: ${resultado.total} produtos encontrados no banco local.`);

            // 2. Buscar categorias para resolver nomes → IDs
            let categoriasMap = new Map();
            try {
                const categorias = await Database.categorias.listar();
                (categorias || []).forEach(c => {
                    if (c.nome && c.id_categoria_api) {
                        categoriasMap.set(c.nome.toLowerCase(), c.id_categoria_api);
                    }
                    if (c.nome && c.id) {
                        categoriasMap.set(c.nome.toLowerCase(), c.id);
                    }
                });
                Logger.log(`ProdutoSync: ${categoriasMap.size} categorias carregadas.`);
            } catch (err) {
                Logger.warn(`ProdutoSync: Não foi possível carregar categorias: ${err.message}`);
            }

            // 3. Enviar produto por produto
            for (let i = 0; i < produtos.length; i++) {
                const produto = produtos[i];
                const nomeProduto = produto.nome || produto.nome_produtos || `ID: ${produto.id}`;

                Logger.log(`ProdutoSync: [${i + 1}/${resultado.total}] Processando "${nomeProduto}"...`);

                // Notificar progresso (para UI)
                if (typeof onProgresso === 'function') {
                    onProgresso(i + 1, resultado.total, nomeProduto);
                }

                const res = await this.sincronizarUm(produto, categoriasMap);

                if (res.sucesso) {
                    resultado.enviados++;
                    // Marca como sincronizado no banco local se tiver ID da API
                    if (res.apiId && Database.produtos.marcarSincronizado) {
                        try {
                            await Database.produtos.marcarSincronizado(produto.id, res.apiId);
                        } catch (_) { /* Seguro ignorar */ }
                    }
                } else {
                    resultado.erros++;
                    resultado.falhas.push({ nome: nomeProduto, erro: res.erro });
                }
            }

        } catch (err) {
            Logger.error('ProdutoSync: Erro geral durante sincronização', err.message);
            resultado.falhas.push({ nome: 'GERAL', erro: err.message });
        }

        Logger.log(`ProdutoSync: ============ FIM DA SINCRONIZAÇÃO ============`);
        Logger.log(`ProdutoSync: Total: ${resultado.total} | ✅ Enviados: ${resultado.enviados} | ❌ Erros: ${resultado.erros}`);

        return resultado;
    }

    /**
     * Verifica se a API remota está acessível.
     * @returns {Promise<boolean>}
     */
    async verificarConexao() {
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 5000);

            // Tenta um GET no endpoint base da API
            const baseUrl = API_URL.replace('/produtos', '');
            const response = await fetch(baseUrl, { signal: controller.signal });
            clearTimeout(timeoutId);

            return response.status < 500;
        } catch {
            return false;
        }
    }
}

module.exports = new ProdutoSyncService();
