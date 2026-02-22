<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Models\EstoqueMovimentacao; // Fixed class name
use App\Koketsu\Database\Database;
use App\Koketsu\Core\View;
use App\Koketsu\Core\Redirect;

class EstoqueController {
    public $Estoque_Movimentacao;
    public $db;
    public function __construct() {
        $this->db = Database::getInstance();
        $this->Estoque_Movimentacao  = new EstoqueMovimentacao($this->db);
    }
    // index
    public function index() {
        $resultado = $this->Estoque_Movimentacao->buscarMovimentacoes();
        return $resultado;
    }

    public function viewCriarEstoque_Movimentacao() {
        View::render("estoque/create");
    }

    public function viewListarEstoque_Movimentacao() {
        $movimentacoes = $this->Estoque_Movimentacao->buscarMovimentacoes();
        View::render("estoque/index", ["movimentacoes" => $movimentacoes]);
    }

    public function viewEditarEstoque_Movimentacao($id) {
        $movimentacao = $this->Estoque_Movimentacao->buscarPorId($id);
        View::render("estoque/edit", ["movimentacao" => $movimentacao]);
    }

    public function viewExcluirEstoque_Movimentacao($id) {
        View::render("estoque/delete", ["id" => $id]);
    }

    public function salvarEstoque_Movimentacao() {
        if ($this->Estoque_Movimentacao->inserirMovimentacao(
            $_POST['id_produto'],
            $_POST['tipo'],
            $_POST['quantidade'],
            $_POST['descricao'] ?? null
        )) {
            Redirect::redirecionarComMensagem("/backend/EstoqueMovimentacao/listar", "success", "Movimentação registrada com sucesso!");
        } else {
             Redirect::redirecionarComMensagem("/backend/EstoqueMovimentacao/criar", "error", "Erro ao registrar movimentação.");
        }
    }

    public function atualizarEstoque_Movimentacao($id) {
        if ($this->Estoque_Movimentacao->atualizarMovimentacao(
            $id,
            $_POST['quantidade'] ?? null,
            $_POST['descricao'] ?? null
        )) {
            Redirect::redirecionarComMensagem("/backend/EstoqueMovimentacao/listar", "success", "Movimentação atualizada com sucesso!");
        } else {
            Redirect::redirecionarComMensagem("/backend/EstoqueMovimentacao/editar/$id", "error", "Erro ao atualizar movimentação.");
        }
    }

    public function deletarEstoque_Movimentacao($id) {
        if ($this->Estoque_Movimentacao->excluirMovimentacao($id)) {
            Redirect::redirecionarComMensagem("/backend/EstoqueMovimentacao/listar", "success", "Movimentação excluída com sucesso!");
        } else {
            Redirect::redirecionarComMensagem("/backend/EstoqueMovimentacao/listar", "error", "Erro ao excluir movimentação.");
        }
    }
}