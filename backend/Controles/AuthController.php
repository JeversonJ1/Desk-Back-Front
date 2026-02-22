<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Models\Usuario;
use App\Koketsu\Database\Database;
use App\Koketsu\Core\View;
use App\Koketsu\Core\Redirect;
use App\Koketsu\Validadores\UsuarioValidador;
use App\Koketsu\Core\FileManager;
use App\Koketsu\Core\Flash;
use App\Koketsu\Core\Session;
use App\Koketsu\core\NotificacaoEmail;

class AuthController{
    private Usuario $usuarioModel;
    private Session $session;
    private $notificacaoEmail;

    public function __construct(){
        $db = Database::getInstance();
        $this->usuarioModel = new Usuario($db);
        $this->session = new Session();
        $this->notificacaoEmail = new NotificacaoEmail();

    }
    public function login(): void{
        View::render('auth/login', [], false);
    }
    public function loginadmin(): void{
        View::render('admin/login');
    }
   public function register(): void {
    View::render('auth/register', [], false);
}

  public function logout(): void {
    $tipo = $this->session->get('usuario_tipo');
    $this->session->destroy();

    if ($tipo === 'admin') {
        // Admin vai para a página inicial do site
        header('Location: /');
        exit;
    }

    // Cliente vai para o login com mensagem
    Redirect::redirecionarComMensagem('/login', 'success', 'Você saiu com segurança.');
}

  public function authenticar(): void {
    $email = $_POST['email_usuarios'] ?? null;
    $senha = $_POST['senha_usuarios'] ?? null;
    $usuario = $this->usuarioModel->checarCredenciais($email, $senha);

    if ($usuario) {
        if ($usuario['nivel_acesso'] !== 'cliente') {
            Redirect::redirecionarComMensagem('/login', 'error', 'Este login é apenas para clientes.');
            return;
        }

        session_regenerate_id(true);
        $this->session->set('usuario_id', $usuario['id_usuarios']);
        $this->session->set('usuario_nome', $usuario['nome_usuarios']);
        $this->session->set('usuario_tipo', $usuario['nivel_acesso']);
        $this->session->set('foto_usuarios', $usuario['foto_usuarios'] ?? '/img/logoperf.jpg');
        Redirect::redirecionarPara('/cliente/dashboard');
    } else {
        Redirect::redirecionarComMensagem('/login', 'error', 'E-mail ou senha incorretos.');
    }
}

  public function authenticarUnificado(): void {
    $email = $_POST['email_usuarios'] ?? null;
    $senha = $_POST['senha_usuarios'] ?? null;
    $usuario = $this->usuarioModel->checarCredenciais($email, $senha);

    if (!$usuario) {
        Redirect::redirecionarComMensagem('/login', 'error', 'E-mail ou senha incorretos.');
        return;
    }

    session_regenerate_id(true);
    $this->session->set('usuario_id', $usuario['id_usuarios']);
    $this->session->set('usuario_nome', $usuario['nome_usuarios']);
    $this->session->set('usuario_tipo', $usuario['nivel_acesso']);
    $this->session->set('foto_usuarios', $usuario['foto_usuarios'] ?? '/img/logoperf.jpg');

    if ($usuario['nivel_acesso'] === 'admin') {
        Redirect::redirecionarPara('/admin/dashboard');
    } else {
        Redirect::redirecionarPara('/cliente/dashboard');
    }
}
public function authenticaradmin(): void {
    $email = $_POST['email_usuarios'] ?? null;
    $senha = $_POST['senha_usuarios'] ?? null;
    $usuario = $this->usuarioModel->checarCredenciais($email, $senha);

    if ($usuario) {
        if ($usuario['nivel_acesso'] !== 'admin') {
            Redirect::redirecionarComMensagem('/admin', 'error', 'Este login é restrito para administradores.');
            return;
        }

        session_regenerate_id(true);
        $this->session->set('usuario_id', $usuario['id_usuarios']);
        $this->session->set('usuario_nome', $usuario['nome_usuarios']);
        $this->session->set('usuario_tipo', $usuario['nivel_acesso']);
        $this->session->set('foto_usuarios', $usuario['foto_usuarios'] ?? '/img/logoperf.jpg');
        Redirect::redirecionarPara('/admin/dashboard');
    } else {
        Redirect::redirecionarComMensagem('/admin', 'error', 'E-mail ou senha incorretos.');
    }
}

  public function cadastrarUsuario(): void {
    $erros = UsuarioValidador::validarEntradas($_POST);

    
    if (!empty($erros)) {
        Redirect::redirecionarComMensagem('/register', 'erros', implode("<br>", $erros));
    }
    
    $nome = $_POST['nome_usuarios'] ?? null;
    $email = $_POST['email_usuarios'] ?? null;
    $senha = $_POST['senha_usuarios'] ?? null;
    $senha_confirm = $_POST['senha_confirm'] ?? null;
  if ($senha !== $senha_confirm) {
    Redirect::redirecionarComMensagem('/register', 'erros', 'As senhas não conferem.');
  }


  if (!empty($this->usuarioModel->buscarUsuariosPorEmail($email))) {

    Redirect::redirecionarComMensagem('/register', 'erros', 'Erro ao cadastrar, problema no seu e-mail.');
  }
  $novoUsuarioId = $this->usuarioModel->inserirUsuario($nome, $email, $senha, 'cliente', null);

  if ($novoUsuarioId) {
    $this->notificacaoEmail->boasVindas($email, $nome);
    Redirect::redirecionarComMensagem('/login', 'success', 'Cadastro realizado! Por favor, faça o login.');
  } else {
    Redirect::redirecionarComMensagem('/register', 'error', 'Erro no servidor. Tente novamente.');
  }

}
}