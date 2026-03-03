<?php
$pdo = new PDO('mysql:host=localhost;dbname=koketsu;charset=utf8', 'root', '');
$stmt = $pdo->query("SELECT id_produto, nome_produtos, imagem_produtos FROM tbl_produtos WHERE imagem_produtos IS NOT NULL LIMIT 20");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getCaminhoFisico($caminhoRelativo)
{
    if (empty($caminhoRelativo))
        return "vazio";
    if (strpos($caminhoRelativo, 'http') === 0 && strpos($caminhoRelativo, 'localhost') === false)
        return "externo";

    $caminhoRelativo = urldecode($caminhoRelativo);
    $caminhoFisico = '';

    if (strpos($caminhoRelativo, 'file://') === 0) {
        $caminhoFisico = str_replace(['file:///', 'file://'], '', $caminhoRelativo);
        $caminhoFisico = str_replace('/', DIRECTORY_SEPARATOR, $caminhoFisico);
    } else {
        $caminhoLimpo = str_replace('backend/upload/backend/upload/', 'backend/upload/', $caminhoRelativo);
        if (strpos($caminhoLimpo, 'desktop/storage') !== false) {
            $caminhoLimpo = preg_replace('/^backend\/upload\//', '', $caminhoLimpo);
        }
        $caminhoFisico = __DIR__ . DIRECTORY_SEPARATOR . 'backend' . DIRECTORY_SEPARATOR . 'Controles' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . ltrim($caminhoLimpo, '/');
    }

    $pathReal = print_r($caminhoFisico, true);
    if (file_exists($caminhoFisico) && is_file($caminhoFisico)) {
        return "OK -> " . $pathReal;
    }
    return "FALHA -> Tentou: " . $pathReal;
}

foreach ($rows as $r) {
    echo $r['id_produto'] . ' (' . $r['imagem_produtos'] . '): ' . getCaminhoFisico($r['imagem_produtos']) . PHP_EOL;
}
