<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Koketsu\Controles\PublicApiController;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

try {
    $controller = new PublicApiController();
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method === 'GET') {
        // Se houver ID na URL (ex: avaliacoes.php?id=1 ou avaliacoes.php/produto/1)
        // O Bramus router não está agindo aqui, então vamos simplificar.
        $path = $_SERVER['REQUEST_URI'];
        if (strpos($path, 'stats/produto/') !== false) {
            $parts = explode('stats/produto/', $path);
            $id = end($parts);
            $controller->getRatingStatsByProduto($id);
        } elseif (strpos($path, 'produto/') !== false) {
            $parts = explode('produto/', $path);
            $id = end($parts);
            $controller->getAvaliacoesByProduto($id);
        } elseif (strpos($path, 'ultimas') !== false) {
            $controller->getLatestAvaliacoes();
        } else {
            $controller->getAvaliacoes();
        }
    } elseif ($method === 'POST') {
        $controller->createPublicAvaliacao();
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
