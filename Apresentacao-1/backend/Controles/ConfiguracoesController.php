<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Core\View;
use App\Koketsu\Controles\Admin\AuthenticatedController;
use App\Koketsu\Models\Preferencias;
use App\Koketsu\Models\Usuario;
use App\Koketsu\Models\Perfil;
use App\Koketsu\Models\Pedidos;
use App\Koketsu\Database\Database;

class ConfiguracoesController extends AuthenticatedController {
    private $configFile;
    private $preferenciasModel;
    private $usuarioModel;
    private $perfilModel;
    private $pedidosModel;

    public function __construct() {
        parent::__construct();
        $this->configFile = __DIR__ . '/../Config/settings.json';
        $db = Database::getInstance();
        $this->preferenciasModel = new Preferencias($db);
        $this->usuarioModel = new Usuario($db);
        $this->perfilModel = new Perfil($db);
        $this->pedidosModel = new Pedidos($db);
    }

    public function index(): void {
        $config = json_decode(file_get_contents($this->configFile), true);
        $usuarioId = $this->session->get('usuario_id');

        // Buscar preferências do usuário
        $preferencias = $this->preferenciasModel->buscarPorUsuario($usuarioId);

        View::render('configuracoes/index', [
            'nomeUsuario' => $this->session->get('usuario_nome'),
            'usuarioTipo' => $this->session->get('usuario_tipo'),
            'usuarioId' => $usuarioId,
            'manutencaoAtiva' => $config['manutencao'] ?? false,
            'preferencias' => $preferencias ?? []
        ]);
    }

    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $usuarioId = $this->session->get('usuario_id');

        if (!$usuarioId) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
            return;
        }

        if ($this->preferenciasModel->salvarOuAtualizar($usuarioId, $input)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar preferências']);
        }
    }

    public function salvarManutencao() {
        if ($this->session->get('usuario_tipo') !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Não autorizado']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $status = $input['status'] ?? false;

        $config = ['manutencao' => (bool)$status];
        
        if (file_put_contents($this->configFile, json_encode($config, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar arquivo']);
        }
    }

    public function exportarDados() {
        $usuarioId = $this->session->get('usuario_id');
        if (!$usuarioId) return;

        $dados = [
            'usuario' => $this->usuarioModel->buscarPorID($usuarioId),
            'perfil' => $this->perfilModel->buscarPerfilPorUsuario($usuarioId),
            'preferencias' => $this->preferenciasModel->buscarPorUsuario($usuarioId),
            'pedidos' => $this->pedidosModel->buscarPedidosPorCliente($usuarioId)
        ];

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="meus_dados_koketsu.json"');
        echo json_encode($dados, JSON_PRETTY_PRINT);
        exit;
    }

    public function excluirConta() {
        $usuarioId = $this->session->get('usuario_id');
        if (!$usuarioId) return;

        if ($this->usuarioModel->deletarUsuario($usuarioId)) {
            $this->session->destroy();
            header('Location: /login?msg=conta_excluida');
            exit;
        } else {
            header('Location: /backend/configuracoes?msg=erro_exclusao');
            exit;
        }
    }
}
