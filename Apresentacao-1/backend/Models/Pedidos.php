<?php
namespace App\Koketsu\Models;
use PDO;
use PDOException;

class Pedidos {
      private $id_pedido;
      private $id_perfil;
      private $id_usuarios;
      private $data_pedido;
      private $total_pedido;
      private $status_pedido;
      private $criado_em;
      private $atualizado_em;
      private $excluido_em;
      private $db;

      public function __construct($db) {
           $this->db = $db;
      }

    // Buscar todos os pedidos ativos
    function buscarPedidos() {
        $sql = "SELECT * FROM tbl_pedidos WHERE excluido_em IS NULL"; // Corrigido SELECT * FROM tbl_pedidos
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function buscarPedidosAtivos() {
    $sql = "SELECT * FROM tbl_pedidos WHERE excluido_em IS NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Buscar pedido por ID
    public function buscarPedidoPorId(int $id) {
    
        $sql = "SELECT tbl_pedidos.*, tbl_perfil.endereco_perfil, tbl_perfil.id_usuarios, tbl_usuarios.nome_usuarios AS nome_cliente
                FROM tbl_pedidos
                LEFT JOIN tbl_perfil ON tbl_pedidos.id_perfil = tbl_perfil.id_perfil
                LEFT JOIN tbl_usuarios ON tbl_perfil.id_usuarios = tbl_usuarios.id_usuarios
                WHERE tbl_pedidos.id_pedido = :id_pedido";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_pedido', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }
    
    // Contar todos os pedidos
    public function contarTodosPedidos()
    {
        $sql = "SELECT COUNT(id_pedido) FROM tbl_pedidos";
        $stmt = $this->db->query($sql);
        return $stmt->fetchColumn();
    }

    // Buscar pedidos de um perfil específico
    function buscarPedidosPorCliente($id_perfil) {
        $sql = "SELECT * FROM tbl_pedidos 
                WHERE id_perfil = :id_perfil AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_perfil', $id_perfil);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar todos os pedidos de um usuário (através de todos os seus perfis)
    public function buscarPedidosPorUsuario(int $id_usuario) {
        $sql = "SELECT p.* FROM tbl_pedidos p
                JOIN tbl_perfil pf ON p.id_perfil = pf.id_perfil
                WHERE pf.id_usuarios = :id_usuario AND p.excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Buscar vendas mensais
    public function buscarVendasMensais()
        {
            // Atenção: A coluna SUM(data_pedido) estava incorreta e foi alterada para SUM(total_pedido)
            $sql = "
                SELECT
                    DATE_FORMAT(data_pedido, '%Y-%m-01') AS mes,
                    SUM(total_pedido) AS total_vendas
                FROM tbl_pedidos
                WHERE data_pedido >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                AND status_pedido IN ('pago', 'enviado', 'concluido')
                GROUP BY mes
                ORDER BY mes ASC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Busca a contagem de pedidos para cada status.
         * @return array
         */
        public function contarPedidosPorStatus()
        {
            $sql = "
                SELECT
                    status_pedido,
                    COUNT(id_pedido) AS contagem
                FROM tbl_pedidos
                WHERE excluido_em IS NULL
                GROUP BY status_pedido
                ORDER BY contagem DESC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        /**
         * Busca o faturamento (receita) mensal por categoria.
         * @return array
         */
        public function buscarVendasMensaisPorCategoria()
        {
            $sql = "
                SELECT
                    DATE_FORMAT(p.data_pedido, '%Y-%m-01') AS mes,
                    c.nome_categorias AS categoria,
                    SUM(ip.quantidade * ip.preco_unitario) AS faturamento
                FROM tbl_pedidos p
                
                JOIN tbl_itens_pedidos ip ON p.id_pedido = ip.id_pedido
                JOIN tbl_produtos prod ON ip.id_produto = prod.id_produto
                JOIN tbl_categorias c ON prod.id_categoria = c.id_categorias
                
                WHERE p.data_pedido >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                AND p.status_pedido IN ('pago', 'enviado', 'concluido')
                GROUP BY mes, c.nome_categorias
                ORDER BY mes ASC, faturamento DESC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Calcula o Ticket Médio (Total Vendido / Total de Pedidos) mensalmente.
         * Foi corrigido o uso de AVG(total_pedido) no lugar de AVG(data_pedido)
         * @return array
         */
        public function calcularTicketMedioMensal()
        {
            $sql = "
                SELECT
                    DATE_FORMAT(data_pedido, '%Y-%m-01') AS mes,
                    AVG(total_pedido) AS ticket_medio
                FROM tbl_pedidos
                WHERE data_pedido >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                AND status_pedido IN ('pago', 'enviado', 'concluido')
                GROUP BY mes
                ORDER BY mes ASC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
    // Função de paginação
public function paginacao(int $pagina = 1, int $por_pagina = 100, ?string $busca = null): array{

$offset = ($pagina - 1) * $por_pagina;

if ($busca) {
    $busca_formatada = '%' . $busca . '%';
    $totalQuery = "SELECT COUNT(*) FROM `tbl_pedidos` 
        LEFT JOIN tbl_perfil ON tbl_pedidos.id_perfil = tbl_perfil.id_perfil
        LEFT JOIN tbl_usuarios ON tbl_perfil.id_usuarios = tbl_usuarios.id_usuarios
        WHERE (
            CAST(tbl_pedidos.id_pedido AS CHAR) LIKE :busca
            OR LOWER(tbl_usuarios.nome_usuarios) LIKE LOWER(:busca)
            OR LOWER(tbl_perfil.endereco_perfil) LIKE LOWER(:busca)
        )";
    $totalStmt = $this->db->prepare($totalQuery);
    $totalStmt->bindParam(':busca', $busca_formatada);
    $totalStmt->execute();
} else {
    $totalQuery = "SELECT COUNT(*) FROM `tbl_pedidos`";
    $totalStmt = $this->db->query($totalQuery);
}

$total_de_registros = $totalStmt->fetchColumn();

if ($busca) {
    $busca_formatada = '%' . $busca . '%';
    $dataQuery = "SELECT 
        tbl_pedidos.*, 
        tbl_perfil.endereco_perfil, 
        tbl_usuarios.nome_usuarios AS nome_cliente 
    FROM tbl_pedidos 
    LEFT JOIN tbl_perfil ON tbl_pedidos.id_perfil = tbl_perfil.id_perfil
    LEFT JOIN tbl_usuarios ON tbl_perfil.id_usuarios = tbl_usuarios.id_usuarios
    WHERE (
        CAST(tbl_pedidos.id_pedido AS CHAR) LIKE :busca
        OR LOWER(tbl_usuarios.nome_usuarios) LIKE LOWER(:busca)
        OR LOWER(tbl_perfil.endereco_perfil) LIKE LOWER(:busca)
    )
    ORDER BY tbl_pedidos.data_pedido DESC
    LIMIT :limit OFFSET :offset";
    
    $dataStmt = $this->db->prepare($dataQuery);
    $dataStmt->bindParam(':busca', $busca_formatada);
    $dataStmt->bindValue(':limit', $por_pagina, PDO::PARAM_INT);
    $dataStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
} else {
    $dataQuery = "SELECT 
        tbl_pedidos.*, 
        tbl_perfil.endereco_perfil, 
        tbl_usuarios.nome_usuarios AS nome_cliente 
    FROM tbl_pedidos 
    LEFT JOIN tbl_perfil ON tbl_pedidos.id_perfil = tbl_perfil.id_perfil
    LEFT JOIN tbl_usuarios ON tbl_perfil.id_usuarios = tbl_usuarios.id_usuarios
    ORDER BY tbl_pedidos.data_pedido DESC
    LIMIT :limit OFFSET :offset";
    
    $dataStmt = $this->db->prepare($dataQuery);
    $dataStmt->bindValue(':limit', $por_pagina, PDO::PARAM_INT);
    $dataStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
}

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
  public function paginacaoAPI(int $pagina = 1, int $por_pagina = 50): array{
        $totalQuery = "SELECT COUNT(*) FROM `tbl_pedidos`";
        $totalStmt = $this->db->query($totalQuery);
        $total_de_registros = $totalStmt->fetchColumn();
        $offset = ($pagina - 1) * $por_pagina;
        $dataQuery = "SELECT * FROM `tbl_pedidos` LIMIT :limit OFFSET :offset";
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
 
    
    // Inserir novo pedido
    function inserirPedido($id_perfil, $data_pedido, $total_pedido, $status_pedido) { 
        
        $sql = "INSERT INTO tbl_pedidos
        (id_perfil, data_pedido, total_pedido, status_pedido, criado_em) 
        VALUES (:id_perfil, :data_pedido, :total_pedido, :status_pedido, NOW())"; 

        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':id_perfil', $id_perfil, PDO::PARAM_INT);
        $stmt->bindParam(':data_pedido', $data_pedido);
        $stmt->bindParam(':total_pedido', $total_pedido);
        $stmt->bindParam(':status_pedido', $status_pedido);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        } else {
            error_log("Erro ao inserir pedido: " . json_encode($stmt->errorInfo()));
            return false;
        }
    }

    // Contar total de pedidos ativos (usado para o cabeçalho/dashboard)
    function totalDePedidos() {
        $sql = "SELECT COUNT(*) FROM `tbl_pedidos` WHERE excluido_em IS NULL";
        
        $stmt = $this->db->query($sql);
        
        return $stmt->fetchColumn(); 
    }

    // Atualizar pedido
    public function atualizarPedido($id_pedido, $total_pedido, $data_pedido, $status_pedido, $imagem = null)
    {
        $dataatual = date('Y-m-d H:i:s');

        // SQL Base
        $sql = "UPDATE tbl_pedidos SET 
                    total_pedido = :total_pedido,
                    data_pedido = :data_pedido,
                    status_pedido = :status_pedido,
                    atualizado_em = :atualizado_em";

        // Se tiver imagem, adiciona ao SQL
        if (!empty($imagem)) {
            $sql .= ", imagem_pedidos = :imagem";
        }

        $sql .= " WHERE id_pedido = :id_pedido";

        $stmt = $this->db->prepare($sql);

        // Bind obrigatório
        $stmt->bindParam(':total_pedido', $total_pedido);
        $stmt->bindParam(':data_pedido', $data_pedido);
        $stmt->bindParam(':status_pedido', $status_pedido);
        $stmt->bindParam(':atualizado_em', $dataatual);
        $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);

        // Bind opcional da imagem
        if (!empty($imagem)) {
            $stmt->bindParam(':imagem', $imagem);
        }

        // Execução
        if ($stmt->execute()) {
            return true;
        }

        // Log de erro para debug
        error_log("Erro ao atualizar pedido: " . json_encode($stmt->errorInfo()));
        return false;
    }

    // Excluir pedido (Soft Delete)
    public function excluirPedido(int $id_pedido) {
        try {
            $dataatual = date('Y-m-d H:i:s');
            $sql = "UPDATE tbl_pedidos SET excluido_em = :excluido_em 
                    WHERE id_pedido = :id_pedido"; 
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':excluido_em', $dataatual);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT); 
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro no Soft Delete do Pedido #{$id_pedido}: " . $e->getMessage());
            return false;
        }
    }

    // Toggle de ativação/desativação de pedido (igual ao deletarUsuario)
    public function deletarPedido(int $id_pedido) {
        try {
            $agora = date("Y-m-d H:i:s");
            $pedido = $this->buscarPedidoPorId($id_pedido);
            
            // Se já está excluído, ativa (NULL). Se está ativo, exclui (data atual)
            $excluido_em = $pedido['excluido_em'] != NULL ? NULL : $agora;
            
            $sql = "UPDATE tbl_pedidos SET excluido_em = :excluido_em WHERE id_pedido = :id_pedido";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->bindParam(':excluido_em', $excluido_em);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao alterar status do Pedido #{$id_pedido}: " . $e->getMessage());
            return false;
        }
    }

    public function ativarPedido(int $id) {
    $coluna = NULL; 
    $sql = "UPDATE tbl_pedidos SET excluido_em = :excluido_em WHERE id_pedido = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':excluido_em', $coluna, PDO::PARAM_NULL); 
    
    return $stmt->execute();
}

    public static function contarPedidos($db) {
        $sql = "SELECT COUNT(*) as total FROM tbl_pedidos";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    
    // Buscar produtos que o usuário comprou (independente do perfil) e ainda não avaliou
    public function buscarProdutosCompradosPorUsuario($id_usuario) {
        $sql = "SELECT DISTINCT p.id_produto, p.nome_produtos, p.imagem_produtos
                FROM tbl_itens_pedidos ip
                JOIN tbl_pedidos ped ON ip.id_pedido = ped.id_pedido
                JOIN tbl_produtos p ON ip.id_produto = p.id_produto
                JOIN tbl_perfil perf ON ped.id_perfil = perf.id_perfil
                WHERE perf.id_usuarios = :id_usuario
                  AND ped.status_pedido IN ('concluido', 'pago', 'Concluido', 'Pago', 'CONCLUIDO', 'PAGO')
                  AND ped.excluido_em IS NULL
                  AND p.id_produto NOT IN (
                      SELECT a.id_produto 
                      FROM tbl_avaliacoes a
                      JOIN tbl_perfil perf2 ON a.id_cliente = perf2.id_perfil
                      WHERE perf2.id_usuarios = :id_usuario2 
                      AND a.excluido_em IS NULL
                      AND a.id_produto IS NOT NULL
                  )";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario2', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Buscar produtos que o cliente comprou (pedidos concluidos/pagos) e ainda não avaliou
    public function buscarProdutosCompradosPorPerfil($id_perfil) {
        $sql = "SELECT DISTINCT p.id_produto, p.nome_produtos, p.imagem_produtos
                FROM tbl_itens_pedidos ip
                JOIN tbl_pedidos ped ON ip.id_pedido = ped.id_pedido
                JOIN tbl_produtos p ON ip.id_produto = p.id_produto
                WHERE ped.id_perfil = :id_perfil
                  AND ped.status_pedido IN ('concluido', 'pago')
                  AND ped.excluido_em IS NULL
                  AND p.id_produto NOT IN (
                      SELECT a.id_produto FROM tbl_avaliacoes a
                      WHERE a.id_cliente = :id_perfil2 AND a.excluido_em IS NULL
                  )";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_perfil', $id_perfil, PDO::PARAM_INT);
        $stmt->bindParam(':id_perfil2', $id_perfil, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}