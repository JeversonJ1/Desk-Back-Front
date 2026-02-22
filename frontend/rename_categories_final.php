<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Koketsu\Database\Database;

try {
    $db = Database::getInstance();
    
    $stmt = $db->prepare("UPDATE tbl_categorias SET nome_categorias = 'Chapéus e Bonés' WHERE nome_categorias = 'Bonés'");
    $stmt->execute();
    echo "Rows affected (Bonés): " . $stmt->rowCount() . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
