<?php
require_once __DIR__ . '/../backend/Database/Database.php';

try {
    $db = \App\Koketsu\Database\Database::getInstance();
    
    // Gerar próximo ID
    $stmtMax = $db->query("SELECT COALESCE(MAX(id_carrossel), 0) + 1 AS next_id FROM tbl_imagem_carrossel");
    $nextId = (int)$stmtMax->fetch(\PDO::FETCH_ASSOC)['next_id'];
    
    echo "Next ID: $nextId\n";

    $sql = "INSERT INTO tbl_imagem_carrossel (id_carrossel, url_imagem_imagem_carrossel, link_destino_imagem_carrossel, ordem_imagem_carrossel, ativo_imagem_carrossel) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $res = $stmt->execute([
        $nextId,
        'banners/test.png',
        'https://test.com',
        1,
        1
    ]);
    
    echo "Insert result: " . ($res ? "SUCCESS" : "FAIL") . "\n";
    
    // Clean up
    $db->exec("DELETE FROM tbl_imagem_carrossel WHERE id_carrossel = $nextId");
    echo "Clean up success!\n";
    
} catch (Exception $e) {
    echo "DATABASE INSERT ERROR: " . $e->getMessage() . "\n";
}
