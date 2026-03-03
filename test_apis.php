<?php
/**
 * Script de teste de todas as APIs do Koketsu
 * Executa GET/POST requests e reporta status
 */

$baseUrl = 'http://localhost:8000';

// Cores para terminal
function green($text)
{
    return "\033[32m$text\033[0m";
}
function red($text)
{
    return "\033[31m$text\033[0m";
}
function yellow($text)
{
    return "\033[33m$text\033[0m";
}
function cyan($text)
{
    return "\033[36m$text\033[0m";
}
function bold($text)
{
    return "\033[1m$text\033[0m";
}

function testEndpoint($method, $url, $data = null, $description = '')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    $totalTime = round(curl_getinfo($ch, CURLINFO_TOTAL_TIME) * 1000);

    return [
        'method' => $method,
        'url' => $url,
        'description' => $description,
        'httpCode' => $httpCode,
        'response' => $response,
        'error' => $error,
        'time' => $totalTime
    ];
}

function printResult($result)
{
    $code = $result['httpCode'];
    $method = $result['method'];
    $url = str_replace('http://localhost:8000', '', $result['url']);
    $time = $result['time'] . 'ms';
    $desc = $result['description'];

    // Determina status
    if ($result['error']) {
        $status = red("ERRO");
        $detail = red($result['error']);
    } elseif ($code >= 200 && $code < 300) {
        $status = green("OK $code");
        // Tenta decodificar JSON
        $json = json_decode($result['response'], true);
        if ($json !== null) {
            if (isset($json['data']) && is_array($json['data'])) {
                $count = count($json['data']);
                $detail = green("$count registros retornados");
            } elseif (isset($json['status'])) {
                $detail = green($json['status'] . (isset($json['message']) ? ' - ' . $json['message'] : ''));
            } else {
                $detail = green("JSON válido retornado");
            }
        } else {
            $bodyLen = strlen($result['response']);
            if ($bodyLen > 200) {
                $detail = yellow("HTML/Texto ($bodyLen bytes)");
            } else {
                $detail = green(substr($result['response'], 0, 100));
            }
        }
    } elseif ($code >= 300 && $code < 400) {
        $status = yellow("REDIRECT $code");
        $detail = yellow("Redirecionamento");
    } elseif ($code == 401 || $code == 403) {
        $status = yellow("AUTH $code");
        $detail = yellow("Autenticação necessária (esperado)");
    } elseif ($code == 404) {
        $status = red("404 NOT FOUND");
        $detail = red("Rota não encontrada");
    } elseif ($code == 500) {
        $status = red("500 SERVER ERROR");
        $json = json_decode($result['response'], true);
        if ($json && isset($json['error'])) {
            $detail = red($json['error']);
        } else {
            $detail = red(substr($result['response'], 0, 150));
        }
    } else {
        $status = red("HTTP $code");
        $detail = red(substr($result['response'], 0, 150));
    }

    echo sprintf("  %-7s %-40s %s  %s\n", $method, $url, $status, "($time)");
    if ($desc) {
        echo "          " . cyan($desc) . "\n";
    }
    echo "          $detail\n\n";
}

// =====================================================
// INÍCIO DOS TESTES
// =====================================================
echo bold("\n" . str_repeat("=", 70)) . "\n";
echo bold("   TESTE DE TODAS AS APIs - KOKETSU") . "\n";
echo bold(str_repeat("=", 70)) . "\n";
echo "   Base URL: $baseUrl\n";
echo "   Data: " . date('Y-m-d H:i:s') . "\n";
echo str_repeat("-", 70) . "\n\n";

$results = [];
$passed = 0;
$failed = 0;
$auth_required = 0;

// =====================================================
// 1. HEALTH CHECK
// =====================================================
echo bold(cyan("\n📡 HEALTH CHECK\n"));
echo str_repeat("-", 40) . "\n";

$r = testEndpoint('GET', "$baseUrl/api/health", null, 'Verifica se o servidor está online');
printResult($r);
$results[] = $r;

// =====================================================
// 2. API GET - LISTAGENS
// =====================================================
echo bold(cyan("\n📋 API GET - LISTAGENS\n"));
echo str_repeat("-", 40) . "\n";

$getEndpoints = [
    ['/api/produtos', 'Lista todos os produtos'],
    ['/api/clientes', 'Lista todos os clientes'],
    ['/api/pedidos', 'Lista todos os pedidos'],
    ['/api/usuarios', 'Lista todos os usuários'],
    ['/api/categorias', 'Lista todas as categorias'],
    ['/api/cores', 'Lista todas as cores'],
    ['/api/perfis', 'Lista todos os perfis'],
    ['/api/tamanhos', 'Lista todos os tamanhos'],
    ['/api/itenspedidos', 'Lista todos os itens de pedidos'],
    ['/api/avaliacoes', 'Lista todas as avaliações'],
    ['/api/imagens', 'Lista todas as imagens'],
    ['/api/carrinho', 'Lista itens do carrinho'],
    ['/api/estoque', 'Lista movimentações de estoque'],
    ['/api/banners', 'Lista todos os banners'],
];

foreach ($getEndpoints as [$endpoint, $desc]) {
    $r = testEndpoint('GET', "$baseUrl$endpoint", null, $desc);
    printResult($r);
    $results[] = $r;
}

