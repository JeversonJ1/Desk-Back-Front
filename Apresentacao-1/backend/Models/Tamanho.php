<?php 
namespace App\Koketsu\Models;
use PDO;

class Tamanho {
  private $id_tamanhos;
  private $id_produto;
  private $tamanho_tamanhos;
  private $quantidade_tamanhos;
  private $criado_em;
  private $atualizado_em;
  private $excluido_em;
  private $db;

  public function __construct($db) {
    $this->db = $db;
  }

  // Método para buscar todos os tamanhos não excluídos
  function buscarTamanhos() {
    $sql = "SELECT * FROM tbl_tamanhos WHERE excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function buscarPorID($id){
        $sql = "SELECT * FROM tbl_tamanhos WHERE id_tamanhos = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

  function totalDeUsuarios() {
    $sql = "SELECT COUNT(*) AS total FROM tbl_tamanhos";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN);
}

function buscarTamanhosAtivos() {
    $sql = "SELECT COUNT(*) AS total_ativos FROM tbl_tamanhos WHERE excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN);
}

function buscarTamanhosInativos($email){
   $sql = "SELECT COUNT(*) AS total_inativos FROM tbl_tamanhos WHERE excluido_em IS NOT NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN);
}

public function paginacao(int $pagina = 1, int $porPagina = 50){
        $offset = ($pagina - 1) * $porPagina;
        $sql = "SELECT id_tamanhos, id_produto, tamanho_tamanhos, quantidade_tamanhos, excluido_em FROM tbl_tamanhos
                LIMIT :offset, :porPagina";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':porPagina', $porPagina, PDO::PARAM_INT);
        $stmt->execute();
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalStmt = $this->db->query("SELECT COUNT(*) FROM tbl_tamanhos");
        $total = $totalStmt->fetchColumn();
        $totalPaginas = ceil($total / $porPagina);

        return [
            'data' => $dados,
            'total' => (int) $total,
            'por_pagina' => (int) $porPagina,
            'pagina_atual' => (int) $pagina,
            'total_paginas' => (int) $totalPaginas
        ];
    }

  // Método para buscar tamanhos por produto
  function buscarTamanhosPorIdProduto($id_produto) {
    $sql = "SELECT * FROM tbl_tamanhos 
            WHERE id_produto = :id_produto AND excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_produto', $id_produto);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Método para inserir um novo tamanho
  function inserirTamanho($id_produto, $tamanho, $quantidade) {
    $sql = "INSERT INTO tbl_tamanhos (id_produto, tamanho_tamanhos, quantidade_tamanhos) 
            VALUES (:id_produto, :tamanho, :quantidade)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_produto', $id_produto);
    $stmt->bindParam(':tamanho', $tamanho);
    $stmt->bindParam(':quantidade', $quantidade);
    if ($stmt->execute()) {
      return $this->db->lastInsertId();
    } else {
      return false;
    }
  }

  // Método para atualizar um tamanho existente
  function atualizarTamanho($id_tamanhos, $id_produto, $tamanho, $quantidade) {
    $dataAtual = date('Y-m-d H:i:s');
    $sql = "UPDATE tbl_tamanhos SET 
        id_produto = :id_produto, 
        tamanho_tamanhos = :tamanho, 
        quantidade_tamanhos = :quantidade, 
        atualizado_em = :atualizado 
        WHERE id_tamanhos = :id_tamanhos AND excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_tamanhos', $id_tamanhos);
    $stmt->bindParam(':id_produto', $id_produto);
    $stmt->bindParam(':tamanho', $tamanho);
    $stmt->bindParam(':quantidade', $quantidade);
    $stmt->bindParam(':atualizado', $dataAtual);
     if($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  function atualizarTamanhos($id, $id_produto, $tamanho, $quantidade){
    $dataatual = date('Y-m-d H:i:s');
    $sql = "UPDATE tbl_tamanhos SET 
              id_produto = :id_produto,
              tamanho_tamanhos = :tamanho,
              quantidade_tamanhos = :quantidade,
              atualizado_em = :atual
            WHERE id_tamanhos = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':id_produto', $id_produto);
    $stmt->bindParam(':tamanho', $tamanho);
    $stmt->bindParam(':quantidade', $quantidade);
    $stmt->bindParam(':atual', $dataatual);

    if($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
  
  // Método para deletar (soft delete) um tamanho
  function deletarTamanho(int $id_tamanhos) {
    $dataAtual = date("Y-m-d h:m:s");
    $status = $this->buscarPorID($id_tamanhos);
    $status = $status['excluido_em'] != NULL ? NULL : $dataAtual;

    $sql = "UPDATE tbl_tamanhos 
            SET excluido_em = :excluido 
            WHERE id_tamanhos = :id_tamanhos";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_tamanhos', $id_tamanhos);
    $stmt->bindParam(':excluido', $dataAtual);
    return $stmt->execute();
  }
}
