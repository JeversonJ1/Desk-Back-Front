<?php
namespace App\Koketsu\Controles\Admin;

use App\Koketsu\Core\Redirect;

abstract class AdminController extends AuthenticatedController
{
    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->session->get('usuario_tipo'), ['admin', 'vendedor'])) {
            Redirect::redirecionarComMensagem(
                '/login',
                'error',
                'Você não tem permissão para acessar esta área.'
            );
        }
    }
}