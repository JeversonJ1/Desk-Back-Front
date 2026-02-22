<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Koketsu\Database\Database;
use App\Koketsu\Models\Produtos;

// Replicate the logic from PublicApiController for testing
function formatPath($imagem_bd) {
    if (empty($imagem_bd) || $imagem_bd === 'NULL') {
        return "img/default.png"; // Default fallback
    }
    
    $caminho_limpo = str_replace('\\', '/', $imagem_bd);
    // Remove 'img/' or 'produtos/' from the START
    $caminho_limpo = preg_replace('/^(img\/|produtos\/)/i', '', $caminho_limpo);
    
    if (!empty($caminho_limpo)) {
        return "img/" . $caminho_limpo;
    }
    return "img/default.png";
}

try {
    $db = Database::getInstance();
    $produtosModel = new Produtos($db);
    $produtos = $produtosModel->buscarProdutosAtivosComCategoria();
    
    $output = "ID | Nome | Imagem RAW | Caminho Gerado | Arquivo Existe?\n";
    $output .= str_repeat("-", 80) . "\n";
    
    foreach ($produtos as $p) {
        $raw = $p['imagem_produtos'];
        $generated = formatPath($raw);
        $fileExists = file_exists(__DIR__ . '/' . $generated) ? "SIM" : "NAO";
        
        $output .= sprintf(
            "%d | %s | %s | %s | %s\n",
            $p['id_produto'],
            substr($p['nome_produtos'], 0, 20),
            $raw,
            $generated,
            $fileExists
        );
    }
    
    file_put_contents('debug_result.txt', $output);
    echo "Debug completed. Results saved to debug_result.txt\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
