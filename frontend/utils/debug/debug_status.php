<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Koketsu\Database\Database;

try {
    $db = Database::getInstance();
    $stmt = $db->query("SELECT COUNT(*) as total FROM tbl_produtos");
    $total = $stmt->fetchColumn();
    echo "Total Produtos: $total\n";
    
    if ($total > 0) {
        $stmt = $db->query("SELECT id_produto, nome_produtos, criado_em FROM tbl_produtos LIMIT 5");
        $prods = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($prods as $p) {
            echo "{$p['id_produto']} - {$p['nome_produtos']} ({$p['criado_em']})\n";
        }
    }
} catch (Exception $e) {
    echo "Erro DB: " . $e->getMessage();
}
