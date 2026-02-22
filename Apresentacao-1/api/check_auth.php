<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Koketsu\Core\Session;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

try {
    $session = new Session();
    // DEBUG AUTH
    file_put_contents(__DIR__ . '/../debug_auth.log', date('H:i:s') . " - Sessão: " . session_id() . " - User: " . ($session->get('usuario_id') ?? 'NULO') . "\n", FILE_APPEND);

    if ($session->has('usuario_id')) {
        $foto = $session->get('foto_usuarios');
        // Normalizar caminho da foto
        if ($foto && !str_starts_with($foto, 'http') && !str_starts_with($foto, '/')) {
            $foto = '/backend/upload/' . $foto;
        }
        
        echo json_encode([
            'authenticated' => true,
            'user' => [
                'id' => $session->get('usuario_id'),
                'nome' => $session->get('usuario_nome'),
                'tipo' => $session->get('usuario_tipo'),
                'foto' => $foto
            ]
        ]);
    } else {
        echo json_encode(['authenticated' => false]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
