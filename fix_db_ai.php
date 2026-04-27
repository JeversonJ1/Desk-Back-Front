<?php
$pdo = new PDO('mysql:host=localhost;dbname=koketsu', 'root', '');
// Add AUTO_INCREMENT to tbl_cores
$pdo->exec('ALTER TABLE tbl_cores MODIFY id_cores INT(11) NOT NULL AUTO_INCREMENT;');
// Add AUTO_INCREMENT to tbl_tamanhos
$pdo->exec('ALTER TABLE tbl_tamanhos MODIFY id_tamanhos INT(11) NOT NULL AUTO_INCREMENT;');

echo "Fixed DB Auto Increments.\n";
