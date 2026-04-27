<?php
/**
 * API pública para retornar configurações do site (número de WhatsApp, etc.)
 * Acesso: GET /api/config
 */

$configFile = __DIR__ . '/../../backend/Config/settings.json';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!file_exists($configFile)) {
    echo json_encode([
        'success' => true,
        'whatsapp_numero' => '5511999999999',
        'whatsapp_ativo' => true
    ]);
    exit;
}

$config = json_decode(file_get_contents($configFile), true);

echo json_encode([
    'success'          => true,
    'whatsapp_numero'  => $config['whatsapp_numero'] ?? '5511999999999',
    'whatsapp_ativo'   => $config['whatsapp_ativo'] ?? true,
]);
