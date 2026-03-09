<?php
/**
 * Script de Simulação End-to-End (Cliente Novo -> Pedido)
 */

require_once __DIR__ . '/vendor/autoload.php';
$baseUrl = 'http://localhost:8000';
$cookieFile = __DIR__ . '/cookie_teste.txt';
if (file_exists($cookieFile))
    unlink($cookieFile);

function makeRequest($url, $method = 'GET', $data = null, $isJson = false)
{
    global $cookieFile;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($isJson) {
            $jsonPayload = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonPayload)
            ]);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
    }

    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    // curl_close($ch); Está depreciado no PHP mais que 8.0

    return ['res' => $response, 'code' => $info['http_code'], 'url' => $info['url']];
}

$email = "cliente.teste" . time() . "@koketsu.com";
$senha = "senha123";

echo "1. Iniciando Sessao...\n";
makeRequest("$baseUrl/");

echo "2. Cadastrando o cliente ($email)...\n";
$reg = makeRequest("$baseUrl/register", 'POST', [
    'nome_usuarios' => 'Cliente Simulado',
    'email_usuarios' => $email,
    'senha_usuarios' => $senha,
    'senha_confirm' => $senha
]);
echo "Status Cadastro: " . $reg['code'] . "\n";

echo "3. Fazendo Login...\n";
$login = makeRequest("$baseUrl/login", 'POST', [
    'email_usuarios' => $email,
    'senha_usuarios' => $senha
]);
echo "Código HTTP Login: " . $login['code'] . "\n";
echo "URL Final Login: " . $login['url'] . "\n";
file_put_contents('debug_login.html', $login['res']);
$auth = makeRequest("$baseUrl/api/check_auth.php");
$authData = json_decode($auth['res'], true);
if (empty($authData['authenticated'])) {
    die("Falha na autenticação da API.\n");
}
$userId = $authData['user']['id'];
echo "Usuario ID: $userId autenticado com sucesso.\n";

echo "5. Verificando se exige Perfil (API)...\n";
$perf = makeRequest("$baseUrl/api/perfil_api.php");
$perfData = json_decode($perf['res'], true);

// Se não tiver perfil preenchido, o site exige preencher (Dashboard -> Meu Perfil)
// Vamos preencher usando o endpoint correto ou o banco diretamente como bypass de UI
echo "6. Criando/Atualizando Perfil para finalizar a compra...\n";
// No backend, como o perfil já é exigido, a rota de update de perfil é /backend/cliente/perfil/salvar
// O ID do perfil talvez já tenha sido criado em branco ou precisamos inserir no banco.
// Vamos checar diretamente pelo DB se o perfil existe
require_once __DIR__ . '/backend/Database/Database.php';
$db = \App\Koketsu\Database\Database::getInstance();
$stmtP = $db->prepare('SELECT id_perfil FROM tbl_perfil WHERE id_usuarios = ?');
$stmtP->execute([$userId]);
$perfilRow = $stmtP->fetch();

if (!$perfilRow) {
    // Insere Pefil
    $stmtIns = $db->prepare('INSERT INTO tbl_perfil (id_usuarios, endereco_perfil, telefone_perfil) VALUES (?, ?, ?)');
    $stmtIns->execute([$userId, 'Rua Ficticia, 123', '11999999999']);
    $idPerfil = $db->lastInsertId();
    echo "Perfil criado manualmente! ID: $idPerfil\n";
} else {
    $idPerfil = $perfilRow['id_perfil'];
    $stmtUp = $db->prepare('UPDATE tbl_perfil SET endereco_perfil=?, telefone_perfil=? WHERE id_perfil=?');
    $stmtUp->execute(['Rua Atualizada, 456', '11999999999', $idPerfil]);
    echo "Perfil preenchido manualmente! ID: $idPerfil\n";
}

echo "7. Buscando um Produto para comprar...\n";
$prodStmt = $db->query('SELECT id_produto, preco_produtos FROM tbl_produtos LIMIT 1');
$produto = $prodStmt->fetch();
if (!$produto)
    die("Nenhum produto cadastrado para simular compra!\n");

$payloadPedido = [
    'id_perfil' => $idPerfil,
    'data_pedido' => date('Y-m-d H:i:s'),
    'total_pedido' => $produto['preco_produtos'],
    'status_pedido' => 'pendente',
    'itens' => [
        [
            'id_produto' => $produto['id_produto'],
            'quantidade' => 1,
            'preco_unitario' => $produto['preco_produtos'],
            'tamanho' => 'M',
            'cor' => 'Preta'
        ]
    ]
];

echo "8. Tentando Finalizar Pedido na API...\n";
$pedido = makeRequest("$baseUrl/api/pedidos.php", 'POST', $payloadPedido, true);
echo "Resposta Pedido: " . $pedido['res'] . "\n";
$reqData = json_decode($pedido['res'], true);
if (isset($reqData['id_pedido'])) {
    echo "========= SUCESSO! =========\n";
    echo "Pedido gerado com o ID: " . $reqData['id_pedido'] . "\n";

    echo "9. Verificando histórico de pedidos do Cliente...\n";
    $histStmt = $db->prepare('SELECT * FROM tbl_pedidos WHERE id_perfil = ?');
    $histStmt->execute([$idPerfil]);
    $historico = $histStmt->fetchAll(\PDO::FETCH_ASSOC);
    if (count($historico) > 0) {
        echo "Validado! Encontramos " . count($historico) . " pedido(s) associado(s) ao novo cliente.\n";
    } else {
        echo "Erro: O pedido não foi encontrado no histórico do usuário!\n";
    }
} else {
    echo "Falha na criação do pedido.\n";
}

echo "\nFIM DA SIMULAÇÃO.\n";
?>