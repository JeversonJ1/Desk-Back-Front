<?php
namespace App\Koketsu\Controles;

use App\Koketsu\Core\View;
use App\Koketsu\Controles\Admin\AuthenticatedController;
use App\Koketsu\Models\Preferencias;
use App\Koketsu\Models\Usuario;
use App\Koketsu\Models\Perfil;
use App\Koketsu\Models\Pedidos;
use App\Koketsu\Database\Database;
use App\Koketsu\Core\FileManager;

class ConfiguracoesController extends AuthenticatedController {
    private $configFile;
    private $preferenciasModel;
    private $usuarioModel;
    private $perfilModel;
    private $pedidosModel;

    public function __construct() {
        parent::__construct();
        
        // Bloquear vendedor de acessar configuracoes
        if ($this->session->get('usuario_tipo') === 'vendedor') {
            \App\Koketsu\Core\Redirect::redirecionarComMensagem("/backend/admin/dashboard", "error", "Acesso restrito apenas a administradores.");
        }

        $this->configFile = __DIR__ . '/../Config/settings.json';
        $db = Database::getInstance();
        $this->preferenciasModel = new Preferencias($db);
        $this->usuarioModel = new Usuario($db);
        $this->perfilModel = new Perfil($db);
        $this->pedidosModel = new Pedidos($db);
    }

    public function index(): void {
        if ($this->session->get('usuario_tipo') !== 'admin') {
            \App\Koketsu\Core\Redirect::redirecionarComMensagem("/backend/admin/dashboard", "error", "Acesso restrito apenas a administradores.");
        }

        $config = json_decode(file_get_contents($this->configFile), true);
        $usuarioId = $this->session->get('usuario_id');

        View::render('configuracoes/index', [
            'nomeUsuario'      => $this->session->get('usuario_nome'),
            'usuarioTipo'      => $this->session->get('usuario_tipo'),
            'usuarioId'        => $usuarioId,
            'manutencaoAtiva'  => $config['manutencao'] ?? false,
            'whatsappNumero'   => $config['whatsapp_numero'] ?? '5511999999999',
            'whatsappAtivo'    => $config['whatsapp_ativo'] ?? true,
            'config'           => $config ?? []
        ]);
    }

    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $usuarioId = $this->session->get('usuario_id');

