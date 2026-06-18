<?php
namespace App\Koketsu\Controles\Admin;

use App\Koketsu\Core\View;
use App\Koketsu\Database\Database;
use App\Koketsu\Models\Usuario;
use App\Koketsu\Controles\Admin\AuthenticatedController;

class DashboardController extends AuthenticatedController {
    public $usuario;
    public $db;

    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance();
        $this->usuario = new Usuario($this->db);
    }

    public function index(): void {
        // --- KPIs Principais ---
        $totalUsuarios   = (int) $this->db->query("SELECT COUNT(*) FROM tbl_usuarios WHERE excluido_em IS NULL")->fetchColumn();
        $totalProdutos   = (int) $this->db->query("SELECT COUNT(*) FROM tbl_produtos WHERE excluido_em IS NULL")->fetchColumn();
        $totalPedidos    = (int) $this->db->query("SELECT COUNT(*) FROM tbl_pedidos WHERE excluido_em IS NULL")->fetchColumn();
        $receitaTotal    = (float) $this->db->query("SELECT COALESCE(SUM(total_pedido),0) FROM tbl_pedidos WHERE status_pedido IN ('pago','concluido','enviado') AND excluido_em IS NULL")->fetchColumn();

        // --- Pedidos por Status ---
        $pedidosPorStatus = $this->db->query("
            SELECT status_pedido, COUNT(*) as total
            FROM tbl_pedidos WHERE excluido_em IS NULL
            GROUP BY status_pedido
        ")->fetchAll(\PDO::FETCH_KEY_PAIR);

        // --- Receita dos Últimos 7 Dias ---
        $receitaSemana = $this->db->query("
            SELECT DATE(data_pedido) as dia, COALESCE(SUM(total_pedido),0) as receita
            FROM tbl_pedidos
            WHERE status_pedido IN ('pago','concluido','enviado')
              AND excluido_em IS NULL
              AND data_pedido >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
            GROUP BY DATE(data_pedido)
            ORDER BY dia ASC
        ")->fetchAll(\PDO::FETCH_ASSOC);

        // --- Últimos 8 Pedidos ---
        $ultimosPedidos = $this->db->query("
            SELECT p.id_pedido, p.data_pedido, p.total_pedido, p.status_pedido,
                   u.nome_usuarios, u.foto_usuarios
            FROM tbl_pedidos p
            LEFT JOIN tbl_perfil pf ON pf.id_perfil = p.id_perfil
            LEFT JOIN tbl_usuarios u ON u.id_usuarios = pf.id_usuarios
            WHERE p.excluido_em IS NULL
            ORDER BY p.data_pedido DESC
            LIMIT 8
        ")->fetchAll(\PDO::FETCH_ASSOC);

        // --- Top 5 Produtos Mais Vendidos ---
        $topProdutos = $this->db->query("
            SELECT pr.nome_produtos, pr.imagem_produtos, pr.preco_produtos,
                   SUM(ip.quantidade) as total_vendido,
                   SUM(ip.quantidade * ip.preco_unitario) as receita_produto
            FROM tbl_itens_pedidos ip
            JOIN tbl_pedidos p ON p.id_pedido = ip.id_pedido
            JOIN tbl_produtos pr ON pr.id_produto = ip.id_produto
            WHERE p.status_pedido IN ('pago','concluido','enviado')
              AND p.excluido_em IS NULL
            GROUP BY ip.id_produto, pr.nome_produtos, pr.imagem_produtos, pr.preco_produtos
            ORDER BY total_vendido DESC
            LIMIT 5
        ")->fetchAll(\PDO::FETCH_ASSOC);

        // --- Estoque Baixo (<=10 unidades) ---
        $estoqueBaixo = $this->db->query("
            SELECT nome_produtos, estoque_produtos, imagem_produtos
            FROM tbl_produtos
            WHERE excluido_em IS NULL AND estoque_produtos <= 10
            ORDER BY estoque_produtos ASC
            LIMIT 5
        ")->fetchAll(\PDO::FETCH_ASSOC);

        // --- Novos Usuários esta semana ---
        $novosUsuariosSemana = (int) $this->db->query("
            SELECT COUNT(*) FROM tbl_usuarios
            WHERE excluido_em IS NULL AND criado_em >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ")->fetchColumn();

        View::render('admin/dashboard/index', [
            'nomeUsuario'        => $this->session->get('usuario_nome'),
            'Tipo'               => $this->session->get('usuario_tipo'),
            'totalUsuarios'      => $totalUsuarios,
            'totalProdutos'      => $totalProdutos,
            'totalPedidos'       => $totalPedidos,
            'receitaTotal'       => $receitaTotal,
            'pedidosPorStatus'   => $pedidosPorStatus,
            'receitaSemana'      => $receitaSemana,
            'ultimosPedidos'     => $ultimosPedidos,
            'topProdutos'        => $topProdutos,
            'estoqueBaixo'       => $estoqueBaixo,
            'novosUsuariosSemana'=> $novosUsuariosSemana,
        ]);
    }
}