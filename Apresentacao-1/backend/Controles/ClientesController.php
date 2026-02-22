<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Controles\Admin\AdminController;
use App\Koketsu\Models\Usuario;
use App\Koketsu\Models\Perfil;
use App\Koketsu\Database\Database;
use App\Koketsu\Core\View;
use App\Koketsu\Core\Redirect;

class ClientesController extends AdminController {
    public $usuario;
    public $perfil;
    public $db;

    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance();
        $this->usuario = new Usuario($this->db);
        $this->perfil = new Perfil($this->db);
    }

    public function index($pagina = 1) {
        $pagina = (int)$pagina > 0 ? (int)$pagina : 1;
        $dados = $this->usuario->paginacaoClientes($pagina);
        
        // Estatísticas específicas para clientes
        $total_clientes = $this->usuario->contarClientes($this->db);
        
        View::render('clientes/index', [
            "clientes" => $dados['data'],
            "total_clientes" => $total_clientes,
            "paginacao" => $dados
        ]);
    }

    public function viewEditarCliente($id) {
        Redirect::redirecionarPara("/usuario/editar/$id");
    }

    public function viewExcluirCliente($id) {
        Redirect::redirecionarPara("/usuario/excluir/$id");
    }
}
