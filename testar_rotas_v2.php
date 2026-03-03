<?php
require_once __DIR__ . '/backend/Rotas/rotas.php';
use App\Koketsu\Rotas\Rotas;

$routes = Rotas::get();
$baseUrl = 'http://localhost:8000';
$getRoutes = $routes['GET'] ?? [];
$results = [];

foreach ($getRoutes as $route => $handler) {
    if (strpos($route, '{id}') !== false) {
        $route = str_replace('{id}', '1', $route);
    }
    if (strpos($route, '{pagina}') !== false) {
        $route = str_replace('{pagina}', '1', $route);
    }
    if (strpos($route, '{data1}') !== false) {
        $route = str_replace('{data1}', '2024-01-01', $route);
    }
    if (strpos($route, '{data2}') !== false) {
        $route = str_replace('{data2}', '2024-01-31', $route);
    }

    $url = $baseUrl . $route;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);

    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    $results[] = [
        'route' => $route,
        'handler' => $handler,
        'http_code' => $httpCode,
        'curl_error' => $error
    ];
}

file_put_contents(__DIR__ . '/route_test_results.json', json_encode($results, JSON_PRETTY_PRINT));
echo "Tests completed.\n";
