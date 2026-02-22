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


public function __construct() {
    parent::__construct();
    $this->db = Database::getInstance();
    $this->produtos = new Produtos($this->db);
    $this->corModel = new Cor($this->db);
    $this->tamanhoModel = new Tamanho($this->db);
    $this->gerenciarImagem = new FileManager('upload');
}
// index
public function index(){
    $this->viewListarProduto();
}  

 public function viewListarProduto($pagina = 1) {
    $produto = $this->produtos->categoriasProdu();
    $total = $this->produtos->categoriasProdu();
    
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
        "total" => $total,
        'paginacao' => $dados,
        'busca' => $nomeBusca
    ]);
}

public function viewProdutoUnico(int $id_produto) {
        
        $produto = $this->produtos->buscarProdutoPorId($id_produto);
        
        if ($produto) {
           
            \App\Koketsu\Core\View::render('produtos/detalhes', [
                'produto' => $produto
            ]);
        } else {
          
            \App\Koketsu\Core\Redirect::redirecionarComMensagem("/produto/listar", "error", "Produto não encontrado.");
        }
    }
    
public function viewCriarProduto(){
 view::render("produtos/create");
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
            Redirect::redirecionarComMensagem("/produtos/listar", "success", "Produto ativado com sucesso!");
        } else {
            Redirect::redirecionarComMensagem("/produtos/listar", "error", "Erro ao ativar produto.");
        }
    }

public function atualizarProdutos() {
    $id_produto = (int)$_POST['id_produto'];
    $nome = $_POST['nome_produtos'];
    $descricao = $_POST['descricao_produtos'];
    $preco = $_POST['preco_produtos'];
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
                    
                    // Verificar se já existe (para reativar e manter o ID vinculado a imagens)
                    $stmt = $this->db->prepare("SELECT id_cores FROM tbl_cores WHERE id_produto = ? AND cor_cores = ? LIMIT 1");
                    $stmt->execute([$id_produto, $corNome]);
                    $existente = $stmt->fetch();

                    if ($existente) {
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
                    
                    // Verificar se já existe
                    $stmt = $this->db->prepare("SELECT id_tamanhos FROM tbl_tamanhos WHERE id_produto = ? AND tamanho_tamanhos = ? LIMIT 1");
                    $stmt->execute([$id_produto, $tamNome]);
                    $existente = $stmt->fetch();

                    if ($existente) {
                        $this->db->prepare("UPDATE tbl_tamanhos SET quantidade_tamanhos = ?, excluido_em = NULL, atualizado_em = NOW() WHERE id_tamanhos = ?")
                                 ->execute([$qtd, $existente['id_tamanhos']]);
                    } else {
                        $this->tamanhoModel->inserirTamanho($id_produto, $tamNome, $qtd);
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
            Redirect::redirecionarComMensagem("/produtos/listar", "success", "Produto inativado com sucesso!");
        } else {
            Redirect::redirecionarComMensagem("/produtos/listar", "error", "Erro ao inativar produto.");
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
        
        View::render("produtos/edit", [
            "produtos" => $produtos,
            "cores" => $cores,
            "tamanhos" => $tamanhos
        ]);
    }
}
