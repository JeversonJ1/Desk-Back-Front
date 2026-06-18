<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mysql', 'root', '');
    $pdo->exec("DROP TABLE IF EXISTS column_stats");
    // Or just clear the table
    // $pdo->exec("DELETE FROM column_stats WHERE db_name = 'koketsu'");
} catch (Exception $e) {
    echo "Could not clean column_stats: " . $e->getMessage() . "\n";
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=koketsu', 'root', '');
    // Try to fix AUTO_INCREMENT for tbl_cores
    $pdo->exec('ALTER TABLE tbl_cores MODIFY id_cores INT(11) NOT NULL AUTO_INCREMENT;');
    // Try to fix AUTO_INCREMENT for tbl_tamanhos
    $pdo->exec('ALTER TABLE tbl_tamanhos MODIFY id_tamanhos INT(11) NOT NULL AUTO_INCREMENT;');
    echo "Fixed AUTO_INCREMENT for tbl_cores and tbl_tamanhos.\n";
} catch (Exception $e) {
    echo "Error adding AUTO_INCREMENT: " . $e->getMessage() . "\n";
}
