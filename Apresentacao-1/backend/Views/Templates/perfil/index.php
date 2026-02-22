 <style>
    .page-wrapper {
        padding: 24px;
        background-color: var(--bg-main) !important;
        min-height: 100vh;
        color: var(--text-main);
    }
    .header-breadcrumb {
        color: var(--text-muted);
        margin-bottom: 30px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border-color);
    }
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    .stat-card {
        background: var(--bg-card);
        padding: 24px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: 0.3s;
    }
    .stat-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); border-color: var(--accent); }
    .stat-icon { font-size: 32px; color: var(--accent); opacity: 0.8; }
    .stat-info h3 { margin: 0; font-size: 24px; font-weight: 800; }
    .stat-info p { margin: 0; font-size: 11px; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 1px; }

    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }
    .custom-table thead th {
        padding: 12px 18px;
        color: var(--text-muted);
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: 1px;
    }
    .custom-table tbody tr {
        background: var(--bg-card);
        box-shadow: var(--shadow-sm);
        transition: 0.2s;
    }
    .custom-table tbody tr:hover { transform: scale(1.005); box-shadow: var(--shadow-md); }
    .custom-table td { padding: 18px !important; border-bottom: 1px solid var(--border-color); vertical-align: middle; }
    .custom-table td:first-child { border-radius: 12px 0 0 12px; border-left: 1px solid var(--border-color); }
    .custom-table td:last-child { border-radius: 0 12px 12px 0; border-right: 1px solid var(--border-color); }
    
    .btn-action { padding: 8px 16px; border-radius: 8px; font-size: 11px; font-weight: 700; text-decoration: none; transition: 0.3s; }
    .btn-edit { background: var(--bg-main); color: #2196F3; border: 1px solid #2196F3; }
    .btn-edit:hover { background: #2196F3; color: #fff; }
    .btn-delete { background: var(--bg-main); color: #f44336; border: 1px solid #f44336; }
    .btn-delete:hover { background: #f44336; color: #fff; }

    .pagination-wrapper { margin-top: 30px; display: flex; justify-content: space-between; align-items: center; color: var(--text-muted); font-size: 13px; }
    .page-nav-link { color: var(--accent); text-decoration: none; font-weight: 700; padding: 5px 12px; border: 1px solid var(--border-color); border-radius: 6px; transition: 0.3s; }
    .page-nav-link:hover { background: var(--accent); color: #000; border-color: var(--accent); }
 </style>

<div class="page-wrapper">
    <header class="header-breadcrumb">
        <h5><b><i class="fas fa-id-card"></i> Gerenciar Perfis de Usuários - Koketsu</b></h5>
    </header>

    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-tags"></i></div>
            <div class="stat-info">
                <h3>120</h3>
                <p>Produtos</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
            <div class="stat-info">
                <h3>87</h3>
                <p>Pedidos</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <h3>56</h3>
                <p>Clientes</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-star"></i></div>
            <div class="stat-info">
                <h3>4.8★</h3>
                <p>Avaliações</p>
            </div>
        </div>
    </div>

    <?php if (isset($perfil) && count($perfil) > 0): ?>
    <table class="custom-table">
        <thead>
            <tr>
                <th>Telefone</th>
                <th>Endereço</th>
                <th>Cadastro</th>
                <th>Status</th>
                <th style="text-align: center;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($perfil as $p): ?>
            <tr>
                <td style="font-weight: 700; color: var(--text-main);">
                    <?php 
                        $tel = preg_replace('/\D/', '', $p['telefone_perfil']);
                        if (strlen($tel) == 11) {
                            $formatted = '(' . substr($tel, 0, 2) . ') ' . substr($tel, 2, 1) . ' ' . substr($tel, 3, 4) . '-' . substr($tel, 7);
                        } elseif (strlen($tel) == 10) {
                            $formatted = '(' . substr($tel, 0, 2) . ') ' . substr($tel, 2, 4) . '-' . substr($tel, 6);
                        } else {
                            $formatted = $p['telefone_perfil'];
                        }
                        echo htmlspecialchars($formatted);
                    ?>
                </td>
                <td style="max-width: 300px; color: var(--text-muted); font-size: 13px;"><?= htmlspecialchars($p['endereco_perfil']) ?></td>
                <td><?= date('d/m/Y', strtotime($p['data_cadastro'])) ?></td>
                <td>
                    <?php if(!empty($p['excluido_em'])): ?>
                        <span style="color: #f44336; font-weight: 800; font-size: 10px; background: rgba(244,67,54,0.1); padding: 4px 8px; border-radius: 4px;">INATIVO</span>
                    <?php else: ?>
                        <span style="color: #4CAF50; font-weight: 800; font-size: 10px; background: rgba(76,175,80,0.1); padding: 4px 8px; border-radius: 4px;">ATIVO</span>
                    <?php endif; ?>
                </td>
                <td style="text-align: center;">
                    <a class="btn-action btn-edit" href="/backend/perfil/editar/<?= htmlspecialchars($p['id_perfil']) ?>">Editar</a>
                    <a class="btn-action btn-delete" href="/backend/perfil/excluir/<?= htmlspecialchars($p['id_perfil']) ?>" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination-wrapper">
        <span>Mostrando página <?= $paginacao['pagina_atual'] ?> de <?= $paginacao['ultima_pagina'] ?></span>
        <div style="display: flex; gap: 10px;">
            <?php if ($paginacao['pagina_atual'] > 1): ?>
                <a class="page-nav-link" href="/backend/perfil/listar/<?= $paginacao['pagina_atual'] - 1 ?>"><i class="fas fa-chevron-left"></i> Anterior</a>
            <?php endif; ?>
            <?php if ($paginacao['pagina_atual'] < $paginacao['ultima_pagina']): ?>
                <a class="page-nav-link" href="/backend/perfil/listar/<?= $paginacao['pagina_atual'] + 1 ?>">Próximo <i class="fas fa-chevron-right"></i></a>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
        <div style="text-align: center; padding: 50px; color: var(--text-muted);">
            <i class="fas fa-user-circle" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i>
            <p>Nenhum perfil de usuário encontrado.</p>
        </div>
    <?php endif; ?>
</div>

 