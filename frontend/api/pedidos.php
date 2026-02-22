<?php
// Wrapper para atender rota /api/pedidos.php no servidor built-in PHP
// Desativar output de erros HTML
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Koketsu\Database\Database;
use App\Koketsu\Controles\PublicApiController;

// Configurar Headers CORS e JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Instanciar DB e Controller
try {
    $db = Database::getInstance();
    $controller = new PublicApiController($db);

    // Roteamento Simples
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->salvarPedido();
    } else {
        http_response_code(405); // Method Not Allowed
        echo json_encode(['status' => 'error', 'message' => 'Método não permitido']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erro interno wrapper: ' . $e->getMessage()]);
}
