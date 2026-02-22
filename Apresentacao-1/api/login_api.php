<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Koketsu\Database\Database;
use App\Koketsu\Models\Usuario;
use App\Koketsu\Core\Session;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
    exit;
}

try {
    // Lê dados do body (JSON ou form-data)
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'] ?? ($_POST['email'] ?? null);
    $senha = $input['senha'] ?? ($_POST['senha'] ?? null);

    if (empty($email) || empty($senha)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'E-mail e senha são obrigatórios.']);
        exit;
    }

    $db = Database::getInstance();
    $usuarioModel = new Usuario($db);
    $usuario = $usuarioModel->checarCredenciais($email, $senha);

    if ($usuario) {
        $session = new Session();
        session_regenerate_id(true);
        $session->set('usuario_id', $usuario['id_usuarios']);
        $session->set('usuario_nome', $usuario['nome_usuarios']);
        $session->set('usuario_tipo', $usuario['nivel_acesso']);
        $session->set('foto_usuarios', $usuario['foto_usuarios'] ?? '/img/logoperf.jpg');

        $foto = $usuario['foto_usuarios'] ?? '/img/logoperf.jpg';
        if ($foto && !str_starts_with($foto, 'http') && !str_starts_with($foto, '/')) {
            $foto = '/backend/upload/' . $foto;
        }

        // Admin vai para o painel, cliente fica no site
        $redirectUrl = null;
        if ($usuario['nivel_acesso'] === 'admin') {
            $redirectUrl = '/backend/admin/dashboard';
        }

        echo json_encode([
            'success' => true,
            'message' => 'Login realizado com sucesso!',
            'redirect_url' => $redirectUrl,
            'user' => [
                'id'   => $usuario['id_usuarios'],
                'nome' => $usuario['nome_usuarios'],
                'tipo' => $usuario['nivel_acesso'],
                'foto' => $foto
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'E-mail ou senha incorretos.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor.']);
}
