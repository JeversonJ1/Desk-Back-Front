<?php
$path = 'C:/Users/jeverson.bsantos/OneDrive - SENAC - SP/Área de Trabalho/desk-koketsu/vendor/autoload.php';
echo "Testando caminho: $path\n";
if (file_exists($path)) {
    echo "Arquivo existe.\n";
    require $path;
    echo "Require com sucesso!\n";
} else {
    echo "Arquivo NAO encontrado.\n";
}
