<?php
namespace App\Koketsu\Core;
use App\Koketsu\Core\EmailService;
class NotificacaoEmail{
    private EmailService $emailService;
    public function __construct(){
        $this->emailService = new EmailService();
    }
    public function esqueciASenha(string $email, string $token): void {
        $assunto = "Redefinição de Senha";
        $mensagem = "Clique no link abaixo para redefinir sua senha: ";
        $mensagem .= "http://localhost:4000/backend/redefinir-senha?token=" . urlencode($token);
        $this->emailService->send($email, $assunto, $mensagem);
    }
    public function boasVindas(string $email, string $nome): void {
        $assunto = "Bem-vindo ao Koketsu!";
        
        // Caminho do arquivo de template
        $templatePath = __DIR__ . '/../Views/Templates/emails/bem_vindo.php';
        
        if (file_exists($templatePath)) {
            ob_start();
            require $templatePath;
            $mensagem = ob_get_clean();
        } else {
            // Fallback caso o template não exista
            $mensagem = "<b><h2>Olá " . htmlspecialchars($nome) ."</h2></b>\n\n";
            $mensagem .= "<p>Obrigado por se cadastrar no site da Koketsu grife!!</p>\n\n";
            $mensagem .= "<p>Atenciosamente,\nEquipe Koketsu</p>";
        }

        $this->emailService->send($email, $assunto, $mensagem);
    }
    public function enviarPromocao(string $email, string $assunto, string $mensagem, string $imagem_url = '', string $local_caminho = ''): bool {
        try {
            $templatePath = __DIR__ . '/../Views/Templates/emails/newsletter_promo.php';

            // Embutir logo da Koketsu via CID
            $logo_cid = '';
            $logoPaths = [
                __DIR__ . '/../../frontend/assets/img/logo2026.png',
                __DIR__ . '/../Views/Templates/emails/logo_koketsu.png',
            ];
            foreach ($logoPaths as $lp) {
                if (file_exists($lp)) {
                    $this->emailService->embedImage($lp, 'koketsu_logo');
                    $logo_cid = 'koketsu_logo';
                    break;
                }
            }

            // Se tiver imagem promocional local, embutir via CID no PHPMailer
            if (!empty($local_caminho) && file_exists($local_caminho)) {
                $this->emailService->embedImage($local_caminho, 'promo_banner');
            }

            if (file_exists($templatePath)) {
                ob_start();
                require $templatePath;
                $corpo = ob_get_clean();
            } else {
                $corpo = "<h2>$assunto</h2><p>" . nl2br($mensagem) . "</p>";
                if ($imagem_url) {
                    $corpo .= "<img src='cid:promo_banner' style='max-width:100%'>";
                }
            }

            return $this->emailService->send($email, $assunto, $corpo);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Envia o e-mail de recuperação de senha com link de redefinição
     */
    public function recuperacaoSenha(string $email, string $nome, string $link): void
    {
        $assunto = '🔑 Recuperação de Senha — Koketsu Grife';

        $corpo = "
        <!DOCTYPE html>
        <html lang='pt-BR'>
        <head><meta charset='UTF-8'></head>
        <body style='margin:0;padding:0;background:#050505;font-family:Arial,sans-serif;'>
          <table width='100%' cellpadding='0' cellspacing='0' style='background:#050505;padding:40px 0;'>
            <tr><td align='center'>
              <table width='560' cellpadding='0' cellspacing='0' style='background:#0f0f0f;border-radius:20px;border:1px solid rgba(242,204,125,0.15);overflow:hidden;'>

                <!-- Header dourado -->
                <tr><td style='background:linear-gradient(135deg,#f2cc7d,#b8860b);padding:32px;text-align:center;'>
                  <h1 style='margin:0;color:#000;font-size:22px;font-weight:900;letter-spacing:4px;text-transform:uppercase;'>KOKETSU GRIFE</h1>
                  <p style='margin:6px 0 0;color:#000;font-size:12px;letter-spacing:2px;opacity:0.7;text-transform:uppercase;'>Luxury Streetwear</p>
                </td></tr>

                <!-- Ícone chave -->
                <tr><td align='center' style='padding:36px 40px 0;'>
                  <div style='width:72px;height:72px;background:rgba(242,204,125,0.1);border:2px solid rgba(242,204,125,0.3);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;'>
                    <span style='font-size:32px;'>🔑</span>
                  </div>
                </td></tr>

                <!-- Conteúdo -->
                <tr><td style='padding:28px 48px 0;text-align:center;'>
                  <h2 style='color:#f2cc7d;font-size:20px;letter-spacing:2px;text-transform:uppercase;margin:0 0 12px;'>Recuperar Senha</h2>
                  <p style='color:#888;font-size:14px;line-height:1.7;margin:0 0 8px;'>Olá, <strong style='color:#fff;'>" . htmlspecialchars($nome) . "</strong></p>
                  <p style='color:#888;font-size:14px;line-height:1.7;margin:0 0 32px;'>Recebemos uma solicitação para redefinir a senha da sua conta.<br>Clique no botão abaixo para criar uma nova senha.</p>

                  <!-- Botão CTA -->
                  <a href='" . htmlspecialchars($link) . "' style='display:inline-block;background:linear-gradient(135deg,#f2cc7d,#b8860b);color:#000;text-decoration:none;padding:18px 48px;border-radius:12px;font-weight:900;font-size:14px;letter-spacing:2px;text-transform:uppercase;'>
                    Redefinir Minha Senha
                  </a>
                </td></tr>

                <!-- Aviso de expiração -->
                <tr><td style='padding:28px 48px;text-align:center;'>
                  <p style='color:#555;font-size:12px;line-height:1.6;margin:0;border-top:1px solid #1a1a1a;padding-top:24px;'>
                    ⏱ Este link expira em <strong style='color:#f2cc7d;'>1 hora</strong>.<br>
                    Se você não solicitou a recuperação de senha, ignore este e-mail — sua conta continua segura.<br><br>
                    <span style='font-size:11px;color:#444;word-break:break-all;'>Link: " . htmlspecialchars($link) . "</span>
                  </p>
                </td></tr>

                <!-- Footer -->
                <tr><td style='padding:20px 40px;text-align:center;background:#0a0a0a;border-top:1px solid #1a1a1a;'>
                  <p style='color:#333;font-size:11px;letter-spacing:2px;text-transform:uppercase;margin:0;'>&copy; " . date('Y') . " Koketsu Grife — Todos os direitos reservados</p>
                </td></tr>

              </table>
            </td></tr>
          </table>
        </body>
        </html>";

        $this->emailService->send($email, $assunto, $corpo);
    }
}