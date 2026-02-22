<?php
$db = new PDO("mysql:host=localhost;dbname=koketsu", "root", "");
$stmt = $db->query("SELECT DISTINCT nome_categorias FROM tbl_categorias ORDER BY nome_categorias");
echo "Categorias disponíveis:\n";
foreach($stmt->fetchAll(PDO::FETCH_COLUMN) as $cat) {
    echo "- $cat\n";
}
