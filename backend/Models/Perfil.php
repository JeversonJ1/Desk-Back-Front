<?php 
namespace App\Koketsu\Models;
use PDO;

class Perfil {
  private $id_perfil;
  private $telefone_perfil;
  private $endereco_perfil;
  private $data_cadastro;
  private $criado_em;
  private $atualizado_em;
  private $excluido_em;
  private $id_usuarios;
  private $db;
  private $id;

  public function __construct($db) {
    $this->db = $db;
  }

  // Buscar todos os perfis não excluídos
  function buscarPerfis($id_perfil) {
    $sql = "SELECT * FROM tbl_perfil WHERE excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  function buscarPerfisPorId($id) {
    $sql = "SELECT * FROM tbl_perfil WHERE id_perfil = :id AND excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  
  function totalDePerfis() {
    $sql = "SELECT COUNT(*) AS total FROM tbl_perfil";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN);
}

function buscarPerfisAtivos() {
    $sql = "SELECT COUNT(*) AS total_ativos FROM tbl_perfil WHERE excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN);
}
function buscarPerfisInativos() {
    $sql = "SELECT COUNT(*) AS total_inativos FROM tbl_perfil WHERE excluido_em IS NOT NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN);
}
  public function paginacao(int $pagina = 1, int $por_pagina = 10): array{
        $totalQuery = "SELECT COUNT(*) FROM `tbl_perfil`";
        $totalStmt = $this->db->query($totalQuery);
        $total_de_registros = $totalStmt->fetchColumn();
        $offset = ($pagina - 1) * $por_pagina;
        $dataQuery = "SELECT * FROM `tbl_perfil` LIMIT :limit OFFSET :offset";
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

  function restaurarPerfil($id_perfil) {
    $sql = "UPDATE tbl_perfil 
            SET excluido_em = NULL 
            WHERE id_perfil = :id_perfil";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_perfil', $id_perfil);
    if ($stmt->execute()) {
    echo "Perfil restaurado com sucesso!";
} else {
    echo "Erro ao restaurar perfil.";
}
}

  function buscarUsuarioInativoPorId($id_perfil) {
    $sql = "SELECT * FROM tbl_perfil WHERE excluido_em IS NOT NULL AND id_perfil = :id_perfil";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_perfil', $id_perfil);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

  // Contar perfis inativos
  function contarPerfisInativos() {
    $sql = "SELECT COUNT(*) as total FROM tbl_perfil WHERE excluido_em IS NOT NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

  // Buscar perfil por ID de usuário
  function buscarPerfilPorUsuario($id_usuarios) {
    $sql = "SELECT * FROM tbl_perfil 
            WHERE id_usuarios = :id_usuarios AND excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_usuarios', $id_usuarios);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Inserir novo perfil
  function inserirPerfil($telefone, 
  $endereco, 
  $data_cadastro, 
  $id_usuarios) {
    $sql = "INSERT INTO tbl_perfil (telefone_perfil, endereco_perfil, data_cadastro, id_usuarios) 
            VALUES (:telefone, :endereco, :data_cadastro, :id_usuarios)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':data_cadastro', $data_cadastro);
    $stmt->bindParam(':id_usuarios', $id_usuarios);
    if ($stmt->execute()) {
      return $this->db->lastInsertId();
    } else {
      return false;
    }
  }

  // Atualizar perfil existente
  function atualizarPerfil($id_perfil, $telefone, $endereco, $data_cadastro, $id_usuarios) {
    $dataAtual = date('Y-m-d H:i:s');
    $sql = "UPDATE tbl_perfil SET 
        telefone_perfil = :telefone, 
        endereco_perfil = :endereco, 
        data_cadastro = :data_cadastro,
        id_usuarios = :id_usuarios,
        atualizado_em = :atualizado 
        WHERE id_perfil = :id_perfil AND excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_perfil', $id_perfil);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':data_cadastro', $data_cadastro);
    $stmt->bindParam(':id_usuarios', $id_usuarios);
    $stmt->bindParam(':atualizado', $dataAtual);
     if($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  // Soft delete do perfil
  function deletarPerfil($id_perfil) {
    $dataAtual = date('Y-m-d H:i:s');
    $sql = "UPDATE tbl_perfil 
            SET excluido_em = :excluido 
            WHERE id_perfil = :id_perfil";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_perfil', $id_perfil);
    $stmt->bindParam(':excluido', $dataAtual);
    return $stmt->execute();
  }
  function ativarPerfil($id_perfil){
    $dataatual = NULL;
    $sql = "UPDATE tbl_perfil SET 
    excluido_em = :atual
    WHERE id_perfil = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id_perfil);
    $stmt->bindParam(':atual', $dataatual);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }

  }
}