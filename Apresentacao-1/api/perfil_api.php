<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Koketsu\Database\Database;
use App\Koketsu\Models\Perfil;
use App\Koketsu\Core\Session;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$session = new Session();
if (!$session->get('usuario_id')) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$usuario_id = $session->get('usuario_id');

// DEBUG TEMPORÁRIO
file_put_contents(__DIR__ . '/../debug_session.log', date('Y-m-d H:i:s') . " - Sessão ID: " . session_id() . " - Usuario ID: " . ($usuario_id ?? 'NULO') . "\n", FILE_APPEND);

$db = Database::getInstance();
$perfilModel = new Perfil($db);

// GET: Buscar perfil atual
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $perfil = $perfilModel->buscarPerfilPorUsuario($usuario_id);
        
        if ($perfil) {
            echo json_encode([
                'success' => true,
                'data' => [
                    'id_perfil' => $perfil['id_perfil'],
                    'telefone' => $perfil['telefone_perfil'],
                    'endereco' => $perfil['endereco_perfil']
                ]
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'data' => null // Perfil ainda não existe
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erro ao buscar perfil.']);
    }
    exit;
}

// POST: Salvar/Atualizar perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $telefone = $input['telefone'] ?? null;
        $endereco = $input['endereco'] ?? null;

        if (empty($telefone) || empty($endereco)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Telefone e endereço são obrigatórios.']);
            exit;
        }

        $perfil = $perfilModel->buscarPerfilPorUsuario($usuario_id);
        $data_cadastro = date('Y-m-d H:i:s');

        if ($perfil) {
            // Atualizar
            $resultado = $perfilModel->atualizarPerfil(
                $perfil['id_perfil'],
                $telefone,
                $endereco,
                $perfil['data_cadastro'], // Mantém data original
                $usuario_id
            );
        } else {
            // Criar novo
            $resultado = $perfilModel->inserirPerfil(
                $telefone,
                $endereco,
                $data_cadastro,
                $usuario_id
            );
        }

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Dados atualizados com sucesso!']);
        } else {
            throw new Exception("Falha ao salvar no banco.");
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar dados: ' . $e->getMessage()]);
    }
    exit;
}
