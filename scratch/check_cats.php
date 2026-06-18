<?php
require_once __DIR__ . '/../backend/Database/Database.php';
try {
    $db = \App\Koketsu\Database\Database::getInstance();
    $stmt = $db->query("SELECT id_categorias, nome_categorias, excluido_em FROM tbl_categorias");
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "CATEGORIAS:\n";
    foreach ($res as $row) {
        echo "- ID: {$row['id_categorias']}, Nome: '{$row['nome_categorias']}', Excluido: " . ($row['excluido_em'] ?? 'null') . "\n";
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
