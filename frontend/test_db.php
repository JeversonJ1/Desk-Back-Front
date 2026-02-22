<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Koketsu\Database\Database;

try {
    $db = Database::getInstance();
    echo "Conexão bem-sucedida!\n";
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM tbl_produtos");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total de produtos: " . $count['total'] . "\n";
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM tbl_categorias");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total de categorias: " . $count['total'] . "\n";

    $stmt = $db->query("SELECT * FROM tbl_categorias WHERE excluido_em IS NULL");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Categorias ativas: " . count($categorias) . "\n";
    foreach ($categorias as $cat) {
        echo "- " . $cat['nome_categorias'] . " (ID: " . $cat['id_categorias'] . ")\n";
        
        $stmtProd = $db->prepare("SELECT COUNT(*) FROM tbl_produtos WHERE id_categoria = ? AND excluido_em IS NULL AND estoque_produtos > 0");
        $stmtProd->execute([$cat['id_categorias']]);
        echo "  - Produtos ativos nesta categoria: " . $stmtProd->fetchColumn() . "\n";
    }

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
