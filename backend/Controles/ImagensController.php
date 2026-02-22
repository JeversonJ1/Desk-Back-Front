<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Models\Imagem;
use App\Koketsu\Database\Database;
use App\Koketsu\Core\View;
use App\Koketsu\Core\Redirect;

class ImagensController {
    public $imagem;
    public $db;
    public function __construct() {
        $this->db = Database::getInstance();
        $this->imagem  = new Imagem($this->db);
    }
    // index
    public function index() {
        $resultado = $this->imagem->buscarImagens();
        return $resultado;
    }

    public function viewCriarImagem() {
        View::render("imagem/create");
    }

    public function viewListarImagem() {
        $imagens = $this->imagem->buscarImagens();
        View::render("imagem/index", ["imagens" => $imagens]);
    }

    public function viewEditarImagem($id) {
         $img = $this->imagem->buscarPorId($id);
         View::render("imagem/edit", ["imagem" => $img]);
    }

    public function viewExcluirImagem($id) {
        View::render("imagem/delete", ["id" => $id]);
    }

    public function salvarImagens() {
        // Upload logic should be here, using generic path for now
        $caminho = $_POST['caminho_imagem'] ?? ''; 
        
        if ($this->imagem->inserirImagem(
            $_POST['id_produto'],
            $_POST['id_cor'],
            $_POST['id_tamanho'],
            $caminho,
            $_POST['descricao_imagem']
        )) {
             Redirect::redirecionarComMensagem("/backend/Imagens/listar", "success", "Imagem cadastrada com sucesso!");
        } else {
             Redirect::redirecionarComMensagem("/backend/Imagens/criar", "error", "Erro ao cadastrar imagem.");
        }
    }

    public function atualizarImagens($id) {
        $caminho = $_POST['caminho_imagem'] ?? '';
        
        if ($this->imagem->atualizarImagem(
            $id,
            $_POST['id_produto'],
            $_POST['id_cor'],
            $_POST['id_tamanho'],
            $caminho,
            $_POST['descricao_imagem']
        )) {
            Redirect::redirecionarComMensagem("/backend/Imagens/listar", "success", "Imagem atualizada com sucesso!");
        } else {
            Redirect::redirecionarComMensagem("/backend/Imagens/editar/$id", "error", "Erro ao atualizar imagem.");
        }
    }

    public function deletarImagens($id) {
        if ($this->imagem->excluirImagem($id)) {
             Redirect::redirecionarComMensagem("/backend/Imagens/listar", "success", "Imagem excluída com sucesso!");
        } else {
             Redirect::redirecionarComMensagem("/backend/Imagens/listar", "error", "Erro ao excluir imagem.");
        }
    }
}