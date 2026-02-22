<?php
namespace App\Koketsu\Models;
use PDO;
 class EstoqueMovimentacao {
    private $id_estoque_movimentacao;
    private $id_produto;
    private $tipo_estoque_movimentacao;
    private $quantidade_estoque_movimentacao;
    private $data_movimentacao_estoque_movimentacao;
    private $descricao_estoque_movimentacao;
    private $criado_em;
    private $atualizado_em;
    private $excluido_em;
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Buscar todas as movimentações (não excluídas)
    public function buscarMovimentacoes() {
        $sql = "SELECT * FROM tbl_estoque_movimentacao WHERE excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar movimentações de um produto específico
    public function buscarPorProduto($id_produto) {
        $sql = "SELECT * FROM tbl_estoque_movimentacao 
                WHERE id_produto = :id_produto AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_produto', $id_produto);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar movimentação específica por ID
    public function buscarPorId($id_estoque_movimentacao) {
        $sql = "SELECT * FROM tbl_estoque_movimentacao 
                WHERE id_estoque_movimentacao = :id AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id_estoque_movimentacao);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Inserir nova movimentação
    public function inserirMovimentacao($id_produto, $tipo, $quantidade, $descricao = null) {
        $sql = "INSERT INTO tbl_estoque_movimentacao 
                (id_produto, tipo_estoque_movimentacao, quantidade_estoque_movimentacao, 
                 data_movimentacao_estoque_movimentacao, descricao_estoque_movimentacao, criado_em)
                VALUES (:id_produto, :tipo, :quantidade, NOW(), :descricao, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_produto', $id_produto);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->bindParam(':descricao', $descricao);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Atualizar movimentação (apenas descrição ou quantidade)
    public function atualizarMovimentacao($id_estoque_movimentacao, $quantidade = null, $descricao = null) {
        $sql = "UPDATE tbl_estoque_movimentacao SET ";
        $updates = [];

        if ($quantidade !== null) {
            $updates[] = "quantidade_estoque_movimentacao = :quantidade";
        }
        if ($descricao !== null) {
            $updates[] = "descricao_estoque_movimentacao = :descricao";
        }

        $sql .= implode(', ', $updates);
        $sql .= ", atualizado_em = NOW() WHERE id_estoque_movimentacao = :id AND excluido_em IS NULL";

        $stmt = $this->db->prepare($sql);
        if ($quantidade !== null) {
            $stmt->bindParam(':quantidade', $quantidade);
        }
        if ($descricao !== null) {
            $stmt->bindParam(':descricao', $descricao);
        }
        $stmt->bindParam(':id', $id_estoque_movimentacao);

        return $stmt->execute();
    }

    // Exclusão lógica da movimentação
    public function excluirMovimentacao($id_estoque_movimentacao) {
        $sql = "UPDATE tbl_estoque_movimentacao SET excluido_em = NOW() WHERE id_estoque_movimentacao = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id_estoque_movimentacao);
        return $stmt->execute();
    }
}
