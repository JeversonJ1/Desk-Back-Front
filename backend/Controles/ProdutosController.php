<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Models\Produtos;
use App\Koketsu\Models\Cor;
use App\Koketsu\Models\Tamanho;
use App\Koketsu\Database\Database;
use App\Koketsu\Core\View;
use App\Koketsu\Core\Redirect;
use App\Koketsu\Core\FileManager;
use App\Koketsu\Controles\Admin\AdminController;

class ProdutosController extends AdminController{
public $produtos;
public $corModel;
public $tamanhoModel;
public $db;
 public $gerenciarImagem;
 public $imagemModel;


public function __construct() {
    parent::__construct();
    
    // Bloquear vendedor de gerenciar produtos
    if ($this->session->get('usuario_tipo') === 'vendedor') {
        Redirect::redirecionarComMensagem("/backend/admin/dashboard", "error", "Acesso restrito apenas a administradores.");
    }

    $this->db = Database::getInstance();
    $this->produtos = new Produtos($this->db);
    $this->corModel = new Cor($this->db);
    $this->tamanhoModel = new Tamanho($this->db);
    $this->imagemModel = new \App\Koketsu\Models\Imagem($this->db);
    $this->gerenciarImagem = new FileManager(__DIR__ . '/../../backend/upload');

    // Se a requisição enviar JSON, parseia para $_POST
    if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (is_array($data)) {
            $_POST = array_merge($_POST, $data);
        }
    }
}

private function sendResponse($success, $message, $extra = [], $fallbackUrl = "/produtos/listar", $errorType = "error") {
    $isAjax = isset($_GET['json']) || 
              (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
              (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) ||
              (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
              
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(array_merge([
            'success' => $success,
            'message' => $message
        ], $extra));
        exit;
    }
    
    $msgType = $success ? "success" : $errorType;
    Redirect::redirecionarComMensagem($fallbackUrl, $msgType, $message);
    exit;
}
// index
public function index(){
    $this->viewListarProduto();
}  

 public function viewListarProduto($pagina = 1) {
    $produto = $this->produtos->categoriasProdu();
    
    if (empty($pagina) || $pagina <= 0) $pagina = 1;
    
    // Buscar por nome se foi feita uma pesquisa
    $nomeBusca = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nome_produtos'])) {
        $nomeBusca = trim($_POST['nome_produtos']);
    }
    
    $dados = $this->produtos->paginacao($pagina, 50, $nomeBusca);
    
    View::render("produtos/index", [
        "produtos" => $dados['data'],
        "produto" => $produto,
        'paginacao' => $dados,
        'busca' => $nomeBusca
    ]);
}

public function viewProdutoUnico(int $id_produto) {
        
        $produto = $this->produtos->buscarProdutoPorId($id_produto);
        
        if ($produto) {
           
            View::render('produtos/detalhes', [
                'produto' => $produto
            ]);
        } else {
          
            Redirect::redirecionarComMensagem("/produto/listar", "error", "Produto não encontrado.");
        }
    }
    
public function viewCriarProduto(){
    $categorias = (new \App\Koketsu\Models\Categoria($this->db))->buscarCategorias();
    View::render("produtos/create", ['categorias' => $categorias]);
}


public function viewExcluirProduto(int $id) {
        $dados = $this->produtos->buscarPorID($id);
        View::render("produtos/delete", ["produtos" => $dados]);
    }

    public function viewAtivarProdutos($id){
         $dados = $this->produtos->buscarPorID($id);
         View::render("/produtos/ativar",["produtos" => $dados]);
    }

    public function ativarProduto(){
        $id = (int)$_POST['id_produto'];
        if ($this->produtos->ativarProduto($id)) {
            $this->sendResponse(true, "Produto ativado com sucesso!");
        } else {
            $this->sendResponse(false, "Erro ao ativar produto.");
        }
    }

