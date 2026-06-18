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
        'success'          => true,
        'whatsapp_numero'  => '5511999999999',
        'whatsapp_ativo'   => true,
        'manutencao'       => false,
        'banner_ativo'     => false,
        'banner_texto'     => '',
        'banner_cor'       => 'dourado',
        'seo_titulo'       => '',
        'seo_descricao'    => '',
        'cnpj'             => '',
        'email_contato'    => '',
        'endereco'         => '',
        'social_instagram' => '',
        'social_tiktok'    => '',
        'social_youtube'   => '',
        'social_facebook'  => '',
    ]);
    exit;
}

$config = json_decode(file_get_contents($configFile), true);

echo json_encode([
    'success'          => true,
    'whatsapp_numero'  => $config['whatsapp_numero'] ?? '5511999999999',
    'whatsapp_ativo'   => (bool)($config['whatsapp_ativo'] ?? true),
    'manutencao'       => (bool)($config['manutencao'] ?? false),
    'banner_ativo'     => (bool)($config['banner_ativo'] ?? false),
    'banner_texto'     => $config['banner_texto'] ?? '',
    'banner_cor'       => $config['banner_cor'] ?? 'dourado',
    
    // SEO
    'seo_titulo'       => $config['seo_titulo'] ?? '',
    'seo_descricao'    => $config['seo_descricao'] ?? '',
    'cnpj'             => $config['cnpj'] ?? '',
    'email_contato'    => $config['email_contato'] ?? '',
    'endereco'         => $config['endereco'] ?? '',
    
    // Social
    'social_instagram' => $config['social_instagram'] ?? '',
    'social_tiktok'    => $config['social_tiktok'] ?? '',
    'social_youtube'   => $config['social_youtube'] ?? '',
    'social_facebook'  => $config['social_facebook'] ?? '',
]);
