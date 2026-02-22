<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Models\Categoria;
use App\Koketsu\Database\Database;
use App\Koketsu\Core\View;
use App\Koketsu\Core\Redirect;

class CategoriasController {
    public $categoria;
    public $db;
    public function __construct() {
        $this->db = Database::getInstance();
        $this->categoria = new Categoria($this->db);
    }
    // index
    public function index(){
        $resultado = $this->categoria->buscarCategorias();
        return $resultado;
    }
     public function viewListarCategoria($pagina){
    $dados = $this->categoria->paginacao($pagina);
    $total = $this->categoria->totalDeCategorias($pagina);
    $total_inativos = $this->categoria->buscarCategoriasInativos($pagina);
    $total_ativos = $this->categoria->buscarCategoriasAtivos($pagina);
    view::render('categoria/index', 
    [
        "categorias" => $dados['data'],
        "total_categorias" => $total,
        "total_inativos" => $total_inativos,
        "total_ativos" => $total_ativos,
        'paginacao' => $dados
    ] 
  );
}

    public function viewCriarCategoria(){
        view::render("categoria/create");
    }

    public function viewEditarCategoria(int $id){
       $dados = $this->categoria->buscarCategoriaPorId($id);
       
    //    foreach($dados as $categoria){
    //     $dados = $categoria;
    //    }
       var_dump($dados);
       view::render("categoria/edit", ["categoria" => $dados]);
    }


    public function viewExcluirCategoria($id){
         $dados = $this->categoria->buscarCategoriaPorId($id);
         if ($dados) {
             view::render("categoria/delete", ["categoria" => $dados]);
         } else {
             Redirect::redirecionarComMensagem("categoria/listar", "error", "Categoria não encontrada.");
         }
    }

    public function relatorioCategoria($id, $data1, $data2){
     view::render("categoria/relatorio",
           ["id" => $id, "data1" => $data1, "data2" => $data2]
      );
    }

    public function salvarCategoria(){
       if($this->categoria->inserirCategoria(
            $_POST["nome_categorias"],
            $_POST["descricao_categorias"],
            "Ativo"
        )){
            Redirect::redirecionarComMensagem("categoria/listar", "success", "Categoria criada com sucesso!");
        }else{
            Redirect::redirecionarComMensagem("categoria/create", "error", "Erro ao criar categoria. Tente novamente.");
        }
    }
    public function atualizarCategoria($id){
        if($this->categoria->atualizarCategoria(
            $id,
            $_POST["nome_categorias"],
            $_POST["descricao_categorias"]
        )){
            Redirect::redirecionarComMensagem("categoria/listar", "success", "Categoria atualizada com sucesso!");
        }else{
            Redirect::redirecionarComMensagem("categoria/editar/$id", "error", "Erro ao atualizar categoria.");
        }
    }
    public function deletarCategoria($id){
        if($this->categoria->deletarCategoria($id)){
            Redirect::redirecionarComMensagem("categoria/listar", "success", "Categoria deletada com sucesso!");
        }else{
            Redirect::redirecionarComMensagem("categoria/listar", "error", "Erro ao deletar categoria.");
        }
    }   

}