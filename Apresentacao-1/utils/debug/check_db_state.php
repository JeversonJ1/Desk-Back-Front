<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Koketsu\Database\Database;

$db = Database::getInstance();
$stmt = $db->query("SELECT COUNT(*) as total FROM tbl_produtos");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Total Produtos: " . $row['total'] . "\n";

// Check for recently added
$stmt2 = $db->query("SELECT * FROM tbl_produtos ORDER BY id_produto DESC LIMIT 5");
$recent = $stmt2->fetchAll(PDO::FETCH_ASSOC);
echo "Ultimos 5 produtos:\n";
foreach($recent as $r) {
    echo $r['id_produto'] . ": " . $r['nome_produtos'] . " (" . $r['criado_em'] . ")\n";
}
