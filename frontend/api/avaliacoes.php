<?php
/**
 * Proxy de Avaliações — Frontend → Backend
 * Roteia requisições GET/POST para o PublicApiController.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../vendor/autoload.php';
use App\Koketsu\Controles\PublicApiController;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

try {
    $controller = new PublicApiController();
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method === 'GET') {
        $path = $_SERVER['REQUEST_URI'];
        if (strpos($path, 'stats/produto/') !== false) {
            $parts = explode('stats/produto/', $path);
            $id = intval(end($parts));
            $controller->getRatingStatsByProduto($id);
        } elseif (strpos($path, 'produto/') !== false) {
            $parts = explode('produto/', $path);
            $id = intval(end($parts));
            $controller->getAvaliacoesByProduto($id);
        } elseif (strpos($path, 'ultimas') !== false) {
            $controller->getLatestAvaliacoes();
        } else {
            $controller->getAvaliacoes();
        }
    } elseif ($method === 'POST') {
        $controller->createPublicAvaliacao();
    } else {
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Método não permitido']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
