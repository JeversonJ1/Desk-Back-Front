<?php
// Script para criar a tabela de recuperação de senha
// Execute: php criar_tabela_recuperacao.php

require_once __DIR__ . '/backend/index.php';

use App\Koketsu\Database\Database;

try {
    $db = Database::getInstance();

    $sql = "CREATE TABLE IF NOT EXISTS tbl_recuperacao_senha (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_usuarios INT NOT NULL,
        token VARCHAR(64) NOT NULL UNIQUE,
        expiracao DATETIME NOT NULL,
        usado TINYINT(1) NOT NULL DEFAULT 0,
        criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_token (token),
        INDEX idx_usuario (id_usuarios)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $db->exec($sql);
    echo "✅ Tabela tbl_recuperacao_senha criada com sucesso!\n";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
