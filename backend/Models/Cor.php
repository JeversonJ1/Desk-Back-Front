<?php
namespace App\Koketsu\Models;
use PDO;

class Cor {
  private $id_cores;
  private $id_produto;
  private $cor_cores;
  private $quantidade_cores;
  private $criado_em;
  private $atualizado_em;
  private $excluido_em;
  private $db;

  public function __construct($db) {
    $this->db = $db;
  }

  // Método para buscar todas as cores não excluídas
  function buscarCores() {
    $sql = "SELECT * FROM tbl_cores WHERE excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  function totalDeCores() {
    $sql = "SELECT COUNT(*) AS total FROM tbl_cores";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN);
}

function buscarCoresAtivos() {
    $sql = "SELECT COUNT(*) AS total_ativos FROM tbl_cores WHERE excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN);
}
function buscarCoresInativos() {
    $sql = "SELECT COUNT(*) AS total_inativos FROM tbl_cores WHERE excluido_em IS NOT NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN);
}
  public function paginacao(int $pagina = 1, int $por_pagina = 10): array{
        $totalQuery = "SELECT COUNT(*) FROM `tbl_cores`";
        $totalStmt = $this->db->query($totalQuery);
        $total_de_registros = $totalStmt->fetchColumn();
        $offset = ($pagina - 1) * $por_pagina;
        $dataQuery = "SELECT * FROM `tbl_cores` LIMIT :limit OFFSET :offset";
        $dataStmt = $this->db->prepare($dataQuery);
        $dataStmt->bindValue(':limit', $por_pagina, PDO::PARAM_INT);
        $dataStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $dataStmt->execute();
        $dados = $dataStmt->fetchAll(PDO::FETCH_ASSOC);
        $lastPage = ceil($total_de_registros / $por_pagina);
 
        return [
            'data' => $dados,
            'total' => (int) $total_de_registros,
            'por_pagina' => (int) $por_pagina,
            'pagina_atual' => (int) $pagina,
            'ultima_pagina' => (int) $lastPage,
            'de' => $offset + 1,
            'para' => $offset + count($dados)
        ];
    }

  // Método para buscar cores por produto
  function buscarCoresPorIdProduto($id_produto) {
    $sql = "SELECT * FROM tbl_cores WHERE id_produto = :id_produto AND excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_produto', $id_produto);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Método para inserir uma nova cor
  function inserirCor($id_produto, 
  $cor, 
  $quantidade) {
    $sql = "INSERT INTO tbl_cores (id_produto, cor_cores, quantidade_cores) 
            VALUES (:id_produto, :cor, :quantidade)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_produto', $id_produto);
    $stmt->bindParam(':cor', $cor);
    $stmt->bindParam(':quantidade', $quantidade);
    if ($stmt->execute()) {
      return $this->db->lastInsertId();
    } else {
      return false;
    }
  }

  // Método para atualizar uma cor existente
  function atualizarCor($id_cores, $id_produto, $cor, $quantidade) {
    $dataAtual = date('Y-m-d H:i:s');
    $sql = "UPDATE tbl_cores SET 
        id_produto = :id_produto, 
        cor_cores = :cor, 
        quantidade_cores = :quantidade, 
        atualizado_em = :atualizado 
        WHERE id_cores = :id_cores AND excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_cores', $id_cores);
    $stmt->bindParam(':id_produto', $id_produto);
    $stmt->bindParam(':cor', $cor);
    $stmt->bindParam(':quantidade', $quantidade);
    $stmt->bindParam(':atualizado', $dataAtual);
     if($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
  

  // Método para deletar (soft delete) uma cor
  function deletarCor($id_cores) {
    $dataAtual = date('Y-m-d H:i:s');
    $sql = "UPDATE tbl_cores SET excluido_em = :excluido WHERE id_cores = :id_cores";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_cores', $id_cores);
    $stmt->bindParam(':excluido', $dataAtual);
    return $stmt->execute();
  }
}
