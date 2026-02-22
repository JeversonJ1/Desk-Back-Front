const BetterSQLite = require('better-sqlite3');
const path = require('path');

const dbPath = path.join(__dirname, 'koketsu.sqlite');

let db = null;

function getDatabase() {
    if (db) return db;

    db = new BetterSQLite(dbPath);
    db.pragma('journal_mode = WAL');
    db.pragma('foreign_keys = ON');

    createTables(db);
    runMigrations(db);

    return db;
}

function saveDatabase() {
    try {
        if (db) db.pragma('wal_checkpoint(PASSIVE)');
    } catch (e) { /* sem efeito crítico */ }
}

function createTables(database) {
    database.exec(`
    CREATE TABLE IF NOT EXISTS tbl_usuarios (
        id_usuarios INTEGER PRIMARY KEY,
        nome_usuarios TEXT,
        email_usuarios TEXT UNIQUE,
        nivel_acesso TEXT DEFAULT 'admin',
        senha_usuarios TEXT,
        foto_usuarios TEXT,
        sincronizado BOOLEAN DEFAULT 1,
        deletado BOOLEAN DEFAULT 0
    );

    CREATE TABLE IF NOT EXISTS tbl_categorias (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT,
        id_categoria_api INTEGER UNIQUE
    );

    CREATE TABLE IF NOT EXISTS tbl_produtos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT,
        descricao TEXT,
        preco REAL DEFAULT 0,
        estoque INTEGER DEFAULT 0,
        imagem TEXT,
        categoria INTEGER,
        criado_em TEXT DEFAULT (datetime('now')),
        atualizado_em TEXT,
        id_produto_api INTEGER UNIQUE,
        sincronizado BOOLEAN DEFAULT 1,
        deletado BOOLEAN DEFAULT 0
    );

    CREATE TABLE IF NOT EXISTS tbl_pedidos (
        id_pedido INTEGER PRIMARY KEY,
        id_perfil INTEGER,
        data_pedido TEXT,
        total_pedido REAL DEFAULT 0,
        status_pedido TEXT DEFAULT 'pendente',
        criado_em TEXT DEFAULT (datetime('now')),
        atualizado_em TEXT,
        id_usuarios INTEGER,
        id_pedido_api INTEGER UNIQUE,
        sincronizado BOOLEAN DEFAULT 1,
        deletado BOOLEAN DEFAULT 0
    );

    CREATE TABLE IF NOT EXISTS tbl_itens_pedidos (
        id_itens_pedidos INTEGER PRIMARY KEY,
        id_pedido INTEGER,
        id_produto INTEGER,
        quantidade INTEGER DEFAULT 1,
        preco_unitario REAL DEFAULT 0
    );

    CREATE TABLE IF NOT EXISTS tbl_clientes (
        id_cliente INTEGER PRIMARY KEY,
        nome_clientes TEXT,
        email_clientes TEXT,
        telefone_clientes TEXT,
        cpf_cnpj_clientes TEXT,
        data_nascimento_clientes TEXT,
        logradouro_clientes TEXT,
        numero_clientes TEXT,
        bairro_clientes TEXT,
        cidade_clientes TEXT,
        cep_clientes TEXT,
        observacoes_clientes TEXT,
        sincronizado BOOLEAN DEFAULT 1,
        deletado BOOLEAN DEFAULT 0
    );

    CREATE TABLE IF NOT EXISTS tbl_estoque_movimentacao (
        id_estoque_movimentacao INTEGER PRIMARY KEY,
        id_produto INTEGER,
        descricao_estoque_movimentacao TEXT,
        quantidade_estoque_movimentacao INTEGER,
        data_estoque_movimentacao TEXT,
        tipo_estoque_movimentacao TEXT DEFAULT 'entrada',
        sincronizado BOOLEAN DEFAULT 1,
        deletado BOOLEAN DEFAULT 0
    );

    CREATE TABLE IF NOT EXISTS tbl_tamanhos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        id_produto INTEGER,
        tamanho TEXT,
        quantidade INTEGER DEFAULT 0
    );

    CREATE TABLE IF NOT EXISTS tbl_banners (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        titulo TEXT,
        imagem TEXT,
        link TEXT,
        ordem INTEGER DEFAULT 0,
        ativo BOOLEAN DEFAULT 1,
        criado_em TEXT DEFAULT (datetime('now')),
        atualizado_em TEXT
    );

    CREATE TABLE IF NOT EXISTS tbl_imagem (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        id_produto INTEGER,
        caminho_imagem TEXT,
        principal BOOLEAN DEFAULT 0
    );

    CREATE TABLE IF NOT EXISTS tbl_cores (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        id_produto INTEGER,
        nome_cor TEXT,
        codigo_cor TEXT
    );
    `);
}

function runMigrations(database) {
    // Adicionar colunas que podem estar ausentes em bancos antigos
    const extras = [
        { table: 'tbl_usuarios',             col: 'criado_em',    type: 'TEXT' },
        { table: 'tbl_usuarios',             col: 'atualizado_em',type: 'TEXT' },
        { table: 'tbl_usuarios',             col: 'foto_usuarios', type: 'TEXT' },
        { table: 'tbl_categorias',           col: 'criado_em',    type: 'TEXT' },
        { table: 'tbl_clientes',             col: 'criado_em',    type: 'TEXT' },
        { table: 'tbl_clientes',             col: 'atualizado_em',type: 'TEXT' },
        { table: 'tbl_produtos',             col: 'criado_em',    type: 'TEXT DEFAULT (datetime(\'now\'))' },
        { table: 'tbl_estoque_movimentacao', col: 'criado_em',    type: 'TEXT' },
    ];
    extras.forEach(({ table, col, type }) => {
        try { database.exec(`ALTER TABLE ${table} ADD COLUMN ${col} ${type}`); } catch (e) { /* já existe */ }
    });

    // Reset de senha do admin principal para 'admin123' (hash bcrypt $2b$)
    const ADMIN_HASH = '$2b$10$wqgL/0YvjcOMI6NGtYmjheQ6OMxMNsU7g8skW1YAosU4FdrTKzCce';
    try {
        const row = database.prepare("SELECT senha_usuarios FROM tbl_usuarios WHERE email_usuarios = 'adm.master@email.com'").get();
        if (row && !row.senha_usuarios.startsWith('$2b$')) {
            database.prepare("UPDATE tbl_usuarios SET senha_usuarios = ? WHERE email_usuarios = 'adm.master@email.com'").run(ADMIN_HASH);
        }
    } catch (e) { /* ignore */ }
}

module.exports = { getDatabase, saveDatabase };