public function atualizarProdutos() {
    $id_produto = (int)$_POST['id_produto'];
    $nome = $_POST['nome_produtos'];
    $descricao = $_POST['descricao_produtos'];
    $precoRaw = $_POST['preco_produtos'];
    $preco = str_replace(['R$', '.', ' '], '', $precoRaw);
    $preco = str_replace(',', '.', $preco);
    $estoque = $_POST['estoque_produtos'];
    $id_categoria = $_POST['id_categoria'];
    $imagem = null;
    if (isset($_FILES['imagem_produtos']) && $_FILES['imagem_produtos']['error'] == 0) {
        $imagem = $this->gerenciarImagem->salvarArquivo($_FILES['imagem_produtos'], 'produtos');
        
    }

    if ($this->produtos->atualizarProduto($id_produto, $nome, $descricao, $preco, $estoque, $imagem, $id_categoria)) {
        
        // Atualizar Cores (Soft Delete inicial para evitar quebra de FK e inconsistência)
        $this->db->prepare("UPDATE tbl_cores SET excluido_em = NOW() WHERE id_produto = ?")->execute([$id_produto]);
        if (!empty($_POST['cores'])) {
            foreach ($_POST['cores'] as $index => $corNome) {
                if (!empty($corNome)) {
                    $qtd = $_POST['quantidade_cores'][$index] ?? 0;
                    
                    // Verificar se já existe (mesmo inativo) para reativar
                    $stmt = $this->db->prepare("SELECT id_cores FROM tbl_cores WHERE id_produto = ? AND cor_cores = ? LIMIT 1");
                    $stmt->execute([$id_produto, $corNome]);
                    $existente = $stmt->fetch(\PDO::FETCH_ASSOC);

                    if (is_array($existente) && isset($existente['id_cores'])) {
                        $this->db->prepare("UPDATE tbl_cores SET quantidade_cores = ?, excluido_em = NULL, atualizado_em = NOW() WHERE id_cores = ?")
                                 ->execute([$qtd, $existente['id_cores']]);
                    } else {
                        $this->corModel->inserirCor($id_produto, $corNome, $qtd);
                    }
                }
            }
        }

        // Atualizar Tamanhos (Soft Delete inicial)
        $this->db->prepare("UPDATE tbl_tamanhos SET excluido_em = NOW() WHERE id_produto = ?")->execute([$id_produto]);
        if (!empty($_POST['tamanhos'])) {
            foreach ($_POST['tamanhos'] as $index => $tamNome) {
                if (!empty($tamNome)) {
                    $qtd = $_POST['quantidade_tamanhos'][$index] ?? 0;
                    
                    // Verificar se já existe (mesmo inativo) para reativar
                    $stmt = $this->db->prepare("SELECT id_tamanhos FROM tbl_tamanhos WHERE id_produto = ? AND tamanho_tamanhos = ? LIMIT 1");
                    $stmt->execute([$id_produto, $tamNome]);
                    $existente = $stmt->fetch(\PDO::FETCH_ASSOC);

                    if (is_array($existente) && isset($existente['id_tamanhos'])) {
                        $this->db->prepare("UPDATE tbl_tamanhos SET quantidade_tamanhos = ?, excluido_em = NULL, atualizado_em = NOW() WHERE id_tamanhos = ?")
                                 ->execute([$qtd, $existente['id_tamanhos']]);
                    } else {
                        $this->tamanhoModel->inserirTamanho($id_produto, $tamNome, $qtd);
                    }
                }
            }
        }

        // Remover imagens da galeria solicitadas
        if (!empty($_POST['remover_imagens']) && is_array($_POST['remover_imagens'])) {
            foreach ($_POST['remover_imagens'] as $idImgRemover) {
                $imgData = $this->imagemModel->buscarPorId($idImgRemover);
                if ($imgData) {
                    $this->gerenciarImagem->delete($imgData['caminho_imagem']);
                    $this->imagemModel->excluirImagem($idImgRemover);
                }
            }
        }

        // Salvar Galeria Adicional
        if (!empty($_FILES['galeria_produtos']['name'][0])) {
            foreach ($_FILES['galeria_produtos']['name'] as $key => $name) {
                if ($_FILES['galeria_produtos']['error'][$key] == 0) {
                    $fileArray = [
                        'name' => $_FILES['galeria_produtos']['name'][$key],
                        'type' => $_FILES['galeria_produtos']['type'][$key],
                        'tmp_name' => $_FILES['galeria_produtos']['tmp_name'][$key],
                        'error' => $_FILES['galeria_produtos']['error'][$key],
                        'size' => $_FILES['galeria_produtos']['size'][$key]
                    ];
                    $caminho = $this->gerenciarImagem->salvarArquivo($fileArray, 'produtos/galeria', ['image/jpeg', 'image/png', 'image/webp', 'video/mp4'], 52428800);
                    if ($caminho) {
                        $this->imagemModel->inserirImagem($id_produto, null, null, $caminho, 'Galeria');
                    }
                }
            }
        }

        Redirect::redirecionarComMensagem("/produtos/listar/", "success", "Produto atualizado com sucesso!");
    } else {
        Redirect::redirecionarComMensagem("/produtos/editar/" . $id_produto, "error", "Erro ao atualizar produto!");
    }
}

    public function deletarProdutos(){
        $id = (int)$_POST['id_produto'];
        if ($this->produtos->deletarProdutos($id)) {
            $this->sendResponse(true, "Produto inativado com sucesso!");
        } else {
            $this->sendResponse(false, "Erro ao inativar produto.");
        }
    }

    public function excluirPermanente() {
        $id = (int)($_POST['id_produto'] ?? 0);
        if (!$id) {
            $this->sendResponse(false, "ID do produto inválido.");
        }
        
        // Buscar informações do produto para apagar a foto principal do disco
        $prod = $this->produtos->buscarPorID($id);
        if ($prod) {
            if (!empty($prod['imagem_produtos'])) {
                $this->gerenciarImagem->delete($prod['imagem_produtos']);
            }
        }

        if ($this->produtos->excluirProdutoPermanente($id)) {
            $this->sendResponse(true, "Produto excluído permanentemente do sistema!");
        } else {
            $this->sendResponse(false, "Erro ao excluir permanentemente o produto.");
        }
    }

    public function acaoEmLote() {
        $ids = $_POST['ids'] ?? [];
        $acao = $_POST['acao'] ?? '';

        if (empty($ids) || !is_array($ids)) {
            $this->sendResponse(false, "Nenhum produto selecionado.");
        }

        if (!in_array($acao, ['inativar', 'ativar', 'excluir_permanente'])) {
            $this->sendResponse(false, "Ação em lote inválida.");
        }

        $sucessos = 0;
        $erros = 0;

        foreach ($ids as $id) {
            $id = (int)$id;
            if ($acao === 'inativar') {
                $prod = $this->produtos->buscarPorID($id);
                if ($prod && empty($prod['excluido_em'])) {
                    if ($this->produtos->deletarProdutos($id)) $sucessos++;
                    else $erros++;
                } else {
                    $sucessos++; // já inativo
                }
            } elseif ($acao === 'ativar') {
                $prod = $this->produtos->buscarPorID($id);
                if ($prod && !empty($prod['excluido_em'])) {
                    if ($this->produtos->ativarProduto($id)) $sucessos++;
                    else $erros++;
                } else {
                    $sucessos++; // já ativo
                }
            } elseif ($acao === 'excluir_permanente') {
                $prod = $this->produtos->buscarPorID($id);
                if ($prod) {
                    if (!empty($prod['imagem_produtos'])) {
                        $this->gerenciarImagem->delete($prod['imagem_produtos']);
                    }
                    if ($this->produtos->excluirProdutoPermanente($id)) $sucessos++;
                    else $erros++;
                }
            }
        }

        if ($erros > 0) {
            $this->sendResponse(false, "Ação em lote concluída com alguns erros: {$sucessos} com sucesso, {$erros} falhas.");
        } else {
            $this->sendResponse(true, "Ação em lote executada com sucesso para todos os itens!");
        }
    }
