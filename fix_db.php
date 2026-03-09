<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=koketsu', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec('ALTER TABLE tbl_tamanhos MODIFY id_tamanhos INT AUTO_INCREMENT;');
    echo "SUCCESS: Modificado tbl_tamanhos para auto_increment.";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
