<?php
// MySQL Cleanup
try {
    $pdo = new PDO('mysql:host=localhost;dbname=koketsu', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("DELETE FROM tbl_usuarios WHERE nivel_acesso IN ('admin', 'vendedor', 'Admin', 'Vendedor') AND id_usuarios NOT IN (54, 55)");
    $stmt->execute();
    echo "MySQL: Limpo!\n";
} catch (Exception $e) {
    echo "MySQL Error: " . $e->getMessage() . "\n";
}

// SQLite Cleanup
try {
    $sqlitePath = __DIR__ . '/desktop/database/koketsu.sqlite';
    if(file_exists($sqlitePath)) {
        $sqlite = new PDO('sqlite:' . $sqlitePath);
        $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt2 = $sqlite->prepare("DELETE FROM tbl_usuarios WHERE nivel_acesso IN ('admin', 'vendedor', 'Admin', 'Vendedor') AND id_usuarios NOT IN (54, 55)");
        $stmt2->execute();
        echo "SQLite: Limpo!\n";
    } else {
        echo "SQLite DB n?o encontrado.\n";
    }
} catch (Exception $e) {
    echo "SQLite Error: " . $e->getMessage() . "\n";
}
