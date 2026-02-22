-- ==============================================================
-- KOKETSU - SCHEMA V2 (SQLITE - DESKTOP)
-- ==============================================================
-- Obs: SQLite não suporta alguns recursos do MySQL (ex: ENUM, AUTO_INCREMENT como palavra-chave).
-- O campo AUTOINCREMENT no SQLite sempre acompanha INTEGER PRIMARY KEY.

-- 1. USUARIOS
CREATE TABLE IF NOT EXISTS `tbl_usuarios` (
  `id_usuarios` INTEGER PRIMARY KEY AUTOINCREMENT,
  `nome_usuarios` TEXT NOT NULL,
  `email_usuarios` TEXT NOT NULL UNIQUE,
  `senha_usuarios` TEXT NOT NULL,
  `nivel_acesso` TEXT NOT NULL DEFAULT 'cliente',
  `foto_usuarios` TEXT DEFAULT NULL,
  `sincronizado` INTEGER DEFAULT 0,
  `criado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `excluido_em` DATETIME DEFAULT NULL
);

-- 2. CLIENTES
CREATE TABLE IF NOT EXISTS `tbl_clientes` (
  `id_cliente` INTEGER PRIMARY KEY AUTOINCREMENT,
  `id_usuarios` INTEGER DEFAULT NULL,
  `nome_clientes` TEXT NOT NULL,
  `email_clientes` TEXT DEFAULT NULL,
  `telefone_clientes` TEXT DEFAULT NULL,
  `cpf_cnpj_clientes` TEXT DEFAULT NULL,
  `data_nascimento_clientes` DATE DEFAULT NULL,
  `logradouro_clientes` TEXT DEFAULT NULL,
  `numero_clientes` TEXT DEFAULT NULL,
  `bairro_clientes` TEXT DEFAULT NULL,
  `cidade_clientes` TEXT DEFAULT NULL,
  `cep_clientes` TEXT DEFAULT NULL,
  `observacoes_clientes` TEXT DEFAULT NULL,
  `sincronizado` INTEGER DEFAULT 0,
  `criado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `excluido_em` DATETIME DEFAULT NULL,
  FOREIGN KEY (`id_usuarios`) REFERENCES `tbl_usuarios`(`id_usuarios`) ON DELETE SET NULL
);

-- 3. CATEGORIAS
CREATE TABLE IF NOT EXISTS `tbl_categorias` (
  `id_categorias` INTEGER PRIMARY KEY AUTOINCREMENT,
  `id_categoria_api` INTEGER DEFAULT NULL,
  `nome_categorias` TEXT NOT NULL,
  `descricao_categorias` TEXT DEFAULT NULL,
  `sincronizado` INTEGER DEFAULT 0,
  `criado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `excluido_em` DATETIME DEFAULT NULL
);

-- 4. PRODUTOS
CREATE TABLE IF NOT EXISTS `tbl_produtos` (
  `id_produto` INTEGER PRIMARY KEY AUTOINCREMENT,
  `id_produto_api` INTEGER DEFAULT NULL,
  `nome_produtos` TEXT NOT NULL,
  `descricao_produtos` TEXT DEFAULT NULL,
  `preco_produtos` REAL NOT NULL DEFAULT 0.00,
  `estoque_produtos` INTEGER NOT NULL DEFAULT 0,
  `imagem_produtos` TEXT DEFAULT NULL,
  `id_categoria` INTEGER DEFAULT NULL,
  `sincronizado` INTEGER DEFAULT 0,
  `criado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `excluido_em` DATETIME DEFAULT NULL,
  FOREIGN KEY (`id_categoria`) REFERENCES `tbl_categorias`(`id_categorias`) ON DELETE SET NULL
);

-- 5. TAMANHOS E CORES
CREATE TABLE IF NOT EXISTS `tbl_tamanhos` (
  `id_tamanhos` INTEGER PRIMARY KEY AUTOINCREMENT,
  `id_produto` INTEGER NOT NULL,
  `tamanho_tamanhos` TEXT NOT NULL,
  `quantidade_tamanhos` INTEGER NOT NULL DEFAULT 0,
  `sincronizado` INTEGER DEFAULT 0,
  `criado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `excluido_em` DATETIME DEFAULT NULL,
  FOREIGN KEY (`id_produto`) REFERENCES `tbl_produtos`(`id_produto`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `tbl_cores` (
  `id_cores` INTEGER PRIMARY KEY AUTOINCREMENT,
  `id_produto` INTEGER NOT NULL,
  `cor_cores` TEXT NOT NULL,
  `quantidade_cores` INTEGER NOT NULL DEFAULT 0,
  `sincronizado` INTEGER DEFAULT 0,
  `criado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `excluido_em` DATETIME DEFAULT NULL,
  FOREIGN KEY (`id_produto`) REFERENCES `tbl_produtos`(`id_produto`) ON DELETE CASCADE
);

-- 6. PEDIDOS
CREATE TABLE IF NOT EXISTS `tbl_pedidos` (
  `id_pedido` INTEGER PRIMARY KEY AUTOINCREMENT,
  `id_pedido_api` INTEGER DEFAULT NULL,
  `id_usuarios` INTEGER NOT NULL,
  `data_pedido` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `total_pedido` REAL NOT NULL DEFAULT 0.00,
  `status_pedido` TEXT DEFAULT 'pendente', -- SQLite doesn't have ENUM, we use TEXT
  `sincronizado` INTEGER DEFAULT 0,
  `criado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `excluido_em` DATETIME DEFAULT NULL,
  FOREIGN KEY (`id_usuarios`) REFERENCES `tbl_usuarios`(`id_usuarios`)
);

-- 7. ITENS DO PEDIDO
CREATE TABLE IF NOT EXISTS `tbl_itens_pedidos` (
  `id_itens_pedidos` INTEGER PRIMARY KEY AUTOINCREMENT,
  `id_pedido` INTEGER NOT NULL,
  `id_produto` INTEGER NOT NULL,
  `quantidade` INTEGER NOT NULL DEFAULT 1,
  `preco_unitario` REAL NOT NULL DEFAULT 0.00,
  `sincronizado` INTEGER DEFAULT 0,
  `criado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `excluido_em` DATETIME DEFAULT NULL,
  FOREIGN KEY (`id_pedido`) REFERENCES `tbl_pedidos`(`id_pedido`) ON DELETE CASCADE,
  FOREIGN KEY (`id_produto`) REFERENCES `tbl_produtos`(`id_produto`)
);

-- 8. BANNERS (Carousel)
CREATE TABLE IF NOT EXISTS `tbl_banners` (
  `id_carrossel` INTEGER PRIMARY KEY AUTOINCREMENT,
  `url_imagem_imagem_carrossel` TEXT NOT NULL,
  `link_destino_imagem_carrossel` TEXT DEFAULT NULL,
  `ordem_imagem_carrossel` INTEGER DEFAULT 0,
  `ativo_imagem_carrossel` INTEGER DEFAULT 1,
  `sincronizado` INTEGER DEFAULT 0,
  `criado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `excluido_em` DATETIME DEFAULT NULL
);

-- SEED BÁSICO (Para o admin conseguir logar no Desktop na primeira vez)
INSERT INTO `tbl_usuarios` (`nome_usuarios`, `email_usuarios`, `senha_usuarios`, `nivel_acesso`) VALUES
('Admin Desktop', 'admin@desktop.local', '$2b$10$wqgL/0YvjcOMI6NGtYmjheQ6OMxMNsU7g8skW1YAosU4FdrTKzCce', 'admin');
-- A senha acima é 'admin123' criptografada com bcryptjs ($2b$)
