<?php
// test_debug.php
require_once __DIR__ . '/vendor/autoload.php';

use App\Koketsu\Database\Database;
use App\Koketsu\Models\Produtos;

echo "Testing Database Connection...\n";

try {
    $db = Database::getInstance();
    echo "Connection successful!\n";
} catch (Exception $e) {
    echo "Connection FAILED: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Testing Produtos Model...\n";
try {
    $model = new Produtos($db);
    $produtos = $model->buscarProdutosAtivosComCategoria();
    echo "Found " . count($produtos) . " products.\n";
    if (count($produtos) > 0) {
        echo "First product: " . $produtos[0]['nome_produtos'] . "\n";
    }
} catch (Exception $e) {
    echo "Query FAILED: " . $e->getMessage() . "\n";
    exit(1);
}
