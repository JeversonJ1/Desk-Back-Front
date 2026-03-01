<?php
/**
 * Servidor de Desenvolvimento Local
 * Este arquivo roteia as requisições entre o Frontend e o Backend
 * rodando ambos na mesma porta (8000).
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// 1. Se a URL começar com /api, /painel, /auth, etc (rotas do backend)
if (preg_match('/^\/(api|auth|painel|configuracoes|cliente\/perfil|tamanho|usuario|newsletter)/', $uri) || $uri === '/' . basename(__FILE__)) {
    // Redireciona para o index.php do backend
    require_once __DIR__ . '/backend/index.php';
    return true;
}

// 2. Se for uma requisição para a raiz do site, mostra o index.html do frontend
if ($uri === '/' || $uri === '/index.html') {
    require_once __DIR__ . '/frontend/index.html';
    return true;
}

// 3. Se for um arquivo físico dentro da pasta frontend (CSS, JS, Imagens, etc)
$frontendPath = __DIR__ . '/frontend' . $uri;
if (file_exists($frontendPath) && is_file($frontendPath)) {
    // O servidor embutido do PHP sabe servir os arquivos corretamente se retornarmos false
    return false;
}

// 4. Se for um arquivo físico solto na raiz (menos provável, mas garantimos)
$rootPath = __DIR__ . $uri;
if (file_exists($rootPath) && is_file($rootPath)) {
    return false;
}

// 5. Se for qualquer outra rota de página do frontend (ex: /shop.html), tenta carregar
if (strpos($uri, '.html') !== false) {
    if (file_exists($frontendPath)) {
        require_once $frontendPath;
        return true;
    }
}

// Se não achou nada, cai no index do frontend por padrão (comportamento de SPA/Site)
require_once __DIR__ . '/frontend/index.html';
return true;
