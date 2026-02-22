<?php
// Permitir acesso de qualquer origem (CORS) para debug
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

// Iniciar sessão (com configurações seguras se necessário, mas padrão por enquanto)
session_start();

$response = [
    'timestamp' => date('Y-m-d H:i:s'),
    'session_id' => session_id(),
    'session_status' => session_status(),
    'session_data' => $_SESSION,
    'cookies_received' => $_COOKIE,
    'auth_check_simulated' => isset($_SESSION['usuario_id']) ? 'LOGGED_IN' : 'NOT_LOGGED_IN'
];

echo json_encode($response, JSON_PRETTY_PRINT);
