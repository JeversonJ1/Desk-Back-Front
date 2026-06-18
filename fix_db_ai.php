<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/backend/Database/Config.php';
require_once __DIR__ . '/backend/Database/database.php';
try {
    $db = \App\Koketsu\Database\Database::getInstance();
    $stmt = $db->query("
        SELECT c.id_categorias, c.nome_categorias, COUNT(p.id_produto) as num_products
        FROM tbl_categorias c
        LEFT JOIN tbl_produtos p ON c.id_categorias = p.id_categoria AND p.excluido_em IS NULL
        WHERE c.excluido_em IS NULL
        GROUP BY c.id_categorias, c.nome_categorias
        ORDER BY c.nome_categorias ASC
    ");
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "CATEGORIAS E PRODUTOS:\n";
    foreach ($res as $row) {
        echo "- ID: {$row['id_categorias']}, Nome: '{$row['nome_categorias']}', Ativos: {$row['num_products']}\n";
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
