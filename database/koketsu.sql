-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/02/2026 às 15:19
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `koketsu`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_avaliacoes`
--

CREATE TABLE `tbl_avaliacoes` (
  `id_avaliacoes` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `nota_avaliacoes` int(11) DEFAULT NULL CHECK (`nota_avaliacoes` between 1 and 5),
  `comentario_avaliacoes` text DEFAULT NULL,
  `data_avaliacao_avaliacoes` datetime DEFAULT current_timestamp(),
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `excluido_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_avaliacoes`
--

INSERT INTO `tbl_avaliacoes` (`id_avaliacoes`, `id_produto`, `id_cliente`, `nota_avaliacoes`, `comentario_avaliacoes`, `data_avaliacao_avaliacoes`, `criado_em`, `atualizado_em`, `excluido_em`) VALUES
(1, 1, 1, 1, 'Excelente camiseta, material de ótima qualidade.', '2025-08-28 11:40:33', NULL, '2026-02-10 09:04:08', NULL),
(2, 2, 2, 4, 'Calça jeans confortável, só a cor que é um pouco diferente da foto.', '2025-08-28 11:40:33', NULL, '2026-02-10 09:05:25', NULL),
(3, 3, 3, 5, 'O vestido é lindo e o caimento perfeito!', '2025-08-28 11:40:33', NULL, NULL, NULL),
(4, 4, 4, 4, 'Saia muito elegante, chegou rápido.', '2025-08-28 11:40:33', NULL, NULL, NULL),
(5, 5, 5, 5, 'Blusa de tricot macia e quentinha, adorei!', '2025-08-28 11:40:33', NULL, NULL, NULL),
(6, 6, 6, 3, 'A jaqueta é boa, mas o tamanho P ficou um pouco grande.', '2025-08-28 11:40:33', NULL, NULL, NULL),
(7, 7, 7, 5, 'Moletom super confortável e estiloso.', '2025-08-28 11:40:33', NULL, NULL, NULL),
(8, 8, 8, 4, 'Bermuda leve e ideal para exercícios.', '2025-08-28 11:40:33', NULL, '2026-02-10 09:03:45', NULL),
(9, 9, 9, 5, 'Ótimo cinto, ajustável e resistente.', '2025-08-28 11:40:33', NULL, NULL, NULL),
(10, 10, 10, 4, 'Tênis bonito, mas a forma é um pouco pequena.', '2025-08-28 11:40:33', NULL, NULL, NULL),
(11, 11, 11, 5, 'Biquíni de alta qualidade, veste super bem.', '2025-08-28 11:40:33', NULL, NULL, '2026-02-10 09:04:48'),
(12, 12, 12, 5, 'Lingerie linda e confortável, recomendo.', '2025-08-28 11:40:33', NULL, NULL, NULL),
(13, 13, 13, 4, 'Calça de academia muito boa, não fica transparente.', '2025-08-28 11:40:33', NULL, NULL, NULL),
(14, 14, 14, 5, 'Vestido infantil adorável, minha filha amou!', '2025-08-28 11:40:33', NULL, NULL, NULL),
(15, 15, 15, 5, 'Blusa plus size perfeita, caimento excelente.', '2025-08-28 11:40:33', NULL, '2026-02-10 09:12:13', NULL),
(16, 16, 16, 4, 'Conjunto esportivo de boa qualidade.', '2025-08-28 11:40:33', NULL, NULL, NULL),
(17, 17, 17, 5, 'Camiseta dry-fit ideal para treinar, leve e fresca.', '2025-08-28 11:40:33', NULL, NULL, '2026-02-10 09:12:20'),
(18, 18, 18, 3, 'Camisa social de bom tecido, a cor é exatamente como na foto.', '2025-08-28 11:40:33', NULL, '2026-02-10 09:03:28', NULL),
(19, 19, 19, 5, 'Calça jeans super confortável e estilosa.', '2025-08-28 11:40:33', NULL, NULL, NULL),
(20, 20, 20, 5, 'Macacão lindo e fresco, perfeito para o verão.', '2025-08-28 11:40:33', NULL, NULL, '2026-02-10 09:04:24'),
(22, 34, 18, 2, 'teste', '2026-02-10 09:17:54', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_carrinho`
--

