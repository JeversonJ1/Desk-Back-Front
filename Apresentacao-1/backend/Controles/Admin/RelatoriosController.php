<?php

namespace App\Koketsu\Controles\Admin;

use App\Koketsu\Models\Produtos;
use App\Koketsu\Models\Pedidos;
use App\Koketsu\Models\Usuario;
use App\Koketsu\Models\Categoria;
use App\Koketsu\Controles\Admin\AuthenticatedController;
use App\Koketsu\Core\Redirect;
use App\Koketsu\Core\View;
use App\Koketsu\Database\Database;

class RelatoriosController extends AuthenticatedController
{
    public function index()
    {
        $db = Database::getInstance();
        
        // Dados de vendas totais
        $vendastotais = $this->obterVendasTotais($db);
        $produtostotais = Produtos::contarProdutos($db);
        $clientestotais = Usuario::contarClientes($db);
        $pedidostotais = Pedidos::contarPedidos($db);
        
        // Dados para gráficos
        $vendasPorMes = $this->obterVendasPorMes($db);
        $produtosMaisVendidos = $this->obterProdutosMaisVendidos($db);
        $statusPedidos = $this->obterStatusPedidos($db);
        $categoriasMaisProdutos = $this->obterCategoriasMaisProdutos($db);
        
        $data = [
            'vendastotais' => $vendastotais,
            'produtostotais' => $produtostotais,
            'clientestotais' => $clientestotais,
            'pedidostotais' => $pedidostotais,
            'vendasPorMes' => json_encode($vendasPorMes),
            'produtosMaisVendidos' => json_encode($produtosMaisVendidos),
            'statusPedidos' => json_encode($statusPedidos),
            'categoriasMaisProdutos' => json_encode($categoriasMaisProdutos),
            'nomeUsuario' => $_SESSION['usuario_nome'] ?? 'Admin'
        ];
        
        View::render('admin/relatorios', $data);
    }
    
    public function relatorioDetalhado()
    {
        $db = Database::getInstance();
        
        // Obter todos os pedidos com detalhes
        $sql = "SELECT 
                    p.id_pedido,
                    p.data_pedido,
                    p.total_pedido,
                    p.status_pedido,
                    u.nome_usuarios,
                    u.email_usuarios,
                    COALESCE(SUM(ip.quantidade), 0) as quantidade
                FROM tbl_pedidos p
                LEFT JOIN tbl_perfil pf ON p.id_perfil = pf.id_perfil
                LEFT JOIN tbl_usuarios u ON pf.id_usuarios = u.id_usuarios
                LEFT JOIN tbl_itens_pedidos ip ON p.id_pedido = ip.id_pedido
                WHERE p.excluido_em IS NULL
                GROUP BY p.id_pedido, p.data_pedido, p.total_pedido, p.status_pedido, u.nome_usuarios, u.email_usuarios
                ORDER BY p.data_pedido DESC
                LIMIT 100";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $pedidos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $data = [
            'pedidos' => $pedidos,
            'nomeUsuario' => $_SESSION['usuario_nome'] ?? 'Admin'
        ];
        
