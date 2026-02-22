<?php
// router.php

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
error_log("Router.php Path: " . $path);

// Se a rota for a raiz, serve o index.html
if ($path === '/' || $path === '') {
    if (file_exists(__DIR__ . '/index.html')) {
        include __DIR__ . '/index.html';
        return true;
    }
}

// Se o arquivo existir fisicamente, sirva-o (imagens, css, js, html)
if (file_exists(__DIR__ . $path) && !is_dir(__DIR__ . $path)) {
    return false; // Retorna false para o servidor embutido servir o arquivo
}

// Se a rota for para a API ou Backend, redireciona para o index do backend
if (strpos($path, '/api/') === 0 || strpos($path, '/backend/') === 0 || strpos($path, '/admin/') === 0 || strpos($path, '/cliente/') === 0 || strpos($path, '/login') === 0 || strpos($path, '/register') === 0) {
    // Corrige o path para o router Bramus funcionar corretamente
    // O Bramus espera que a execução venha do index.php, então o base folder pode confundir
    // Mas vamos apenas incluir o backend/index.php
    require_once __DIR__ . '/backend/index.php';
    exit;
}

// Para qualquer outra coisa não encontrada, 404
http_response_code(404);
echo "404 Not Found";
