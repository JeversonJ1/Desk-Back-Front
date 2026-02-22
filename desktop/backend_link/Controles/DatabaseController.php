<?php

namespace App\Koketsu\Controles;

use App\Koketsu\Classes\Database;

class DatabaseController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function testarConexao()
    {
        $resultado = $this->db->testar();
        header('Content-Type: application/json');
        echo json_encode($resultado);
    }

    public function getProdutos()
    {
        try {
            $sql = "SELECT * FROM tbl_produtos LIMIT 20";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $produtos = $stmt->fetchAll();

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'sucesso',
                'dados' => $produtos,
                'total' => count($produtos)
            ]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'erro',
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function getClientes()
    {
        try {
            $sql = "SELECT * FROM tbl_clientes LIMIT 20";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $clientes = $stmt->fetchAll();

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'sucesso',
                'dados' => $clientes,
                'total' => count($clientes)
            ]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'erro',
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function getPedidos()
    {
        try {
            $sql = "SELECT p.*, c.nome_clientes FROM tbl_pedidos p 
                    LEFT JOIN tbl_clientes c ON p.id_cliente = c.id_cliente 
                    LIMIT 20";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $pedidos = $stmt->fetchAll();

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'sucesso',
                'dados' => $pedidos,
                'total' => count($pedidos)
            ]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'erro',
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function getCategorias()
    {
        try {
            $sql = "SELECT * FROM tbl_categorias";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $categorias = $stmt->fetchAll();

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'sucesso',
                'dados' => $categorias,
                'total' => count($categorias)
            ]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'erro',
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function salvarProduto()
    {
        try {
            // Ler dados JSON do corpo da requisição
            $json = file_get_contents('php://input');
            $dados = json_decode($json, true);

            if (!$dados) {
                throw new \Exception('Dados inválidos');
            }

            // Validar campos obrigatórios
            if (empty($dados['nome_produtos']) || empty($dados['preco_produtos'])) {
                throw new \Exception('Nome e preço são obrigatórios');
            }

            // Processar imagem Base64
            $caminhoImagem = null;
            if (!empty($dados['imagem_produtos']) && strpos($dados['imagem_produtos'], 'data:image') === 0) {
                // Extrair extensão e dados Base64
                preg_match('/data:image\/(\w+);base64,(.+)/', $dados['imagem_produtos'], $matches);
                if (count($matches) === 3) {
                    $extensao = $matches[1];
                    $imagemBase64 = $matches[2];

                    // Gerar nome único
                    $nomeArquivo = uniqid() . '.' . $extensao;
                    $diretorio = __DIR__ . '/../storage/produtos/';

                    // Criar diretório se não existir
                    if (!is_dir($diretorio)) {
                        mkdir($diretorio, 0777, true);
                    }

                    // Salvar arquivo
                    $caminhoCompleto = $diretorio . $nomeArquivo;
                    file_put_contents($caminhoCompleto, base64_decode($imagemBase64));

                    // Caminho relativo para salvar no banco
                    $caminhoImagem = 'storage/produtos/' . $nomeArquivo;
                }
            }

            // Inserir no banco
            $sql = "INSERT INTO tbl_produtos 
                    (nome_produtos, descricao_produtos, preco_produtos, estoque_produtos, id_categoria, imagem_produtos) 
                    VALUES (:nome, :descricao, :preco, :estoque, :categoria, :imagem)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nome' => $dados['nome_produtos'],
                ':descricao' => $dados['descricao_produtos'] ?? '',
                ':preco' => $dados['preco_produtos'],
                ':estoque' => $dados['estoque_produtos'] ?? 0,
                ':categoria' => $dados['id_categoria'] ?? null,
                ':imagem' => $caminhoImagem
            ]);

            $idProduto = $this->db->lastInsertId();

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Produto criado com sucesso',
                'data' => [
                    'id' => $idProduto,
                    'nome' => $dados['nome_produtos'],
                    'imagem' => $caminhoImagem
                ]
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
