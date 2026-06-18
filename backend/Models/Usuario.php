<?php
namespace App\Koketsu\Models;
use PDO;

class Usuario
{
  private $id_usuarios;
  private $nome_usuarios;
  private $email_usuarios;
  private $senha_usuarios;
  private $nivel_acesso;
  private $foto_usuarios;
  private $criado_em;
  private $atualizado_em;
  private $excluido_em;
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  // Buscar todos os usuários
  function buscarUsuarios()
  {
    $sql = "SELECT * FROM tbl_usuarios WHERE excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  function totalDeUsuarios()
  {
    $sql = "SELECT COUNT(*) AS total FROM tbl_usuarios";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN);
  }

  function buscarUsuariosAtivos()
  {
    $sql = "SELECT COUNT(*) AS total_ativos FROM tbl_usuarios WHERE excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN);
  }

  function buscarUsuariosAdmin()
  {
    $sql = "SELECT COUNT(*) AS total_admin FROM tbl_usuarios WHERE nivel_acesso = 'admin' and excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN);
  }

  function buscarUsuariosInativos()
  {
    $sql = "SELECT COUNT(*) AS total_inativos FROM tbl_usuarios WHERE excluido_em IS NOT NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN);
  }

  public function paginacao(int $pagina = 1, int $porPagina = 50)
  {
    $offset = ($pagina - 1) * $porPagina;
    $sql = "SELECT id_usuarios, nome_usuarios, email_usuarios, nivel_acesso, excluido_em, foto_usuarios, criado_em FROM tbl_usuarios 
                LIMIT :offset, :porPagina";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':porPagina', $porPagina, PDO::PARAM_INT);
    $stmt->execute();
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalStmt = $this->db->query("SELECT COUNT(*) FROM tbl_usuarios");
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

  public function paginacaoClientes(int $pagina = 1, int $porPagina = 50)
  {
    $offset = ($pagina - 1) * $porPagina;
    $sql = "SELECT u.id_usuarios, u.nome_usuarios, u.email_usuarios, u.nivel_acesso, u.excluido_em, u.foto_usuarios, u.criado_em, p.telefone_perfil 
                FROM tbl_usuarios u
                LEFT JOIN tbl_perfil p ON u.id_usuarios = p.id_usuarios
                WHERE u.nivel_acesso = 'cliente'
                GROUP BY u.id_usuarios
                LIMIT :offset, :porPagina";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':porPagina', $porPagina, PDO::PARAM_INT);
    $stmt->execute();
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalStmt = $this->db->prepare("SELECT COUNT(*) FROM tbl_usuarios WHERE nivel_acesso = 'cliente'");
    $totalStmt->execute();
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

  public function paginacaoAPI(int $pagina = 1, int $por_pagina = 10): array
  {
    $totalQuery = "SELECT COUNT(*) FROM `tbl_usuarios`";
    $totalStmt = $this->db->query($totalQuery);
    $total_de_registros = $totalStmt->fetchColumn();
    $offset = ($pagina - 1) * $por_pagina;
    $dataQuery = "SELECT * FROM `tbl_usuarios` LIMIT :limit OFFSET :offset";
    $dataStmt = $this->db->prepare($dataQuery);
    $dataStmt->bindValue(':limit', $por_pagina, PDO::PARAM_INT);
    $dataStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $dataStmt->execute();
    $dados = $dataStmt->fetchAll(PDO::FETCH_ASSOC);
    $lastPage = ceil($total_de_registros / $por_pagina);

    return [
      'data' => $dados,
    ];
  }



  // Buscar usuários por email
  function buscarUsuariosPorEmail($email)
  {
    $sql = "SELECT * FROM tbl_usuarios WHERE email_usuarios = :email AND excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $resultado;
  }
  function buscarUsuariosPorId($id)
  {
    $sql = "SELECT * FROM tbl_usuarios where id_usuarios = :id_usuarios and excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_usuarios', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  function buscarUsuariosPorEmailInativos($email)
  {
    $sql = "SELECT * FROM tbl_usuarios where email_usuarios = :email and excluido_em IS NOT NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Inserir novo usuário
  function inserirUsuario(string $nome, string $email, string $senha, string $nivel, $imagem = null)
  {
    $senha = password_hash($senha, PASSWORD_DEFAULT);

    // Gerar próximo ID manualmente (workaround para tabelas sem AUTO_INCREMENT)
    $stmtMax = $this->db->query("SELECT COALESCE(MAX(id_usuarios), 0) + 1 AS next_id FROM tbl_usuarios");
    $nextId = (int) $stmtMax->fetch(PDO::FETCH_ASSOC)['next_id'];

    $sql = "INSERT INTO tbl_usuarios 
(id_usuarios, nome_usuarios, email_usuarios, senha_usuarios, nivel_acesso, foto_usuarios, criado_em)
VALUES (:id, :nome, :email, :senha, :nivel, :imagem, NOW())";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $nextId, PDO::PARAM_INT);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':nivel', $nivel);

    // Se houver imagem, usar; senão usar padrão
    $fotoPath = $imagem ?? '/img/logoperf.jpg';
    $stmt->bindParam(':imagem', $fotoPath);

    if ($stmt->execute()) {
      return $nextId;
    } else {
      return false;
    }
  }

  public function buscarPorID($id)
  {
    $sql = "SELECT * FROM tbl_usuarios WHERE id_usuarios = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  function atualizarUsuario($id, $nome, $email, $senha, $nivel, $imagem = null)
  {
    $senha = password_hash($senha, PASSWORD_DEFAULT);
    $dataatual = date('Y-m-d H:i:s');
    $sql = "UPDATE tbl_usuarios SET 
              nome_usuarios = :nome,
              email_usuarios = :email,
              senha_usuarios = :senha,
              nivel_acesso = :nivel,
              atualizado_em = :atual";

    if ($imagem) {
      $sql .= ", foto_usuarios = :imagem";
    }

    $sql .= " WHERE id_usuarios = :id";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':nivel', $nivel);
    $stmt->bindParam(':atual', $dataatual);

    if ($imagem) {
      $stmt->bindParam(':imagem', $imagem);
    }

    return $stmt->execute();
  }

  // Excluir usuário (soft delete)
  function deletarUsuario(int $id)
  {
    $agora = date("Y-m-d h:m:s");
    $coluna = $this->buscarPorID($id);
    //ternario
    $coluna = $coluna['excluido_em'] != NULL ? NULL : $agora;

    $sql = "UPDATE tbl_usuarios SET excluido_em = :excluido_em WHERE id_usuarios = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':excluido_em', $coluna);
    return $stmt->execute();
  }

  public function ativarUsuario(int $id)
  {
    $coluna = NULL;
    $sql = "UPDATE tbl_usuarios SET excluido_em = :excluido_em WHERE id_usuarios = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':excluido_em', $coluna, PDO::PARAM_NULL);

    return $stmt->execute();
  }

  public static function contarClientes($db)
  {
    $sql = "SELECT COUNT(*) as total FROM tbl_usuarios WHERE nivel_acesso = 'cliente'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
  }

  public function checarCredenciais(string $email, string $senha)
  {
    $usuario = $this->buscarUsuariosPorEmail($email);
    if (count($usuario) !== 1) {
      return false;
    }

    $usuario = $usuario[0];
    if (password_verify($senha, $usuario['senha_usuarios'])) {
      return $usuario;
    }
    return false;
  }

  // ─── Recuperação de Senha ───────────────────────────────────────────────────

  /**
   * Salva (ou atualiza) o token de recuperação de senha para o usuário
   */
  public function salvarTokenRecuperacao(int $idUsuario, string $token, string $expiracao): bool
  {
    // Remove tokens anteriores deste usuário
    $del = $this->db->prepare("DELETE FROM tbl_recuperacao_senha WHERE id_usuarios = :id");
    $del->bindParam(':id', $idUsuario, PDO::PARAM_INT);
    $del->execute();

    $sql = "INSERT INTO tbl_recuperacao_senha (id_usuarios, token, expiracao, usado, criado_em)
            VALUES (:id, :token, :expiracao, 0, NOW())";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id',        $idUsuario,  PDO::PARAM_INT);
    $stmt->bindParam(':token',     $token);
    $stmt->bindParam(':expiracao', $expiracao);
    return $stmt->execute();
  }

  /**
   * Busca um token válido (não expirado e não usado)
   */
  public function buscarTokenRecuperacao(string $token): ?array
  {
    $sql = "SELECT * FROM tbl_recuperacao_senha
            WHERE token = :token
              AND usado = 0
              AND expiracao > NOW()
            LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
  }

  /**
   * Atualiza somente a senha do usuário (não altera outros dados)
   */
  public function atualizarSenha(int $idUsuario, string $novaSenha): bool
  {
    $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
    $sql  = "UPDATE tbl_usuarios SET senha_usuarios = :senha, atualizado_em = NOW() WHERE id_usuarios = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':senha', $hash);
    $stmt->bindParam(':id',    $idUsuario, PDO::PARAM_INT);
    return $stmt->execute();
  }

  /**
   * Marca o token como usado após a redefinição
   */
  public function invalidarTokenRecuperacao(string $token): bool
  {
    $sql  = "UPDATE tbl_recuperacao_senha SET usado = 1 WHERE token = :token";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':token', $token);
    return $stmt->execute();
  }
}