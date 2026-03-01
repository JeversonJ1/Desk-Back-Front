// =============================================================
// mysql-database.cjs — Implementa a mesma interface do sqlite-database.cjs
// usando MySQL via mysql2/promise (pool de conexão assíncrono)
// =============================================================
const pool = require('./mysql-connection');

const Database = {

    //
    // USUARIOS
    //
    usuarios: {
        async listar() {
            const [rows] = await pool.query(
                "SELECT * FROM tbl_usuarios WHERE excluido_em IS NULL ORDER BY nome_usuarios ASC"
            );
            return rows.map(mapUsuario);
        },
        async buscarPorEmail(email) {
            const [rows] = await pool.query(
                "SELECT * FROM tbl_usuarios WHERE email_usuarios = ? AND excluido_em IS NULL LIMIT 1",
                [email]
            );
            return rows.length ? mapUsuario(rows[0]) : null;
        },
        async buscarPorId(id) {
            const [rows] = await pool.query(
                "SELECT * FROM tbl_usuarios WHERE id_usuarios = ? LIMIT 1",
                [id]
            );
            return rows.length ? mapUsuario(rows[0]) : null;
        },
        async criar(usuario) {
            const [result] = await pool.query(
                `INSERT INTO tbl_usuarios (nome_usuarios, email_usuarios, senha_usuarios, nivel_acesso, criado_em)
                 VALUES (?, ?, ?, ?, NOW())`,
                [usuario.nome, usuario.email, usuario.senha, usuario.tipo || usuario.nivel_acesso || 'admin']
            );
            return { id: result.insertId };
        },
        async sincronizar(usuario, autoSave = true) {
            const id = usuario.id_usuarios || usuario.id;
            if (!id) return;
            const [rows] = await pool.query(
                "SELECT id_usuarios FROM tbl_usuarios WHERE id_usuarios = ?", [id]
            );
            if (rows.length) {
                await pool.query(
                    `UPDATE tbl_usuarios SET nome_usuarios=?, email_usuarios=?, nivel_acesso=?, foto_usuarios=?, atualizado_em=NOW()
                     WHERE id_usuarios=?`,
                    [usuario.nome_usuarios || usuario.nome, usuario.email_usuarios || usuario.email,
                    usuario.nivel_acesso || 'vendedor', usuario.foto_usuarios || null, id]
                );
            } else {
                await pool.query(
                    `INSERT INTO tbl_usuarios (id_usuarios, nome_usuarios, email_usuarios, senha_usuarios, nivel_acesso, foto_usuarios, criado_em)
                     VALUES (?, ?, ?, ?, ?, ?, NOW())`,
                    [id, usuario.nome_usuarios || usuario.nome, usuario.email_usuarios || usuario.email,
                        usuario.senha_usuarios || '', usuario.nivel_acesso || 'vendedor', usuario.foto_usuarios || null]
                );
            }
        },
        async atualizarSenha(id, novoHash) {
            await pool.query(
                "UPDATE tbl_usuarios SET senha_usuarios=?, atualizado_em=NOW() WHERE id_usuarios=?",
                [novoHash, id]
            );
        },
        async listarPendentes() { return []; },
        async marcarSincronizado() { }
    },

    //
    // CATEGORIAS
    //
    categorias: {
        async listar() {
            const [rows] = await pool.query(
                "SELECT * FROM tbl_categorias WHERE excluido_em IS NULL ORDER BY nome_categorias ASC"
            );
            return rows.map(mapCategoria);
        },
        async buscarOuCriar(nome) {
            const [rows] = await pool.query(
                "SELECT id_categorias FROM tbl_categorias WHERE LOWER(nome_categorias) = LOWER(?) AND excluido_em IS NULL LIMIT 1",
                [nome]
            );
            if (rows.length) return rows[0].id_categorias;
            const [result] = await pool.query(
                "INSERT INTO tbl_categorias (nome_categorias, criado_em) VALUES (?, NOW())",
                [nome]
            );
            return result.insertId;
        },
        async sincronizar(cat, autoSave = true) {
            const id_api = cat.id || cat.id_categorias;
            if (!id_api) return;
            const [rows] = await pool.query(
                "SELECT id_categorias FROM tbl_categorias WHERE id_categorias = ?", [id_api]
            );
            if (rows.length) {
                await pool.query(
                    "UPDATE tbl_categorias SET nome_categorias=?, atualizado_em=NOW() WHERE id_categorias=?",
                    [cat.nome || cat.nome_categorias, id_api]
                );
            } else {
                await pool.query(
                    "INSERT INTO tbl_categorias (id_categorias, nome_categorias, criado_em) VALUES (?, ?, NOW())",
                    [id_api, cat.nome || cat.nome_categorias]
                );
            }
        },
        async listarPendentes() { return []; },
        async marcarSincronizado() { }
    },

    //
    // PRODUTOS
    //
    produtos: {
        _imgBase: 'http://localhost:8000/backend/upload/',
        async listar() {
            const [rows] = await pool.query(
                "SELECT * FROM tbl_produtos WHERE excluido_em IS NULL ORDER BY nome_produtos ASC"
            );
            return rows.map(mapProduto);
        },
        async criar(prod) {
            const categoriaId = prod.categoria || null;
            const [result] = await pool.query(
                `INSERT INTO tbl_produtos (nome_produtos, descricao_produtos, preco_produtos, estoque_produtos,
                 imagem_produtos, id_categoria, criado_em)
                 VALUES (?, ?, ?, ?, ?, ?, NOW())`,
                [prod.nome, prod.descricao, prod.preco, prod.estoque, prod.imagem, categoriaId]
            );
            return { id: result.insertId, id_produto: result.insertId, ...prod };
        },
        async atualizar(id, prod) {
            const categoriaId = prod.categoria || null;
            await pool.query(
                `UPDATE tbl_produtos SET nome_produtos=?, descricao_produtos=?, preco_produtos=?,
                 estoque_produtos=?, imagem_produtos=?, id_categoria=?, atualizado_em=NOW()
                 WHERE id_produto=?`,
                [prod.nome, prod.descricao, prod.preco, prod.estoque, prod.imagem, categoriaId, id]
            );
            return { id, id_produto: id, ...prod };
        },
        async excluir(id) {
            await pool.query(
                "UPDATE tbl_produtos SET excluido_em=NOW(), atualizado_em=NOW() WHERE id_produto=?",
                [id]
            );
        },
        async subtrairEstoque(id, quantidade) {
            await pool.query(
                `UPDATE tbl_produtos
                 SET estoque_produtos = GREATEST(0, estoque_produtos - ?), atualizado_em=NOW()
                 WHERE id_produto = ?`,
                [parseInt(quantidade) || 0, id]
            );
        },
        async sincronizar(produto, autoSave = true) {
            const id_api = produto.id_produto || produto.id;
            if (!id_api) return;
            const [rows] = await pool.query(
                "SELECT id_produto FROM tbl_produtos WHERE id_produto = ?", [id_api]
            );
            const imagePath = produto.imagem || produto.imagem_produtos || '';
            const nome = produto.nome_produtos || produto.nome || '';
            const descricao = produto.descricao_produtos || '';
            const preco = parseFloat(produto.preco_produtos || produto.preco || 0);
            const estoque = parseInt(produto.estoque_produtos || produto.estoque || 0);
            const categoria = parseInt(produto.id_categoria || produto.categoria || 0) || null;
            if (rows.length) {
                await pool.query(
                    `UPDATE tbl_produtos SET nome_produtos=?, descricao_produtos=?, preco_produtos=?,
                     estoque_produtos=?, imagem_produtos=?, id_categoria=?, atualizado_em=NOW()
                     WHERE id_produto=?`,
                    [nome, descricao, preco, estoque, imagePath, categoria, id_api]
                );
            } else {
                await pool.query(
                    `INSERT INTO tbl_produtos (id_produto, nome_produtos, descricao_produtos, preco_produtos,
                     estoque_produtos, imagem_produtos, id_categoria, criado_em)
                     VALUES (?, ?, ?, ?, ?, ?, ?, NOW())`,
                    [id_api, nome, descricao, preco, estoque, imagePath, categoria]
                );
            }
        },
        async listarPendentes() { return []; },
        async marcarSincronizado() { },
        async listarPendentesDelecao() { return []; }
    },

    //
    // PEDIDOS
    //
    pedidos: {
        async listar() {
            try {
                const [rows] = await pool.query(
                    "SELECT * FROM tbl_pedidos WHERE excluido_em IS NULL ORDER BY criado_em DESC"
                );
                return rows.map(p => ({
                    id: p.id_pedido, id_pedido: p.id_pedido,
                    status: p.status_pedido, status_pedido: p.status_pedido,
                    total: p.total_pedido, total_pedido: p.total_pedido,
                    data_pedido: p.data_pedido,
                    id_usuarios: p.id_perfil
                }));
            } catch (e) { return []; }
        },
        async criar(pedido) {
            const [result] = await pool.query(
                `INSERT INTO tbl_pedidos (status_pedido, total_pedido, data_pedido, criado_em)
                 VALUES (?, ?, NOW(), NOW())`,
                ['pendente', parseFloat(pedido.total || 0)]
            );
            const pedidoId = result.insertId;
            if (Array.isArray(pedido.itens)) {
                for (const item of pedido.itens) {
                    const produtoId = item.produto_id || item.id_produto;
                    await pool.query(
                        `INSERT INTO tbl_itens_pedidos (id_pedido, id_produto, quantidade, preco_unitario, criado_em)
                         VALUES (?, ?, ?, ?, NOW())`,
                        [pedidoId, produtoId, item.quantidade, item.preco_unitario || 0]
                    );
                }
            }
            return { id: pedidoId, status: 'pendente', total: pedido.total, data_pedido: new Date().toISOString() };
        },
        async atualizar(id, dados) {
            await pool.query(
                "UPDATE tbl_pedidos SET status_pedido=?, atualizado_em=NOW() WHERE id_pedido=?",
                [dados.status || dados.status_pedido, id]
            );
            return { id, status: dados.status || dados.status_pedido };
        },
        async excluir(id) {
            await pool.query(
                "UPDATE tbl_pedidos SET excluido_em=NOW(), atualizado_em=NOW() WHERE id_pedido=?",
                [id]
            );
        },
        async sincronizar(pedido, autoSave = true) {
            const id = pedido.id_pedido || pedido.id;
            if (!id) return;
            const [rows] = await pool.query(
                "SELECT id_pedido FROM tbl_pedidos WHERE id_pedido = ?", [id]
            );
            if (rows.length) {
                await pool.query(
                    "UPDATE tbl_pedidos SET status_pedido=?, total_pedido=?, atualizado_em=NOW() WHERE id_pedido=?",
                    [pedido.status_pedido || pedido.status, pedido.total_pedido || pedido.total, id]
                );
            } else {
                await pool.query(
                    `INSERT INTO tbl_pedidos (id_pedido, id_perfil, status_pedido, total_pedido, data_pedido, criado_em)
                     VALUES (?, ?, ?, ?, ?, NOW())`,
                    [id, pedido.id_usuarios || pedido.id_perfil,
                        pedido.status_pedido || pedido.status,
                        pedido.total_pedido || pedido.total, pedido.data_pedido]
                );
            }
        },
        async listarPendentes() { return []; },
        async marcarSincronizado() { }
    },

    //
    // ITENS DE PEDIDOS
    //
    itensPedido: {
        async listarPorPedido(pedidoId) {
            const [rows] = await pool.query(
                "SELECT * FROM tbl_itens_pedidos WHERE id_pedido = ?",
                [parseInt(pedidoId) || 0]
            );
            return rows;
        },
        async sincronizar(item, autoSave = true) {
            if (item.id_itens_pedidos) {
                const [rows] = await pool.query(
                    "SELECT id_itens_pedidos FROM tbl_itens_pedidos WHERE id_itens_pedidos = ?",
                    [item.id_itens_pedidos]
                );
                if (rows.length) {
                    await pool.query(
                        `UPDATE tbl_itens_pedidos SET id_pedido=?, id_produto=?, quantidade=?, preco_unitario=?
                         WHERE id_itens_pedidos=?`,
                        [item.id_pedido, item.id_produto, item.quantidade, item.preco_unitario || 0, item.id_itens_pedidos]
                    );
                    return;
                }
            }
            await pool.query(
                `INSERT INTO tbl_itens_pedidos (id_pedido, id_produto, quantidade, preco_unitario, criado_em)
                 VALUES (?, ?, ?, ?, NOW())`,
                [item.id_pedido, item.id_produto, item.quantidade, item.preco_unitario || 0]
            );
        }
    },

    //
    // CLIENTES (usa tbl_usuarios com nivel_acesso = 'cliente' + tbl_perfil)
    //
    clientes: {
        async listar() {
            const [rows] = await pool.query(
                `SELECT u.id_usuarios, u.nome_usuarios, u.email_usuarios, u.foto_usuarios,
                        p.telefone_perfil, p.endereco_perfil
                 FROM tbl_usuarios u
                 LEFT JOIN tbl_perfil p ON p.id_usuarios = u.id_usuarios
                 WHERE LOWER(u.nivel_acesso) = 'cliente' AND u.excluido_em IS NULL
                 ORDER BY u.nome_usuarios ASC`
            );
            return rows.map(r => ({
                id_cliente: r.id_usuarios,
                nome_clientes: r.nome_usuarios,
                email_clientes: r.email_usuarios,
                telefone_clientes: r.telefone_perfil,
                endereco_clientes: r.endereco_perfil,
                foto_usuarios: r.foto_usuarios
            }));
        },
        async criar(cliente) {
            const [result] = await pool.query(
                `INSERT INTO tbl_usuarios (nome_usuarios, email_usuarios, senha_usuarios, nivel_acesso, criado_em)
                 VALUES (?, ?, ?, 'cliente', NOW())`,
                [cliente.nome_clientes || cliente.nome || '', cliente.email_clientes || cliente.email || '', '']
            );
            return { id: result.insertId };
        },
        async sincronizar(cliente, autoSave = true) {
            const id = cliente.id_cliente || cliente.id;
            if (!id) return;
            const [rows] = await pool.query(
                "SELECT id_usuarios FROM tbl_usuarios WHERE id_usuarios = ?", [id]
            );
            if (!rows.length) {
                await pool.query(
                    `INSERT INTO tbl_usuarios (id_usuarios, nome_usuarios, email_usuarios, nivel_acesso, criado_em)
                     VALUES (?, ?, ?, 'cliente', NOW())`,
                    [id, cliente.nome_clientes || cliente.nome || '', cliente.email_clientes || cliente.email || '']
                );
            }
        },
        async excluir(id) {
            await pool.query(
                "UPDATE tbl_usuarios SET excluido_em=NOW() WHERE id_usuarios=?",
                [id]
            );
        },
        async listarPendentes() { return []; },
        async marcarSincronizado() { }
    },

    //
    // TAMANHOS
    //
    tamanhos: {
        async listar() {
            const [rows] = await pool.query(
                "SELECT * FROM tbl_tamanhos WHERE excluido_em IS NULL ORDER BY id_produto, tamanho_tamanhos"
            );
            return rows.map(mapTamanho);
        },
        async listarPorProduto(produtoId) {
            const [rows] = await pool.query(
                "SELECT * FROM tbl_tamanhos WHERE id_produto = ? AND excluido_em IS NULL",
                [parseInt(produtoId) || 0]
            );
            return rows.map(mapTamanho);
        },
        async criar(tamanho) {
            const [result] = await pool.query(
                "INSERT INTO tbl_tamanhos (id_produto, tamanho_tamanhos, quantidade_tamanhos, criado_em) VALUES (?, ?, ?, NOW())",
                [tamanho.id_produto, tamanho.tamanho, parseInt(tamanho.quantidade) || 0]
            );
            return { id: result.insertId, ...tamanho };
        },
        async excluir(id) {
            await pool.query(
                "UPDATE tbl_tamanhos SET excluido_em=NOW() WHERE id_tamanhos=?",
                [id]
            );
        },
        async excluirPorProduto(produtoId) {
            await pool.query(
                "UPDATE tbl_tamanhos SET excluido_em=NOW() WHERE id_produto=?",
                [parseInt(produtoId) || 0]
            );
        },
        async sincronizar(tamanho, autoSave = true) {
            const [rows] = await pool.query(
                "SELECT id_tamanhos FROM tbl_tamanhos WHERE id_produto = ? AND tamanho_tamanhos = ? AND excluido_em IS NULL",
                [tamanho.id_produto, tamanho.tamanho]
            );
            if (rows.length) {
                await pool.query(
                    "UPDATE tbl_tamanhos SET quantidade_tamanhos=?, atualizado_em=NOW() WHERE id_tamanhos=?",
                    [tamanho.quantidade || 0, rows[0].id_tamanhos]
                );
            } else {
                await pool.query(
                    "INSERT INTO tbl_tamanhos (id_produto, tamanho_tamanhos, quantidade_tamanhos, criado_em) VALUES (?, ?, ?, NOW())",
                    [tamanho.id_produto, tamanho.tamanho, tamanho.quantidade || 0]
                );
            }
        }
    },

    //
    // ESTOQUE / IMAGENS / CORES (stubs — dados ficam no MySQL via backend PHP)
    //
    estoque: {
        async listar() { return []; },
        async sincronizar() { },
        async listarPendentes() { return []; },
        async marcarSincronizado() { }
    },
    imagens: { async sincronizar() { } },
    cores: { async sincronizar() { } },

    //
    // BANNERS (usa tbl_imagem_carrossel)
    //
    banners: {
        async listar() {
            const [rows] = await pool.query(
                "SELECT * FROM tbl_imagem_carrossel WHERE excluido_em IS NULL ORDER BY ordem_imagem_carrossel ASC, id_carrossel ASC"
            );
            return rows.map(mapBanner);
        },
        async criar(banner) {
            const [result] = await pool.query(
                `INSERT INTO tbl_imagem_carrossel (url_imagem_imagem_carrossel, link_destino_imagem_carrossel,
                 ordem_imagem_carrossel, ativo_imagem_carrossel, criado_em)
                 VALUES (?, ?, ?, ?, NOW())`,
                [banner.imagem || banner.url_imagem_imagem_carrossel,
                banner.link || banner.link_destino_imagem_carrossel || '',
                banner.ordem || banner.ordem_imagem_carrossel || 0,
                banner.ativo !== undefined ? (banner.ativo ? 1 : 0) : 1]
            );
            return { id: result.insertId, ...banner };
        },
        async atualizar(id, banner) {
            await pool.query(
                `UPDATE tbl_imagem_carrossel
                 SET url_imagem_imagem_carrossel=?, link_destino_imagem_carrossel=?,
                     ordem_imagem_carrossel=?, ativo_imagem_carrossel=?, atualizado_em=NOW()
                 WHERE id_carrossel=?`,
                [banner.imagem || banner.url_imagem_imagem_carrossel,
                banner.link || banner.link_destino_imagem_carrossel || '',
                banner.ordem || banner.ordem_imagem_carrossel || 0,
                banner.ativo !== undefined ? (banner.ativo ? 1 : 0) : 1,
                    id]
            );
            return { id, ...banner };
        },
        async excluir(id) {
            await pool.query(
                "UPDATE tbl_imagem_carrossel SET excluido_em=NOW() WHERE id_carrossel=?",
                [id]
            );
        }
    },

    // Compatibilidade (não faz nada no MySQL — não há arquivo local para salvar)
    salvar: async () => { }
};

