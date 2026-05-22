<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable('c:\Users\jever\OneDrive\Área de Trabalho\Desk-Back-Front');
$dotenv->load();
$db = App\Koketsu\Database\Database::getInstance();
$stmt = $db->query("SHOW CREATE TABLE tbl_pedidos");
print_r($stmt->fetch(PDO::FETCH_ASSOC));

$stmt2 = $db->query("SHOW CREATE TABLE tbl_itens_pedidos");
print_r($stmt2->fetch(PDO::FETCH_ASSOC));
