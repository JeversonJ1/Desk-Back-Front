-- Adicionar campo tamanho na tabela tbl_itens_pedidos
ALTER TABLE tbl_itens_pedidos 
ADD COLUMN tamanho VARCHAR(10) DEFAULT NULL AFTER preco_unitario;

-- Verificar estrutura atualizada
DESCRIBE tbl_itens_pedidos;