        if (!$usuarioId) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
            return;
        }

        if ($this->preferenciasModel->salvarOuAtualizar($usuarioId, $input)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar preferências']);
        }
    }

    public function salvarManutencao() {
        if ($this->session->get('usuario_tipo') !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Não autorizado']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $status = $input['status'] ?? false;

        $config = file_exists($this->configFile)
            ? json_decode(file_get_contents($this->configFile), true)
            : [];
        $config['manutencao'] = (bool)$status;
        
        if (file_put_contents($this->configFile, json_encode($config, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar arquivo']);
        }
    }

    public function salvarWhatsapp() {
        header('Content-Type: application/json');

        if ($this->session->get('usuario_tipo') !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Não autorizado']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $numero = preg_replace('/\D/', '', $input['whatsapp_numero'] ?? '');
        $ativo  = isset($input['whatsapp_ativo']) ? (bool)$input['whatsapp_ativo'] : true;

        if (strlen($numero) < 10 || strlen($numero) > 13) {
            echo json_encode(['success' => false, 'message' => 'Número de WhatsApp inválido']);
            return;
        }

        $config = file_exists($this->configFile)
            ? json_decode(file_get_contents($this->configFile), true)
            : [];

        $config['whatsapp_numero'] = $numero;
        $config['whatsapp_ativo']  = $ativo;

        if (file_put_contents($this->configFile, json_encode($config, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => true, 'whatsapp_numero' => $numero]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar arquivo de configurações']);
        }
    }

    public function salvarGerais() {
        header('Content-Type: application/json');

        if ($this->session->get('usuario_tipo') !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Não autorizado']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $config = file_exists($this->configFile)
            ? json_decode(file_get_contents($this->configFile), true)
            : [];

        // Save banner settings
        if (isset($input['banner_texto'])) $config['banner_texto'] = htmlspecialchars($input['banner_texto'], ENT_QUOTES, 'UTF-8');
        if (isset($input['banner_ativo'])) $config['banner_ativo'] = (bool)$input['banner_ativo'];
        if (isset($input['banner_cor'])) $config['banner_cor'] = htmlspecialchars($input['banner_cor'], ENT_QUOTES, 'UTF-8');

        // Save SEO/Metadata settings
        if (isset($input['seo_titulo'])) $config['seo_titulo'] = htmlspecialchars($input['seo_titulo'], ENT_QUOTES, 'UTF-8');
        if (isset($input['seo_descricao'])) $config['seo_descricao'] = htmlspecialchars($input['seo_descricao'], ENT_QUOTES, 'UTF-8');
        if (isset($input['cnpj'])) $config['cnpj'] = htmlspecialchars($input['cnpj'], ENT_QUOTES, 'UTF-8');
        if (isset($input['email_contato'])) $config['email_contato'] = htmlspecialchars($input['email_contato'], ENT_QUOTES, 'UTF-8');
        if (isset($input['endereco'])) $config['endereco'] = htmlspecialchars($input['endereco'], ENT_QUOTES, 'UTF-8');

        // Save social settings
        if (isset($input['social_instagram'])) $config['social_instagram'] = htmlspecialchars($input['social_instagram'], ENT_QUOTES, 'UTF-8');
        if (isset($input['social_tiktok'])) $config['social_tiktok'] = htmlspecialchars($input['social_tiktok'], ENT_QUOTES, 'UTF-8');
        if (isset($input['social_youtube'])) $config['social_youtube'] = htmlspecialchars($input['social_youtube'], ENT_QUOTES, 'UTF-8');
        if (isset($input['social_facebook'])) $config['social_facebook'] = htmlspecialchars($input['social_facebook'], ENT_QUOTES, 'UTF-8');


        if (file_put_contents($this->configFile, json_encode($config, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar configurações']);
        }
    }

    public function exportarDados() {
        $usuarioId = $this->session->get('usuario_id');
        if (!$usuarioId) return;

        $dados = [
            'usuario' => $this->usuarioModel->buscarPorID($usuarioId),
            'perfil' => $this->perfilModel->buscarPerfilPorUsuario($usuarioId),
            'preferencias' => $this->preferenciasModel->buscarPorUsuario($usuarioId),
            'pedidos' => $this->pedidosModel->buscarPedidosPorCliente($usuarioId)
        ];

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="meus_dados_koketsu.json"');
        echo json_encode($dados, JSON_PRETTY_PRINT);
        exit;
    }

    public function excluirConta() {
        $usuarioId = $this->session->get('usuario_id');
        if (!$usuarioId) return;

        if ($this->usuarioModel->deletarUsuario($usuarioId)) {
            $this->session->destroy();
            header('Location: /login?msg=conta_excluida');
            exit;
        } else {
            header('Location: /backend/configuracoes?msg=erro_exclusao');
            exit;
        }
    }

    public function listarBannersJson() {
        header('Content-Type: application/json');
        if ($this->session->get('usuario_tipo') !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Não autorizado']);
            return;
        }

        try {
            $db = Database::getInstance();
            $stmt = $db->query("SELECT * FROM tbl_imagem_carrossel WHERE excluido_em IS NULL ORDER BY ordem_imagem_carrossel ASC");
            $banners = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => $banners]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function salvarBanner() {
        header('Content-Type: application/json');

        if ($this->session->get('usuario_tipo') !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Não autorizado']);
            return;
        }

        if (!isset($_FILES['banner_imagem']) || $_FILES['banner_imagem']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Selecione uma imagem de banner válida.']);
            return;
        }

        try {
            $baseUploadDir = __DIR__ . '/../../backend/upload';
            $fileManager = new FileManager($baseUploadDir);
            
            // Salva na pasta banners
            $caminhoRelativo = $fileManager->salvarArquivo($_FILES['banner_imagem'], 'banners');

            // Parâmetros extras
            $link = isset($_POST['banner_link']) ? htmlspecialchars($_POST['banner_link'], ENT_QUOTES, 'UTF-8') : '';
            $ordem = isset($_POST['banner_ordem']) ? (int)$_POST['banner_ordem'] : 0;
            $ativo = isset($_POST['banner_ativo']) ? (int)(bool)$_POST['banner_ativo'] : 1;

            $db = Database::getInstance();
            
            // Gerar próximo ID manualmente (compatível com a lógica de tabelas sem auto increment)
            $stmtMax = $db->query("SELECT COALESCE(MAX(id_carrossel), 0) + 1 AS next_id FROM tbl_imagem_carrossel");
            $nextId = (int)$stmtMax->fetch(\PDO::FETCH_ASSOC)['next_id'];

            $sql = "INSERT INTO tbl_imagem_carrossel (id_carrossel, url_imagem_imagem_carrossel, link_destino_imagem_carrossel, ordem_imagem_carrossel, ativo_imagem_carrossel) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $nextId,
                $caminhoRelativo,
                $link,
                $ordem,
                $ativo
            ]);

            echo json_encode(['success' => true, 'id' => $nextId, 'caminho' => $caminhoRelativo]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function excluirBanner($id) {
        header('Content-Type: application/json');
        if ($this->session->get('usuario_tipo') !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Não autorizado']);
            return;
        }

        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("UPDATE tbl_imagem_carrossel SET excluido_em = NOW() WHERE id_carrossel = ?");
            $stmt->execute([(int)$id]);
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function toggleBanner($id) {
        header('Content-Type: application/json');
        if ($this->session->get('usuario_tipo') !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Não autorizado']);
            return;
        }

        try {
            $db = Database::getInstance();
            // Get current status
            $stmt = $db->prepare("SELECT ativo_imagem_carrossel FROM tbl_imagem_carrossel WHERE id_carrossel = ?");
            $stmt->execute([(int)$id]);
            $banner = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($banner) {
                $newStatus = $banner['ativo_imagem_carrossel'] ? 0 : 1;
                $stmtUpdate = $db->prepare("UPDATE tbl_imagem_carrossel SET ativo_imagem_carrossel = ?, atualizado_em = NOW() WHERE id_carrossel = ?");
                $stmtUpdate->execute([$newStatus, (int)$id]);
                echo json_encode(['success' => true, 'new_status' => (bool)$newStatus]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Banner não encontrado']);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function editarBanner($id) {
        header('Content-Type: application/json');

        if ($this->session->get('usuario_tipo') !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Não autorizado']);
            return;
        }

        try {
            $db = Database::getInstance();

            // Verify banner exists
            $stmt = $db->prepare("SELECT * FROM tbl_imagem_carrossel WHERE id_carrossel = ? AND excluido_em IS NULL");
            $stmt->execute([(int)$id]);
            $banner = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$banner) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Banner não encontrado']);
                exit;
            }

            $link  = isset($_POST['banner_link'])  ? htmlspecialchars($_POST['banner_link'],  ENT_QUOTES, 'UTF-8') : $banner['link_destino_imagem_carrossel'];
            $ordem = isset($_POST['banner_ordem']) ? (int)$_POST['banner_ordem']                                  : (int)$banner['ordem_imagem_carrossel'];
            $ativo = isset($_POST['banner_ativo']) ? (int)(bool)$_POST['banner_ativo']                            : (int)$banner['ativo_imagem_carrossel'];

            $novaImagem = $banner['url_imagem_imagem_carrossel'];

            // Trocar imagem, se enviada
            if (isset($_FILES['banner_imagem']) && $_FILES['banner_imagem']['error'] === UPLOAD_ERR_OK) {
                $baseUploadDir = __DIR__ . '/../../backend/upload';
                $fileManager   = new FileManager($baseUploadDir);
                $novaImagem    = $fileManager->salvarArquivo($_FILES['banner_imagem'], 'banners');
            }

            $stmtUpdate = $db->prepare(
                "UPDATE tbl_imagem_carrossel
                    SET url_imagem_imagem_carrossel   = ?,
                        link_destino_imagem_carrossel = ?,
                        ordem_imagem_carrossel        = ?,
                        ativo_imagem_carrossel        = ?,
                        atualizado_em                 = NOW()
                  WHERE id_carrossel = ?"
            );
            $stmtUpdate->execute([$novaImagem, $link, $ordem, $ativo, (int)$id]);

            echo json_encode(['success' => true, 'caminho' => $novaImagem]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}