        View::render('admin/relatorio-detalhado', $data);
    }
    
    public function relatorioFinanceiro()
    {
        $db = Database::getInstance();
        
        // Receita total
        $sql = "SELECT 
                    SUM(total_pedido) as receita_total,
                    COUNT(id_pedido) as total_pedidos,
                    AVG(total_pedido) as ticket_medio,
                    MIN(total_pedido) as menor_venda,
                    MAX(total_pedido) as maior_venda
                FROM tbl_pedidos
                WHERE status_pedido IN ('pago', 'enviado', 'concluido', 'pendente', 'processamento') AND excluido_em IS NULL";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $resumoFinanceiro = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        // Vendas por mês
        $sqlMeses = "SELECT 
                        DATE_FORMAT(data_pedido, '%Y-%m') as mes_raw,
                        DATE_FORMAT(data_pedido, '%b/%y') as mes,
                        SUM(total_pedido) as valor,
                        COUNT(id_pedido) as quantidade
                    FROM tbl_pedidos
                    WHERE status_pedido IN ('pago', 'enviado', 'concluido', 'pendente', 'processamento') AND excluido_em IS NULL
                    GROUP BY mes_raw, mes
                    ORDER BY mes_raw ASC
                    LIMIT 12";
        
        $stmt = $db->prepare($sqlMeses);
        $stmt->execute();
        $vendasPorMes = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Para a tabela, pode ser interessante mostrar do mais recente primeiro
        $vendasPorMesTabela = array_reverse($vendasPorMes);
        
        $data = [
            'resumoFinanceiro' => $resumoFinanceiro,
            'vendasPorMes' => $vendasPorMes,
            'vendasPorMesTabela' => $vendasPorMesTabela,
            'nomeUsuario' => $_SESSION['usuario_nome'] ?? 'Admin'
        ];
        
        View::render('admin/relatorio-financeiro', $data);
    }
    
    public function relatorioProdutos()
    {
        $db = Database::getInstance();
        
        // Produtos mais vendidos - Agora filtrando por status de pedido e excluídos
        $sql = "SELECT 
                    pr.id_produto,
                    pr.nome_produtos,
                    pr.preco_produtos,
                    c.nome_categorias,
                    COUNT(ip.id_itens_pedidos) as total_vendido,
                    SUM(ip.quantidade) as quantidade_total,
                    SUM(ip.preco_unitario * ip.quantidade) as receita
                FROM tbl_produtos pr
                LEFT JOIN tbl_categorias c ON pr.id_categoria = c.id_categorias
                INNER JOIN tbl_itens_pedidos ip ON pr.id_produto = ip.id_produto
                INNER JOIN tbl_pedidos ped ON ip.id_pedido = ped.id_pedido
                WHERE ped.status_pedido IN ('pago', 'enviado', 'concluido', 'pendente', 'processamento') 
                AND ped.excluido_em IS NULL
                GROUP BY pr.id_produto, pr.nome_produtos, pr.preco_produtos, c.nome_categorias
                ORDER BY quantidade_total DESC
                LIMIT 50";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $produtosMaisVendidos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $data = [
            'produtosMaisVendidos' => $produtosMaisVendidos,
            'nomeUsuario' => $_SESSION['usuario_nome'] ?? 'Admin'
        ];
        
        View::render('admin/relatorio-produtos', $data);
    }
    
    private function obterVendasTotais($db)
    {
        $sql = "SELECT SUM(total_pedido) as total FROM tbl_pedidos WHERE status_pedido IN ('pago', 'enviado', 'concluido', 'pendente', 'processamento') AND excluido_em IS NULL";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    
    private function obterVendasPorMes($db)
    {
        // Alterado para ORDER BY mes ASC para garantir ordem cronológica correta
        $sql = "SELECT 
                    DATE_FORMAT(data_pedido, '%Y-%m') as mes_raw,
                    DATE_FORMAT(data_pedido, '%b/%y') as mes_formatado,
                    SUM(total_pedido) as valor
                FROM tbl_pedidos
                WHERE status_pedido IN ('pago', 'enviado', 'concluido', 'pendente', 'processamento') AND excluido_em IS NULL
                GROUP BY mes_raw, mes_formatado
                ORDER BY mes_raw ASC
                LIMIT 12";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $meses = [];
        $valores = [];
        
        foreach ($resultado as $row) {
            // Tradução simples de meses para PT-BR se necessário, ou usar o formato raw
            $meses[] = $row['mes_formatado'];
            $valores[] = (float)$row['valor'];
        }
        
        return [
            'labels' => $meses,
            'data' => $valores
        ];
    }
    
    private function obterProdutosMaisVendidos($db)
    {
        $sql = "SELECT 
                    pr.nome_produtos,
                    SUM(ip.quantidade) as quantidade
                FROM tbl_produtos pr
                    JOIN tbl_itens_pedidos ip ON pr.id_produto = ip.id_produto
                    JOIN tbl_pedidos ped ON ip.id_pedido = ped.id_pedido
                    WHERE ped.status_pedido IN ('pago', 'enviado', 'concluido', 'pendente', 'processamento') AND ped.excluido_em IS NULL
                    GROUP BY pr.id_produto, pr.nome_produtos
                    ORDER BY quantidade DESC
                    LIMIT 8";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $produtos = [];
        $quantidades = [];
        
        foreach ($resultado as $row) {
            $produtos[] = substr($row['nome_produtos'], 0, 20) . '...';
            $quantidades[] = (int)$row['quantidade'];
        }
        
        return [
            'labels' => $produtos,
            'data' => $quantidades
        ];
    }
    
    private function obterStatusPedidos($db)
    {
        $sql = "SELECT 
                    status_pedido,
                    COUNT(id_pedido) as quantidade
                FROM tbl_pedidos
                GROUP BY status_pedido";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $status = [];
        $quantidades = [];
        
        foreach ($resultado as $row) {
            $status[] = $row['status_pedido'];
            $quantidades[] = (int)$row['quantidade'];
        }
        
        return [
            'labels' => $status,
            'data' => $quantidades
        ];
    }
    
    private function obterCategoriasMaisProdutos($db)
    {
        $sql = "SELECT 
                    c.nome_categorias,
                    COUNT(p.id_produto) as quantidade
                FROM tbl_categorias c
                LEFT JOIN tbl_produtos p ON c.id_categorias = p.id_categoria
                GROUP BY c.id_categorias, c.nome_categorias
                ORDER BY quantidade DESC
                LIMIT 6";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $categorias = [];
        $quantidades = [];
        
        foreach ($resultado as $row) {
            $categorias[] = $row['nome_categorias'];
            $quantidades[] = (int)$row['quantidade'];
        }
        
        return [
            'labels' => $categorias,
            'data' => $quantidades
        ];
    }
}
