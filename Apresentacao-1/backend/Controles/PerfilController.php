<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Models\Perfil;
use App\Koketsu\Database\Database;
use App\Koketsu\Core\View;
use App\Koketsu\Core\Redirect;

class PerfilController {
    public $perfil;
    public $db;
    public function __construct() {
        $this->db = Database::getInstance();
        $this->perfil = new Perfil($this->db);
    }
    // index
    public function index(){
        $resultado = $this->perfil->buscarPerfis(2);
        return $resultado;
    }
     public function viewListarPerfis($pagina){
        $dados = $this->perfil->paginacao($pagina);
    $total = $this->perfil->totalDePerfis($pagina);
    $total_inativos = $this->perfil->buscarPerfisInativos($pagina);
    $total_ativos = $this->perfil->buscarPerfisAtivos($pagina);
    view::render('perfil/index', 
    [
        "perfil" => $dados['data'],
        "total_perfil" => $total,
        "total_inativos" => $total_inativos,
        "total_ativos" => $total_ativos,
        'paginacao' => $dados
    ] 
  );
}

    public function viewCriarPerfil(){
        view::render("perfil/create");
    }

    public function viewEditarPerfil(int $id){
         $dados = $this->perfil->buscarPerfisPorId($id);
       
    //    foreach($dados as $perfil){
    //     $dados = $perfil;
    //    }
       var_dump($dados);
       view::render("perfil/edit", ["perfil" => $dados]);
    }

    public function viewExcluirPerfil(){
         view::render("perfil/delete");
    }
    public function salvarPerfil(){
       if($this->perfil->inserirPerfil(
            $_POST["telefone_perfil"],
            $_POST["endereco_perfil"],
            $_POST["data_cadastro"],
            "Ativo"
        )){
            Redirect::redirecionarComMensagem("/perfil/listar", "success", "perfil criado com sucesso!");
        }else{
            Redirect::redirecionarComMensagem("perfil/create", "error", "Erro ao criar perfil. Tente novamente.");
        }
    }
    public function atualizarPerfil($id) {
        if($this->perfil->atualizarPerfil(
            $id,
            $_POST["telefone_perfil"],
            $_POST["endereco_perfil"],
            $_POST["data_cadastro"],
            $_POST["id_usuarios"]
        )){
            Redirect::redirecionarComMensagem("/perfil/listar", "success", "Perfil atualizado com sucesso!");
        }else{
            Redirect::redirecionarComMensagem("/perfil/editar/$id", "error", "Erro ao atualizar perfil.");
        }
    }

    public function deletarPerfil($id) {
        if($this->perfil->deletarPerfil($id)){
            Redirect::redirecionarComMensagem("/perfil/listar", "success", "Perfil deletado com sucesso!");
        }else{
            Redirect::redirecionarComMensagem("/perfil/listar", "error", "Erro ao deletar perfil.");
        }
    }
}