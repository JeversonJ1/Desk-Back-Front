<?php
namespace App\Koketsu\Core;

class View {
    public static function render($nomeView, $dados = [], $layout = true){
        extract($dados);
        if ($layout) {
            require_once __DIR__."/../Views/Templates/partials/header.php";
        }
        require_once __DIR__."/../Views/Templates/{$nomeView}.php";
        if ($layout) {
            require_once __DIR__."/../Views/Templates/partials/footer.php";
        }
    }
}