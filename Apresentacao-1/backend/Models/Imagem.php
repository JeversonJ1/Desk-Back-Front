<?php
namespace App\backend\models;
use PDO;
class Imagem {
    private $id_imagem;
    private $id_produto;
    private $id_cor;
    private $id_tamanho;
    private $caminho_imagem;
    private $descricao_imagem;
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Buscar todas as imagens
    public function buscarImagens() {
        $sql = "SELECT * FROM tbl_imagem";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar imagens por produto
    public function buscarPorProduto($id_produto) {
        $sql = "SELECT * FROM tbl_imagem WHERE id_produto = :id_produto";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_produto', $id_produto);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar imagem por ID
    public function buscarPorId($id_imagem) {
        $sql = "SELECT * FROM tbl_imagem WHERE id_imagem = :id_imagem";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_imagem', $id_imagem);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Inserir nova imagem
    public function inserirImagem($id_produto, $id_cor, $id_tamanho, $caminho, $descricao) {
        $sql = "INSERT INTO tbl_imagem 
                (id_produto, id_cor, id_tamanho, caminho_imagem, descricao_imagem)
                VALUES (:id_produto, :id_cor, :id_tamanho, :caminho, :descricao)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_produto', $id_produto);
        $stmt->bindParam(':id_cor', $id_cor);
        $stmt->bindParam(':id_tamanho', $id_tamanho);
        $stmt->bindParam(':caminho', $caminho);
        $stmt->bindParam(':descricao', $descricao);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Atualizar imagem existente
    public function atualizarImagem($id_imagem, $id_produto, $id_cor, $id_tamanho, $caminho, $descricao) {
        $sql = "UPDATE tbl_imagem 
                SET id_produto = :id_produto,
                    id_cor = :id_cor,
                    id_tamanho = :id_tamanho,
                    caminho_imagem = :caminho,
                    descricao_imagem = :descricao
                WHERE id_imagem = :id_imagem";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_imagem', $id_imagem);
        $stmt->bindParam(':id_produto', $id_produto);
        $stmt->bindParam(':id_cor', $id_cor);
        $stmt->bindParam(':id_tamanho', $id_tamanho);
        $stmt->bindParam(':caminho', $caminho);
        $stmt->bindParam(':descricao', $descricao);

        return $stmt->execute();
    }

    // Excluir imagem (remoção física do banco)
    public function excluirImagem($id_imagem) {
        $sql = "DELETE FROM tbl_imagem WHERE id_imagem = :id_imagem";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_imagem', $id_imagem);
        return $stmt->execute();
    }
}
