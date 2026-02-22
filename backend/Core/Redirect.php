<?php
namespace App\Koketsu\Core;
use App\Koketsu\Core\Flash;

class Redirect{
    public static function redirecionarPara($url){
        // Se a URL já começar com /backend ou for uma URL completa (http/https), não adicionar o prefixo
        if (strpos($url, '/backend') === 0 || strpos($url, 'http') === 0) {
            header("Location: " . $url);
        } else {
            header("Location: /backend" . (strpos($url, '/') === 0 ? "" : "/") . $url);
        }
        exit;
    }

    public static function redirecionarComMensagem($url, $type, $message){
        
        Flash::set($type, $message);
        self::redirecionarPara($url);

    }
    public static function voltarPaginaAnteriorComMensagem($type, $message){
        $url = $_SERVER['HTTP_REFERER'] ?? '/';
        self::redirecionarComMensagem($url, $type, $message);
    }
}