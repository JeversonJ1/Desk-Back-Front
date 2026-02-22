<?php
namespace App\backend\models;
use PDO;
class Carrinho {
    private $id_carrinho;
    private $id_cliente;
    private $data_pedido_carrinho;
    private $total_carrinho;
    private $status_carrinho;
    private $criado_em;
    private $atualizado_em;
    private $excluido_em;
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Buscar todos os carrinhos (não excluídos)
    public function buscarCarrinhos() {
        $sql = "SELECT * FROM tbl_carrinho WHERE excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Buscar carrinhos de um cliente específico
    public function buscarPorCliente($id_cliente) {
        $sql = "SELECT * FROM tbl_carrinho WHERE id_cliente = :id_cliente AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Buscar carrinho por ID
    public function buscarPorId($id_carrinho) {
        $sql = "SELECT * FROM tbl_carrinho WHERE id_carrinho = :id_carrinho AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_carrinho', $id_carrinho);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Inserir novo carrinho
    public function inserirCarrinho($id_cliente, $total, $status = 'Aberto') {
        $sql = "INSERT INTO tbl_carrinho 
                (id_cliente, total_carrinho, status_carrinho, data_pedido_carrinho, criado_em)
                VALUES (:id_cliente, :total, :status, NOW(), NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':status', $status);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Atualizar carrinho
    public function atualizarCarrinho($id_carrinho, $total, $status) {
        $sql = "UPDATE tbl_carrinho SET 
                total_carrinho = :total,
                status_carrinho = :status,
                atualizado_em = NOW()
                WHERE id_carrinho = :id_carrinho AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_carrinho', $id_carrinho);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':status', $status);
        
        return $stmt->execute();
    }

    // Deletar carrinho (soft delete)
    public function deletarCarrinho($id_carrinho) {
        $sql = "UPDATE tbl_carrinho SET excluido_em = NOW() WHERE id_carrinho = :id_carrinho";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_carrinho', $id_carrinho);
        return $stmt->execute();
    }
}
