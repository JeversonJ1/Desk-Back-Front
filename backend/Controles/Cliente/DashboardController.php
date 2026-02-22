<?php
namespace App\Koketsu\Controles\Cliente;

use App\Koketsu\Core\View;
use App\Koketsu\Core\Redirect;
use App\Koketsu\Core\FileManager;
use App\Koketsu\Database\Database;
use App\Koketsu\Models\Usuario;
use App\Koketsu\Models\Perfil;
use App\Koketsu\Models\Pedidos;
use App\Koketsu\Controles\Admin\AuthenticatedController;

class DashboardController extends AuthenticatedController{
    public $usuario;
    public $perfil;
    public $pedidos;
    public $db;
    public $gerenciarImagem;
    
    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance();
        $this->usuario = new Usuario($this->db);
        $this->perfil = new Perfil($this->db);
        $this->pedidos = new Pedidos($this->db);
        $this->gerenciarImagem = new FileManager('upload');
    }
    
    public function index(): void{
        $usuario_id = $this->session->get('usuario_id');
        $perfil = $this->perfil->buscarPerfilPorUsuario($usuario_id);
        
        $pedidosRecentes = $this->pedidos->buscarPedidosPorUsuario($usuario_id);
        $totalPedidos = count($pedidosRecentes);

        // Contar avaliações do cliente (todos os perfis)
        $avaliacaoModel = new \App\Koketsu\Models\Avaliacao($this->db);
        $totalAvaliacoes = count($avaliacaoModel->buscarPorUsuario($usuario_id));
        
        // Ordenar e limitar
        usort($pedidosRecentes, function($a, $b) {
            return strtotime($b['data_pedido']) - strtotime($a['data_pedido']);
        });
        $pedidosRecentes = array_slice($pedidosRecentes, 0, 5);

        View::render('cliente/dashboard/index', [
            'nomeUsuario' => $this->session->get('usuario_nome'),
            'usuarioId' => $usuario_id,
            'Tipo' => $this->session->get('usuario_tipo'),
            'perfil' => $perfil,
            'pedidosRecentes' => $pedidosRecentes,
            'totalPedidos' => $totalPedidos,
            'totalAvaliacoes' => $totalAvaliacoes
        ]);
    }

    public function viewEditarCliente(int $id){
        $usuario = $this->usuario->buscarPorID($id);
        if (!$usuario) {
            Redirect::redirecionarComMensagem("/cliente/dashboard", "error", "Cliente não encontrado.");
        }
        
        $perfil = $this->perfil->buscarPerfilPorUsuario($id);
        
        View::render("cliente/editar", [
            "usuario" => $usuario,
            "perfil" => $perfil
        ]);
    }

    public function atualizarCliente(int $id) {
        $usuario = $this->usuario->buscarPorID($id);
        if (!$usuario) {
            Redirect::redirecionarComMensagem("/cliente/dashboard", "error", "Cliente não encontrado.");
        }

        $nome = $_POST['nome_usuarios'] ?? '';
        $email = $_POST['email_usuarios'] ?? '';
        $senha = $_POST['senha_usuarios'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';

        // Validações
        if (empty($nome)) {
            Redirect::redirecionarComMensagem("/backend/cliente/meu-perfil/$id", "error", "Nome é obrigatório.");
        }
    
        if (empty($email)) {
            Redirect::redirecionarComMensagem("/backend/cliente/meu-perfil/$id", "error", "Email é obrigatório.");
        }
    
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            Redirect::redirecionarComMensagem("/backend/cliente/meu-perfil/$id", "error", "Email inválido.");
        }
    
        // Se uma nova senha foi fornecida
        if (!empty($senha)) {
            if (strlen($senha) < 6) {
                Redirect::redirecionarComMensagem("/backend/cliente/meu-perfil/$id", "error", "Senha deve ter no mínimo 6 caracteres.");
            }

            if ($senha !== $confirmarSenha) {
                Redirect::redirecionarComMensagem("/backend/cliente/meu-perfil/$id", "error", "As senhas não conferem.");
            }
        }

        // Processar imagem
        $fotoCaminho = $usuario['foto_usuarios'];
        if (isset($_FILES['foto_usuarios']) && $_FILES['foto_usuarios']['error'] == 0) {
            $fotoCaminho = $this->gerenciarImagem->salvarArquivo($_FILES['foto_usuarios'], 'usuarios');
        }

        // Atualizar usuário
        $senhaHash = empty($senha) ? $usuario['senha_usuarios'] : password_hash($senha, PASSWORD_DEFAULT);

        // Inserir/Atualizar perfil
        $telefone = $_POST['telefone_perfil'] ?? '';
        $endereco = $_POST['endereco_perfil'] ?? '';
        $perfilExistente = $this->perfil->buscarPerfilPorUsuario($id);

        if ($perfilExistente) {
            $this->perfil->atualizarPerfil(
                $perfilExistente['id_perfil'],
                $telefone,
                $endereco,
                $perfilExistente['data_cadastro'],
                $id
            );
        } else {
            $this->perfil->inserirPerfil(
                $telefone,
                $endereco,
                date('Y-m-d H:i:s'),
                $id
            );
        }

        // Atualizar usuário
        $sql = "UPDATE tbl_usuarios SET 
                nome_usuarios = :nome,
                email_usuarios = :email,
                senha_usuarios = :senha,
                foto_usuarios = :foto,
                atualizado_em = NOW()
                WHERE id_usuarios = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senhaHash);
        $stmt->bindParam(':foto', $fotoCaminho);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            // Atualizar sessão
            $this->session->set('usuario_nome', $nome);
            if (!empty($fotoCaminho)) {
                $this->session->set('foto_usuarios', $fotoCaminho);
            }

            Redirect::redirecionarComMensagem("/cliente/dashboard", "success", "Perfil atualizado com sucesso!");
        } else {
            Redirect::redirecionarComMensagem("/backend/cliente/meu-perfil/$id", "error", "Erro ao atualizar perfil. Tente novamente.");
        }
    }
}