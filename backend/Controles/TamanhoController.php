<?php
namespace App\Koketsu\controles;

use App\Koketsu\Controles\Admin\AdminController;
use App\Koketsu\Models\Tamanho;
use App\Koketsu\Database\Database;
use App\Koketsu\Core\View;
use App\Koketsu\Core\Redirect;
use App\koketsu\Validadores\UsuarioValidador;
use App\Koketsu\Controles\Admin\AuthenticatedController;

class TamanhoController extends AdminController {
    public $tamanho;
    public $db;
    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance();
        $this->tamanho = new Tamanho($this->db);
    }
    // index
     public function viewListarTamanhos($pagina = 1){
    $dados = $this->tamanho->paginacao($pagina);
    $total = $this->tamanho->totalDeUsuarios();
    $total_inativos = $this->tamanho->buscarTamanhosInativos($pagina);
    $total_ativos = $this->tamanho->buscartamanhosAtivos($pagina);
    view::render('tamanho/index', 
    [
        "tamanhos" => $dados['data'],
        "total_tamanhos" => $total,
        "total_inativos" => $total_inativos,
        "total_ativos" => $total_ativos,
        'paginacao' => $dados
    ] 
  );
    }

    public function viewCriarTamanho(){
        view::render("/tamanho/create");
    }

    public function viewEditarTamanho(int $id){
        $dados = $this->tamanho->buscarPorID($id);
       View::render("tamanho/edit", ["tamanho" => $dados]);
    }

    public function viewExcluirTamanho($id){
         $dados = $this->tamanho->buscarPorID($id);
         View::render("tamanho/delete",["tamanho" => $dados]);
    }
    public function salvarTamanho(){
       if($this->tamanho->inserirTamanho(
            $_POST["id_produto"],
            $_POST["tamanho_tamanhos"],
            $_POST["quantidade_tamanho"],
            "Ativo"
        )){
            Redirect::redirecionarComMensagem("/tamanho/listar", "success", "Tamanho criado com sucesso!");
        }else{
            Redirect::redirecionarComMensagem("/tamanho/create", "error", "Erro ao criar tamanho. Tente novamente.");
        }
    }
    public function atualizarTamanho(){
        $id = (int)$_POST['id_tamanhos'];
        $id_produtos = $_POST['id_produto'];
        $tamanho = $_POST['tamanhos_tamanhos'];
        $quantidade = $_POST['quantidade_tamanhos'];
        if ($this->tamanho->atualizarTamanho($id, $id_produtos, $tamanho, $quantidade)) {
            Redirect::redirecionarComMensagem("/tamanho/listar", "success", "Tamanho atualizado com sucesso!");
        } else {
            Redirect::redirecionarComMensagem("/tamanho/editar" . $id, "error", "Erro ao atualizar tamanho.");
        }
    }
    public function deletarTamanho(){
        $id = (int)$_POST['id_tamanhos'];
        if ($this->tamanho->deletarTamanho($id)) {
            Redirect::redirecionarComMensagem("/tamanho/listar", "success", "Tamanho inativado com sucesso!");
        } else {
            Redirect::redirecionarComMensagem("/tamanho/listar", "error", "Erro ao inativar tamanho.");
        }
    }
}