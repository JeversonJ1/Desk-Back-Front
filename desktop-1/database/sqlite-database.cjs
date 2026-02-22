// =============================================================
// sqlite-database.cjs  Usa better-sqlite3 (API sincrona)
// .prepare(sql).all(params)  -> array de objetos
// .prepare(sql).get(params)  -> um objeto ou undefined
// .prepare(sql).run(params)  -> { changes, lastInsertRowid }
// =============================================================
const { getDatabase, saveDatabase } = require('./sqlite-connection');

const Database = {

    // 
    // USUARIOS
    // 
    usuarios: {
        async listar() {
            const db = getDatabase();
            return db.prepare("SELECT * FROM tbl_usuarios WHERE deletado = 0").all().map(mapUsuario);
        },
        async buscarPorEmail(email) {
            const db = getDatabase();
            return mapUsuario(db.prepare("SELECT * FROM tbl_usuarios WHERE email_usuarios = ? AND deletado = 0").get(email));
        },
        async buscarPorId(id) {
            const db = getDatabase();
            return mapUsuario(db.prepare("SELECT * FROM tbl_usuarios WHERE id_usuarios = ?").get(id));
        },
        async criar(usuario) {
            const db = getDatabase();
            const info = db.prepare(
                `INSERT INTO tbl_usuarios (nome_usuarios, email_usuarios, senha_usuarios, nivel_acesso)
                 VALUES (?, ?, ?, ?)`
            ).run(usuario.nome, usuario.email, usuario.senha, usuario.tipo || usuario.nivel_acesso || 'admin');
            saveDatabase();
            return { id: info.lastInsertRowid };
        },
        async sincronizar(usuario, autoSave = true) {
            const db = getDatabase();
            const id = usuario.id_usuarios || usuario.id;
            if (!id) return;
            const existing = db.prepare("SELECT id_usuarios FROM tbl_usuarios WHERE id_usuarios = ?").get(id);
            if (existing) {
                db.prepare(
                    `UPDATE tbl_usuarios SET nome_usuarios=?, email_usuarios=?, nivel_acesso=?, foto_usuarios=?, sincronizado=1
                     WHERE id_usuarios=?`
                ).run(usuario.nome_usuarios || usuario.nome, usuario.email_usuarios || usuario.email,
                      usuario.nivel_acesso || 'vendedor', usuario.foto_usuarios || null, id);
            } else {
                db.prepare(
                    `INSERT INTO tbl_usuarios (id_usuarios, nome_usuarios, email_usuarios, senha_usuarios, nivel_acesso, foto_usuarios, sincronizado)
                     VALUES (?, ?, ?, ?, ?, ?, 1)`
                ).run(id, usuario.nome_usuarios || usuario.nome, usuario.email_usuarios || usuario.email,
                      usuario.senha_usuarios || '', usuario.nivel_acesso || 'vendedor', usuario.foto_usuarios || null);
            }
            if (autoSave) saveDatabase();
        },
        async atualizarSenha(id, novoHash) {
            const db = getDatabase();
            db.prepare("UPDATE tbl_usuarios SET senha_usuarios=? WHERE id_usuarios=?").run(novoHash, id);
            saveDatabase();
        },
        async listarPendentes() { return []; },
        async marcarSincronizado() { }
    },

    // 
    // CATEGORIAS
    // 
    categorias: {
        async listar() {
            const db = getDatabase();
            return db.prepare("SELECT * FROM tbl_categorias ORDER BY nome ASC").all().map(mapCategoria);
        },
        async buscarOuCriar(nome) {
            const db = getDatabase();
            const existing = db.prepare("SELECT id FROM tbl_categorias WHERE LOWER(nome) = LOWER(?)").get(nome);
            if (existing) return existing.id;
            const info = db.prepare("INSERT INTO tbl_categorias (nome) VALUES (?)").run(nome);
            saveDatabase();
            return info.lastInsertRowid;
        },
        async sincronizar(cat, autoSave = true) {
            const db = getDatabase();
            const id_api = cat.id || cat.id_categorias;
            if (!id_api) return;
            const existing = db.prepare("SELECT id FROM tbl_categorias WHERE id_categoria_api = ?").get(id_api);
            if (existing) {
                db.prepare(
                    `UPDATE tbl_categorias SET nome=? WHERE id_categoria_api=?`
                ).run(cat.nome || cat.nome_categorias, id_api);
            } else {
                db.prepare(
                    `INSERT INTO tbl_categorias (id_categoria_api, nome) VALUES (?, ?)`
                ).run(id_api, cat.nome || cat.nome_categorias);
            }
            if (autoSave) saveDatabase();
        },
        async listarPendentes() { return []; },
        async marcarSincronizado() { }
    },

    // 
    // PRODUTOS
    // 
    produtos: {
        _imgBase: 'http://localhost:4000/backend/upload/',
        async listar() {
            const db = getDatabase();
            return db.prepare("SELECT * FROM tbl_produtos WHERE deletado = 0 ORDER BY nome ASC").all().map(mapProduto);
        },
        async criar(prod) {
            const db = getDatabase();
            const info = db.prepare(
                `INSERT INTO tbl_produtos (nome, descricao, preco, estoque, imagem, categoria, criado_em)
                 VALUES (?, ?, ?, ?, ?, ?, datetime('now'))`
            ).run(prod.nome, prod.descricao, prod.preco, prod.estoque, prod.imagem, prod.categoria);
            saveDatabase();
            return { id: info.lastInsertRowid, ...prod };
        },
        async atualizar(id, prod) {
            const db = getDatabase();
            db.prepare(
                `UPDATE tbl_produtos SET nome=?, descricao=?, preco=?, estoque=?,
                 imagem=?, categoria=?, atualizado_em=datetime('now') WHERE id=?`
            ).run(prod.nome, prod.descricao, prod.preco, prod.estoque, prod.imagem, prod.categoria, id);
            saveDatabase();
            return { id, ...prod };
        },
        async excluir(id) {
            const db = getDatabase();
            db.prepare("UPDATE tbl_produtos SET deletado=1, atualizado_em=datetime('now') WHERE id=?").run(id);
            saveDatabase();
        },
        async subtrairEstoque(id, quantidade) {
            const db = getDatabase();
            db.prepare(
                `UPDATE tbl_produtos
                 SET estoque = MAX(0, estoque - ?), atualizado_em = datetime('now')
                 WHERE id = ?`
            ).run(parseInt(quantidade) || 0, id);
        },
        async sincronizar(produto, autoSave = true) {
            const db = getDatabase();
            const id_api = produto.id_produto || produto.id;
            if (!id_api) return;
            const existing = db.prepare("SELECT id FROM tbl_produtos WHERE id_produto_api = ?").get(id_api);
            const imagePath = produto.imagem || produto.imagem_produtos || '';
            const nome = produto.nome_produtos || produto.nome || '';
            const descricao = produto.descricao_produtos || '';
            const preco = parseFloat(produto.preco_produtos || produto.preco || 0);
            const estoque = parseInt(produto.estoque_produtos || produto.estoque || 0);
            const categoria = parseInt(produto.id_categoria || produto.categoria || 0) || null;
            if (existing) {
                db.prepare(
                    `UPDATE tbl_produtos SET nome=?, descricao=?, preco=?, estoque=?,
                     imagem=?, categoria=?, atualizado_em=datetime('now') WHERE id_produto_api=?`
                ).run(nome, descricao, preco, estoque, imagePath, categoria, id_api);
            } else {
                db.prepare(
                    `INSERT INTO tbl_produtos (id_produto_api, nome, descricao, preco, estoque, imagem, categoria, criado_em)
                     VALUES (?, ?, ?, ?, ?, ?, ?, datetime('now'))`
                ).run(id_api, nome, descricao, preco, estoque, imagePath, categoria);
            }
            if (autoSave) saveDatabase();
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
            const db = getDatabase();
            try {
                return db.prepare("SELECT * FROM tbl_pedidos WHERE deletado = 0 ORDER BY criado_em DESC").all().map(p => ({
                    id: p.id_pedido, id_pedido: p.id_pedido,
                    status: p.status_pedido, status_pedido: p.status_pedido,
                    total: p.total_pedido, total_pedido: p.total_pedido,
                    data_pedido: p.data_pedido, id_usuarios: p.id_usuarios
                }));
            } catch (e) { return []; }
        },
        async criar(pedido) {
            const db = getDatabase();
            const info = db.prepare(
                `INSERT INTO tbl_pedidos (status_pedido, total_pedido, data_pedido, criado_em)
                 VALUES (?, ?, datetime('now'), datetime('now'))`
            ).run('aberto', parseFloat(pedido.total || 0));
            const pedidoId = info.lastInsertRowid;
            if (Array.isArray(pedido.itens)) {
                for (const item of pedido.itens) {
                    const produtoId = item.produto_id || item.id_produto;
                    db.prepare(
                        `INSERT INTO tbl_itens_pedidos (id_pedido, id_produto, quantidade, preco_unitario)
                         VALUES (?, ?, ?, ?)`
                    ).run(pedidoId, produtoId, item.quantidade, item.preco_unitario || 0);
                }
            }
            saveDatabase();
            return { id: pedidoId, status: 'aberto', total: pedido.total, data_pedido: new Date().toISOString() };
        },
        async atualizar(id, dados) {
            const db = getDatabase();
            db.prepare(
                `UPDATE tbl_pedidos SET status_pedido=?, atualizado_em=datetime('now') WHERE id_pedido=?`
            ).run(dados.status || dados.status_pedido, id);
            saveDatabase();
            return { id, status: dados.status || dados.status_pedido };
        },
        async excluir(id) {
            const db = getDatabase();
            db.prepare("UPDATE tbl_pedidos SET deletado=1, atualizado_em=datetime('now') WHERE id_pedido=?").run(id);
            saveDatabase();
        },
        async sincronizar(pedido, autoSave = true) {
            const db = getDatabase();
            const id = pedido.id_pedido || pedido.id;
            if (!id) return;
            const existing = db.prepare("SELECT id_pedido FROM tbl_pedidos WHERE id_pedido_api = ?").get(id);
            if (existing) {
                db.prepare(`UPDATE tbl_pedidos SET status_pedido=?, total_pedido=?, atualizado_em=datetime('now') WHERE id_pedido_api=?`)
                  .run(pedido.status_pedido || pedido.status, pedido.total_pedido || pedido.total, id);
            } else {
                db.prepare(
                    `INSERT INTO tbl_pedidos (id_pedido_api, id_usuarios, status_pedido, total_pedido, data_pedido, criado_em)
                     VALUES (?, ?, ?, ?, ?, datetime('now'))`
                ).run(id, pedido.id_usuarios || pedido.id_perfil,
                      pedido.status_pedido || pedido.status,
                      pedido.total_pedido || pedido.total, pedido.data_pedido);
            }
            if (autoSave) saveDatabase();
        },
        async listarPendentes() { return []; },
        async marcarSincronizado() { }
    },

    // 
    // ITENS DE PEDIDOS
    // 
    itensPedido: {
        async listarPorPedido(pedidoId) {
            const db = getDatabase();
            return db.prepare("SELECT * FROM tbl_itens_pedidos WHERE id_pedido = ?").all(parseInt(pedidoId) || 0);
        },
        async sincronizar(item, autoSave = true) {
            const db = getDatabase();
            const existing = item.id_itens_pedidos
                ? db.prepare("SELECT id_itens_pedidos FROM tbl_itens_pedidos WHERE id_itens_pedidos = ?").get(item.id_itens_pedidos)
                : null;
            if (existing) {
                db.prepare(
                    `UPDATE tbl_itens_pedidos SET id_pedido=?, id_produto=?, quantidade=?, preco_unitario=?
                     WHERE id_itens_pedidos=?`
                ).run(item.id_pedido, item.id_produto, item.quantidade, item.preco_unitario || 0, item.id_itens_pedidos);
            } else {
                db.prepare(
                    `INSERT INTO tbl_itens_pedidos (id_pedido, id_produto, quantidade, preco_unitario)
                     VALUES (?, ?, ?, ?)`
                ).run(item.id_pedido, item.id_produto, item.quantidade, item.preco_unitario || 0);
            }
            if (autoSave) saveDatabase();
        }
    },

    // 
    // CLIENTES
    // 
    clientes: {
        async listar() {
            const db = getDatabase();
            return db.prepare("SELECT * FROM tbl_clientes WHERE deletado = 0 ORDER BY nome_clientes ASC").all();
        },
        async criar(cliente) {
            const db = getDatabase();
            const id = cliente.id_cliente || null;
            const info = db.prepare(
                `INSERT INTO tbl_clientes
                 (id_cliente, nome_clientes, email_clientes, telefone_clientes, cpf_cnpj_clientes,
                  data_nascimento_clientes, logradouro_clientes, numero_clientes, bairro_clientes,
                  cidade_clientes, cep_clientes, observacoes_clientes, sincronizado, criado_em)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now'))`
            ).run(
                id,
                cliente.nome_clientes || cliente.nome || null,
                cliente.email_clientes || cliente.email || null,
                cliente.telefone_clientes || null,
                cliente.cpf_cnpj_clientes || null,
                cliente.data_nascimento_clientes || null,
                cliente.logradouro_clientes || cliente.endereco_clientes || null,
                cliente.numero_clientes || null,
                cliente.bairro_clientes || null,
                cliente.cidade_clientes || null,
                cliente.cep_clientes || null,
                cliente.observacoes_clientes || null,
                cliente.sincronizado ?? 0
            );
            saveDatabase();
            return { id: id || info.lastInsertRowid };
        },
        async sincronizar(cliente, autoSave = true) {
            const db = getDatabase();
            const id = cliente.id_cliente || cliente.id;
            if (!id) return;
            const existing = db.prepare("SELECT id_cliente FROM tbl_clientes WHERE id_cliente = ?").get(id);
            const params = [
                cliente.nome_clientes || cliente.nome || null,
                cliente.email_clientes || cliente.email || null,
                cliente.telefone_clientes || null,
                cliente.cpf_cnpj_clientes || null,
                cliente.data_nascimento_clientes || null,
                cliente.logradouro_clientes || cliente.endereco_clientes || null,
                cliente.numero_clientes || null,
                cliente.bairro_clientes || null,
                cliente.cidade_clientes || null,
                cliente.cep_clientes || null,
                cliente.observacoes_clientes || null,
                cliente.sincronizado ?? 1,
                id
            ];
            if (existing) {
                db.prepare(
                    `UPDATE tbl_clientes SET
                     nome_clientes=?, email_clientes=?, telefone_clientes=?, cpf_cnpj_clientes=?,
                     data_nascimento_clientes=?, logradouro_clientes=?, numero_clientes=?, bairro_clientes=?,
                     cidade_clientes=?, cep_clientes=?, observacoes_clientes=?, sincronizado=?,
                     atualizado_em=datetime('now') WHERE id_cliente=?`
                ).run(...params);
            } else {
                db.prepare(
                    `INSERT INTO tbl_clientes
                     (nome_clientes, email_clientes, telefone_clientes, cpf_cnpj_clientes,
                      data_nascimento_clientes, logradouro_clientes, numero_clientes, bairro_clientes,
                      cidade_clientes, cep_clientes, observacoes_clientes, sincronizado, id_cliente, criado_em)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now'))`
                ).run(...params);
            }
            if (autoSave) saveDatabase();
        },
        async excluir(id) {
            const db = getDatabase();
            db.prepare("UPDATE tbl_clientes SET deletado=1 WHERE id_cliente=?").run(id);
            saveDatabase();
        },
        async listarPendentes() {
            const db = getDatabase();
            return db.prepare("SELECT * FROM tbl_clientes WHERE sincronizado = 0 AND deletado = 0").all();
        },
        async marcarSincronizado(id) {
            const db = getDatabase();
            db.prepare("UPDATE tbl_clientes SET sincronizado = 1 WHERE id_cliente = ?").run(id);
            saveDatabase();
        }
    },

    // 
    // TAMANHOS
    // 
    tamanhos: {
        async listar() {
            const db = getDatabase();
            return db.prepare("SELECT * FROM tbl_tamanhos ORDER BY id_produto, tamanho").all();
        },
        async listarPorProduto(produtoId) {
            const db = getDatabase();
            return db.prepare("SELECT * FROM tbl_tamanhos WHERE id_produto = ?").all(parseInt(produtoId) || 0);
        },
        async criar(tamanho) {
            const db = getDatabase();
            const info = db.prepare("INSERT INTO tbl_tamanhos (id_produto, tamanho, quantidade) VALUES (?, ?, ?)")
                .run(tamanho.id_produto, tamanho.tamanho, parseInt(tamanho.quantidade) || 0);
            saveDatabase();
            return { id: info.lastInsertRowid, ...tamanho };
        },
        async excluir(id) {
            const db = getDatabase();
            db.prepare("DELETE FROM tbl_tamanhos WHERE id = ?").run(id);
            saveDatabase();
        },
        async excluirPorProduto(produtoId) {
            const db = getDatabase();
            db.prepare("DELETE FROM tbl_tamanhos WHERE id_produto = ?").run(parseInt(produtoId) || 0);
            saveDatabase();
        },
        async sincronizar(tamanho, autoSave = true) {
            const db = getDatabase();
            const existing = db.prepare("SELECT id FROM tbl_tamanhos WHERE id_produto = ? AND tamanho = ?")
                .get(tamanho.id_produto, tamanho.tamanho);
            if (existing) {
                db.prepare("UPDATE tbl_tamanhos SET quantidade = ? WHERE id_produto = ? AND tamanho = ?")
                    .run(tamanho.quantidade || 0, tamanho.id_produto, tamanho.tamanho);
            } else {
                db.prepare("INSERT INTO tbl_tamanhos (id_produto, tamanho, quantidade) VALUES (?, ?, ?)")
                    .run(tamanho.id_produto, tamanho.tamanho, tamanho.quantidade || 0);
            }
            if (autoSave) saveDatabase();
        }
    },

    // 
    // ESTOQUE / IMAGENS / CORES
    // 
    estoque: {
        async listar() { return []; },
        async sincronizar() { },
        async listarPendentes() { return []; },
        async marcarSincronizado() { }
    },
    imagens: { async sincronizar() { } },
    cores:   { async sincronizar() { } },

    // 
    // BANNERS
    // 
    banners: {
        async listar() {
            const db = getDatabase();
            return db.prepare("SELECT * FROM tbl_banners ORDER BY ordem ASC, id ASC").all();
        },
        async criar(banner) {
            const db = getDatabase();
            const info = db.prepare(
                `INSERT INTO tbl_banners (titulo, imagem, link, ordem, ativo, criado_em)
                 VALUES (?, ?, ?, ?, ?, datetime('now'))`
            ).run(banner.titulo, banner.imagem, banner.link || '', banner.ordem || 0, banner.ativo ? 1 : 0);
            saveDatabase();
            return { id: info.lastInsertRowid, ...banner };
        },
        async atualizar(id, banner) {
            const db = getDatabase();
            db.prepare(
                `UPDATE tbl_banners SET titulo=?, imagem=?, link=?, ordem=?, ativo=?, atualizado_em=datetime('now')
                 WHERE id=?`
            ).run(banner.titulo, banner.imagem, banner.link || '', banner.ordem || 0, banner.ativo ? 1 : 0, id);
            saveDatabase();
            return { id, ...banner };
        },
        async excluir(id) {
            const db = getDatabase();
            db.prepare("DELETE FROM tbl_banners WHERE id=?").run(id);
            saveDatabase();
        }
    },

    salvar:  saveDatabase
};

