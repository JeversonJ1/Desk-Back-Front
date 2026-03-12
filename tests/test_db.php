<?php
$db = new PDO('mysql:host=localhost;dbname=koketsu', 'root', '');
$stmt = $db->query("SHOW CREATE TABLE tbl_tamanhos");
print_r($stmt->fetch());
