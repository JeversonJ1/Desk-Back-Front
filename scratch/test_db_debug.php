<?php
require_once __DIR__ . '/../backend/Database/Database.php';

try {
    $db = \App\Koketsu\Database\Database::getInstance();
    echo "DB Connection Success!\n";
    
    // Check tables
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . implode(', ', $tables) . "\n";
    
    // Inspect tbl_imagem_carrossel
    if (in_array('tbl_imagem_carrossel', $tables)) {
        echo "tbl_imagem_carrossel exists!\n";
        $stmt = $db->query("DESCRIBE tbl_imagem_carrossel");
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    } else {
        echo "tbl_imagem_carrossel DOES NOT EXIST!\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
