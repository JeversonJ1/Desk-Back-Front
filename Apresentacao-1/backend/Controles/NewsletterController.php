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

        $inscritos = $this->newsletterModel->listarTodos();
        $total = count($inscritos);
        $enviados = 0;

        $emailService = new NotificacaoEmail();

        foreach ($inscritos as $inscrito) {
            if ($emailService->enviarPromocao($inscrito['email_newsletter'], $assunto, $mensagem, $imagemUrl, $localPath)) {
                $enviados++;
            }
        }

        Redirect::redirecionarComMensagem('/admin/newsletter', 'success', "Disparo concluído: $enviados de $total e-mails enviados com sucesso!");
    }
}
