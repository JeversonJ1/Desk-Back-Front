<?php

require_once __DIR__ . '/backend/Rotas/rotas.php';

use App\Koketsu\Rotas\Rotas;

$routes = Rotas::get();
$baseUrl = 'http://localhost:8000';

echo "Iniciando testes de rotas (GET)...\n";
echo str_repeat("-", 50) . "\n";

$getRoutes = $routes['GET'] ?? [];
$results = [];

foreach ($getRoutes as $route => $handler) {
    // Replace parameters with mock values
    $testRoute = str_replace(
        ['{id}', '{pagina}', '{data1}', '{data2}'],
        ['1', '1', '2024-01-01', '2024-01-31'],
        $route
    );

    $url = $baseUrl . $testRoute;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true); // just HEAD request to get status, or maybe GET is better
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $statusStr = $httpCode >= 200 && $httpCode < 300 ? "\033[32m$httpCode OK\033[0m" : ($httpCode == 404 ? "\033[33m$httpCode Not Found\033[0m" : "\033[31m$httpCode Error\033[0m");

    echo str_pad($testRoute, 60) . " => " . $statusStr . "\n";

    $results[] = [
        'route' => $testRoute,
        'code' => $httpCode,
        'handler' => $handler
    ];
}

echo str_repeat("-", 50) . "\n";
echo "Testes concluídos!\n";
