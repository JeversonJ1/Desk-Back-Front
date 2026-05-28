<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Database\Database;
use App\Koketsu\Models\Newsletter;
use App\Koketsu\Core\View;
use App\Koketsu\Core\Flash;
use App\Koketsu\Core\Redirect;
use App\Koketsu\Core\NotificacaoEmail;
use App\Koketsu\Core\FileManager;

class NewsletterController {
    private $db;
    private $newsletterModel;

    public function __construct() {
        $this->db = Database::getInstance();
        
        try {
            $sql = "CREATE TABLE IF NOT EXISTS `tbl_newsletter` (
                `id_newsletter` INT(11) NOT NULL AUTO_INCREMENT,
                `email_newsletter` VARCHAR(150) NOT NULL,
                `data_inscricao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `status_newsletter` VARCHAR(50) NOT NULL DEFAULT 'Ativo',
                `criado_em` DATETIME DEFAULT NULL,
                `atualizado_em` DATETIME DEFAULT NULL,
                `excluido_em` DATETIME DEFAULT NULL,
                PRIMARY KEY (`id_newsletter`),
                UNIQUE KEY `uq_email_newsletter` (`email_newsletter`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
            $this->db->exec($sql);
        } catch (\Exception $e) {
            // Silently ignore
        }

        $this->newsletterModel = new Newsletter($this->db);
    }

    /**
     * Inscreve um e-mail na newsletter (Público)
     */
    public function inscrever() {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $email = $input['email_newsletter'] ?? ($_POST['email_newsletter'] ?? null);

            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Por favor, informe um e-mail válido.']);
                exit;
            }

            if ($this->newsletterModel->emailExiste($email)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado em nossa newsletter!']);
                exit;
            }

            if ($this->newsletterModel->inserir($email)) {
                http_response_code(201);
                echo json_encode(['success' => true, 'message' => 'Inscrição realizada com sucesso! Prepare-se para as novidades.']);
            } else {
                throw new \Exception('Erro ao salvar no banco de dados.');
            }

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro interno ao processar sua inscrição.']);
        }
        exit;
    }

    /**
     * Lista os inscritos no Painel Admin
     */
    public function listar() {
        $inscritos = $this->newsletterModel->listarTodos();
        View::render('admin/newsletter/index', ['inscritos' => $inscritos]);
    }

    /**
     * Exclui um inscrito do banco (Painel Admin)
     */
    public function excluir(int $id) {
        if ($this->newsletterModel->excluir($id)) {
            Redirect::redirecionarComMensagem('/admin/newsletter', 'success', 'E-mail removido da lista.');
        } else {
            Redirect::redirecionarComMensagem('/admin/newsletter', 'error', 'Erro ao remover e-mail.');
        }
    }

    /**
     * Exporta a lista para CSV
     */
    public function exportar() {
        $inscritos = $this->newsletterModel->listarTodos();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=lista_newsletter_koketsu_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'E-mail', 'Data de Inscrição', 'Status']);
        
        foreach ($inscritos as $row) {
            fputcsv($output, [
                $row['id_newsletter'],
                $row['email_newsletter'],
                $row['data_inscricao'],
                $row['status_newsletter']
            ]);
        }
        fclose($output);
        exit;
    }

    /**
     * Dispara e-mail para todos os inscritos com suporte a imagem e design premium
     */
    public function enviarFila() {
        $assunto = $_POST['assunto'] ?? '';
        $mensagem = $_POST['mensagem'] ?? '';
        $imagemUrl = '';

        if (empty($assunto) || empty($mensagem)) {
            Redirect::redirecionarComMensagem('/admin/newsletter', 'error', 'Assunto e mensagem são obrigatórios.');
            return;
        }

        // Processamento da Imagem (se houver)
        $localPath = '';
        if (isset($_FILES['imagem_promo']) && $_FILES['imagem_promo']['error'] === UPLOAD_ERR_OK) {
            try {
                $baseUploadDir = __DIR__ . '/../upload';
                $fileManager = new FileManager($baseUploadDir);
                $caminhoRelativo = $fileManager->salvarArquivo($_FILES['imagem_promo'], 'newsletter');
                
                $localPath = $baseUploadDir . '/' . $caminhoRelativo;

                // Constrói a URL absoluta (opcional, mantida para compatibilidade se necessário)
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost:4000';
                $imagemUrl = $protocol . "://" . $host . "/backend/upload/" . $caminhoRelativo;
                
            } catch (\Exception $e) {
                Redirect::redirecionarComMensagem('/admin/newsletter', 'error', 'Erro no upload da imagem: ' . $e->getMessage());
                return;
            }
        }
        $destinatario = $_POST['destinatario'] ?? 'todos';
        if ($destinatario === 'custom') {
            $destinatario = $_POST['email_personalizado'] ?? '';
            if (empty($destinatario) || !filter_var($destinatario, FILTER_VALIDATE_EMAIL)) {
                Redirect::redirecionarComMensagem('/admin/newsletter', 'error', 'Por favor, informe um e-mail válido para envio exclusivo.');
                return;
            }
        }
        $emailService = new NotificacaoEmail();

        if ($destinatario === 'todos') {
            $inscritos = $this->newsletterModel->listarTodos();
            $total = count($inscritos);
            $enviados = 0;

            foreach ($inscritos as $inscrito) {
                if ($emailService->enviarPromocao($inscrito['email_newsletter'], $assunto, $mensagem, $imagemUrl, $localPath)) {
                    $enviados++;
                }
            }

            Redirect::redirecionarComMensagem('/admin/newsletter', 'success', "Disparo concluído: $enviados de $total e-mails enviados com sucesso!");
        } else {
            if ($emailService->enviarPromocao($destinatario, $assunto, $mensagem, $imagemUrl, $localPath)) {
                Redirect::redirecionarComMensagem('/admin/newsletter', 'success', "Cupom exclusivo enviado com sucesso para " . htmlspecialchars($destinatario) . "!");
            } else {
                Redirect::redirecionarComMensagem('/admin/newsletter', 'error', "Erro ao enviar o e-mail exclusivo para " . htmlspecialchars($destinatario) . ".");
            }
        }
    }
}
