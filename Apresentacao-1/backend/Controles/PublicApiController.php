<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Models\Produtos;
use App\Koketsu\Models\Pedidos;
use App\Koketsu\Models\Usuario;
use App\Koketsu\Models\Categoria;
use App\Koketsu\Models\Cor;
use App\Koketsu\Models\Perfil;
use App\Koketsu\Models\Tamanho;
use App\Koketsu\Models\ItensPedidos;
use App\Koketsu\Models\Imagem;
use App\Koketsu\Models\Carrinho;
use App\Koketsu\Models\EstoqueMovimentacao;
use App\Koketsu\Database\Database;

class PublicApiController {
    private $produtosModel;
    private $pedidosModel;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->produtosModel = new Produtos($this->db);
        $this->pedidosModel = new Pedidos($this->db);
    }

    // ==================== PRODUTOS ====================
    public function getProdutos() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $registros_por_pagina = 10;
        $offset = ($page - 1) * $registros_por_pagina;
        
        // Total de registros
        $sqlCount = "SELECT COUNT(*) as total FROM tbl_produtos WHERE excluido_em IS NULL";
        $stmtCount = $this->db->prepare($sqlCount);
        $stmtCount->execute();
        $total = $stmtCount->fetch(\PDO::FETCH_ASSOC)['total'];
        $total_paginas = ceil($total / $registros_por_pagina);
        
        // Dados paginados
        $sql = "SELECT * FROM tbl_produtos WHERE excluido_em IS NULL LIMIT " . intval($registros_por_pagina) . " OFFSET " . intval($offset);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $dados = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach ($dados as &$produto) {
            $caminho = 'backend/upload/' . $produto['imagem_produtos'];
            $produto['caminho_imagem'] = $this->converterParaBase64($caminho);
        }
        unset($produto);
        
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $dados,
            'paginacao' => [
                'pagina_atual' => $page,
                'registros_por_pagina' => $registros_por_pagina,
                'total_registros' => $total,
                'total_paginas' => $total_paginas
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function getProdutoById($id) {
        $id = (int)$id;
        $produto = $this->produtosModel->buscarPorId($id);
        
        header('Content-Type: application/json');
        if ($produto) {
            $caminho = 'backend/upload/' . $produto['imagem_produtos'];
            $produto['caminho_imagem'] = $this->converterParaBase64($caminho);
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $produto], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Produto não encontrado']);
        }
        exit;
    }

    public function createProduto() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
            exit;
        }

        $id = $this->produtosModel->inserirProduto(
            $data['nome_produtos'],
            $data['descricao_produtos'] ?? '',
            (float)$data['preco_produtos'],
            (int)$data['estoque_produtos'],
            (int)$data['id_categoria'],
            $data['imagem_produtos'] ?? 'default.jpg'
        );

        if ($id) {
            http_response_code(201);
            echo json_encode(['status' => 'success', 'message' => 'Produto sincronizado com sucesso', 'id_produto' => $id]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro ao sincronizar produto']);
        }
        exit;
    }

    public function getProdutosVitrine() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // 1. Buscar todas as categorias ativas
            $sqlCat = "SELECT id_categorias, nome_categorias FROM tbl_categorias WHERE excluido_em IS NULL";
            $stmtCat = $this->db->prepare($sqlCat);
            $stmtCat->execute();
            $categorias = $stmtCat->fetchAll(\PDO::FETCH_ASSOC);

            $resultado = [];

            foreach ($categorias as $cat) {
                $itemCategoria = [
                    'categoria' => $cat['nome_categorias'],
                    'itens' => []
                ];

                // 2. Buscar produtos da categoria
                $sqlProd = "SELECT id_produto, nome_produtos, preco_produtos, imagem_produtos, estoque_produtos 
                           FROM tbl_produtos 
                           WHERE id_categoria = ? AND excluido_em IS NULL AND estoque_produtos > 0";
                $stmtProd = $this->db->prepare($sqlProd);
                $stmtProd->execute([$cat['id_categorias']]);
                $produtos = $stmtProd->fetchAll(\PDO::FETCH_ASSOC);

                foreach ($produtos as $prod) {
                    // 3. Buscar Tamanhos
                    $sqlTamanhos = "SELECT tamanho_tamanhos FROM tbl_tamanhos WHERE id_produto = ? AND excluido_em IS NULL";
                    $stmtT = $this->db->prepare($sqlTamanhos);
                    $stmtT->execute([$prod['id_produto']]);
                    $tamanhos = $stmtT->fetchAll(\PDO::FETCH_COLUMN);

                    // 4. Buscar Cores
                    $sqlCores = "SELECT cor_cores FROM tbl_cores WHERE id_produto = ? AND excluido_em IS NULL";
                    $stmtC = $this->db->prepare($sqlCores);
                    $stmtC->execute([$prod['id_produto']]);
                    $cores = $stmtC->fetchAll(\PDO::FETCH_COLUMN);

                    // 5. Formatar item
                    $itemCategoria['itens'][] = [
                        'id' => (int)$prod['id_produto'],
                        'nome' => $prod['nome_produtos'],
                        'preco' => (float)$prod['preco_produtos'],
                        'img' => $this->converterParaBase64('backend/upload/' . $prod['imagem_produtos']),
                        'tamanhos' => $tamanhos,
                        'cores' => $cores,
                        'oferta' => null, // Opcional, se tiver lógica futura
                        'desconto' => null
                    ];
                }

                if (!empty($itemCategoria['itens'])) {
                    $resultado[] = $itemCategoria;
                }
            }

            echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    // ==================== PEDIDOS ====================
    public function getPedidos() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $registros_por_pagina = 10;
        $offset = ($page - 1) * $registros_por_pagina;
        
        // Total de registros
        $sqlCount = "SELECT COUNT(*) as total FROM tbl_pedidos WHERE excluido_em IS NULL";
        $stmtCount = $this->db->prepare($sqlCount);
        $stmtCount->execute();
        $total = $stmtCount->fetch(\PDO::FETCH_ASSOC)['total'];
        $total_paginas = ceil($total / $registros_por_pagina);
        
        // Dados paginados com JOIN para pegar o id_usuario
        $sql = "SELECT p.id_pedido, p.id_perfil, p.data_pedido, p.total_pedido, p.status_pedido, p.criado_em, p.atualizado_em, pf.id_usuarios 
                FROM tbl_pedidos p 
                LEFT JOIN tbl_perfil pf ON p.id_perfil = pf.id_perfil 
                WHERE p.excluido_em IS NULL 
                LIMIT " . intval($registros_por_pagina) . " OFFSET " . intval($offset);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $pedidos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $pedidos,
            'paginacao' => [
                'pagina_atual' => $page,
                'registros_por_pagina' => $registros_por_pagina,
                'total_registros' => $total,
                'total_paginas' => $total_paginas
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function getPedidoById($id) {
        $id = (int)$id;
        $pedido = $this->pedidosModel->buscarPedidoPorId($id);
        
        header('Content-Type: application/json');
        if ($pedido) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $pedido], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Pedido não encontrado']);
        }
        exit;
    }

    public function salvarPedido() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
            exit;
        }

        $id_perfil = $data['id_perfil'] ?? null;
        $data_pedido = $data['data_pedido'] ?? date('Y-m-d H:i:s');
        $total_pedido = $data['total_pedido'] ?? 0;
        $status_pedido = $data['status_pedido'] ?? 'pendente';
        $itens = $data['itens'] ?? [];

        if (!$id_perfil) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID Perfil é obrigatório']);
            exit;
        }

        $id_pedido = $this->pedidosModel->inserirPedido($id_perfil, $data_pedido, $total_pedido, $status_pedido);
        
        if ($id_pedido) {
            if (!empty($itens) && is_array($itens)) {
                $itensModel = new ItensPedidos($this->db);
                foreach ($itens as $item) {
                    $itensModel->inserirItemPedido(
                        $id_pedido, 
                        $item['id_produto'], 
                        $item['quantidade'], 
                        $item['preco_unitario']
                    );
                }
            }
            http_response_code(201);
            echo json_encode([
                'status' => 'success', 
                'message' => 'Pedido sincronizado com sucesso', 
                'id_pedido' => $id_pedido
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro ao sincronizar pedido']);
        }
        exit;
    }

    public function createPedido() {
        return $this->salvarPedido();
    }

    // ==================== USUARIOS ====================
    public function getUsuarios() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $registros_por_pagina = 10;
        $offset = ($page - 1) * $registros_por_pagina;
        
        // Total de registros
        $sqlCount = "SELECT COUNT(*) as total FROM tbl_usuarios WHERE excluido_em IS NULL";
        $stmtCount = $this->db->prepare($sqlCount);
        $stmtCount->execute();
        $total = $stmtCount->fetch(\PDO::FETCH_ASSOC)['total'];
        $total_paginas = ceil($total / $registros_por_pagina);
        
        // Dados paginados
        $sql = "SELECT id_usuarios, nome_usuarios, email_usuarios, senha_usuarios, nivel_acesso, foto_usuarios FROM tbl_usuarios WHERE excluido_em IS NULL LIMIT " . intval($registros_por_pagina) . " OFFSET " . intval($offset);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $usuarios,
            'paginacao' => [
                'pagina_atual' => $page,
                'registros_por_pagina' => $registros_por_pagina,
                'total_registros' => $total,
                'total_paginas' => $total_paginas
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function getUsuarioById($id) {
        $id = (int)$id;
        $sql = "SELECT id_usuarios, nome_usuarios, email_usuarios, senha_usuarios, nivel_acesso, foto_usuarios FROM tbl_usuarios WHERE id_usuarios = ? AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        if ($usuario) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $usuario], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Usuário não encontrado']);
        }
        exit;
    }

    public function createUsuario() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
            exit;
        }

        $usuarioModel = new Usuario($this->db);
        $id = $usuarioModel->inserirUsuario(
            $data['nome_usuarios'],
            $data['email_usuarios'],
            $data['senha_usuarios'],
            $data['nivel_acesso'] ?? 'cliente',
            $data['foto_usuarios'] ?? null
        );

        if ($id) {
            http_response_code(201);
            echo json_encode(['status' => 'success', 'message' => 'Usuário/Cliente sincronizado com sucesso', 'id_usuarios' => $id]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro ao sincronizar usuário']);
        }
        exit;
    }

    // ==================== CATEGORIAS ====================
    public function getCategorias() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $registros_por_pagina = 10;
        $offset = ($page - 1) * $registros_por_pagina;
        
        // Total de registros
        $sqlCount = "SELECT COUNT(*) as total FROM tbl_categorias WHERE excluido_em IS NULL";
        $stmtCount = $this->db->prepare($sqlCount);
        $stmtCount->execute();
        $total = $stmtCount->fetch(\PDO::FETCH_ASSOC)['total'];
        $total_paginas = ceil($total / $registros_por_pagina);
        
        // Dados paginados
        $sql = "SELECT id_categorias, nome_categorias FROM tbl_categorias WHERE excluido_em IS NULL LIMIT " . intval($registros_por_pagina) . " OFFSET " . intval($offset);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $categorias = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $categorias,
            'paginacao' => [
                'pagina_atual' => $page,
                'registros_por_pagina' => $registros_por_pagina,
                'total_registros' => $total,
                'total_paginas' => $total_paginas
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function getCategoriaById($id) {
        $id = (int)$id;
        $sql = "SELECT id_categorias, nome_categorias FROM tbl_categorias WHERE id_categorias = ? AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $categoria = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        if ($categoria) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $categoria], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Categoria não encontrada']);
        }
        exit;
    }

    public function createCategoria() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
            exit;
        }
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Categoria criada com sucesso']);
        exit;
    }

    // ==================== CORES ====================
    public function getCores() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $registros_por_pagina = 10;
        $offset = ($page - 1) * $registros_por_pagina;
        
        // Total de registros
        $sqlCount = "SELECT COUNT(*) as total FROM tbl_cores WHERE excluido_em IS NULL";
        $stmtCount = $this->db->prepare($sqlCount);
        $stmtCount->execute();
        $total = $stmtCount->fetch(\PDO::FETCH_ASSOC)['total'];
        $total_paginas = ceil($total / $registros_por_pagina);
        
        // Dados paginados
        $sql = "SELECT id_cores, cor_cores FROM tbl_cores WHERE excluido_em IS NULL LIMIT " . intval($registros_por_pagina) . " OFFSET " . intval($offset);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $cores = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $cores,
            'paginacao' => [
                'pagina_atual' => $page,
                'registros_por_pagina' => $registros_por_pagina,
                'total_registros' => $total,
                'total_paginas' => $total_paginas
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function getCorById($id) {
        $id = (int)$id;
        $sql = "SELECT id_cores, cor_cores FROM tbl_cores WHERE id_cores = ? AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $cor = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        if ($cor) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $cor], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Cor não encontrada']);
        }
        exit;
    }

    public function createCor() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
            exit;
        }
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Cor criada com sucesso']);
        exit;
    }

    // ==================== PERFIS ====================
    public function getPerfis() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $registros_por_pagina = 10;
        $offset = ($page - 1) * $registros_por_pagina;
        
        // Total de registros
        $sqlCount = "SELECT COUNT(*) as total FROM tbl_perfil WHERE excluido_em IS NULL";
        $stmtCount = $this->db->prepare($sqlCount);
        $stmtCount->execute();
        $total = $stmtCount->fetch(\PDO::FETCH_ASSOC)['total'];
        $total_paginas = ceil($total / $registros_por_pagina);
        
        // Dados paginados
        $sql = "SELECT id_perfil, endereco_perfil, id_usuarios FROM tbl_perfil WHERE excluido_em IS NULL LIMIT " . intval($registros_por_pagina) . " OFFSET " . intval($offset);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $perfis = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $perfis,
            'paginacao' => [
                'pagina_atual' => $page,
                'registros_por_pagina' => $registros_por_pagina,
                'total_registros' => $total,
                'total_paginas' => $total_paginas
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function getPerfilById($id) {
        $id = (int)$id;
        $sql = "SELECT id_perfil, endereco_perfil, id_usuarios FROM tbl_perfil WHERE id_perfil = ? AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $perfil = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        if ($perfil) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $perfil], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Perfil não encontrado']);
        }
        exit;
    }

    public function createPerfil() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
            exit;
        }
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Perfil criado com sucesso']);
        exit;
    }

    // ==================== TAMANHOS ====================
    public function getTamanhos() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $registros_por_pagina = 10;
        $offset = ($page - 1) * $registros_por_pagina;
        
        // Total de registros
        $sqlCount = "SELECT COUNT(*) as total FROM tbl_tamanhos WHERE excluido_em IS NULL";
        $stmtCount = $this->db->prepare($sqlCount);
        $stmtCount->execute();
        $total = $stmtCount->fetch(\PDO::FETCH_ASSOC)['total'];
        $total_paginas = ceil($total / $registros_por_pagina);
        
        // Dados paginados
        $sql = "SELECT id_tamanhos, tamanho_tamanhos FROM tbl_tamanhos WHERE excluido_em IS NULL LIMIT " . intval($registros_por_pagina) . " OFFSET " . intval($offset);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $tamanhos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $tamanhos,
            'paginacao' => [
                'pagina_atual' => $page,
                'registros_por_pagina' => $registros_por_pagina,
                'total_registros' => $total,
                'total_paginas' => $total_paginas
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function getTamanhoById($id) {
        $id = (int)$id;
        $sql = "SELECT id_tamanhos, tamanho_tamanhos FROM tbl_tamanhos WHERE id_tamanhos = ? AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $tamanho = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        if ($tamanho) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $tamanho], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Tamanho não encontrado']);
        }
        exit;
    }

    public function createTamanho() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
            exit;
        }
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Tamanho criado com sucesso']);
        exit;
    }

    // ==================== ITENS PEDIDOS ====================
    public function getItenspedidos() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $registros_por_pagina = 10;
        $offset = ($page - 1) * $registros_por_pagina;
        
        // Total de registros
        $sqlCount = "SELECT COUNT(*) as total FROM tbl_itens_pedidos WHERE excluido_em IS NULL";
        $stmtCount = $this->db->prepare($sqlCount);
        $stmtCount->execute();
        $total = $stmtCount->fetch(\PDO::FETCH_ASSOC)['total'];
        $total_paginas = ceil($total / $registros_por_pagina);
        
        // Dados paginados
        $sql = "SELECT id_itens_pedidos, id_pedido, id_produto, quantidade, preco_unitario FROM tbl_itens_pedidos WHERE excluido_em IS NULL LIMIT " . intval($registros_por_pagina) . " OFFSET " . intval($offset);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $itens = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $itens,
            'paginacao' => [
                'pagina_atual' => $page,
                'registros_por_pagina' => $registros_por_pagina,
                'total_registros' => $total,
                'total_paginas' => $total_paginas
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function getItemPedidoById($id) {
        $id = (int)$id;
        $sql = "SELECT id_itens_pedidos, id_pedido, id_produto, quantidade, preco_unitario FROM tbl_itens_pedidos WHERE id_itens_pedidos = ? AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $item = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        if ($item) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $item], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Item de pedido não encontrado']);
        }
        exit;
    }

    public function createItemPedido() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
            exit;
        }
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Item de pedido criado com sucesso']);
        exit;
    }

    // ==================== AVALIAÇÕES ====================
    public function getAvaliacoes() {
        $avaliacaoModel = new \App\Koketsu\Models\Avaliacao(Database::getInstance());
        $avaliacoes = $avaliacaoModel->buscarAvaliacoes();
        
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $avaliacoes]);
        exit;
    }

    public function getRatingStatsByProduto($id_produto) {
        $avaliacaoModel = new \App\Koketsu\Models\Avaliacao(Database::getInstance());
        $stats = $avaliacaoModel->getStatsPorProduto($id_produto);
        
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $stats]);
        exit;
    }

    public function getAvaliacoesByProduto($id_produto) {
        $avaliacaoModel = new \App\Koketsu\Models\Avaliacao(Database::getInstance());
        $avaliacoes = $avaliacaoModel->buscarPorProduto($id_produto);
        
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $avaliacoes]);
        exit;
    }

    public function getLatestAvaliacoes() {
        $avaliacaoModel = new \App\Koketsu\Models\Avaliacao(Database::getInstance());
        $avaliacoes = $avaliacaoModel->buscarUltimasAvaliacoes(6);
        
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $avaliacoes]);
        exit;
    }

    public function getAvaliacaoById($id) {
        $avaliacaoModel = new \App\Koketsu\Models\Avaliacao(Database::getInstance());
        $avaliacao = $avaliacaoModel->buscarPorId($id);
        
        header('Content-Type: application/json');
        if ($avaliacao) {
            echo json_encode(['status' => 'success', 'data' => $avaliacao]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Avaliação não encontrada']);
        }
        exit;
    }

    public function createPublicAvaliacao() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data || !isset($data['id_produto']) || !isset($data['id_usuarios']) || !isset($data['nota_avaliacoes'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
            exit;
        }

        $db = Database::getInstance();
        $avaliacaoModel = new \App\Koketsu\Models\Avaliacao($db);
        
        $stmt = $db->prepare("SELECT id_perfil FROM tbl_perfil WHERE id_usuarios = :id_usuario LIMIT 1");
        $stmt->bindParam(':id_usuario', $data['id_usuarios']);
        $stmt->execute();
        $perfil = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$perfil) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Perfil do usuário não encontrado']);
            exit;
        }

        $res = $avaliacaoModel->inserirAvaliacao(
            $data['id_produto'],
            $perfil['id_perfil'],
            $data['nota_avaliacoes'],
            $data['comentario_avaliacoes'] ?? ''
        );

        if ($res) {
            echo json_encode(['status' => 'success', 'message' => 'Avaliação enviada!']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar avaliação. Certifique-se de que o produto já foi entregue.']);
        }
        exit;
    }

    public function createAvaliacao() {
        // Redireciona para o método público mais robusto
        return $this->createPublicAvaliacao();
    }

    // ==================== IMAGENS ====================
    public function getImagens() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $registros_por_pagina = 10;
        $offset = ($page - 1) * $registros_por_pagina;
        
        // Total de registros
        $sqlCount = "SELECT COUNT(*) as total FROM tbl_imagem WHERE excluido_em IS NULL";
        $stmtCount = $this->db->prepare($sqlCount);
        $stmtCount->execute();
        $total = $stmtCount->fetch(\PDO::FETCH_ASSOC)['total'];
        $total_paginas = ceil($total / $registros_por_pagina);
        
        // Dados paginados
        $sql = "SELECT id_imagem, caminho_imagem, id_produto FROM tbl_imagem WHERE excluido_em IS NULL LIMIT " . intval($registros_por_pagina) . " OFFSET " . intval($offset);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $imagens = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach ($imagens as &$img) {
            $img['caminho_imagem'] = $this->converterParaBase64($img['caminho_imagem']);
        }
        unset($img);
        
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $imagens,
            'paginacao' => [
                'pagina_atual' => $page,
                'registros_por_pagina' => $registros_por_pagina,
                'total_registros' => $total,
                'total_paginas' => $total_paginas
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function getImagemById($id) {
        $id = (int)$id;
        $sql = "SELECT id_imagem, caminho_imagem, id_produto FROM tbl_imagem WHERE id_imagem = ? AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $imagem = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        if ($imagem) {
            $imagem['caminho_imagem'] = $this->converterParaBase64($imagem['caminho_imagem']);
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $imagem], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Imagem não encontrada']);
        }
        exit;
    }

    public function createImagem() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
            exit;
        }
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Imagem criada com sucesso']);
        exit;
    }

    // ==================== CARRINHO ====================
    public function getCarrinho() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $registros_por_pagina = 10;
        $offset = ($page - 1) * $registros_por_pagina;
        
        // Total de registros
        $sqlCount = "SELECT COUNT(*) as total FROM tbl_carrinho WHERE excluido_em IS NULL";
        $stmtCount = $this->db->prepare($sqlCount);
        $stmtCount->execute();
        $total = $stmtCount->fetch(\PDO::FETCH_ASSOC)['total'];
        $total_paginas = ceil($total / $registros_por_pagina);
        
        // Dados paginados
        $sql = "SELECT id_carrinho, id_perfil, total_carrinho, status_carrinho FROM tbl_carrinho WHERE excluido_em IS NULL LIMIT " . intval($registros_por_pagina) . " OFFSET " . intval($offset);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $carrinho = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $carrinho,
            'paginacao' => [
                'pagina_atual' => $page,
                'registros_por_pagina' => $registros_por_pagina,
                'total_registros' => $total,
                'total_paginas' => $total_paginas
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function getCarrinhoById($id) {
        $id = (int)$id;
        $sql = "SELECT id_carrinho, id_perfil, total_carrinho, status_carrinho FROM tbl_carrinho WHERE id_carrinho = ? AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $item = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        if ($item) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $item], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Item do carrinho não encontrado']);
        }
        exit;
    }

    public function createCarrinho() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
            exit;
        }
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Item adicionado ao carrinho com sucesso']);
        exit;
    }

    // ==================== ESTOQUE ====================
    public function getEstoque() {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $registros_por_pagina = 10;
        $offset = ($page - 1) * $registros_por_pagina;
        
        // Total de registros
        $sqlCount = "SELECT COUNT(*) as total FROM tbl_estoque_movimentacao WHERE excluido_em IS NULL";
        $stmtCount = $this->db->prepare($sqlCount);
        $stmtCount->execute();
        $total = $stmtCount->fetch(\PDO::FETCH_ASSOC)['total'];
        $total_paginas = ceil($total / $registros_por_pagina);
        
        // Dados paginados
        $sql = "SELECT id_estoque_movimentacao, id_produto, descricao_estoque_movimentacao, quantidade_estoque_movimentacao, data_estoque_movimentacao FROM tbl_estoque_movimentacao WHERE excluido_em IS NULL LIMIT " . intval($registros_por_pagina) . " OFFSET " . intval($offset);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $estoque = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $estoque,
            'paginacao' => [
                'pagina_atual' => $page,
                'registros_por_pagina' => $registros_por_pagina,
                'total_registros' => $total,
                'total_paginas' => $total_paginas
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function getEstoqueById($id) {
        $id = (int)$id;
        $sql = "SELECT id_estoque_movimentacao, id_produto, descricao_estoque_movimentacao, quantidade_estoque_movimentacao, data_estoque_movimentacao FROM tbl_estoque_movimentacao WHERE id_estoque_movimentacao = ? AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $item = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        if ($item) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $item], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Movimentação de estoque não encontrada']);
        }
        exit;
    }

    public function createEstoque() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
            exit;
        }

        $estoqueModel = new EstoqueMovimentacao($this->db);
        $id = $estoqueModel->inserirMovimentacao(
            $data['id_produto'],
            $data['tipo_estoque_movimentacao'] ?? 'entrada',
            $data['quantidade_estoque_movimentacao'],
            $data['descricao_estoque_movimentacao'] ?? null
        );

        if ($id) {
            http_response_code(201);
            echo json_encode(['status' => 'success', 'message' => 'Movimentação de estoque sincronizada', 'id_estoque_movimentacao' => $id]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro ao sincronizar estoque']);
        }
        exit;
    }


    private function converterParaBase64($caminhoRelativo) {
        if (empty($caminhoRelativo)) return null;

        // Se o caminho contiver http, assume que é uma URL externa
        if (strpos($caminhoRelativo, 'http') !== false) {
            // Extrai a URL real se estiver prefixada por backend/upload/
            if (preg_match('/(https?:\/\/[^\s]+)/', $caminhoRelativo, $matches)) {
                return $matches[1];
            }
            return $caminhoRelativo;
        }

        // Limpa o caminho se ele começar com /
        $caminhoLimpo = ltrim($caminhoRelativo, '/');
        
        // __DIR__ é backend/Controles, precisamos subir 2 níveis para a raiz do projeto
        $caminhoCompleto = __DIR__ . '/../../' . $caminhoLimpo;
        
        if (file_exists($caminhoCompleto) && is_file($caminhoCompleto)) {
            $conteudo = file_get_contents($caminhoCompleto);
            $tipo = mime_content_type($caminhoCompleto);
            $base64 = base64_encode($conteudo);
            return "data:$tipo;base64,$base64";
        }
        
        return null;
    }

    public function viewManutencao() {
        include __DIR__ . '/../Views/Templates/manutencao.php';
        exit;
    }
}