// ============================================================
// HELPERS DE MAPEAMENTO (MySQL -> formato esperado pelo frontend)
// ============================================================

function mapUsuario(u) {
    if (!u) return null;
    return {
        id: u.id_usuarios,
        id_usuarios: u.id_usuarios,
        nome: u.nome_usuarios,
        nome_usuarios: u.nome_usuarios,
        email: u.email_usuarios,
        email_usuarios: u.email_usuarios,
        senha: u.senha_usuarios,
        senha_usuarios: u.senha_usuarios,
        nivel_acesso: u.nivel_acesso,
        tipo: u.nivel_acesso,
        tipo_usuarios: u.nivel_acesso,
        foto_usuarios: u.foto_usuarios
    };
}

function mapCategoria(c) {
    if (!c) return null;
    return {
        id: c.id_categorias,
        id_categorias: c.id_categorias,
        id_categoria_api: c.id_categorias,
        nome: c.nome_categorias,
        nome_categorias: c.nome_categorias,
        imagem: null,
        caminho_imagem_categorias: null
    };
}

function mapProduto(r) {
    if (!r) return null;
    const _imgBase = Database.produtos._imgBase;
    let finalImage = null;
    if (r.imagem_produtos) {
        if (r.imagem_produtos.startsWith('data:') || r.imagem_produtos.startsWith('http') || r.imagem_produtos.startsWith('file:')) {
            finalImage = r.imagem_produtos;
        } else {
            finalImage = _imgBase + r.imagem_produtos;
        }
    }
    return {
        id: r.id_produto,
        id_produto: r.id_produto,
        id_produto_api: r.id_produto,
        nome: r.nome_produtos,
        nome_produtos: r.nome_produtos,
        descricao: r.descricao_produtos || '',
        descricao_produtos: r.descricao_produtos || '',
        preco: parseFloat(r.preco_produtos) || 0,
        preco_produtos: parseFloat(r.preco_produtos) || 0,
        estoque: parseInt(r.estoque_produtos) || 0,
        estoque_produtos: parseInt(r.estoque_produtos) || 0,
        imagem: finalImage,
        imagem_produtos: r.imagem_produtos || null,
        categoria: r.id_categoria,
        id_categoria: r.id_categoria,
        criado_em: r.criado_em,
        atualizado_em: r.atualizado_em
    };
}

function mapTamanho(t) {
    if (!t) return null;
    return {
        id: t.id_tamanhos,
        id_tamanhos: t.id_tamanhos,
        id_produto: t.id_produto,
        tamanho: t.tamanho_tamanhos,
        tamanho_tamanhos: t.tamanho_tamanhos,
        quantidade: t.quantidade_tamanhos,
        quantidade_tamanhos: t.quantidade_tamanhos
    };
}

function mapBanner(b) {
    if (!b) return null;
    return {
        id: b.id_carrossel,
        titulo: b.descricao_imagem_carrossel || '',
        imagem: b.url_imagem_imagem_carrossel,
        link: b.link_destino_imagem_carrossel || '',
        ordem: b.ordem_imagem_carrossel,
        ativo: b.ativo_imagem_carrossel === 1
    };
}

module.exports = Database;
