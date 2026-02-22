<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Koketsu\Database\Database;

try {
    $db = Database::getInstance();
    
    // 1. Encontrar o usuário Itachi
    $stmt = $db->prepare("SELECT id_usuarios, nome_usuarios FROM tbl_usuarios WHERE nome_usuarios LIKE '%Itachi%'");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "Usuário Itachi não encontrado.\n";
        exit;
    }
    
    $userId = $user['id_usuarios'];
    echo "Usuário encontrado: " . $user['nome_usuarios'] . " (ID: $userId)\n";
    
    // 2. Verificar perfil
    $stmt = $db->prepare("SELECT id_perfil FROM tbl_perfil WHERE id_usuarios = ?");
    $stmt->execute([$userId]);
    $perfil = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$perfil) {
        echo "Perfil não encontrado para o usuário $userId.\n";
        exit;
    }
    
    $perfilId = $perfil['id_perfil'];
    echo "Perfil encontrado: ID $perfilId\n";
    
    // 3. Verificar pedidos
    $stmt = $db->prepare("SELECT COUNT(*) FROM tbl_pedidos WHERE id_perfil = ? AND excluido_em IS NULL");
    $stmt->execute([$perfilId]);
    $count = $stmt->fetchColumn();
    
    echo "Total de pedidos para este perfil: $count\n";
    
    if ($count > 0) {
        $stmt = $db->prepare("SELECT id_pedido, total_pedido, status_pedido FROM tbl_pedidos WHERE id_perfil = ? AND excluido_em IS NULL");
        $stmt->execute([$perfilId]);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($pedidos as $p) {
            echo "- Pedido #{$p['id_pedido']}: R$ {$p['total_pedido']} ({$p['status_pedido']})\n";
        }
    }

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
