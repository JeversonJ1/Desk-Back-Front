<?php
require_once __DIR__ . '/backend/Database/Database.php';
try {
    $db = \App\Koketsu\Database\Database::getInstance();
    $stmt = $db->query("SELECT imagem_produtos FROM tbl_produtos WHERE id_produto=44");
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "DB IMAGEM: " . print_r($res, true) . "\n";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