// =====================================================
// 3. API GET - POR ID (usando id=1 como teste)
// =====================================================
echo bold(cyan("\n🔍 API GET - BY ID (id=1)\n"));
echo str_repeat("-", 40) . "\n";

$getByIdEndpoints = [
    ['/api/produtos/1', 'Produto por ID'],
    ['/api/clientes/1', 'Cliente por ID'],
    ['/api/pedidos/1', 'Pedido por ID'],
    ['/api/usuarios/1', 'Usuário por ID'],
    ['/api/categorias/1', 'Categoria por ID'],
    ['/api/cores/1', 'Cor por ID'],
    ['/api/perfis/1', 'Perfil por ID'],
    ['/api/tamanhos/1', 'Tamanho por ID'],
    ['/api/itenspedidos/1', 'Item de pedido por ID'],
    ['/api/avaliacoes/1', 'Avaliação por ID'],
    ['/api/imagens/1', 'Imagem por ID'],
    ['/api/carrinho/1', 'Carrinho por ID'],
    ['/api/estoque/1', 'Estoque por ID'],
    ['/api/banners/1', 'Banner por ID'],
];

foreach ($getByIdEndpoints as [$endpoint, $desc]) {
    $r = testEndpoint('GET', "$baseUrl$endpoint", null, $desc);
    printResult($r);
    $results[] = $r;
}

// =====================================================
// 4. API POST - AUTH DESKTOP
// =====================================================
echo bold(cyan("\n🔐 API POST - AUTENTICAÇÃO\n"));
echo str_repeat("-", 40) . "\n";

$r = testEndpoint('POST', "$baseUrl/api/auth/desktop", [
    'email' => 'test@test.com',
    'senha' => 'test123'
], 'Auth desktop com credenciais de teste');
printResult($r);
$results[] = $r;

// =====================================================
// 5. NEWSLETTER
// =====================================================
echo bold(cyan("\n📧 NEWSLETTER\n"));
echo str_repeat("-", 40) . "\n";

$r = testEndpoint('POST', "$baseUrl/api/newsletter/inscrever", [
    'email_newsletter' => 'teste_api_' . time() . '@teste.com'
], 'Inscrever email na newsletter');
printResult($r);
$results[] = $r;

// =====================================================
// 6. API POST - CRIAÇÃO VIA API
// =====================================================
echo bold(cyan("\n📝 API POST - CRIAÇÃO DE REGISTROS\n"));
echo str_repeat("-", 40) . "\n";

$postEndpoints = [
    [
        '/api/produtos',
        [
            'nome_produtos' => 'Produto Teste API',
            'descricao_produtos' => 'Teste automatizado',
            'preco_produtos' => 99.90,
            'estoque_produtos' => 10,
            'id_categoria' => 1,
            'imagem_produtos' => 'default.jpg'
        ],
        'Criar produto via API'
    ],
    [
        '/api/clientes',
        [
            'nome_usuarios' => 'Cliente Teste API',
            'email_usuarios' => 'teste_api_' . uniqid() . '@teste.com',
            'senha_usuarios' => 'teste123',
            'nivel_acesso' => 'cliente'
        ],
        'Criar cliente via API'
    ],
    [
        '/api/estoque',
        [
            'id_produto' => 1,
            'tipo_estoque_movimentacao' => 'disponivel',
            'quantidade_estoque_movimentacao' => 5,
            'descricao_estoque_movimentacao' => 'Teste API'
        ],
        'Criar movimentação de estoque via API'
    ],
    [
        '/api/banners',
        [
            'imagem' => 'https://example.com/banner-test.jpg',
            'link' => 'https://example.com',
            'ordem' => 99,
            'ativo' => true
        ],
        'Criar banner via API'
    ],
];

foreach ($postEndpoints as [$endpoint, $data, $desc]) {
    $r = testEndpoint('POST', "$baseUrl$endpoint", $data, $desc);
    printResult($r);
    $results[] = $r;
}

// =====================================================
// RESUMO FINAL
// =====================================================
foreach ($results as $r) {
    $code = $r['httpCode'];
    if ($r['error']) {
        $failed++;
    } elseif ($code >= 200 && $code < 300) {
        $passed++;
    } elseif ($code == 401 || $code == 403) {
        $auth_required++;
    } else {
        $failed++;
    }
}

$total = count($results);

echo bold("\n" . str_repeat("=", 70)) . "\n";
echo bold("   RESUMO DOS TESTES") . "\n";
echo str_repeat("=", 70) . "\n";
echo "   Total de endpoints testados:  $total\n";
echo "   " . green("✅ Sucesso (2xx):             $passed") . "\n";
echo "   " . yellow("🔒 Auth necessária (401/403): $auth_required") . "\n";
echo "   " . red("❌ Falha (4xx/5xx/erro):       $failed") . "\n";
echo str_repeat("=", 70) . "\n\n";

// Lista falhas para fácil debugging
if ($failed > 0) {
    echo bold(red("\n⚠️  ENDPOINTS COM FALHA:\n"));
    echo str_repeat("-", 40) . "\n";
    foreach ($results as $r) {
        $code = $r['httpCode'];
        if ($r['error'] || ($code >= 400 && $code != 401 && $code != 403)) {
            $url = str_replace('http://localhost:8000', '', $r['url']);
            $reason = $r['error'] ?: "HTTP $code";
            echo red("  ❌ {$r['method']} $url → $reason") . "\n";
        }
    }
    echo "\n";
}
