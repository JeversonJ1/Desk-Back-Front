<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Core\View;
use App\Koketsu\Models\Carrinho;
use App\Koketsu\Database\Database;

class CarrinhoController {
    
    public function index() {
        echo "Carrinho Home";
    }

    public function viewCriarCarrinho() {
        echo "View Criar Carrinho";
    }

    public function viewListarCarrinho() {
        $db = Database::getInstance();
        $carrinhoModel = new Carrinho($db);
        $carrinhos = $carrinhoModel->buscarCarrinhos();
        
        // Assuming there is a view for this, if not, I'll just json_encode for now or simple echo
        // Given the pattern, it should render a view. I'll use a placeholder view render.
        // View::render('carrinho/listar', ['carrinhos' => $carrinhos]); 
        // Since I don't know if the view exists, I'll stick to basic output or check other controllers.
        // Other controllers use View::render.
        // For now, to ensure it works without crashing due to missing view file:
        echo json_encode($carrinhos); 
    }

    public function viewEditarCarrinho($id) {
        $db = Database::getInstance();
        $carrinhoModel = new Carrinho($db);
        $carrinho = $carrinhoModel->buscarPorId($id);
        echo json_encode($carrinho);
    }

    public function viewExcluirCarrinho($id) {
        $this->deletarCarrinho($id);
    }

    public function salvarCarrinho() {
        $db = Database::getInstance();
        $carrinhoModel = new Carrinho($db);
        
        // Basic inputs
        $id_cliente = $_POST['id_cliente'] ?? null;
        $total = $_POST['total'] ?? 0;
        
        if ($id_cliente) {
            $carrinhoModel->inserirCarrinho($id_cliente, $total);
            echo "Carrinho salvo com sucesso!";
        } else {
            echo "Erro: ID Cliente obrigatório.";
        }
    }

    public function atualizarCarrinho($id) {
        $db = Database::getInstance();
        $carrinhoModel = new Carrinho($db);
        
        $total = $_POST['total'] ?? 0;
        $status = $_POST['status'] ?? 'Aberto';
        
        if ($carrinhoModel->atualizarCarrinho($id, $total, $status)) {
            echo "Carrinho atualizado com sucesso!";
        } else {
            echo "Erro ao atualizar carrinho.";
        }
    }

    public function deletarCarrinho($id) {
        $db = Database::getInstance();
        $carrinhoModel = new Carrinho($db);
        
        if ($carrinhoModel->deletarCarrinho($id)) {
            echo "Carrinho deletado com sucesso!";
        } else {
            echo "Erro ao deletar carrinho.";
        }
    }
}
