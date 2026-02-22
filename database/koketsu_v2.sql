-- ==============================================================
-- KOKETSU - SCHEMA V2 (MYSQL - BACKEND)
-- ==============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "-03:00";

CREATE DATABASE IF NOT EXISTS `koketsu` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `koketsu`;

-- 1. USUARIOS
CREATE TABLE `tbl_usuarios` (
  `id_usuarios` int(11) NOT NULL AUTO_INCREMENT,
  `nome_usuarios` varchar(100) NOT NULL,
  `email_usuarios` varchar(150) NOT NULL UNIQUE,
  `senha_usuarios` varchar(255) NOT NULL,
  `nivel_acesso` varchar(50) NOT NULL DEFAULT 'cliente',
  `foto_usuarios` varchar(250) DEFAULT NULL,
  `sincronizado` tinyint(1) DEFAULT 1,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `excluido_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuarios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. CLIENTES (Dados adicionais para quem é nivel_acesso='cliente')
-- Renomeado de tbl_perfil para algo mais semântico ou mesclado, mas mantendo a estrutura pedida.
CREATE TABLE `tbl_clientes` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuarios` int(11) DEFAULT NULL,
  `nome_clientes` varchar(100) NOT NULL,
  `email_clientes` varchar(150) DEFAULT NULL,
  `telefone_clientes` varchar(20) DEFAULT NULL,
  `cpf_cnpj_clientes` varchar(20) DEFAULT NULL,
  `data_nascimento_clientes` date DEFAULT NULL,
  `logradouro_clientes` text DEFAULT NULL,
  `numero_clientes` varchar(20) DEFAULT NULL,
  `bairro_clientes` varchar(100) DEFAULT NULL,
  `cidade_clientes` varchar(100) DEFAULT NULL,
  `cep_clientes` varchar(20) DEFAULT NULL,
  `observacoes_clientes` text DEFAULT NULL,
  `sincronizado` tinyint(1) DEFAULT 1,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `excluido_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  FOREIGN KEY (`id_usuarios`) REFERENCES `tbl_usuarios`(`id_usuarios`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. CATEGORIAS
CREATE TABLE `tbl_categorias` (
  `id_categorias` int(11) NOT NULL AUTO_INCREMENT,
  `id_categoria_api` int(11) DEFAULT NULL, -- UUID/ID para sync
  `nome_categorias` varchar(100) NOT NULL,
  `descricao_categorias` text DEFAULT NULL,
  `sincronizado` tinyint(1) DEFAULT 1,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `excluido_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id_categorias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. PRODUTOS
CREATE TABLE `tbl_produtos` (
  `id_produto` int(11) NOT NULL AUTO_INCREMENT,
  `id_produto_api` int(11) DEFAULT NULL, -- Usado pelo desktop para saber o ID do backend
  `nome_produtos` varchar(100) NOT NULL,
  `descricao_produtos` text DEFAULT NULL,
  `preco_produtos` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estoque_produtos` int(11) NOT NULL DEFAULT 0,
  `imagem_produtos` varchar(255) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `sincronizado` tinyint(1) DEFAULT 1,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `excluido_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id_produto`),
  FOREIGN KEY (`id_categoria`) REFERENCES `tbl_categorias`(`id_categorias`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 5. TAMANHOS E CORES
CREATE TABLE `tbl_tamanhos` (
  `id_tamanhos` int(11) NOT NULL AUTO_INCREMENT,
  `id_produto` int(11) NOT NULL,
  `tamanho_tamanhos` varchar(10) NOT NULL,
  `quantidade_tamanhos` int(11) NOT NULL DEFAULT 0,
  `sincronizado` tinyint(1) DEFAULT 1,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `excluido_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id_tamanhos`),
  FOREIGN KEY (`id_produto`) REFERENCES `tbl_produtos`(`id_produto`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_cores` (
  `id_cores` int(11) NOT NULL AUTO_INCREMENT,
  `id_produto` int(11) NOT NULL,
  `cor_cores` varchar(50) NOT NULL,
  `quantidade_cores` int(11) NOT NULL DEFAULT 0,
  `sincronizado` tinyint(1) DEFAULT 1,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `excluido_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id_cores`),
  FOREIGN KEY (`id_produto`) REFERENCES `tbl_produtos`(`id_produto`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 6. PEDIDOS
CREATE TABLE `tbl_pedidos` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `id_pedido_api` int(11) DEFAULT NULL,
  `id_usuarios` int(11) NOT NULL,
  `data_pedido` datetime DEFAULT CURRENT_TIMESTAMP,
  `total_pedido` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status_pedido` enum('pendente','pago','enviado','concluido','cancelado') DEFAULT 'pendente',
  `sincronizado` tinyint(1) DEFAULT 1,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `excluido_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pedido`),
  FOREIGN KEY (`id_usuarios`) REFERENCES `tbl_usuarios`(`id_usuarios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 7. ITENS DO PEDIDO
CREATE TABLE `tbl_itens_pedidos` (
  `id_itens_pedidos` int(11) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `preco_unitario` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sincronizado` tinyint(1) DEFAULT 1,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `excluido_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id_itens_pedidos`),
  FOREIGN KEY (`id_pedido`) REFERENCES `tbl_pedidos`(`id_pedido`) ON DELETE CASCADE,
  FOREIGN KEY (`id_produto`) REFERENCES `tbl_produtos`(`id_produto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 8. BANNERS (Carousel)
CREATE TABLE `tbl_imagem_carrossel` (
  `id_carrossel` int(11) NOT NULL AUTO_INCREMENT,
  `url_imagem_imagem_carrossel` varchar(255) NOT NULL,
  `link_destino_imagem_carrossel` varchar(255) DEFAULT NULL,
  `ordem_imagem_carrossel` int(11) DEFAULT 0,
  `ativo_imagem_carrossel` tinyint(1) DEFAULT 1,
  `sincronizado` tinyint(1) DEFAULT 1,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `excluido_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id_carrossel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 9. OUTRAS TABELAS (Avaliações, Estoque, etc)
CREATE TABLE `tbl_avaliacoes` (
  `id_avaliacoes` int(11) NOT NULL AUTO_INCREMENT,
  `id_produto` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `nota_avaliacoes` int(11) DEFAULT NULL CHECK (`nota_avaliacoes` between 1 and 5),
  `comentario_avaliacoes` text DEFAULT NULL,
  `data_avaliacao_avaliacoes` datetime DEFAULT CURRENT_TIMESTAMP,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `excluido_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id_avaliacoes`),
  FOREIGN KEY (`id_produto`) REFERENCES `tbl_produtos`(`id_produto`),
  FOREIGN KEY (`id_cliente`) REFERENCES `tbl_usuarios`(`id_usuarios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_estoque_movimentacao` (
  `id_estoque_movimentacao` int(11) NOT NULL AUTO_INCREMENT,
  `id_produto` int(11) NOT NULL,
  `tipo_estoque_movimentacao` enum('entrada','saida') NOT NULL,
  `quantidade_estoque_movimentacao` int(11) NOT NULL,
  `data_estoque_movimentacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `descricao_estoque_movimentacao` text DEFAULT NULL,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `excluido_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id_estoque_movimentacao`),
  FOREIGN KEY (`id_produto`) REFERENCES `tbl_produtos`(`id_produto`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- SEED BÁSICO
INSERT INTO `tbl_usuarios` (`nome_usuarios`, `email_usuarios`, `senha_usuarios`, `nivel_acesso`) VALUES
('Admin Koketsu', 'admin@koketsu.com.br', '$2y$10$UTVGO6FkeBwT9zldZtGGqez9ja1AFpZaNEHx45g2kaRXM5teJW5V2', 'admin');
-- A senha acima é 'admin123' (exemplo)

COMMIT;
