<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Models\Avaliacao;
use App\Koketsu\Database\Database;
use App\Koketsu\Core\View;
use App\Koketsu\Core\Redirect;

use App\Koketsu\Controles\Admin\AdminController;

class AvaliacaoController extends AdminController {
    public $avaliacao;
    public $db;
    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance();
        $this->avaliacao  = new Avaliacao($this->db);
    }
    // index
    public function index() {
        Redirect::redirecionarPara("/avaliacao/listar");
    }

    public function viewCriarAvaliacoes() {
        View::render("avaliacao/create");
    }

    public function viewListarAvaliacoes() {
        $avaliacoes = $this->avaliacao->buscarAvaliacoes();
        View::render("avaliacao/index", ["avaliacoes" => $avaliacoes]);
    }

    public function viewEditarAvaliacoes($id) {
        $avaliacao = $this->avaliacao->buscarPorId($id);
        if (!$avaliacao) {
            Redirect::redirecionarComMensagem("/backend/avaliacao/listar", "error", "Avaliação não encontrada.");
        }
        View::render("avaliacao/edit", ["avaliacao" => $avaliacao]);
    }

    public function viewExcluirAvaliacoes($id) {
        $avaliacao = $this->avaliacao->buscarPorId($id);
        if (!$avaliacao) {
            Redirect::redirecionarComMensagem("/backend/avaliacao/listar", "error", "Avaliação não encontrada.");
        }
        View::render("avaliacao/delete", ["avaliacao" => $avaliacao]);
    }

    public function salvarAvaliacao() {
        if ($this->avaliacao->inserirAvaliacao(
            $_POST['id_produto'],
            $_POST['id_cliente'],
            $_POST['nota'],
            $_POST['comentario']
        )) {
            Redirect::redirecionarComMensagem("/avaliacao/listar", "success", "Avaliação cadastrada com sucesso!");
        } else {
            Redirect::redirecionarComMensagem("/avaliacao/criar", "error", "Erro ao cadastrar avaliação.");
        }
    }

    public function atualizarAvaliacao($id) {
        if ($this->avaliacao->atualizarAvaliacao(
            $id,
            $_POST['nota'],
            $_POST['comentario']
        )) {
             Redirect::redirecionarComMensagem("/avaliacao/listar", "success", "Avaliação atualizada com sucesso!");
        } else {
             Redirect::redirecionarComMensagem("/avaliacao/editar/$id", "error", "Erro ao atualizar avaliação.");
        }
    }

    public function deletarAvaliacao($id) {
        if ($this->avaliacao->excluirAvaliacao($id)) {
            Redirect::redirecionarComMensagem("/avaliacao/listar", "success", "Avaliação excluída com sucesso!");
        } else {
            Redirect::redirecionarComMensagem("/avaliacao/listar", "error", "Erro ao excluir avaliação.");
        }
    }
}