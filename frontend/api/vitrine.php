<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Koketsu\Controles\PublicApiController;

// Configurar cabeçalhos para JSON e CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Instanciar o controlador e chamar o método da vitrine
try {
    $controller = new PublicApiController();
    $controller->getProdutosVitrine();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
