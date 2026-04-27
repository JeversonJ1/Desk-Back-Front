<?php
namespace App\Koketsu\Models;
use PDO;
class Imagem
{
    private $id_imagem;
    private $id_produto;
    private $id_cor;
    private $id_tamanho;
    private $caminho_imagem;
    private $descricao_imagem;
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Buscar todas as imagens
    public function buscarImagens()
    {
        $sql = "SELECT * FROM tbl_imagem";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar imagens por produto
    public function buscarPorProduto($id_produto)
    {
        $sql = "SELECT * FROM tbl_imagem WHERE id_produto = :id_produto";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_produto', $id_produto);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar imagem por ID
    public function buscarPorId($id_imagem)
    {
        $sql = "SELECT * FROM tbl_imagem WHERE id_imagem = :id_imagem";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_imagem', $id_imagem);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Inserir nova imagem
    public function inserirImagem($id_produto, $id_cor, $id_tamanho, $caminho, $descricao)
    {
        // Buscar próximo ID caso AUTO_INCREMENT não esteja configurado
        $stmtId = $this->db->query("SELECT MAX(id_imagem) FROM tbl_imagem");
        $nextId = (int)$stmtId->fetchColumn() + 1;

        // Resolver restrições NOT NULL da tabela
        if ($id_cor === null) {
            $stmtC = $this->db->prepare("SELECT id_cores FROM tbl_cores WHERE id_produto = ? LIMIT 1");
            $stmtC->execute([$id_produto]);
            $id_cor = $stmtC->fetchColumn() ?: 0;
        }
        if ($id_tamanho === null) {
            $stmtT = $this->db->prepare("SELECT id_tamanhos FROM tbl_tamanhos WHERE id_produto = ? LIMIT 1");
            $stmtT->execute([$id_produto]);
            $id_tamanho = $stmtT->fetchColumn() ?: 0;
        }

        // Desabilitar chaves estrangeiras temporariamente caso o produto não tenha cores/tamanhos válidos (id 0)
        $this->db->exec("SET FOREIGN_KEY_CHECKS=0;");

        $sql = "INSERT INTO tbl_imagem 
                (id_imagem, id_produto, id_cor, id_tamanho, caminho_imagem, descricao_imagem)
                VALUES (:id_imagem, :id_produto, :id_cor, :id_tamanho, :caminho, :descricao)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_imagem', $nextId);
        $stmt->bindParam(':id_produto', $id_produto);
        $stmt->bindParam(':id_cor', $id_cor);
        $stmt->bindParam(':id_tamanho', $id_tamanho);
        $stmt->bindParam(':caminho', $caminho);
        $stmt->bindParam(':descricao', $descricao);

        $resultado = $stmt->execute();
        $this->db->exec("SET FOREIGN_KEY_CHECKS=1;");

        if ($resultado) {
            return $nextId;
        }
        return false;
    }

    // Atualizar imagem existente
    public function atualizarImagem($id_imagem, $id_produto, $id_cor, $id_tamanho, $caminho, $descricao)
    {
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
    public function excluirImagem($id_imagem)
    {
        $sql = "DELETE FROM tbl_imagem WHERE id_imagem = :id_imagem";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_imagem', $id_imagem);
        return $stmt->execute();
    }
}
