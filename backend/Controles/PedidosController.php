<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Models\Pedidos;
use App\Koketsu\Database\Database;
use App\Koketsu\Core\View;
use App\Koketsu\Core\Redirect;
use App\Koketsu\Models\ItensPedidos;
use App\Koketsu\Core\FileManager;
use App\Koketsu\Controles\Admin\AdminController;


class PedidosController extends AdminController {
    public $pedidos;
    public $itenspedidos; 
    public $db;
    public $gerenciarImagem;

    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance();
        $this->pedidos = new Pedidos($this->db);
        $this->itenspedidos = new ItensPedidos($this->db); 
        $this->gerenciarImagem = new FileManager(__DIR__ . '/../../backend/upload');

        // Se a requisição enviar JSON, parseia para $_POST
        if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            if (is_array($data)) {
                $_POST = array_merge($_POST, $data);
            }
        }
    }

    private function sendResponse($success, $message, $extra = [], $fallbackUrl = "/pedido/listar", $errorType = "error") {
        $isAjax = isset($_GET['json']) || 
                  (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
                  (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) ||
                  (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
                  
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(array_merge([
                'success' => $success,
                'message' => $message
            ], $extra));
            exit;
        }
        
        $msgType = $success ? "success" : $errorType;
        Redirect::redirecionarComMensagem($fallbackUrl, $msgType, $message);
        exit;
    }

public function index(){
     $this->viewListarPedido();
}   

public function viewAtivarPedido(int $id){
         $dados = $this->pedidos->buscarPedidoPorId($id);
         View::render("pedidos/ativar",["pedido" => $dados]);
    }
    public function ativarPedido(){
        if (!isset($_POST['id_pedido']) || $_POST['id_pedido'] === '' || (int)$_POST['id_pedido'] < 0) {
            $this->sendResponse(false, "ID do pedido inválido.");
        }
        $id = (int)$_POST['id_pedido'];
        if ($this->pedidos->ativarPedido($id)) {
            $this->sendResponse(true, "Pedido ativado com sucesso!");
        } else {
            $this->sendResponse(false, "Erro ao ativar pedido.");
        }
    }

    // Método para exibir um único pedido
    public function viewPedidoUnico(int $id_pedido) {
        // 1. Buscar os dados do pedido principal
        $pedido = $this->pedidos->buscarPedidoPorId($id_pedido);
        
        // 2. Buscar todos os itens associados a este pedido
        $itens_pedido = $this->itenspedidos->buscarItensPorPedido($id_pedido);
        
        if ($pedido) {
            $isAjax = isset($_GET['json']) || 
                      (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
                      (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
            if ($isAjax) {
                $this->sendResponse(true, "Pedido encontrado", ['pedido' => $pedido, 'itens' => $itens_pedido]);
            }
            // Se o pedido for encontrado, exibe a view com os dados
            View::render('pedidos/detalhes', [
                'pedido' => $pedido,
                'itens' => $itens_pedido
            ]);
        } else {
            $this->sendResponse(false, "Pedido não encontrado.", [], "/pedido/listar");
        }
    }
    
    // Método para listar todos os pedidos com paginação
    public function viewListarPedido() {
        // Capturar busca se houver
        $busca = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_pedido'])) {
            $busca = trim($_POST['id_pedido']);
        }
        
        $dados = $this->pedidos->paginacao(1, 100, $busca);
        $total = $this->pedidos->totalDePedidos(); 

        $total_pedidos = (int) $total;

        $isAjax = isset($_GET['json']) || 
                  (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
                  (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
        
        if ($isAjax) {
            $this->sendResponse(true, "Lista de pedidos obtida com sucesso.", [
                'pedidos' => $dados['data'] ?? [],
                'total_pedidos' => $total_pedidos
            ]);
        }

        View::render("pedidos/index", [
            "pedidos" => $dados['data'] ?? [],
            "total_pedidos" => $total_pedidos ?? 0,
            "total_inativos" => 0,
            "total_ativos" => $total_pedidos ?? 0,
            "paginacao" => $dados,
            "busca" => $busca
        ]);
    }

    // Método para exibir detalhes de um pedido específico
    public function viewDetalhesPedido(int $id) {
        
        $itensPedidosModel = new \App\Koketsu\Models\ItensPedidos($this->db); 
        $pedido = $this->pedidos->buscarPedidoPorId($id); 

        if (!$pedido) {
        
            $this->sendResponse(false, "Pedido não encontrado.", [], "/pedido/listar");
            return;
        }

        $itens = $itensPedidosModel->buscarItensPorPedido($id); 
        View::render('pedidos/detalhes', [
            'pedido' => $pedido,
            'itens' => $itens
        ]);
    }

    // Método para exibir o formulário de criação de pedidos
    public function viewCriarPedidos() {
        View::render("pedidos/create");
    }

    // Método para exibir o formulário de edição de pedidos
    public function viewEditarPedido(int $id) {
        $dados = $this->pedidos->buscarPedidoPorId($id);

        if (!$dados) {
            $this->sendResponse(false, "Pedido não encontrado.", [], "/pedido/listar");
            return;
        }

        View::render('pedidos/edit', ['pedido' => $dados]);
    }

    // Método para atualizar um pedido no banco de dados
    public function atualizarPedidos() {
        $id_pedido = $_POST["id_pedido"] ?? null;
        $total_pedido = $_POST["total_pedido"] ?? 0;
        $data_pedido = $_POST["data_pedido"] ?? null;
        if ($data_pedido) {
            $data_pedido = str_replace('T', ' ', $data_pedido);
        }
        $status_pedido = $_POST["status_pedido"] ?? null;
        
        
        $imagem = $_POST["imagem_pedidos"] ?? null; 
        
    
        if ($this->pedidos->atualizarPedido($id_pedido, $total_pedido, $data_pedido, $status_pedido, $imagem)) { 
            $this->sendResponse(true, "Pedido atualizado com sucesso!");
        } else {
            $this->sendResponse(false, "Erro ao atualizar pedido!", [], "/pedido/editar/" . $id_pedido);
        }
    }

    // Método para atualizar rapidamente o status de um pedido na lista
    public function mudarStatusRapido() {
        $id_pedido = filter_input(INPUT_POST, 'id_pedido', FILTER_VALIDATE_INT);
        $novo_status = $_POST['status'] ?? '';

        if ($id_pedido === false || $id_pedido === null || $id_pedido < 0 || !$novo_status) {
            $this->sendResponse(false, "Dados inválidos para alterar o status.");
        }

        $pedido = $this->pedidos->buscarPedidoPorId($id_pedido);
        if (!$pedido) {
            $this->sendResponse(false, "Pedido não encontrado.");
        }

        // Usa os mesmos dados do pedido e apenas altera o status
        if ($this->pedidos->atualizarPedido($id_pedido, $pedido['total_pedido'], $pedido['data_pedido'], $novo_status, $pedido['imagem_pedidos'] ?? null)) {
            $this->sendResponse(true, "Status alterado para " . ucfirst($novo_status) . "!");
        } else {
            $this->sendResponse(false, "Erro ao alterar o status do pedido.");
        }
    }

    // Método para salvar um novo pedido e seus itens no banco de dados
    public function salvarPedido() {
        
        // 1. Receber e Tratar os Dados
        $id_perfil = $_POST['id_perfil'] ?? NULL; 
        
        $data_pedido = $_POST['data_pedido'] ?? null;
        if ($data_pedido) {
            $data_pedido = str_replace('T', ' ', $data_pedido);
        }
        $total_pedido_str = $_POST['total_pedido'] ?? '0.00';
        $total_pedido = (float)str_replace(',', '.', $total_pedido_str);
        $status_pedido = $_POST['status_pedido'] ?? 'pendente';
        
        $itens_pedido = $_POST['itens'] ?? []; 


        // 2. Validação
        if (empty($id_perfil) || empty($data_pedido) || $total_pedido <= 0 || empty($itens_pedido)) {
            $this->sendResponse(false, "Preencha o Perfil, a Data, o Total e adicione pelo menos um Item.", [], "/pedido/criar");
        }


        // 3. Salvar o pedido principal
        $novo_id_pedido = $this->pedidos->inserirPedido(
            $id_perfil, 
            $data_pedido, 
            $total_pedido, 
            $status_pedido
        );


        if ($novo_id_pedido) {
            
            // 4. Salvar os itens do pedido
            $todos_itens_salvos = true;
            
            foreach ($itens_pedido as $item) {
                
                $id_produto       = $item['id_produto'] ?? NULL;
                $quantidade       = $item['quantidade'] ?? 0;
                $preco_unitario   = (float)str_replace(',', '.', ($item['preco_unitario'] ?? '0.00')); 

                if ($id_produto && $quantidade > 0 && $preco_unitario > 0) {
                    // Assumindo que $this->itensPedidos está instanciado no Controller
                    $id_item_salvo = $this->itenspedidos->inserirItemPedido(
                        $novo_id_pedido, 
                        $id_produto, 
                        $quantidade, 
                        $preco_unitario
                    );
                    
                    if (!$id_item_salvo) {
                        $todos_itens_salvos = false;
                        break; 
                    }
                } else {
                    $todos_itens_salvos = false;
                    break;
                }
            }
            
            // 5. Finalização
            if ($todos_itens_salvos) {
                $this->sendResponse(true, "Pedido e Itens cadastrados com sucesso! ID: " . $novo_id_pedido, ['id_pedido' => $novo_id_pedido]);
            } else {
                // Reverter ou deletar o pedido principal aqui seria o ideal
                $this->sendResponse(false, "Pedido principal salvo, mas erro ao cadastrar os Itens.", [], "/pedido/criar");
            }

        } else {
            // Falha no Model ao salvar o Pedido Principal
            $this->sendResponse(false, "Erro ao cadastrar pedido principal. Verifique se o ID do Perfil existe no banco.", [], "/pedido/criar");
        }
    }

    // Método para exibir a view de confirmação de exclusão
    public function viewExcluirPedido($id) {
        $dados = $this->pedidos->buscarPedidoPorId($id);
        View::render("/pedidos/delete", ["pedido" => $dados]);
    }

    // Método para processar a exclusão/ativação (soft delete toggle) via POST
    public function deletarPedido() {
        if (!isset($_POST['id_pedido']) || $_POST['id_pedido'] === '' || (int)$_POST['id_pedido'] < 0) {
            $this->sendResponse(false, "ID do pedido inválido.");
        }
        $id = (int)$_POST['id_pedido'];
        if ($this->pedidos->deletarPedido($id)) {
            $this->sendResponse(true, "Status do pedido alterado com sucesso!");
        } else {
            $this->sendResponse(false, "Erro ao alterar status do pedido.");
        }
    }


    // Método para excluir (soft delete) um pedido - DEPRECATED - usar deletarPedido
    public function excluirPedido(int $id_pedido) {
        if ($this->pedidos->excluirPedido($id_pedido)) {
            $this->sendResponse(true, "Pedido #" . $id_pedido . " excluído com sucesso (soft delete).");
        } else {
            $this->sendResponse(false, "Erro ao excluir o Pedido #" . $id_pedido . ".");
        }
    }

    // Método para gerar um relatório de pedidos
    public function relatorioPedido($id, $data1, $data2) {
        View::render("pedidos/relatorio", [
            "id" => $id,
            "data1" => $data1,
            "data2" => $data2
        ]);
    }
    
    // Método para pesquisar pedidos por ID
    public function pesquisarPedido()
    {
        // 1. Capturar e validar o ID de busca (campo '' da View)
        $id_pedido = filter_input(INPUT_POST, 'id_pedido', FILTER_VALIDATE_INT);
        
        $pedidos_a_exibir = [];
        $total_pedidos_na_lista = 0;

        if ($id_pedido) {
    
            $pedido_unico = $this->pedidos->buscarPedidoPorId($id_pedido);
            
        
            if ($pedido_unico) {
                
                $pedidos_a_exibir = [$pedido_unico];
                $total_pedidos_na_lista = 1;
            } 
            
            
            $paginacao = [
                'total' => $total_pedidos_na_lista,
                'de' => $total_pedidos_na_lista,
                'para' => $total_pedidos_na_lista,
                'pagina_atual' => 1,
                'ultima_pagina' => 1
            ];

            
            View::render('pedidos/index', [
                'pedidos' => $pedidos_a_exibir,
                'paginacao' => $paginacao,
            
                'total_pedidos' => $this->pedidos->contarTodosPedidos() 
            ]);

        } else {
        
            $this->sendResponse(false, "ID de Pedido inválido ou vazio. Por favor, digite um número.", [], "/backend/pedido/listar", "warning");
        }
    }
    
}