CREATE TABLE `tbl_carrinho` (
  `id_carrinho` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `data_pedido_carrinho` datetime DEFAULT current_timestamp(),
  `total_carrinho` decimal(10,2) NOT NULL,
  `status_carrinho` varchar(50) DEFAULT 'Pendente',
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `excluido_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_carrinho`
--

INSERT INTO `tbl_carrinho` (`id_carrinho`, `id_perfil`, `data_pedido_carrinho`, `total_carrinho`, `status_carrinho`, `criado_em`, `atualizado_em`, `excluido_em`) VALUES
(1, 1, '2025-08-28 10:00:00', 49.90, 'aberto', NULL, NULL, NULL),
(2, 2, '2025-08-28 10:05:00', 129.90, 'aberto', NULL, NULL, NULL),
(3, 3, '2025-08-28 10:10:00', 89.90, 'fechado', NULL, NULL, NULL),
(4, 4, '2025-08-28 10:15:00', 75.00, 'aberto', NULL, NULL, NULL),
(5, 5, '2025-08-28 10:20:00', 99.90, 'fechado', NULL, NULL, NULL),
(6, 6, '2025-08-28 10:25:00', 189.90, 'aberto', NULL, NULL, NULL),
(7, 7, '2025-08-28 10:30:00', 110.00, 'fechado', NULL, NULL, NULL),
(8, 8, '2025-08-28 10:35:00', 65.00, 'aberto', NULL, NULL, NULL),
(9, 9, '2025-08-28 10:40:00', 55.00, 'fechado', NULL, NULL, NULL),
(10, 10, '2025-08-28 10:45:00', 170.00, 'aberto', NULL, NULL, NULL),
(11, 11, '2025-08-28 10:50:00', 85.00, 'fechado', NULL, NULL, NULL),
(12, 12, '2025-08-28 10:55:00', 79.90, 'aberto', NULL, NULL, NULL),
(13, 13, '2025-08-28 11:00:00', 95.00, 'fechado', NULL, NULL, NULL),
(14, 14, '2025-08-28 11:05:00', 55.00, 'aberto', NULL, NULL, NULL),
(15, 15, '2025-08-28 11:10:00', 70.00, 'fechado', NULL, NULL, NULL),
(16, 16, '2025-08-28 11:15:00', 150.00, 'aberto', NULL, NULL, NULL),
(17, 17, '2025-08-28 11:20:00', 59.90, 'fechado', NULL, NULL, NULL),
(18, 18, '2025-08-28 11:25:00', 120.00, 'aberto', NULL, NULL, NULL),
(19, 19, '2025-08-28 11:30:00', 135.00, 'fechado', NULL, NULL, NULL),
(20, 20, '2025-08-28 11:35:00', 115.00, 'aberto', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_categorias`
--

CREATE TABLE `tbl_categorias` (
  `id_categorias` int(11) NOT NULL,
  `nome_categorias` varchar(100) NOT NULL,
  `descricao_categorias` text DEFAULT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `excluido_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_categorias`
--

INSERT INTO `tbl_categorias` (`id_categorias`, `nome_categorias`, `descricao_categorias`, `criado_em`, `atualizado_em`, `excluido_em`) VALUES
(1, 'Camisetas', 'Categoria de roupas', '2025-08-26 11:47:45', '2025-09-18 14:17:09', NULL),
(2, 'Calças', 'Calças jeans, sarja e moletom', '2025-08-26 11:47:45', NULL, NULL),
(3, 'Tênis', 'Tênis esportivos e casuais', '2025-08-26 11:47:45', NULL, '2025-09-16 16:48:19'),
(4, 'Jaquetas', 'Jaquetas jeans, couro e corta-vento', '2025-08-26 11:47:45', NULL, NULL),
(5, 'Acessórios', 'Bonés, cintos e mochilas', '2025-08-26 11:47:45', NULL, NULL),
(6, 'Camisetas', 'Camisetas em algodão, poliéster e mistas', '2025-08-28 10:59:07', NULL, NULL),
(8, 'Bermudas', 'Bermudas casuais e esportivas', '2025-08-28 10:59:07', NULL, NULL),
(9, 'Jaquetas', 'Jaquetas jeans, couro e corta-vento', '2025-08-28 10:59:07', NULL, NULL),
(10, 'Moletons', 'Moletons com e sem capuz', '2025-08-28 10:59:07', NULL, NULL),
(11, 'Vestidos', 'Vestidos casuais e sociais', '2025-08-28 10:59:07', NULL, NULL),
(12, 'Saias', 'Saias curtas, midi e longas', '2025-08-28 10:59:07', NULL, NULL),
(13, 'Camisas Sociais', 'Camisas para trabalho e eventos', '2025-08-28 10:59:07', NULL, NULL),
(14, 'Blusas', 'Blusas básicas e fashion', '2025-08-28 10:59:07', NULL, NULL),
(15, 'Shorts', 'Shorts jeans e tecido', '2025-08-28 10:59:07', NULL, NULL),
(16, 'Polos', 'Camisas polo variadas', '2025-08-28 10:59:07', NULL, NULL),
(17, 'Macacões', 'Macacões curtos e longos', '2025-08-28 10:59:07', NULL, NULL),
(18, 'Acessórios', 'Cintos, carteiras, óculos etc.', '2025-08-28 10:59:07', NULL, NULL),
(19, 'Bonés', 'Bonés aba reta e curva', '2025-08-28 10:59:07', NULL, NULL),
(20, 'Meias', 'Meias esportivas e sociais', '2025-08-28 10:59:07', NULL, NULL),
(21, 'Roupa Íntima', 'Lingerie e cuecas', '2025-08-28 10:59:07', NULL, NULL),
(22, 'Moda Praia', 'Biquínis, sungas e saídas', '2025-08-28 10:59:07', NULL, NULL),
(23, 'Calçados', 'Tênis, botas e sandálias', '2025-08-28 10:59:07', NULL, NULL),
(24, 'Fitness', 'Roupas para academia', '2025-08-28 10:59:07', NULL, NULL),
(25, 'Plus Size', 'Modelagens especiais e confortáveis', '2025-08-28 10:59:07', NULL, NULL),
(26, 'Camisetas', 'Camisetas em algodão, poliéster e mistas', '2025-08-28 11:23:33', NULL, NULL),
(27, 'Calças', 'Calças jeans, sarja e alfaiataria', '2025-08-28 11:23:33', NULL, NULL),
(28, 'Bermudas', 'Bermudas casuais e esportivas', '2025-08-28 11:23:33', NULL, NULL),
(29, 'Jaquetas', 'Jaquetas jeans, couro e corta-vento', '2025-08-28 11:23:33', NULL, NULL),
(30, 'Moletons', 'Moletons com e sem capuz', '2025-08-28 11:23:33', NULL, NULL),
(31, 'Vestidos', 'Vestidos casuais e sociais', '2025-08-28 11:23:33', NULL, NULL),
(32, 'Saias', 'Saias curtas, midi e longas', '2025-08-28 11:23:33', NULL, NULL),
(33, 'Camisas Sociais', 'Camisas para trabalho e eventos', '2025-08-28 11:23:33', NULL, NULL),
(34, 'Blusas', 'Blusas básicas e fashion', '2025-08-28 11:23:33', NULL, NULL),
(35, 'Shorts', 'Shorts jeans e tecido', '2025-08-28 11:23:33', NULL, NULL),
(36, 'Polos', 'Camisas polo variadas', '2025-08-28 11:23:33', NULL, NULL),
(37, 'Macacões', 'Macacões curtos e longos', '2025-08-28 11:23:33', NULL, NULL),
(38, 'Acessórios', 'Cintos, carteiras, óculos etc.', '2025-08-28 11:23:33', NULL, NULL),
(39, 'Bonés', 'Bonés aba reta e curva', '2025-08-28 11:23:33', NULL, NULL),
(40, 'Meias', 'Meias esportivas e sociais', '2025-08-28 11:23:33', NULL, NULL),
(41, 'Roupa Íntima', 'Lingerie e cuecas', '2025-08-28 11:23:33', NULL, NULL),
(42, 'Moda Praia', 'Biquínis, sungas e saídas', '2025-08-28 11:23:33', NULL, NULL),
(43, 'Calçados', 'Tênis, botas e sandálias', '2025-08-28 11:23:33', NULL, NULL),
(44, 'Fitness', 'Roupas para academia', '2025-08-28 11:23:33', NULL, NULL),
(45, 'Plus Size', 'Modelagens especiais e confortáveis', '2025-08-28 11:23:33', NULL, NULL),
(46, 'arthur', 'clash royale\r\n', NULL, NULL, NULL),
(47, 'arthur', 'carton flx e muito top', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_cores`
--

CREATE TABLE `tbl_cores` (
  `id_cores` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `cor_cores` varchar(50) NOT NULL,
  `quantidade_cores` int(11) NOT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `excluido_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_cores`
--

INSERT INTO `tbl_cores` (`id_cores`, `id_produto`, `cor_cores`, `quantidade_cores`, `criado_em`, `atualizado_em`, `excluido_em`) VALUES
(1, 1, 'Branco', 80, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(2, 2, 'Preto', 70, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(3, 3, 'Azul Escuro', 40, '2025-08-28 11:05:15', '2025-08-28 11:05:15', '2025-09-16 16:52:20'),
(4, 3, 'Azul claro', 30, '2025-08-28 11:05:15', '2025-09-18 13:44:43', NULL),
(5, 4, 'Bege', 25, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(6, 4, 'Marrom', 20, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(7, 5, 'Azul Jeans', 50, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(8, 5, 'Preto', 30, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(9, 6, 'Cinza', 40, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(10, 6, 'Preto', 30, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(11, 7, 'Azul Jeans', 25, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(12, 7, 'Preto', 20, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(13, 8, 'Preto', 30, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(14, 8, 'Marrom', 10, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(15, 9, 'Cinza', 40, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(16, 9, 'Preto', 30, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(17, 10, 'Azul Marinho', 30, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(18, 10, 'Preto', 20, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(19, 11, 'Estampado Floral', 35, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(20, 12, 'Preto', 20, '2025-08-28 11:05:15', '2025-08-28 11:05:15', NULL),
(21, 5, 'amarelo', 18, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_estoque_movimentacao`
--

CREATE TABLE `tbl_estoque_movimentacao` (
  `id_estoque_movimentacao` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `tipo_estoque_movimentacao` enum('disponivel','indisponivel') NOT NULL,
  `quantidade_estoque_movimentacao` int(11) NOT NULL,
  `data_estoque_movimentacao` datetime DEFAULT current_timestamp(),
  `descricao_estoque_movimentacao` text DEFAULT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `excluido_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_estoque_movimentacao`
--

INSERT INTO `tbl_estoque_movimentacao` (`id_estoque_movimentacao`, `id_produto`, `tipo_estoque_movimentacao`, `quantidade_estoque_movimentacao`, `data_estoque_movimentacao`, `descricao_estoque_movimentacao`, `criado_em`, `atualizado_em`, `excluido_em`) VALUES
(1, 1, '', 150, '2025-08-15 00:00:00', 'Estoque inicial de Camiseta Casual', NULL, NULL, NULL),
(2, 2, '', 80, '2025-08-15 00:00:00', 'Estoque inicial de Calça Jeans Skinny', NULL, NULL, NULL),
(3, 3, '', 50, '2025-08-15 00:00:00', 'Estoque inicial de Vestido Florido', NULL, NULL, NULL),
(4, 4, '', 45, '2025-08-15 00:00:00', 'Estoque inicial de Saia Plissada', NULL, NULL, NULL),
(5, 5, '', 60, '2025-08-15 00:00:00', 'Estoque inicial de Blusa de Tricot', NULL, NULL, NULL),
(6, 6, '', 30, '2025-08-15 00:00:00', 'Estoque inicial de Jaqueta Jeans', NULL, NULL, NULL),
(7, 7, '', 70, '2025-08-15 00:00:00', 'Estoque inicial de Moletom Canguru', NULL, NULL, NULL),
(8, 8, '', 90, '2025-08-15 00:00:00', 'Estoque inicial de Bermuda Esportiva', NULL, NULL, NULL),
(9, 9, '', 120, '2025-08-15 00:00:00', 'Estoque inicial de Cinto de Couro', NULL, NULL, NULL),
(10, 10, '', 100, '2025-08-15 00:00:00', 'Estoque inicial de Tênis Casual', NULL, NULL, NULL),
(11, 11, '', 70, '2025-08-15 00:00:00', 'Estoque inicial de Biquíni', NULL, NULL, NULL),
(12, 12, '', 65, '2025-08-15 00:00:00', 'Estoque inicial de Lingerie', NULL, NULL, NULL),
(13, 13, '', 110, '2025-08-15 00:00:00', 'Estoque inicial de Calça Legging Fitness', NULL, NULL, NULL),
(14, 14, '', 85, '2025-08-15 00:00:00', 'Estoque inicial de Vestido Infantil', NULL, NULL, NULL),
(15, 15, '', 40, '2025-08-15 00:00:00', 'Estoque inicial de Blusa Plus Size', NULL, NULL, NULL),
(16, 16, '', 50, '2025-08-15 00:00:00', 'Estoque inicial de Conjunto de Moletons', NULL, NULL, NULL),
(17, 17, '', 80, '2025-08-15 00:00:00', 'Estoque inicial de Camiseta Dry-fit', NULL, NULL, NULL),
(18, 18, '', 75, '2025-08-15 00:00:00', 'Estoque inicial de Camisa Social', NULL, NULL, NULL),
(19, 19, '', 60, '2025-08-15 00:00:00', 'Estoque inicial de Calça Jeans Reta', NULL, NULL, NULL),
(20, 20, '', 55, '2025-08-15 00:00:00', 'Estoque inicial de Macacão', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_imagem`
--

CREATE TABLE `tbl_imagem` (
  `id_imagem` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_cor` int(11) NOT NULL,
  `id_tamanho` int(11) NOT NULL,
  `caminho_imagem` varchar(255) NOT NULL,
  `descricao_imagem` varchar(100) DEFAULT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `excluido_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_imagem`
--

INSERT INTO `tbl_imagem` (`id_imagem`, `id_produto`, `id_cor`, `id_tamanho`, `caminho_imagem`, `descricao_imagem`, `criado_em`, `atualizado_em`, `excluido_em`) VALUES
(1, 1, 1, 1, 'img/camiseta_branca_p.jpg', 'Camiseta branca tamanho P', '0000-00-00 00:00:00', NULL, NULL),
(2, 1, 1, 2, 'img/camiseta_branca_m.jpg', 'Camiseta branca tamanho M', '0000-00-00 00:00:00', NULL, NULL),
(3, 1, 1, 3, 'img/camiseta_branca_g.jpg', 'Camiseta branca tamanho G', '0000-00-00 00:00:00', NULL, NULL),
(4, 2, 2, 4, 'img/camiseta_preta_p.jpg', 'Camiseta preta tamanho P', '0000-00-00 00:00:00', NULL, NULL),
(5, 2, 2, 5, 'img/camiseta_preta_m.jpg', 'Camiseta preta tamanho M', '0000-00-00 00:00:00', NULL, NULL),
(6, 2, 2, 6, 'img/camiseta_preta_g.jpg', 'Camiseta preta tamanho G', '0000-00-00 00:00:00', NULL, NULL),
(7, 3, 3, 7, 'img/calca_jeans_38.jpg', 'Calça jeans azul escuro 38', '0000-00-00 00:00:00', NULL, NULL),
(8, 3, 3, 8, 'img/calca_jeans_40.jpg', 'Calça jeans azul escuro 40', '0000-00-00 00:00:00', NULL, NULL),
(9, 3, 3, 9, 'img/calca_jeans_42.jpg', 'Calça jeans azul escuro 42', '0000-00-00 00:00:00', NULL, NULL),
(10, 4, 5, 10, 'img/calca_sarja_36.jpg', 'Calça sarja bege 36', '0000-00-00 00:00:00', NULL, NULL),
(11, 4, 5, 11, 'img/calca_sarja_38.jpg', 'Calça sarja bege 38', '0000-00-00 00:00:00', NULL, NULL),
(12, 4, 5, 12, 'img/calca_sarja_40.jpg', 'Calça sarja bege 40', '0000-00-00 00:00:00', NULL, NULL),
(13, 5, 7, 13, 'img/bermuda_jeans_p.jpg', 'Bermuda jeans azul P', '0000-00-00 00:00:00', NULL, NULL),
(14, 5, 7, 14, 'img/bermuda_jeans_m.jpg', 'Bermuda jeans azul M', '0000-00-00 00:00:00', NULL, NULL),
(15, 5, 7, 15, 'img/bermuda_jeans_g.jpg', 'Bermuda jeans azul G', '0000-00-00 00:00:00', NULL, NULL),
(16, 6, 9, 16, 'img/bermuda_moletom_p.jpg', 'Bermuda moletom cinza P', '0000-00-00 00:00:00', NULL, NULL),
(17, 6, 9, 17, 'img/bermuda_moletom_m.jpg', 'Bermuda moletom cinza M', '0000-00-00 00:00:00', NULL, NULL),
(18, 6, 9, 18, 'img/bermuda_moletom_g.jpg', 'Bermuda moletom cinza G', '0000-00-00 00:00:00', NULL, NULL),
(19, 7, 11, 19, 'img/jaqueta_jeans_m.jpg', 'Jaqueta jeans azul M', '0000-00-00 00:00:00', NULL, NULL),
(20, 7, 11, 20, 'img/jaqueta_jeans_g.jpg', 'Jaqueta jeans azul G', '0000-00-00 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_imagem_carrossel`
--

CREATE TABLE `tbl_imagem_carrossel` (
  `id_carrossel` int(11) NOT NULL,
  `url_imagem_imagem_carrossel` varchar(255) NOT NULL,
  `link_destino_imagem_carrossel` varchar(255) DEFAULT NULL,
  `ordem_imagem_carrossel` int(11) DEFAULT 0,
  `ativo_imagem_carrossel` tinyint(1) DEFAULT 1,
  `criado_em` datetime DEFAULT current_timestamp(),
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `excluido_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_imagem_carrossel`
--

INSERT INTO `tbl_imagem_carrossel` (`id_carrossel`, `url_imagem_imagem_carrossel`, `link_destino_imagem_carrossel`, `ordem_imagem_carrossel`, `ativo_imagem_carrossel`, `criado_em`, `atualizado_em`, `excluido_em`) VALUES
(1, 'https://site.com/img1.jpg', 'https://site.com/promocao1', 1, 1, '2025-09-01 10:00:00', '2025-09-01 10:00:00', NULL),
(2, 'https://site.com/img2.jpg', 'https://site.com/promocao2', 2, 1, '2025-09-01 10:01:00', '2025-09-01 10:01:00', NULL),
(3, 'https://site.com/img3.jpg', 'https://site.com/promocao3', 3, 1, '2025-09-01 10:02:00', '2025-09-01 10:02:00', NULL),
(4, 'https://site.com/img4.jpg', 'https://site.com/promocao4', 4, 1, '2025-09-01 10:03:00', '2025-09-01 10:03:00', NULL),
(5, 'https://site.com/img5.jpg', 'https://site.com/promocao5', 5, 1, '2025-09-01 10:04:00', '2025-09-01 10:04:00', NULL),
(6, 'https://site.com/img6.jpg', 'https://site.com/promocao6', 6, 1, '2025-09-01 10:05:00', '2025-09-01 10:05:00', NULL),
(7, 'https://site.com/img7.jpg', 'https://site.com/promocao7', 7, 1, '2025-09-01 10:06:00', '2025-09-01 10:06:00', NULL),
(8, 'https://site.com/img8.jpg', 'https://site.com/promocao8', 8, 1, '2025-09-01 10:07:00', '2025-09-01 10:07:00', NULL),
(9, 'https://site.com/img9.jpg', 'https://site.com/promocao9', 9, 1, '2025-09-01 10:08:00', '2025-09-01 10:08:00', NULL),
(10, 'https://site.com/img10.jpg', 'https://site.com/promocao10', 10, 1, '2025-09-01 10:09:00', '2025-09-01 10:09:00', NULL),
(11, 'https://site.com/img11.jpg', 'https://site.com/promocao11', 11, 1, '2025-09-01 10:10:00', '2025-09-01 10:10:00', NULL),
(12, 'https://site.com/img12.jpg', 'https://site.com/promocao12', 12, 1, '2025-09-01 10:11:00', '2025-09-01 10:11:00', NULL),
(13, 'https://site.com/img13.jpg', 'https://site.com/promocao13', 13, 1, '2025-09-01 10:12:00', '2025-09-01 10:12:00', NULL),
(14, 'https://site.com/img14.jpg', 'https://site.com/promocao14', 14, 1, '2025-09-01 10:13:00', '2025-09-01 10:13:00', NULL),
(15, 'https://site.com/img15.jpg', 'https://site.com/promocao15', 15, 1, '2025-09-01 10:14:00', '2025-09-01 10:14:00', NULL),
(16, 'https://site.com/img16.jpg', 'https://site.com/promocao16', 16, 1, '2025-09-01 10:15:00', '2025-09-01 10:15:00', NULL),
(17, 'https://site.com/img17.jpg', 'https://site.com/promocao17', 17, 1, '2025-09-01 10:16:00', '2025-09-01 10:16:00', NULL),
(18, 'https://site.com/img18.jpg', 'https://site.com/promocao18', 18, 1, '2025-09-01 10:17:00', '2025-09-01 10:17:00', NULL),
(19, 'https://site.com/img19.jpg', 'https://site.com/promocao19', 19, 1, '2025-09-01 10:18:00', '2025-09-01 10:18:00', NULL),
(20, 'https://site.com/img20.jpg', 'https://site.com/promocao20', 20, 1, '2025-09-01 10:19:00', '2025-09-01 10:19:00', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_itens_pedidos`
--

CREATE TABLE `tbl_itens_pedidos` (
  `id_itens_pedidos` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `excluido_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_itens_pedidos`
--

INSERT INTO `tbl_itens_pedidos` (`id_itens_pedidos`, `id_pedido`, `id_produto`, `quantidade`, `preco_unitario`, `criado_em`, `atualizado_em`, `excluido_em`) VALUES
(1, 1, 1, 10, 49.90, NULL, NULL, NULL),
(2, 2, 2, 19, 129.90, NULL, '2026-02-04 15:19:03', NULL),
(3, 3, 3, 10, 89.90, NULL, '2026-02-04 15:18:54', NULL),
(4, 4, 4, 16, 75.00, NULL, '2026-02-04 15:18:50', NULL),
(5, 5, 5, 18, 99.90, NULL, '2026-02-04 15:18:44', NULL),
(6, 6, 6, 14, 189.90, NULL, '2026-02-04 15:18:38', NULL),
(7, 7, 7, 23, 110.00, NULL, '2026-02-04 15:18:02', NULL),
(8, 8, 8, 18, 65.00, NULL, '2026-02-04 15:17:58', NULL),
(9, 9, 9, 12, 55.00, NULL, '2026-02-04 15:17:52', NULL),
(10, 10, 10, 100, 170.00, NULL, '2026-02-04 15:17:44', NULL),
(11, 11, 11, 13, 85.00, NULL, '2026-02-04 15:17:36', NULL),
(12, 12, 12, 11, 79.90, NULL, '2026-02-04 15:17:31', NULL),
(13, 13, 13, 5, 95.00, NULL, '2026-02-04 15:17:25', NULL),
(14, 14, 14, 9, 55.00, NULL, '2026-02-04 15:17:19', NULL),
(15, 15, 15, 38, 70.00, NULL, '2026-02-04 15:17:08', NULL),
(16, 16, 16, 23, 150.00, NULL, '2026-02-04 15:16:55', NULL),
(17, 17, 17, 25, 59.90, NULL, '2026-02-04 15:16:49', NULL),
(18, 18, 18, 9, 120.00, NULL, '2026-02-04 15:16:43', NULL),
(19, 19, 19, 7, 135.00, NULL, '2026-02-04 15:16:38', NULL),
(20, 20, 20, 5, 115.00, NULL, '2026-02-04 15:16:34', NULL),
(21, 102, 34, 9, 12.00, '2026-02-04 11:25:47', NULL, NULL),
(22, 103, 36, 2, 65.00, '2026-02-05 11:42:00', NULL, NULL),
(23, 104, 17, 24, 23.00, '2026-02-06 09:19:40', '2026-02-06 13:25:24', NULL),
(24, 105, 4, 23, 24.00, '2026-02-06 09:28:06', NULL, NULL),
(25, 106, 8, 10, 80.00, '2026-02-06 09:58:46', '2026-02-11 13:27:51', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_pedidos`
--

CREATE TABLE `tbl_pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `data_pedido` datetime DEFAULT current_timestamp(),
  `total_pedido` decimal(10,2) NOT NULL,
  `status_pedido` enum('pendente','pago','enviado','concluido','cancelado') DEFAULT 'pendente',
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `excluido_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_pedidos`
--

INSERT INTO `tbl_pedidos` (`id_pedido`, `id_perfil`, `data_pedido`, `total_pedido`, `status_pedido`, `criado_em`, `atualizado_em`, `excluido_em`) VALUES
(1, 1, '2025-08-28 11:22:43', 159.80, 'pago', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(2, 2, '2025-08-28 11:22:00', 229.90, 'cancelado', '2025-08-28 11:22:43', '2026-02-05 15:53:28', NULL),
(3, 3, '2025-08-28 11:22:00', 89.90, 'pago', '2025-08-28 11:22:43', '2026-02-06 12:28:54', NULL),
(4, 4, '2025-08-28 11:22:43', 319.70, 'concluido', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(5, 5, '2025-08-28 11:22:43', 199.90, 'cancelado', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(6, 6, '2025-08-28 11:22:43', 179.90, 'pago', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(7, 7, '2025-08-28 11:22:43', 249.90, 'pendente', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(8, 8, '2025-08-28 11:22:43', 119.90, 'enviado', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(9, 9, '2025-08-28 11:22:43', 459.60, 'pago', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(10, 10, '2025-08-28 11:22:43', 139.90, 'concluido', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(11, 11, '2025-08-28 11:22:43', 69.90, 'pago', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(12, 12, '2025-08-28 11:22:43', 189.90, 'pendente', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(13, 13, '2025-08-28 11:22:43', 209.90, 'pago', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(14, 14, '2025-08-28 11:22:43', 349.70, 'enviado', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(15, 15, '2025-08-28 11:22:43', 99.90, 'pago', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(16, 16, '2025-08-28 11:22:43', 159.90, 'cancelado', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(17, 17, '2025-08-28 11:22:43', 279.80, 'pago', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(18, 18, '2025-08-28 11:22:43', 199.90, 'pendente', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(19, 19, '2025-08-28 11:22:43', 89.90, 'enviado', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(20, 20, '2025-08-28 11:22:43', 499.90, 'pago', '2025-08-28 11:22:43', '2025-08-28 11:22:43', NULL),
(102, 20, '2026-02-04 00:00:00', 200.00, 'cancelado', '2026-02-04 11:25:47', NULL, NULL),
(103, 11, '2026-02-05 00:00:00', 130.00, 'pendente', '2026-02-05 11:42:00', NULL, NULL),
(104, 12, '2026-02-06 00:00:00', 123.00, 'pendente', '2026-02-06 09:19:40', NULL, NULL),
(105, 14, '2026-02-06 00:00:00', 123.00, 'cancelado', '2026-02-06 09:28:06', NULL, NULL),
(106, 7, '2026-02-06 00:00:00', 800.00, 'pendente', '2026-02-06 09:58:46', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_perfil`
--

CREATE TABLE `tbl_perfil` (
  `id_perfil` int(11) NOT NULL,
  `telefone_perfil` varchar(20) DEFAULT NULL,
  `endereco_perfil` text DEFAULT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp(),
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `excluido_em` datetime DEFAULT NULL,
  `id_usuarios` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_perfil`
--

INSERT INTO `tbl_perfil` (`id_perfil`, `telefone_perfil`, `endereco_perfil`, `data_cadastro`, `criado_em`, `atualizado_em`, `excluido_em`, `id_usuarios`) VALUES
(1, '11988887777', 'Rua das Flores, 123 - São Paulo/SP', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, NULL),
(2, '21999996666', 'Av. Brasil, 456 - Rio de Janeiro/RJ', '2026-02-05 00:00:00', '2025-08-28 11:04:11', '2026-02-05 15:49:13', NULL, 57),
(3, '31988885555', 'Rua Goiás, 789 - Belo Horizonte/MG', '2026-02-04 00:00:00', '2025-08-28 11:04:11', '2026-02-04 16:01:38', NULL, 57),
(4, '41977774444', 'Rua Paraná, 321 - Curitiba/PR', '2026-02-04 00:00:00', '2025-08-28 11:04:11', '2026-02-04 15:56:45', NULL, 57),
(5, '51966663333', 'Rua Central, 654 - Porto Alegre/RS', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, 58),
(6, '61955552222', 'Rua Brasília, 987 - Brasília/DF', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, 59),
(7, '71944441111', 'Rua Salvador, 147 - Salvador/BA', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, 60),
(8, '81933339999', 'Av. Recife, 258 - Recife/PE', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, 56),
(9, '11922228888', 'Rua Paulista, 369 - São Paulo/SP', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, 62),
(10, '21911117777', 'Rua Copacabana, 741 - Rio de Janeiro/RJ', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, NULL),
(11, '31900009999', 'Rua Pampulha, 852 - Belo Horizonte/MG', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, NULL),
(12, '41988880000', 'Av. Batel, 963 - Curitiba/PR', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, NULL),
(13, '51977779999', 'Rua Ipiranga, 159 - Porto Alegre/RS', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, NULL),
(14, '61966668888', 'Av. JK, 357 - Brasília/DF', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, NULL),
(15, '71955550000', 'Rua Barra, 753 - Salvador/BA', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, NULL),
(16, '81944443333', 'Rua Boa Vista, 951 - Recife/PE', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, NULL),
(17, '11933334444', 'Av. Faria Lima, 147 - São Paulo/SP', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, NULL),
(18, '21922223333', 'Rua Flamengo, 258 - Rio de Janeiro/RJ', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, NULL),
(19, '31911112222', 'Rua Savassi, 369 - Belo Horizonte/MG', '2025-08-28 11:04:11', '2025-08-28 11:04:11', NULL, NULL, NULL),
(20, '41900001111', 'Av. XV de Novembro, 741 - Curitiba/PR', '2026-02-05 00:00:00', '2025-08-28 11:04:11', '2026-02-05 12:23:26', NULL, NULL),
(21, '11984203485', 'rua jose coelho', '2025-10-16 00:00:00', NULL, NULL, NULL, 0),
(22, '11984203485', 'rua jose coelha', '2025-10-16 00:00:00', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_preferencias`
--

CREATE TABLE `tbl_preferencias` (
  `id_preferencia` int(11) NOT NULL,
  `id_usuarios` int(11) NOT NULL,
  `tamanho_camiseta` varchar(5) DEFAULT NULL,
  `tamanho_calca` varchar(5) DEFAULT NULL,
  `tamanho_calcado` varchar(5) DEFAULT NULL,
  `notif_pedidos` tinyint(1) DEFAULT 1,
  `notif_ofertas` tinyint(1) DEFAULT 1,
  `notif_whatsapp` tinyint(1) DEFAULT 0,
  `dois_fatores_ativo` tinyint(1) DEFAULT 0,
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_produtos`
--

CREATE TABLE `tbl_produtos` (
  `id_produto` int(11) NOT NULL,
  `nome_produtos` varchar(100) NOT NULL,
  `descricao_produtos` text DEFAULT NULL,
  `preco_produtos` decimal(10,2) NOT NULL,
  `estoque_produtos` int(11) NOT NULL,
  `imagem_produtos` varchar(255) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `excluido_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_produtos`
--

INSERT INTO `tbl_produtos` (`id_produto`, `nome_produtos`, `descricao_produtos`, `preco_produtos`, `estoque_produtos`, `imagem_produtos`, `id_categoria`, `criado_em`, `atualizado_em`, `excluido_em`) VALUES
(1, 'camisas', '  camisa branca ', 59.90, 50, 'produtos/69835b7212a1a0.41141087.png', 1, '2025-08-26 11:47:45', '2026-02-04 11:45:06', NULL),
(2, 'Camiseta social', '      Camiseta preta justa, confortável e estilosa', 69.90, 40, 'produtos/69835b7f2ca753.72444365.png', 1, '2025-08-26 11:47:45', '2026-02-04 11:45:19', NULL),
(3, 'Calças', ' Calça jeans slim fit azul escuro', 149.90, 1, 'produtos/698485a70e1832.28204203.png', 2, '2025-08-26 11:47:45', '2026-02-05 08:57:27', NULL),
(4, 'Calça Moletom Cinza', '  Calça moletom unissex, confortável ', 129.90, 20, 'produtos/698485bbca8613.47771737.jpg', 2, '2025-08-26 11:47:45', '2026-02-05 08:57:47', NULL),
(5, 'Tênis ', '   Tênis esportivo leve para corrida ', 299.90, 20, 'produtos/698485c94a8fd6.55247753.png', 3, '2025-08-26 11:47:45', '2026-02-05 08:58:01', NULL),
(6, 'Tênis Casual Branco', '  Tênis branco casual em couro sintético ', 259.90, 23, 'produtos/698485de653c81.31360115.jpg', 3, '2025-08-26 11:47:45', '2026-02-05 08:58:22', NULL),
(7, 'Jaquetas', ' Jaqueta jeans masculina oversized ', 199.90, 15, 'produtos/698485ee9b6f08.69891999.jpg', 4, '2025-08-26 11:47:45', '2026-02-05 08:58:38', NULL),
(8, 'Jaqueta Corta-Vento Vermelha', ' Ideal para treinos e dias de vento', 179.90, 10, 'produtos/698486290460e4.70440603.jpg', 4, '2025-08-26 11:47:45', '2026-02-05 08:59:37', NULL),
(9, 'Bonés', '  Boné estilo trucker preto básico ', 79.90, 35, 'produtos/698486341f31c4.84420674.jpg', 19, '2025-08-26 11:47:45', '2026-02-05 08:59:48', NULL),
(10, 'moletom', '    Mochila leve com compartimento para notebook', 199.90, 1, 'produtos/6984864a4a4e65.05454892.jpg', 10, '2025-08-26 11:47:45', '2026-02-05 09:00:10', NULL),
(11, 'Camiseta Básica Branca', ' Camiseta unissex 100% algodão', 39.90, 150, 'produtos/69848676c807c9.43664354.jpg', 1, '2025-08-28 11:05:05', '2026-02-05 09:00:54', NULL),
(12, 'Camiseta Preta Slim', ' Camiseta preta modelagem slim', 49.90, 120, 'produtos/698486b889f531.75144818.jpg', 1, '2025-08-28 11:05:05', '2026-02-05 09:02:00', NULL),
(13, 'Calça Jeans Azul', '  Calça jeans tradicional masculina ', 119.90, 80, 'produtos/698486d4194f03.60750811.jpg', 2, '2025-08-28 11:05:05', '2026-02-05 09:02:28', NULL),
(14, 'Calça Sarja Bege', '  Calça sarja slim feminina', 139.90, 13, 'produtos/69848e37defdc2.86435686.jpg', 2, '2025-08-28 11:05:05', '2026-02-05 09:33:59', NULL),
(15, 'Bermuda ', '  Bermuda jeans casual masculina', 89.90, 100, 'produtos/6984a9f996cea4.48602829.jpg', 15, '2025-08-28 11:05:05', '2026-02-06 11:45:53', NULL),
(16, 'Bermuda Moletom', ' Bermuda de moletom confortável', 69.90, 70, 'produtos/6984aa662b86f8.01544028.jpg', 15, '2025-08-28 11:05:05', '2026-02-05 11:34:14', NULL),
(17, 'Jaqueta Jeans', ' Jaqueta jeans azul escura', 179.90, 50, 'produtos/6984aab98671c5.23418330.jpg', 4, '2025-08-28 11:05:05', '2026-02-05 11:35:37', NULL),
(18, 'Jaqueta Couro', ' Jaqueta de couro sintético', 229.90, 40, 'produtos/6984aaf7d26e52.87402084.jpg', 4, '2025-08-28 11:05:05', '2026-02-05 11:36:39', NULL),
(19, 'Moletom Canguru', '  Moletom com capuz e bolso frontal', 149.90, 90, 'produtos/6984ab1baebbf2.19480972.jpg', 10, '2025-08-28 11:05:05', '2026-02-05 11:37:15', NULL),
(20, 'Moletom Zíper', '  Moletom com zíper frontal', 159.90, 85, 'produtos/6985feda51a738.72440903.jpg', 10, '2025-08-28 11:05:05', '2026-02-06 11:46:50', NULL),
(21, 'Vestido', '  Vestido leve estampa floral', 119.90, 70, 'produtos/6985fef678c454.13719798.jpg', 11, '2025-08-28 11:05:05', '2026-02-06 11:47:18', NULL),
(22, 'Vestido Social Preto', '  Vestido social preto longo ', 199.90, 40, 'produtos/6985ff1e7d4464.48520473.jpg', 11, '2025-08-28 11:05:05', '2026-02-06 11:47:58', NULL),
(23, 'Saias', '    Saia jeans curta feminina ', 89.90, 55, 'produtos/6985ff817e9a19.17604742.jpg', 12, '2025-08-28 11:05:05', '2026-02-06 11:49:37', NULL),
(24, 'Saia Midi Plissada', '   Saia midi plissada elegante ', 139.90, 35, 'produtos/6985ffab874fe8.11015220.jpg', 12, '2025-08-28 11:05:05', '2026-02-06 11:50:19', NULL),
(25, 'Camisa Social Branca', '  Camisa social manga longa ', 129.90, 65, 'produtos/6985ffcce12d07.37433470.jpg', 1, '2025-08-28 11:05:05', '2026-02-06 11:50:52', NULL),
(26, 'Camisa Social Azul', '  Camisa social azul clara ', 139.90, 60, 'produtos/698b1ca4e86730.38470274.jpg', 1, '2025-08-28 11:05:05', '2026-02-10 08:55:16', NULL),
(27, 'Tênis Casual Branco', '   Tênis casual unissex branco', 179.90, 90, 'produtos/698b1cc1e51916.05149191.jpg', 3, '2025-08-28 11:05:05', '2026-02-10 08:55:45', NULL),
(28, 'Tênis Esportivo Preto', '   Tênis esportivo confortável', 199.90, 70, 'produtos/698b1cd9e8d4e0.65318167.jpg', 3, '2025-08-28 11:05:05', '2026-02-10 08:56:09', NULL),
(29, 'polo', ' polo esportiva', 99.90, 80, 'produtos/698b1cf40a72c4.55056985.jpg', 16, '2025-08-28 11:05:05', '2026-02-10 08:56:36', NULL),
(30, 'moletom bege', ' moletom bege, careca', 89.90, 60, 'produtos/698b1d13161441.17376312.jpg', 10, '2025-08-28 11:05:05', '2026-02-10 08:57:07', NULL),
(31, 'camiseta over', '     over', 45.00, 30, 'produtos/698b1d2cc024b6.63548824.jpg', 1, '2025-10-31 09:38:40', '2026-02-10 08:57:32', NULL),
(32, 'calça jeans cinza', '   Calça jeans cinza', 100.00, 15, 'produtos/698b1d4a432457.88128349.jpg', 2, '2025-10-31 09:49:45', '2026-02-10 08:58:02', NULL),
(33, 'Tenis esportivo ', 'Tênis esportivo moderno e confortável, desenvolvido para oferecer leveza, estabilidade e ótimo amortecimento. Possui design versátil, materiais resistentes e solado antiderrapante, garantindo segurança e desempenho no dia a dia ou na prática de atividades físicas.', 400.00, 20, 'produtos/698b1d5f0e2fe9.14858151.jpg', 3, '2026-01-28 08:39:53', '2026-02-10 08:58:23', NULL),
(34, 'calça baggy', 'Calça baggy jeans com modelagem ampla, garantindo conforto e liberdade de movimento. Confeccionada em jeans resistente, possui cintura confortável e caimento solto nas pernas, trazendo um visual moderno e urbano. Ideal para compor looks casuais e streetwear, unindo estilo, autenticidade e versatilidade no dia a dia.', 200.00, 10, 'produtos/698b1da47c95e3.63422638.jpg', 2, '2026-01-28 08:44:44', '2026-02-10 08:59:32', NULL),
(35, 'Shorts ', 'O clássico que nunca sai de moda. Este shorts verde militar é a peça-chave para composições práticas e cheias de personalidade. Confortável, leve e fácil de combinar. Garanta o seu e domine o estilo streetwear.', 75.00, 32, 'produtos/6984a3540cace3.74307484.jpg', 15, '2026-02-05 11:04:04', '2026-02-05 11:12:08', NULL),
(36, 'tenis de skatista', 'Tênis em camurça de alta resistência com sola vulcanizada para maior aderência e precisão no shape. Possui interior acolchoado e palmilha com absorção de impacto. Estilo street raiz com durabilidade premium.', 500.00, 8, 'produtos/6984a606bac3c0.89701852.jpg', 3, '2026-02-05 11:14:18', '2026-02-05 11:15:34', NULL),
(37, 'boné skatista', 'Boné aba reta com fechamento snapback ajustável. Confeccionado em sarja resistente, ideal para proteger do sol durante o rolê. Estilo urbano clássico com bordado de alta definição.', 89.99, 23, 'produtos/6984a878be9a09.88699743.jpg', 19, '2026-02-05 11:26:00', '2026-02-05 11:26:15', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_tamanhos`
--

CREATE TABLE `tbl_tamanhos` (
  `id_tamanhos` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `tamanho_tamanhos` varchar(10) NOT NULL,
  `quantidade_tamanhos` int(11) NOT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `excluido_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_tamanhos`
--

INSERT INTO `tbl_tamanhos` (`id_tamanhos`, `id_produto`, `tamanho_tamanhos`, `quantidade_tamanhos`, `criado_em`, `atualizado_em`, `excluido_em`) VALUES
(1, 1, '', 40, '2025-08-28 11:04:37', '2025-10-28 15:58:46', NULL),
(2, 1, 'M', 60, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(3, 1, 'G', 50, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(4, 2, 'P', 30, '2025-08-28 11:04:37', '2025-09-18 14:04:48', NULL),
(5, 2, 'M', 50, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(6, 2, 'G', 40, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(7, 3, '38', 20, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(8, 3, '40', 30, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(9, 3, '42', 30, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(10, 4, '36', 15, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(11, 4, '38', 25, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(12, 4, '40', 20, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(13, 5, 'P', 35, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(14, 5, 'M', 40, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(15, 5, 'G', 25, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(16, 6, 'P', 20, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(17, 6, 'M', 30, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(18, 6, 'G', 20, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(19, 7, 'M', 25, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(20, 7, 'G', 25, '2025-08-28 11:04:37', '2025-08-28 11:04:37', NULL),
(21, 8, 'M', 14, NULL, NULL, NULL),
(22, 8, 'M', 14, NULL, NULL, NULL),
(23, 13, 'M', 20, NULL, NULL, NULL),
(24, 22, 'G', 21, NULL, NULL, NULL),
(25, 25, 'G', 24, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_usuarios`
--

CREATE TABLE `tbl_usuarios` (
  `id_usuarios` int(11) NOT NULL,
  `nome_usuarios` varchar(100) NOT NULL,
  `email_usuarios` varchar(150) NOT NULL,
  `senha_usuarios` varchar(255) NOT NULL,
  `nivel_acesso` varchar(50) NOT NULL,
  `foto_usuarios` varchar(250) NOT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `excluido_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_usuarios`
--

INSERT INTO `tbl_usuarios` (`id_usuarios`, `nome_usuarios`, `email_usuarios`, `senha_usuarios`, `nivel_acesso`, `foto_usuarios`, `criado_em`, `atualizado_em`, `excluido_em`) VALUES
(1, 'Administrador Master', 'adm.master@email.com', '$2y$10$Wng9PvK3chNUXdZWfbirtuwHMSWT7ed9pSJgKulGdBS8S44UJih82', 'admin', 'usuarios/69849f587ee5c0.20134749.jpg', '2025-08-28 11:04:20', '2026-02-05 14:47:04', NULL),
(2, 'Vendedor João', 'joao.vendedor@email.com', '$2y$10$eQ.wTEMfVAN5xOTlcb/fo.eyzVKAqpGzsjnyaLQmOO/49mkESLhoq', 'admin', 'usuarios/69834279375be5.57521542.jpg', '2025-08-28 11:04:20', '2026-02-04 13:58:33', '2026-02-05 01:02:12'),
(3, 'Vendedor Maria', 'maria.vendedora@email.com', '$2y$10$.hkvD4doIBjk6hvsGoZjaO.0sKuyZuu1VH2T.HxKShHbhh9JDUl8W', 'vendedor', '', '2025-08-28 11:04:20', '2025-10-29 15:25:15', '2026-02-05 02:02:55'),
(4, 'Administrador Loja1', 'admin.loja1@email.com', '$2y$10$cEs5JONrUmtga6HZvDlcxeq4DPP6QqPwAge2suB7aikLFIQ66eZ2K', 'admin', '', '2025-08-28 11:04:20', '2026-02-05 14:52:38', NULL),
(5, 'Administrador Loja2', 'admin.loja2@email.com', '$2y$10$sgYcWxYb43TS79H7biTb3uGPsR0PgTzUM1LxTbGez0XWTJEuJLlNW', 'admin', '', '2025-08-28 11:04:20', '2026-02-05 14:53:29', NULL),
(6, 'Vendedor Carlos', 'carlos.vendedor@email.com', '$2y$10$mwf4RxYrIQV6d1SdWzkT8.tJ8Vc3nVoNUSReOXeO3r9xZ6ji4qRyy', 'vendedor', '', '2025-08-28 11:04:20', '2025-10-29 13:06:21', '2026-02-05 02:02:54'),
(7, 'Vendedor Ana', 'ana.vendedora@email.com', '$2y$10$lGgM2ey4kphXMvCQerKmxOY3IyB.EvjtA6ay0WaI1rs/xf7UBaGTC', 'vendedor', '', '2025-08-28 11:04:20', '2025-10-29 13:12:59', NULL),
(8, 'Administrador Financeiro', 'financeiro.admin@email.com', '$2y$10$zfw12Dy8VSlGGuxnr8DNZeWrRRO.mHeZgXgjru3peCQtxSKsaB/rq', 'admin', '', '2025-08-28 11:04:20', '2025-10-29 13:13:42', NULL),
(9, 'Administrador RH', 'rh.admin@email.com', '$2y$10$tTR04cuVSZZTmAeaSuEfDOgX3HhBm.lOqPO4eJXShYE9KRST8CYiu', 'vendedor', '', '2025-08-28 11:04:20', '2025-10-29 13:15:00', NULL),
(10, 'Vendedor Pedro', 'pedro.vendedor@email.com', '$2y$10$QHPyE3iE8C/5cpCnezR.YeqBFi/Agfr4ujQ6Gdv3hdOl4rMEgVT8O', 'vendedor', '', '2025-08-28 11:04:20', '2025-10-29 13:16:58', NULL),
(11, 'Vendedor Júlia', 'julia.vendedora@email.com', '$2y$10$CvyzMwWIfBL2rklcBAaXJu4eedCz/34uc3qU6UuYt78GS7orquUCq', 'admin', '', '2025-08-28 11:04:20', '2025-10-29 13:32:22', NULL),
(12, 'Administrador TI', 'ti.admin@email.com', '$2y$10$vfirr5xAZLI8wfFOLD3B3uZPReU9kCZxDK8ZHQcapPNBiwvHBrb4K', 'admin', '', '2025-08-28 11:04:20', '2025-10-29 13:35:03', NULL),
(13, 'Administrador Estoque', 'estoque.admin@email.com', '$2y$10$y1f9j4t0mcpxqYewBRNq/eclt9.rM5xtEDjB1bCjr5JKK4pDFqZ66', 'vendedor', '', '2025-08-28 11:04:20', '2025-10-29 13:36:01', NULL),
(14, 'Vendedor Marcos', 'marcos.vendedor@email.com', '$2y$10$rKmoCCr5o33z/HOL1GHgO.mv9PcrGqev5ShH86Xos.KWtaPmIaVve', 'vendedor', '', '2025-08-28 11:04:20', '2025-10-24 13:41:37', NULL),
(15, 'Vendedor Camila', 'camila.vendedora@email.com', '$2y$10$axMaUsUTVFMB.IIDN0ebnet3yC611p85XCh8YW2SYIwDDj6yzqfSW', 'vendedor', '', '2025-08-28 11:04:20', '2025-10-29 13:39:23', '2026-02-11 12:02:57'),
(16, 'Administrador Regional', 'regional.admin@email.com', '$2y$10$ev/smA4imVHqMSWOnHKGEuhKK3IJr6KpSMiDZfXaAXS9zPNNm1mUq', 'vendedor', '', '2025-08-28 11:04:20', '2025-10-29 13:40:59', NULL),
(17, 'Administrador Nacional', 'nacional.admin@email.com', '$2y$10$Gh1xq0qFfOUqW1Iikjudr.WhiijpnA2BhV3vpSeMHQmqhNkbeyA7S', 'vendedor', '', '2025-08-28 11:04:20', '2025-10-29 13:45:01', NULL),
(18, 'Vendedor Felipe', 'felipe.vendedor@email.com', '$2y$10$p3vYvOrHL5DAF/xLMtELA.Yousmv0OiKPltv/lKMOlbxc/gIep2Am', 'vendedor', '', '2025-08-28 11:04:20', '2025-10-29 13:51:11', NULL),
(19, 'Vendedor Larissa', 'larissa.vendedora@email.com', '$2y$10$4sbebdoJM5WUf3RydM1qRO1GENYrFwkR3FRFbUYBEscHVSs8nzsv2', 'vendedor', '', '2025-08-28 11:04:20', '2025-10-29 13:53:22', NULL),
(20, 'Administrador Sistema', 'sistema.admin@email.com', '$2y$10$DnUmFUCgH3Vbb8sbCnSrh.Zgx7DO2QxxfAL37UpxtKoKxoGsuA222', 'admin', 'usuarios/6978cb481ef416.06745593.jpg', '2025-08-28 11:04:20', '2026-02-09 09:47:42', NULL),
(21, 'funcionario antonio', 'antonio1010@outlook.com', '$2y$10$5oUg2sMWfR6OmzrlyJSjz.n6cJSGrWOq8Dm6kCSQwmbWhx8q5vcIC', 'Vendedor', '', NULL, '2026-01-27 13:11:27', NULL),
(22, 'administradora maria', 'mariaadm1010@gmail.com', '$2y$10$SuwqD1kt6VN2lYBnvS9Um.wwOb5JWh5Hcb2hsN/Fzsnvdz/uZH7Lu', 'admin', 'usuarios/6978cc78b7bef3.26324267.jpg', NULL, '2026-01-27 15:32:24', NULL),
(34, 'artxxx', 'artxxx1010@gmail.com', '$2y$10$iDFGnzSVojl0czL/3UNPT.Q0AcGrvh2sWGEOGlTD0hIafaFX3BJyO', 'admin', 'Ativo', NULL, '2025-10-23 16:34:47', NULL),
(49, 'Vendedor alex', 'Alex@gmail.com', '$2y$10$3wZ.Fyeb69TvTYQpq9iRi.4pOrIqVPghQ4pf5b/8HYUtwy4rDSDDS', 'Admin', '', '2025-10-29 11:37:05', NULL, '2026-02-11 12:02:46'),
(50, 'Vendedor Eduardo', 'edu@gmail.com', '$2y$10$Tc/RExa160dSfSJn95CpSeDMDmp05cmYGL9JKoDEQhGwz2.itjhsi', 'Admin', '', '2025-10-30 09:19:24', NULL, NULL),
(51, 'Administrador geral', 'geral@gmail.com', '$2y$10$jkYE0G3H3THd2QBIV9ov2uwwVSDDyPkxcOXjvcHhRp8WEhiHELro2', 'Admin', '', '2025-10-30 09:20:11', NULL, NULL),
(53, 'Administrador admin', 'adminchefe@gmail.com', '$2y$10$QsuSYunG2Wmmwr5cNa8T5Od79htb7VkxdVWLmuANWHIYU0xqEpjv.', 'Admin', '/img/logoperf.jpg', '2026-01-27 11:18:59', NULL, NULL),
(54, 'Admin Koketsu', 'admin@koketsu.com.br', '$2y$10$UTVGO6FkeBwT9zldZtGGqez9ja1AFpZaNEHx45g2kaRXM5teJW5V2', 'admin', 'usuarios/6978cb8a08c5b8.21248098.jpg', '2026-01-27 11:21:16', '2026-01-27 15:28:26', NULL),
(55, 'vendedor claudia', 'claudiacaixa@gmail.com', '$2y$10$yWm4mfsGX8EpidmZHg7fXumlnDCewU6LeTjsiIvuyreieKQrGGnDe', 'vendedor', '/img/logoperf.jpg', '2026-01-27 11:49:20', '2026-01-28 13:55:55', NULL),
(56, 'sofia', 'sofia1010@gmail.com', '$2y$10$Enaoxr9WcMCrIkbzMPHPJe5//Q8FqDxOm8hPp15tcMU22OPzPX1WK', 'cliente', 'usuarios/6980aa4e8809a4.21091594.jpg', '2026-01-28 09:37:43', '2026-02-02 10:44:46', NULL),
(57, 'Arthur Felix', 'arthurgamer@gmail.com', '$2y$10$g26vWOsyjNss5IcLX3/glOH54aWHvcmwEjmmAC2m7AIQAfhzDBq5W', 'cliente', 'usuarios/698c7d6fed1c90.86260552.jpg', '2026-02-03 08:52:26', '2026-02-11 10:00:37', NULL),
(58, 'kaike', 'kaike1010@gmail.com', '$2y$10$Ph2H4z03K1gKn6P7Zf11weq0qt0mFnDSOi82COu.nTTOSJFXDaW2O', 'cliente', 'usuarios/69834358bcad62.12896903.jpg', '2026-02-04 10:00:24', '2026-02-04 10:02:16', NULL),
(59, 'lucas', 'lucas1010@gmail.com', '$2y$10$HwJCdO23qipyH090TMr6s.7bnw7MK6OUteQLz0WDRiSuV8FOGnOoi', 'cliente', 'usuarios/69834ac4ac6501.69662931.jpg', '2026-02-04 10:32:35', '2026-02-04 10:33:56', '2026-02-09 12:02:37'),
(60, 'marcos', 'marcos1010@gmail.com', '$2y$10$a88.yWw0NgH2rGZ4gFvgaebPlPbTJo3jbURr14Xv..M9C.HZ04tGi', 'cliente', 'usuarios/698353c52944d4.48124288.jpg', '2026-02-04 11:11:33', '2026-02-04 11:12:21', NULL),
(61, 'matheus', 'matheus1010@gmail.com', '$2y$10$dkr9.wYC.QBJIpZjcwwYN.P.Nv6JwdZjK7y5jOdexdQecJfpd/xNe', 'cliente', 'usuarios/6984857641fd10.21666318.jpg', '2026-02-05 08:54:53', '2026-02-05 12:56:38', '2026-02-11 12:02:14'),
(62, 'eduardo oliveira', 'eduardolvr@gmail.com', '$2y$10$QyYSS00fYHJVGuZx45x.mean.nRjZIl4ICgjqIHnNVBpfMIkhY5Uq', 'cliente', 'usuarios/6985f718540c71.69032926.jpg', '2026-02-06 09:53:37', '2026-02-06 15:19:28', NULL),
(63, 'Neymar JR', 'neymarCraque@gmail.com', '$2y$10$RvO15VQ2W0Xb3g6MqVHofOjsrGJejA6PTLb4T2xiowLUMvMLrzNuu', 'Cliente', 'usuarios/6989da89d7df93.04190247.jpg', '2026-02-09 09:59:09', '2026-02-09 10:00:57', NULL),
(64, 'Itachi uchiha', 'ItachiBrabo@gmail.com', '$2y$10$leay5FsfjxmpCbrPALSX2Ov.i58IU10qGKQhlgVbiFSAEUxg4z57O', 'cliente', 'usuarios/6989f4b96c2e28.92800829.jpg', '2026-02-09 11:50:57', '2026-02-09 11:52:41', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbl_avaliacoes`
--
ALTER TABLE `tbl_avaliacoes`
  ADD PRIMARY KEY (`id_avaliacoes`),
  ADD KEY `id_produto` (`id_produto`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `tbl_carrinho`
--
ALTER TABLE `tbl_carrinho`
  ADD PRIMARY KEY (`id_carrinho`),
  ADD KEY `id_cliente` (`id_perfil`);

--
-- Índices de tabela `tbl_categorias`
--
ALTER TABLE `tbl_categorias`
  ADD PRIMARY KEY (`id_categorias`);

--
-- Índices de tabela `tbl_cores`
--
ALTER TABLE `tbl_cores`
  ADD PRIMARY KEY (`id_cores`),
  ADD KEY `fk_cor_produto` (`id_produto`);

--
-- Índices de tabela `tbl_estoque_movimentacao`
--
ALTER TABLE `tbl_estoque_movimentacao`
  ADD PRIMARY KEY (`id_estoque_movimentacao`),
  ADD KEY `fk_estoque_produto` (`id_produto`);

--
-- Índices de tabela `tbl_imagem`
--
ALTER TABLE `tbl_imagem`
  ADD PRIMARY KEY (`id_imagem`),
  ADD KEY `id_produto` (`id_produto`),
  ADD KEY `id_cor` (`id_cor`),
  ADD KEY `id_tamanho` (`id_tamanho`);

--
-- Índices de tabela `tbl_imagem_carrossel`
--
ALTER TABLE `tbl_imagem_carrossel`
  ADD PRIMARY KEY (`id_carrossel`);

--
-- Índices de tabela `tbl_itens_pedidos`
--
ALTER TABLE `tbl_itens_pedidos`
  ADD PRIMARY KEY (`id_itens_pedidos`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_produto` (`id_produto`),
  ADD KEY `id_pedido_2` (`id_pedido`);

--
-- Índices de tabela `tbl_pedidos`
--
ALTER TABLE `tbl_pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_cliente` (`id_perfil`);

--
-- Índices de tabela `tbl_perfil`
--
ALTER TABLE `tbl_perfil`
  ADD PRIMARY KEY (`id_perfil`);

--
-- Índices de tabela `tbl_preferencias`
--
ALTER TABLE `tbl_preferencias`
  ADD PRIMARY KEY (`id_preferencia`),
  ADD KEY `id_usuarios` (`id_usuarios`);

--
-- Índices de tabela `tbl_produtos`
--
ALTER TABLE `tbl_produtos`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `fk_produto_categoria` (`id_categoria`);

--
-- Índices de tabela `tbl_tamanhos`
--
ALTER TABLE `tbl_tamanhos`
  ADD PRIMARY KEY (`id_tamanhos`),
  ADD KEY `fk_tamanho_produto` (`id_produto`);

--
-- Índices de tabela `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  ADD PRIMARY KEY (`id_usuarios`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbl_avaliacoes`
--
ALTER TABLE `tbl_avaliacoes`
  MODIFY `id_avaliacoes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `tbl_carrinho`
--
ALTER TABLE `tbl_carrinho`
  MODIFY `id_carrinho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `tbl_categorias`
--
ALTER TABLE `tbl_categorias`
  MODIFY `id_categorias` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de tabela `tbl_cores`
--
ALTER TABLE `tbl_cores`
  MODIFY `id_cores` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `tbl_estoque_movimentacao`
--
ALTER TABLE `tbl_estoque_movimentacao`
  MODIFY `id_estoque_movimentacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `tbl_imagem`
--
ALTER TABLE `tbl_imagem`
  MODIFY `id_imagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `tbl_imagem_carrossel`
--
ALTER TABLE `tbl_imagem_carrossel`
  MODIFY `id_carrossel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `tbl_itens_pedidos`
--
ALTER TABLE `tbl_itens_pedidos`
  MODIFY `id_itens_pedidos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `tbl_pedidos`
--
ALTER TABLE `tbl_pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT de tabela `tbl_perfil`
--
ALTER TABLE `tbl_perfil`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `tbl_preferencias`
--
ALTER TABLE `tbl_preferencias`
  MODIFY `id_preferencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_produtos`
--
ALTER TABLE `tbl_produtos`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `tbl_tamanhos`
--
ALTER TABLE `tbl_tamanhos`
  MODIFY `id_tamanhos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  MODIFY `id_usuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tbl_avaliacoes`
--
ALTER TABLE `tbl_avaliacoes`
  ADD CONSTRAINT `tbl_avaliacoes_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `tbl_produtos` (`id_produto`),
  ADD CONSTRAINT `tbl_avaliacoes_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `tbl_perfil` (`id_perfil`);

--
-- Restrições para tabelas `tbl_carrinho`
--
ALTER TABLE `tbl_carrinho`
  ADD CONSTRAINT `tbl_carrinho_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `tbl_perfil` (`id_perfil`);

--
-- Restrições para tabelas `tbl_cores`
--
ALTER TABLE `tbl_cores`
  ADD CONSTRAINT `fk_cor_produto` FOREIGN KEY (`id_produto`) REFERENCES `tbl_produtos` (`id_produto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tbl_estoque_movimentacao`
--
ALTER TABLE `tbl_estoque_movimentacao`
  ADD CONSTRAINT `fk_estoque_produto` FOREIGN KEY (`id_produto`) REFERENCES `tbl_produtos` (`id_produto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tbl_imagem`
--
ALTER TABLE `tbl_imagem`
  ADD CONSTRAINT `tbl_imagem_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `tbl_produtos` (`id_produto`),
  ADD CONSTRAINT `tbl_imagem_ibfk_2` FOREIGN KEY (`id_cor`) REFERENCES `tbl_cores` (`id_cores`),
  ADD CONSTRAINT `tbl_imagem_ibfk_3` FOREIGN KEY (`id_tamanho`) REFERENCES `tbl_tamanhos` (`id_tamanhos`);

--
-- Restrições para tabelas `tbl_itens_pedidos`
--
ALTER TABLE `tbl_itens_pedidos`
  ADD CONSTRAINT `tbl_itens_pedidos_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `tbl_produtos` (`id_produto`),
  ADD CONSTRAINT `tbl_itens_pedidos_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `tbl_pedidos` (`id_pedido`);

--
-- Restrições para tabelas `tbl_pedidos`
--
ALTER TABLE `tbl_pedidos`
  ADD CONSTRAINT `tbl_pedidos_ibfk_2` FOREIGN KEY (`id_perfil`) REFERENCES `tbl_perfil` (`id_perfil`);

--
-- Restrições para tabelas `tbl_preferencias`
--
ALTER TABLE `tbl_preferencias`
  ADD CONSTRAINT `tbl_preferencias_ibfk_1` FOREIGN KEY (`id_usuarios`) REFERENCES `tbl_usuarios` (`id_usuarios`) ON DELETE CASCADE;

--
-- Restrições para tabelas `tbl_produtos`
--
ALTER TABLE `tbl_produtos`
  ADD CONSTRAINT `fk_produto_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `tbl_categorias` (`id_categorias`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produtos_categorias` FOREIGN KEY (`id_categoria`) REFERENCES `tbl_categorias` (`id_categorias`);

--
-- Restrições para tabelas `tbl_tamanhos`
--
ALTER TABLE `tbl_tamanhos`
  ADD CONSTRAINT `fk_tamanho_produto` FOREIGN KEY (`id_produto`) REFERENCES `tbl_produtos` (`id_produto`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
