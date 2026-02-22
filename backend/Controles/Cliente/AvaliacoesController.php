<?php
namespace App\Koketsu\Controles\Cliente;

use App\Koketsu\Core\View;
use App\Koketsu\Core\Redirect;
use App\Koketsu\Database\Database;
use App\Koketsu\Models\Avaliacao;
use App\Koketsu\Models\Produtos;
use App\Koketsu\Models\Perfil;
use App\Koketsu\Controles\Admin\AuthenticatedController;

class AvaliacoesController extends AuthenticatedController
{
    private $db;
    private $avaliacaoModel;
    private $produtosModel;
    private $perfilModel;
    private $pedidosModel;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::getInstance();
        $this->avaliacaoModel = new Avaliacao($this->db);
        $this->produtosModel = new Produtos($this->db);
        $this->perfilModel = new Perfil($this->db);
        $this->pedidosModel = new \App\Koketsu\Models\Pedidos($this->db);
    }

    // Buscar o id_perfil do usuário logado (FK de tbl_avaliacoes)
    private function getPerfilId(): ?int
    {
        $usuarioId = $this->session->get('usuario_id');
        $perfil = $this->perfilModel->buscarPerfilPorUsuario($usuarioId);
        return $perfil ? (int) $perfil['id_perfil'] : null;
    }

    // Listar avaliações do cliente logado
    public function index(): void
    {
        $usuarioId = $this->session->get('usuario_id');

        if (empty($usuarioId)) {
            Redirect::redirecionarComMensagem('/login', 'warning', 'Por favor faça login para ver suas avaliações.');
            return;
        }

        
        // Buscar todas as avaliações feitas por este usuário (independente do perfil)
        $avaliacoes = $this->avaliacaoModel->buscarPorUsuario($usuarioId);
        
        // Buscar apenas produtos comprados (de qualquer perfil do usuário) e não avaliados
        $produtos = $this->pedidosModel->buscarProdutosCompradosPorUsuario($usuarioId);

        View::render('cliente/avaliacoes/index', [
            'avaliacoes' => $avaliacoes,
            'produtos' => $produtos,
            'totalAvaliacoes' => count($avaliacoes)
        ]);
    }

    // Salvar nova avaliação
    public function salvar(): void
    {
        $usuarioId = $this->session->get('usuario_id');

        if (empty($usuarioId)) {
            Redirect::redirecionarComMensagem('/login', 'warning', 'Por favor faça login.');
            return;
        }

        $id_produto = $_POST['id_produto'] ?? null;
        $nota = $_POST['nota'] ?? null;
        $comentario = $_POST['comentario'] ?? '';

        if (empty($id_produto) || empty($nota)) {
            Redirect::redirecionarComMensagem('/cliente/avaliacoes', 'error', 'Produto e nota são obrigatórios.');
            return;
        }

        if ($nota < 1 || $nota > 5) {
            Redirect::redirecionarComMensagem('/cliente/avaliacoes', 'error', 'A nota deve ser entre 1 e 5.');
            return;
        }

        $perfilId = $this->getPerfilId();
        if (!$perfilId) {
            Redirect::redirecionarComMensagem('/cliente/avaliacoes', 'error', 'Perfil não encontrado. Complete seu perfil primeiro.');
            return;
        }

        // VERIFICAÇÃO DE SEGURANÇA EXTRA:
        // O produto realmente foi comprado por este usuário (qualquer perfil)?
        $produtosPermitidos = $this->pedidosModel->buscarProdutosCompradosPorUsuario($usuarioId);
        $produtoEhValido = false;
        foreach ($produtosPermitidos as $prod) {
            if ($prod['id_produto'] == $id_produto) {
                $produtoEhValido = true;
                break;
            }
        }

        if (!$produtoEhValido) {
            Redirect::redirecionarComMensagem('/cliente/avaliacoes', 'error', 'Você só pode avaliar produtos que comprou e recebeu.');
            return;
        }

        $resultado = $this->avaliacaoModel->inserirAvaliacao($id_produto, $perfilId, $nota, $comentario);

        if ($resultado) {
            Redirect::redirecionarComMensagem('/cliente/avaliacoes', 'success', 'Avaliação enviada com sucesso!');
        } else {
            Redirect::redirecionarComMensagem('/cliente/avaliacoes', 'error', 'Erro ao enviar avaliação. Tente novamente.');
        }
    }

    // Atualizar avaliação existente
    public function atualizar(int $id): void
    {
        $usuarioId = $this->session->get('usuario_id');

        if (empty($usuarioId)) {
            Redirect::redirecionarComMensagem('/login', 'warning', 'Por favor faça login.');
            return;
        }

        // Verificar se a avaliação pertence ao usuario
        $avaliacao = $this->avaliacaoModel->buscarPorId($id);
        if (!$avaliacao) {
            Redirect::redirecionarComMensagem('/cliente/avaliacoes', 'error', 'Avaliação não encontrada.');
            return;
        }

        // Buscar o perfil da avaliação para ver a quem pertence
        $perfilAvaliacao = $this->perfilModel->buscarPerfisPorId($avaliacao['id_cliente']);
        if (!$perfilAvaliacao || $perfilAvaliacao['id_usuarios'] != $usuarioId) {
            Redirect::redirecionarComMensagem('/cliente/avaliacoes', 'error', 'Acesso negado.');
            return;
        }

        $nota = $_POST['nota'] ?? null;
        $comentario = $_POST['comentario'] ?? '';

        if (empty($nota) || $nota < 1 || $nota > 5) {
            Redirect::redirecionarComMensagem('/cliente/avaliacoes', 'error', 'A nota deve ser entre 1 e 5.');
            return;
        }

        $resultado = $this->avaliacaoModel->atualizarAvaliacao($id, $nota, $comentario);

        if ($resultado) {
            Redirect::redirecionarComMensagem('/cliente/avaliacoes', 'success', 'Avaliação atualizada com sucesso!');
        } else {
            Redirect::redirecionarComMensagem('/cliente/avaliacoes', 'error', 'Erro ao atualizar avaliação.');
        }
    }

    // Excluir avaliação
    public function excluir(int $id): void
    {
        $usuarioId = $this->session->get('usuario_id');

        if (empty($usuarioId)) {
            Redirect::redirecionarComMensagem('/login', 'warning', 'Por favor faça login.');
            return;
        }

        // Verificar se a avaliação pertence ao usuario
        $avaliacao = $this->avaliacaoModel->buscarPorId($id);
        if (!$avaliacao) {
            Redirect::redirecionarComMensagem('/cliente/avaliacoes', 'error', 'Avaliação não encontrada.');
            return;
        }

        // Buscar o perfil da avaliação para ver a quem pertence
        $perfilAvaliacao = $this->perfilModel->buscarPerfisPorId($avaliacao['id_cliente']);
        if (!$perfilAvaliacao || $perfilAvaliacao['id_usuarios'] != $usuarioId) {
            Redirect::redirecionarComMensagem('/cliente/avaliacoes', 'error', 'Acesso negado.');
            return;
        }

        $resultado = $this->avaliacaoModel->excluirAvaliacao($id);

        if ($resultado) {
            Redirect::redirecionarComMensagem('/cliente/avaliacoes', 'success', 'Avaliação excluída com sucesso!');
        } else {
            Redirect::redirecionarComMensagem('/cliente/avaliacoes', 'error', 'Erro ao excluir avaliação.');
        }
    }
}
