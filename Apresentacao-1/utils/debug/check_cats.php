<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Koketsu\Database\Database;

$db = Database::getInstance();
$stmt = $db->query("SELECT * FROM tbl_categorias");
$cats = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "ID | NOME\n";
foreach ($cats as $c) {
    echo "{$c['id_categorias']} | {$c['nome_categorias']}\n";
}
