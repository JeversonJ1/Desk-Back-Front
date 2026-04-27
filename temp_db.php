<?php
$pdo = new PDO('mysql:host=localhost;dbname=koketsu', 'root', '');
$stmt = $pdo->query('SELECT imagem_produtos FROM tbl_produtos ORDER BY id_produto DESC LIMIT 1');
print_r($stmt->fetch());
$stmt = $pdo->query('SELECT * FROM tbl_imagem ORDER BY id_imagem DESC LIMIT 5');
print_r($stmt->fetchAll());
?>
