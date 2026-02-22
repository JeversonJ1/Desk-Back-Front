<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Koketsu\Database\Database;

try {
    $db = Database::getInstance();
    $stmt = $db->prepare("DELETE FROM tbl_produtos WHERE DATE(criado_em) = CURDATE()");
    $stmt->execute();
    echo "Produtos de hoje removidos. Total deletado: " . $stmt->rowCount();
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