// 
// HELPERS DE MAPEAMENTO
// 

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
        id: c.id,
        id_categorias: c.id,
        nome: c.nome,
        nome_categorias: c.nome,
        id_categoria_api: c.id_categoria_api,
        imagem: null,
        caminho_imagem_categorias: null
    };
}

function mapProduto(r) {
    if (!r) return null;
    let finalImage = null;
    if (r.imagem) {
        if (r.imagem.startsWith('data:') || r.imagem.startsWith('http')) {
            finalImage = r.imagem;
        } else if (r.imagem.startsWith('file:')) {
            finalImage = r.imagem;
        } else {
            finalImage = Database.produtos._imgBase + r.imagem;
        }
    }
    return {
        id: r.id,
        id_produto: r.id,
        nome: r.nome,
        nome_produtos: r.nome,
        descricao: r.descricao || '',
        descricao_produtos: r.descricao || '',
        preco: parseFloat(r.preco) || 0,
        preco_produtos: parseFloat(r.preco) || 0,
        estoque: parseInt(r.estoque) || 0,
        estoque_produtos: parseInt(r.estoque) || 0,
        imagem: finalImage,
        imagem_produtos: r.imagem || null,
        categoria: r.categoria,
        id_categoria: r.categoria,
        id_produto_api: r.id_produto_api,
        criado_em: r.criado_em,
        atualizado_em: r.atualizado_em
    };
}

module.exports = Database;
