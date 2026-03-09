<?php
namespace App\Koketsu\Core;
use App\Koketsu\Core\Flash;

class Redirect
{
    public static function redirecionarPara($url)
    {
        if (strpos($url, 'http') === 0) {
            header("Location: " . $url);
        } else {
            $prefix = strpos($url, '/') === 0 ? '' : '/';
            header("Location: " . $prefix . $url);
        }
        exit;
    }

    public static function redirecionarComMensagem($url, $type, $message)
    {

        Flash::set($type, $message);
        self::redirecionarPara($url);

    }
    public static function voltarPaginaAnteriorComMensagem($type, $message)
    {
        $url = $_SERVER['HTTP_REFERER'] ?? '/';
        self::redirecionarComMensagem($url, $type, $message);
    }
}