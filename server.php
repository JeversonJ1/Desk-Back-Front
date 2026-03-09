<?php
/**
 * Servidor de Desenvolvimento Local — Koketsu
 * Roteia requisições entre Frontend e Backend na mesma porta (8000).
 *
 * Iniciar: php -S localhost:8000 server.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// ── 1. PRIORIDADE MÁXIMA: Proxy PHP do frontend (/api/*.php) ─────────────────
//       ex: /api/vitrine.php → frontend/api/vitrine.php
if (preg_match('/^\/api\/(.+\.php)$/', $uri, $m)) {
    $proxyFile = __DIR__ . '/frontend/api/' . $m[1];
    if (file_exists($proxyFile)) {
        require_once $proxyFile;
        return true;
    }
    // Se não existe o proxy, cai no backend REST
}

// ── 2. Arquivos estáticos do frontend (CSS, JS, imagens, HTML) ───────────────
//       Verificado ANTES do backend. Como o frontend/ não é o docroot,
//       precisamos servir com readfile() + Content-Type correto.
$frontendFile = __DIR__ . '/frontend' . $uri;
if (file_exists($frontendFile) && is_file($frontendFile)) {
    $ext = strtolower(pathinfo($frontendFile, PATHINFO_EXTENSION));
    $mime = match ($ext) {
        'css' => 'text/css',
        'js' => 'application/javascript',
        'html' => 'text/html; charset=utf-8',
        'json' => 'application/json',
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        default => 'application/octet-stream',
    };
    header('Content-Type: ' . $mime);
    readfile($frontendFile);
    return true;
}

// ── 4. Arquivos estáticos do backend (uploads, assets do painel) ─────────────
// Corrigir erro onde o desktop tenta carregar `/backend/upload/...` e duplica o backend.
$backendFile = str_starts_with($uri, '/backend') ? __DIR__ . $uri : __DIR__ . '/backend' . $uri;
if (file_exists($backendFile) && is_file($backendFile)) {
    return false;
}

// ── 4.5. Imagens ausentes no upload retornam 404 padrão ──────────────────────
// (fallback de logo removido — o frontend trata a ausência de imagem adequadamente)

// ── 5. Todas as rotas do backend (admin, api, auth, painel, cliente) ──────────
$backendPrefixes = '/^\/(
    api|
    admin|
    login|
    logout|
    register|
    adminlogin|
    backend|
    usuarios?|
    categorias?|
    categoria|
    cores?|
    cor|
    clientes?|
    cliente|
    perfis?|
    perfil|
    tamanhos?|
    tamanho|
    produtos?|
    produto|
    pedidos?|
    pedido|
    avaliacao|
    avaliacoes?|
    relatorios?|
    configuracoes|
    itenspedidos|
    manutencao|
    newsletter
)(\/|$)/xi';

// ── 5a. Rota raiz: serve o frontend ──────────────────────────────────────────
if ($uri === '/') {
    require_once __DIR__ . '/frontend/index.html';
    return true;
}

if (preg_match($backendPrefixes, $uri)) {
    require_once __DIR__ . '/backend/index.php';
    return true;
}

// ── 6. Páginas HTML do frontend (/pages/*.html, etc.) ────────────────────────
if (file_exists($frontendFile)) {
    require_once $frontendFile;
    return true;
}

// ── 7. Fallback: index do frontend (comportamento SPA) ───────────────────────
require_once __DIR__ . '/frontend/index.html';
return true;
