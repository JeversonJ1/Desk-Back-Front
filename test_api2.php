<?php
$response = @file_get_contents('http://localhost:8000/api/produtos');
$data = json_decode($response, true);
foreach ($data['data'] as $prod) {
    $img = substr($prod['caminho_imagem'], 0, 50);
    echo $prod['id_produto'] . ' - ' . $img . PHP_EOL;
}
