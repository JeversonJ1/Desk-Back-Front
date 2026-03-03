<?php
/**
 * Teste de todas as rotas do sistema Koketsu
 * Executa: php test_rotas.php
 */

$base = 'http://localhost:8000';
$ok = 0;
$redirect = 0;
$fail = 0;
$erros = [];

// Credenciais para obter cookie de sessão admin
$loginEmail = 'admin@koketsu.com.br';
$loginSenha = 'admin123';

// ─── obtém cookie de sessão via login ───────────────────────────────────────
$cookieFile = tempnam(sys_get_temp_dir(), 'kok_cookie_');

function req(string $url, string $method = 'GET', array $post = [], string $cookieFile = ''): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,   // não seguir redirect — quero ver o 302
        CURLOPT_TIMEOUT => 8,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HEADER => true,
        CURLOPT_NOBODY => false,
    ]);
    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    }
    if ($method === 'POST' && $post) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $body = substr($response, $headerSize);
    $err = curl_error($ch);
    return ['code' => $code, 'body' => substr(trim($body), 0, 120), 'err' => $err];
}

// Faz login para obter sessão
req($base . '/login', 'POST', [
    'email' => $loginEmail,
    'senha' => $loginSenha,
    'tipo' => 'admin',
], $cookieFile);

// ─── definição de todas as rotas ────────────────────────────────────────────
$rotas = [
    // --- Auth / Público ---
    'Auth' => [
        ['GET', '/login'],
        ['GET', '/register'],
        ['GET', '/admin'],
        ['GET', '/manutencao'],
    ],

    // --- API REST (JSON) ---
    'API GET' => [
        ['GET', '/api/health'],
        ['GET', '/api/produtos'],
        ['GET', '/api/produtos/1'],
        ['GET', '/api/clientes'],
        ['GET', '/api/clientes/1'],
        ['GET', '/api/pedidos'],
        ['GET', '/api/pedidos/1'],
        ['GET', '/api/usuarios'],
        ['GET', '/api/usuarios/1'],
        ['GET', '/api/categorias'],
        ['GET', '/api/categorias/1'],
        ['GET', '/api/cores'],
        ['GET', '/api/cores/1'],
        ['GET', '/api/perfis'],
        ['GET', '/api/perfis/1'],
        ['GET', '/api/tamanhos'],
        ['GET', '/api/tamanhos/1'],
        ['GET', '/api/itenspedidos'],
        ['GET', '/api/itenspedidos/1'],
        ['GET', '/api/avaliacoes'],
        ['GET', '/api/avaliacoes/1'],
        ['GET', '/api/imagens'],
        ['GET', '/api/imagens/1'],
        ['GET', '/api/carrinho'],
        ['GET', '/api/carrinho/1'],
        ['GET', '/api/estoque'],
        ['GET', '/api/estoque/1'],
        ['GET', '/api/banners'],
        ['GET', '/api/banners/1'],
    ],

    // --- Admin (protegidas por sessão) ---
    'Admin' => [
        ['GET', '/admin/dashboard'],
        ['GET', '/usuarios'],
        ['GET', '/usuario/listar'],
        ['GET', '/usuario/criar'],
        ['GET', '/categorias'],
        ['GET', '/categoria/criar'],
        ['GET', '/categoria/listar/1'],
        ['GET', '/cores'],
        ['GET', '/cor/criar'],
        ['GET', '/cor/listar/1'],
        ['GET', '/clientes'],
        ['GET', '/perfis'],
        ['GET', '/perfil/criar'],
        ['GET', '/tamanhos'],
        ['GET', '/tamanho/criar'],
        ['GET', '/pedido'],
        ['GET', '/pedido/listar'],
        ['GET', '/pedido/criar'],
        ['GET', '/avaliacao'],
        ['GET', '/avaliacao/listar'],
        ['GET', '/relatorios'],
        ['GET', '/relatorios/detalhado'],
        ['GET', '/relatorios/financeiro'],
        ['GET', '/relatorios/produtos'],
        ['GET', '/configuracoes'],
        ['GET', '/admin/newsletter'],
        ['GET', '/itenspedidos'],
        ['GET', '/itenspedidos/listar'],
        ['GET', '/backend/carrinho'],
        ['GET', '/backend/EstoqueMovimentacao'],
        ['GET', '/backend/Imagens'],
    ],

    // --- Cliente ---
    'Cliente' => [
        ['GET', '/cliente/dashboard'],
        ['GET', '/cliente/pedidos'],
        ['GET', '/cliente/avaliacoes'],
        ['GET', '/cliente/listar'],
    ],
];

// ─── executa e imprime ───────────────────────────────────────────────────────
$cor = [
    'green' => "\033[32m",
    'yellow' => "\033[33m",
    'red' => "\033[31m",
    'cyan' => "\033[36m",
    'bold' => "\033[1m",
    'reset' => "\033[0m",
];

echo $cor['bold'] . str_repeat('=', 72) . "\n";
echo "  TESTE DE ROTAS — KOKETSU  (base: $base)\n";
echo str_repeat('=', 72) . $cor['reset'] . "\n\n";

foreach ($rotas as $grupo => $lista) {
    echo $cor['bold'] . $cor['cyan'] . "\n▶ $grupo\n" . $cor['reset'];
    echo str_repeat('-', 72) . "\n";

    foreach ($lista as [$method, $uri]) {
        $r = req($base . $uri, $method, [], $cookieFile);
        $code = $r['code'];
        $body = $r['body'];

        if ($code >= 200 && $code < 300) {
            $status = $cor['green'] . "✔ $code OK     " . $cor['reset'];
            $ok++;
        } elseif ($code >= 300 && $code < 400) {
            $status = $cor['yellow'] . "→ $code REDIRECT" . $cor['reset'];
            $redirect++;
        } elseif ($code === 0) {
            $status = $cor['red'] . "✘ TIMEOUT/ERR  " . $cor['reset'];
            $fail++;
            $erros[] = "$method $uri — {$r['err']}";
        } else {
            $status = $cor['red'] . "✘ $code FALHA   " . $cor['reset'];
            $fail++;
            $erros[] = "$method $uri — HTTP $code — $body";
        }

        $line = sprintf("  %-6s %-42s %s", $method, $uri, $status);
        // Mostra trecho do body para APIs JSON
        if (str_starts_with($uri, '/api/') && $code === 200) {
            $preview = strlen($body) > 60 ? substr($body, 0, 60) . '…' : $body;
            $line .= "  " . $cor['cyan'] . $preview . $cor['reset'];
        }
        echo $line . "\n";
    }
}

// ─── resumo ─────────────────────────────────────────────────────────────────
$total = $ok + $redirect + $fail;
echo "\n" . $cor['bold'] . str_repeat('=', 72) . "\n";
echo "  RESUMO: $total rotas testadas\n";
echo str_repeat('=', 72) . $cor['reset'] . "\n";
echo $cor['green'] . "  ✔ OK (2xx):        $ok\n" . $cor['reset'];
echo $cor['yellow'] . "  → Redirect (3xx):  $redirect\n" . $cor['reset'];
echo $cor['red'] . "  ✘ Falha (4xx/5xx): $fail\n" . $cor['reset'];

if ($erros) {
    echo "\n" . $cor['bold'] . $cor['red'] . "⚠  ROTAS COM FALHA:\n" . $cor['reset'];
    foreach ($erros as $e)
        echo "  ✘ $e\n";
}

echo "\n";
@unlink($cookieFile);
