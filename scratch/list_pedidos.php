<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$db = \App\Koketsu\Database\Database::getInstance();
$stmt = $db->query("SELECT id_pedido, id_perfil, status_pedido, excluido_em, total_pedido FROM tbl_pedidos ORDER BY id_pedido DESC LIMIT 10");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results, JSON_PRETTY_PRINT) . "\n";
