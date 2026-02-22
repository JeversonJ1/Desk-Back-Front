<?php
namespace App\Koketsu\Controles\Admin;

use App\Koketsu\Core\Session;
use App\Koketsu\Core\Redirect;

abstract class AuthenticatedController{
    protected Session $session;
    public function __construct(){
        $this->session = new Session();
        if (!$this->session->has('usuario_id')){
            Redirect::redirecionarComMensagem(
                'login',
                'Por favor, faça login para acessar esta área.',
                'error'
            );
        }

        // Check Maintenance Mode
        $configFile = __DIR__ . '/../../Config/settings.json';
        if (file_exists($configFile)) {
            $config = json_decode(file_get_contents($configFile), true);
            if (!empty($config['manutencao']) && $this->session->get('usuario_tipo') !== 'admin') {
                // If it's a normal user and maintenance is active, show maintenance view
                // We render it directly or redirect to a specific route? Let's render or simple output.
                die(include __DIR__ . '/../../Views/Templates/manutencao.php');
            }
        }
    }
}