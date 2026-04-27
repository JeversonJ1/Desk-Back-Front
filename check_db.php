<?php
require 'backend/Models/Database.php';
$db = (new \Models\Database())->getConnection();
$stmt = $db->query("SELECT * FROM tbl_produtos WHERE id_produto=44");
print_r($stmt->fetch(PDO::FETCH_ASSOC));
$stmt2 = $db->query("SELECT * FROM tbl_imagem WHERE id_produto=44");
print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));
