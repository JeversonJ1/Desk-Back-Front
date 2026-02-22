<?php
$db = new PDO("mysql:host=localhost;dbname=koketsu;charset=utf8mb4", "root", "");
$stmt = $db->query("
    SELECT id_produto, nome_produtos, c.nome_categorias 
    FROM tbl_produtos p 
    LEFT JOIN tbl_categorias c ON p.id_categoria = c.id_categorias
    WHERE p.excluido_em IS NULL 
    AND (p.nome_produtos LIKE '%polo%' 
         OR p.nome_produtos LIKE '%social%' 
         OR c.nome_categorias LIKE '%camisa%' 
         OR c.nome_categorias LIKE '%polo%')
    ORDER BY id_produto
");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Produtos de Polo/Camisa Social:\n";
echo str_repeat("-", 60) . "\n";
foreach($produtos as $p) {
    echo sprintf("ID: %3d | %-30s | %s\n", 
        $p['id_produto'], 
        $p['nome_produtos'], 
        $p['nome_categorias'] ?? 'NULL'
    );
}
echo "\nTotal: " . count($produtos) . " produtos\n";
