<?php
namespace App\Koketsu;
require_once __DIR__ . '/../vendor/autoload.php';
use App\Koketsu\Rotas\Rotas;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if (!isset($_SESSION)) {
    session_start();
}

use Bramus\Router\Router;     
$router = new Router();

// --- INÍCIO MODO MANUTENÇÃO GLOBAL ---
$configFile = __DIR__ . '/Config/settings.json';
if (file_exists($configFile)) {
    $config = json_decode(file_get_contents($configFile), true);
    if (!empty($config['manutencao'])) {
        $uri = $_SERVER['REQUEST_URI'];
        $isLoggedIn = isset($_SESSION['usuario_id']);
        $isAdmin = ($isLoggedIn && ($_SESSION['usuario_tipo'] ?? '') === 'admin');
        
        // Rotas permitidas mesmo em manutenção:
        // 1. Qualquer rota que contenha 'login' ou 'auth'
        // 2. A rota de toggle de manutenção
        // 3. Qualquer rota se o usuário for ADMIN
        $isSafeRoute = (
            strpos($uri, 'login') !== false || 
            strpos($uri, 'auth') !== false || 
            strpos($uri, 'manutencao') !== false ||
            strpos($uri, 'admin') !== false || /* Permite acesso a qualquer rota que contenha 'admin' */
            $isAdmin
        );

        if (!$isSafeRoute) {
            include __DIR__ . '/Views/Templates/manutencao.php';
            exit;
        }
    }
}
// --- FIM MODO MANUTENÇÃO GLOBAL ---

// Sanitização Global de $_POST e $_GET
array_walk_recursive($_POST, function(&$item) {
    if (is_string($item)) {
        $item = htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
    }
});
array_walk_recursive($_GET, function(&$item) {
    if (is_string($item)) {
        $item = htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
    }
});

use App\Koketsu\Core\Csrf;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação CSRF básica para todos os POSTs (opcional, pode ser feito por controller)
    // Para evitar quebrar rotas de API pública, validar apenas se houver token ou criar exceção
    // Por enquanto, apenas gera o token. A validação precisa ser injetada nos formulários antes.
}

$rotas = Rotas::get();
foreach ($rotas as $metodohttp => $rota) {
    foreach ($rota as $uri => $acao) {
        $metodoBramus = strtolower($metodohttp);
        
        $router->{$metodoBramus}($uri, function(...$params) use ($acao) {
            try {
                // Separa Controller e Método
                $partes = explode('@', $acao);
                if (count($partes) !== 2) {
                    throw new \Exception("Formato de rota inválido: $acao");
                }
                
                $controllerName = $partes[0];
                $methodName = $partes[1];
                
                // Define o namespace base
                $namespaceBase = 'App\\Koketsu\\Controles\\';
                $fullControllerName = $namespaceBase . $controllerName;
                
                // Verifica se a classe existe
                if (!class_exists($fullControllerName)) {
                    // Tenta sem o namespace base caso já venha completo (embora Rotas use relativo)
                    if (class_exists($controllerName)) {
                        $fullControllerName = $controllerName;
                    } else {
                         throw new \Exception("Controlador não encontrado: $fullControllerName");
                    }
                }
                
                // Instancia o Controller
                $controller = new $fullControllerName();
                
                // Verifica se o método existe
                if (!method_exists($controller, $methodName)) {
                    throw new \Exception("Método $methodName não encontrado em $fullControllerName");
                }
                
                // Chama o método passando os parâmetros da URL
                call_user_func_array([$controller, $methodName], $params);
                
            } catch (\Throwable $e) {
                // Tratamento de erro amigável
                http_response_code(500);
                // Você pode carregar uma view de erro aqui se preferir
                echo "<div style='font-family: sans-serif; padding: 20px; border: 1px solid #f44336; background: #ffebee; color: #b71c1c; border-radius: 5px;'>";
                echo "<h3>Ops! Ocorreu um erro ao processar sua requisição.</h3>";
                echo "<p><strong>Detalhes técnicos:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
                echo "</div>";
            }
        });
    }
}
$router->set404(function() {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo '404, rota não encontrada';
});

$router->run();