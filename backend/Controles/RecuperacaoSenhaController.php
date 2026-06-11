<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Models\Usuario;
use App\Koketsu\Database\Database;
use App\Koketsu\Core\View;
use App\Koketsu\Core\Redirect;
use App\Koketsu\core\NotificacaoEmail;

class RecuperacaoSenhaController
{
    private Usuario $usuarioModel;
    private $notificacaoEmail;
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->usuarioModel = new Usuario($this->db);
        $this->notificacaoEmail = new NotificacaoEmail();
    }

    /**
     * GET /recuperar-senha
     * Exibe o formulário para solicitar link de recuperação
     */
    public function viewRecuperarSenha(): void
    {
        View::render('auth/recuperar-senha', [], false);
    }

    /**
     * POST /recuperar-senha
     * Gera o token e envia o e-mail
     */
    public function solicitarRecuperacao(): void
    {
        $email = trim($_POST['email_recuperar'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Redirect::redirecionarComMensagem('/recuperar-senha', 'error', 'Informe um e-mail válido.');
            return;
        }

        // Verifica se o e-mail existe (sem revelar se existe ou não por segurança)
        $usuario = $this->usuarioModel->buscarUsuariosPorEmail($email);

        // Sempre mostra mensagem genérica (evita enumeração de usuários)
        $mensagemGenerica = 'Se este e-mail estiver cadastrado, você receberá um link em breve. Verifique sua caixa de entrada e spam.';

        if (!empty($usuario)) {
            $usuario = $usuario[0];

            // Gera token seguro
            $token = bin2hex(random_bytes(32));
            $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Salva token no banco
            $salvo = $this->usuarioModel->salvarTokenRecuperacao(
                $usuario['id_usuarios'],
                $token,
                $expiracao
            );

            if ($salvo) {
                // Monta o link de redefinição
                $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
                $link = "{$protocolo}://{$host}/nova-senha?token={$token}";

                // Envia e-mail (usando o sistema de e-mails existente)
                try {
                    $this->notificacaoEmail->recuperacaoSenha(
                        $usuario['email_usuarios'],
                        $usuario['nome_usuarios'],
                        $link
                    );
                } catch (\Exception $e) {
                    // Log silencioso — não revela o erro ao usuário
                    error_log('[RecuperacaoSenha] Erro ao enviar e-mail: ' . $e->getMessage());
                }
            }
        }

        Redirect::redirecionarComMensagem('/recuperar-senha', 'success', $mensagemGenerica);
    }

    /**
     * GET /nova-senha?token=xxx
     * Exibe o formulário de nova senha
     */
    public function viewNovaSenha(): void
    {
        $token = $_GET['token'] ?? '';
        View::render('auth/nova-senha', ['token' => $token], false);
    }

    /**
     * POST /nova-senha
     * Valida o token e atualiza a senha
     */
    public function redefinirSenha(): void
    {
        $token          = $_POST['token'] ?? '';
        $novaSenha      = $_POST['nova_senha'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';

        // Validações básicas
        if (empty($token)) {
            Redirect::redirecionarComMensagem('/recuperar-senha', 'error', 'Token inválido. Solicite um novo link.');
            return;
        }

        if (empty($novaSenha) || strlen($novaSenha) < 8) {
            Redirect::redirecionarComMensagem('/nova-senha?token=' . urlencode($token), 'error', 'A senha deve ter pelo menos 8 caracteres.');
            return;
        }

        if ($novaSenha !== $confirmarSenha) {
            Redirect::redirecionarComMensagem('/nova-senha?token=' . urlencode($token), 'error', 'As senhas não coincidem.');
            return;
        }

        // Busca token válido no banco
        $dados = $this->usuarioModel->buscarTokenRecuperacao($token);

        if (!$dados) {
            Redirect::redirecionarComMensagem('/recuperar-senha', 'error', 'Link expirado ou inválido. Solicite um novo.');
            return;
        }

        // Atualiza a senha
        $atualizado = $this->usuarioModel->atualizarSenha($dados['id_usuarios'], $novaSenha);

        if ($atualizado) {
            // Invalida o token usado
            $this->usuarioModel->invalidarTokenRecuperacao($token);
            Redirect::redirecionarComMensagem('/login', 'success', 'Senha redefinida com sucesso! Faça o login com sua nova senha.');
        } else {
            Redirect::redirecionarComMensagem('/nova-senha?token=' . urlencode($token), 'error', 'Erro ao atualizar a senha. Tente novamente.');
        }
    }
}