public function relatorioProduto($id, $data1, $data2){
 View::render("produto/relatorio",
 ["id"=> $id, "data1"=> $data1, "data2"=> $data2]);
}

 public function salvarProduto() {
if (empty($_POST["nome_produtos"]) || empty($_FILES['imagem_produtos']['name'])) {
            Redirect::redirecionarComMensagem("/produtos/criar", "error", "Nome e Foto são obrigatórios.");
        }

        $imagem = $this->gerenciarImagem->salvarArquivo($_FILES['imagem_produtos'], 'produtos');

        if ($id_produto = $this->produtos->inserirProduto(
            $_POST["nome_produtos"],
            $_POST["descricao_produtos"],
            $_POST['preco_produtos'],
            $_POST['estoque_produtos'], 
            $_POST['id_categoria'],
            $imagem
        )) {
            // Salvar Cores
            if (!empty($_POST['cores'])) {
                foreach ($_POST['cores'] as $index => $corNome) {
                    if (!empty($corNome)) {
                        $qtd = $_POST['quantidade_cores'][$index] ?? 0;
                        $this->corModel->inserirCor($id_produto, $corNome, $qtd);
                    }
                }
            }

            // Salvar Tamanhos
            if (!empty($_POST['tamanhos'])) {
                foreach ($_POST['tamanhos'] as $index => $tamNome) {
                    if (!empty($tamNome)) {
                        $qtd = $_POST['quantidade_tamanhos'][$index] ?? 0;
                        $this->tamanhoModel->inserirTamanho($id_produto, $tamNome, $qtd);
                    }
                }
            }

            // Salvar Galeria Adicional
            if (!empty($_FILES['galeria_produtos']['name'][0])) {
                foreach ($_FILES['galeria_produtos']['name'] as $key => $name) {
                    if ($_FILES['galeria_produtos']['error'][$key] == 0) {
                        $fileArray = [
                            'name' => $_FILES['galeria_produtos']['name'][$key],
                            'type' => $_FILES['galeria_produtos']['type'][$key],
                            'tmp_name' => $_FILES['galeria_produtos']['tmp_name'][$key],
                            'error' => $_FILES['galeria_produtos']['error'][$key],
                            'size' => $_FILES['galeria_produtos']['size'][$key]
                        ];
                        $caminho = $this->gerenciarImagem->salvarArquivo($fileArray, 'produtos/galeria', ['image/jpeg', 'image/png', 'image/webp', 'video/mp4'], 52428800);
                        if ($caminho) {
                            $this->imagemModel->inserirImagem($id_produto, null, null, $caminho, 'Galeria');
                        }
                    }
                }
            }

            Redirect::redirecionarComMensagem("/produtos/listar", "success", "Produtos cadastrado com sucesso!");
        } else {
            Redirect::redirecionarComMensagem("/produtos/criar", "error", "Erro ao cadastrar produtos.");
        }
    }

    public function viewEditarProdutos(int $id) {
        $produtos = $this->produtos->buscarPorID($id);
        if (!$produtos) {
            Redirect::redirecionarComMensagem("/produtos/listar", "error", "Produto não encontrado.");
        }
        
        $cores = $this->corModel->buscarCoresPorIdProduto($id);
        $tamanhos = $this->tamanhoModel->buscarTamanhosPorIdProduto($id);
        $categorias = (new \App\Koketsu\Models\Categoria($this->db))->buscarCategorias();
        $galeria = $this->imagemModel->buscarPorProduto($id);
        
        View::render("produtos/edit", [
            "produtos" => $produtos,
            "cores" => $cores,
            "tamanhos" => $tamanhos,
            "categorias" => $categorias,
            "galeria" => $galeria
        ]);
    }
}
