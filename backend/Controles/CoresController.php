<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Models\Cor;
use App\Koketsu\Database\Database;
use App\Koketsu\Core\View;
use App\Koketsu\Core\Redirect;
use App\Koketsu\Core\FileManager;

class CoresController {
    public $cores;
    public $db;
    public $gerenciarImagem;
    public function __construct() {
        $this->db = Database::getInstance();
        $this->cores = new Cor($this->db);
        $this->gerenciarImagem = new FileManager('upload');
    }
    // index
    public function index(){
        $resultado = $this->cores->buscarCores();
        return $resultado;
    }
     public function viewListarCores($pagina){
        $dados = $this->cores->paginacao($pagina);
        $total = $this->cores->totalDeCores($pagina);
        $total_inativos = $this->cores->buscarCoresInativos($pagina);
        $total_ativos = $this->cores->buscarCoresAtivos($pagina);
         view::render('cores/index', 
    [
        "cores" => $dados['data'],
        "total_cores" => $total,
        "total_inativos" => $total_inativos,
        "total_ativos" => $total_ativos,
        'paginacao' => $dados
    ] 
  );
}

    public function viewCriarCor(){
        view::render("cores/create");
    }

    public function viewEditarCor(int $id){
        $dados = $this->cores->buscarCoresPorIdProduto($id);
       var_dump($dados);
       foreach($dados as $cores){
        $dados = $cores;
       }
       view::render("cores/edit", ["cor" => $dados]);
    }

    public function viewExcluirCor(int $id){
         $dados = $this->cores->buscarCoresPorIdProduto($id);
         $cor = null;
         if ($dados) {
             foreach($dados as $item){
                 $cor = $item;
             }
         }

         if ($cor) {
             view::render("cores/delete", ["cor" => $cor]);
         } else {
             Redirect::redirecionarComMensagem("cor/listar", "error", "Cor não encontrada.");
         }
    }

    public function relatorioCores($id, $data1, $data2){
     view::render("cores/relatorio",
           ["id" => $id, "data1" => $data1, "data2" => $data2]
      );
    }

    public function salvarCor(){
       if($this->cores->inserirCor(
            $_POST["id_produto"],
            $_POST["cor_cores"],
            $_POST["quantidade_cores"],
            "Ativo"
        )){
            Redirect::redirecionarComMensagem("cor/listar", "success", "Cor criada com sucesso!");
        }else{
            Redirect::redirecionarComMensagem("cor/create", "error", "Erro ao criar cor. Tente novamente.");
        }
    }
    public function atualizarCor($id){
        if($this->cores->atualizarCor(
            $id, // id_cores matches first argument in Model::atualizarCor($id_cores, $id_produto, $cor, $quantidade)
            $_POST["id_produto"],
            $_POST["cor_cores"],
            $_POST["quantidade_cores"]
        )){
            Redirect::redirecionarComMensagem("cor/listar", "success", "Cor atualizada com sucesso!");
        }else{
            Redirect::redirecionarComMensagem("cor/editar/$id", "error", "Erro ao atualizar cor.");
        }
    }
    public function deletarCor($id){
        if($this->cores->deletarCor($id)){
            Redirect::redirecionarComMensagem("cor/listar", "success", "Cor deletada com sucesso!");
        }else{
            Redirect::redirecionarComMensagem("cor/listar", "error", "Erro ao deletar cor.");
        }
    }  
}