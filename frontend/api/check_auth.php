<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Koketsu\Core\Session;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

try {
    $session = new Session();

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
