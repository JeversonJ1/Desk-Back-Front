<?php
namespace App\Koketsu\Controles\Cliente;

use App\Koketsu\Core\View;
use App\Koketsu\Core\Redirect;
use App\Koketsu\Database\Database;
use App\Koketsu\Models\Pedidos;
use App\Koketsu\Models\Perfil;
use App\Koketsu\Models\ItensPedidos;
use App\Koketsu\Controles\Admin\AuthenticatedController;

class PedidosController extends AuthenticatedController
{
    private $db;
    private $pedidosModel;
    private $perfilModel;
    private $itensModel;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::getInstance();
        $this->pedidosModel = new Pedidos($this->db);
        $this->perfilModel = new Perfil($this->db);
        $this->itensModel = new ItensPedidos($this->db);
    }

    // Lista os pedidos do cliente logado
    public function index(): void
    {
        $usuarioId = $this->session->get('usuario_id');

        if (empty($usuarioId)) {
            Redirect::redirecionarComMensagem('/login', 'warning', 'Por favor faça login para ver seus pedidos.');
            return;
        }

        $perfil = $this->perfilModel->buscarPerfilPorUsuario($usuarioId);

        if (!$perfil) {
            View::render('cliente/pedidos/index', [
                'pedidos' => [],
                'mensagem' => 'Nenhum perfil associado a sua conta. Preencha seu perfil para começar a fazer pedidos.'
            ]);
            return;
        }

        $pedidos = $this->pedidosModel->buscarPedidosPorUsuario($usuarioId);

        View::render('cliente/pedidos/index', [
            'pedidos' => $pedidos,
            'perfil' => $perfil
        ]);
    }

    // Detalhes de um pedido do cliente
    public function detalhes(int $id): void
    {
        $usuarioId = $this->session->get('usuario_id');
        if (empty($usuarioId)) {
            Redirect::redirecionarComMensagem('/login', 'warning', 'Por favor faça login para ver seus pedidos.');
            return;
        }

        $pedido = $this->pedidosModel->buscarPedidoPorId($id);
        if (!$pedido) {
            Redirect::redirecionarComMensagem('/backend/cliente/pedidos', 'error', 'Pedido não encontrado.');
            return;
        }

        // Verifica se o pedido pertence ao usuário logado
        if ($pedido['id_usuarios'] != $usuarioId) {
            Redirect::redirecionarComMensagem('/cliente/pedidos', 'error', 'Acesso negado ao pedido solicitado.');
            return;
        }

        $itens = $this->itensModel->buscarItensPorPedido($id);

        View::render('cliente/pedidos/detalhes', [
            'pedido' => $pedido,
            'itens' => $itens
        ]);
    }
}
