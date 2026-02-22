<style>
    /* --- AJUSTES GERAIS DE LAYOUT --- */
    .page-wrapper {
        padding: 20px;
        width: 100%;
        box-sizing: border-box;
        background-color: var(--bg-main) !important;
        min-height: 100vh;
        color: var(--text-main);
    }

    .page-title {
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 5px;
        color: var(--text-main);
        text-transform: uppercase;
        letter-spacing: -1px;
    }

    .header-breadcrumb {
        color: var(--text-muted);
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border-color);
    }

    /* --- DASHBOARD CARDS PREMIUM --- */
    .dashboard-grid {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .stat-card {
        flex: 1;
        min-width: 220px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: 0.3s;
        box-shadow: var(--shadow-sm);
    }

    .stat-card:hover { 
        border-color: var(--accent); 
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .stat-icon {
        font-size: 30px;
        color: var(--accent);
        background: rgba(242, 204, 125, 0.1);
        width: 55px;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }

    .stat-info h3 { margin: 0; font-size: 28px; color: var(--text-main); font-weight: 800; }
    .stat-info p { margin: 0; color: var(--text-muted); text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: 700; }

    /* --- ÁREA DE AÇÕES (BOTÃO + PESQUISA) --- */
    .actions-bar {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    /* Estilo da Barra de Pesquisa */
    .search-container {
        position: relative;
        flex: 1;
        max-width: 400px;
    }

    .search-container i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--accent);
    }

    .search-input {
        width: 100%;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        padding: 13px 15px 13px 45px;
        border-radius: 10px;
        color: var(--text-main);
        font-size: 14px;
        transition: 0.3s;
        outline: none;
        box-shadow: var(--shadow-sm);
    }

    .search-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(197, 160, 45, 0.1);
    }

    /* --- TABELA DE CLIENTES --- */
    .client-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
        color: var(--text-main);
    }

    .client-table thead th {
        color: var(--text-muted) !important;
        text-transform: uppercase;
        font-size: 11px;
        padding: 15px;
        letter-spacing: 1.5px;
        font-weight: 800;
    }

    .client-table tbody tr {
        background: var(--bg-card);
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    .client-table tbody tr:hover { 
        transform: scale(1.005);
        box-shadow: var(--shadow-md);
        border-color: var(--accent);
    }

    .client-table td { padding: 18px 15px !important; border: none; vertical-align: middle; }
    .client-table td:first-child { border-radius: 12px 0 0 12px; border-left: 1px solid var(--border-color); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); }
    .client-table td:last-child { border-radius: 0 12px 12px 0; border-right: 1px solid var(--border-color); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); }

    .id-column { font-family: monospace; color: var(--text-muted) !important; font-weight: bold; }
    .email-column { color: var(--text-muted) !important; }

    .badge-status { padding: 6px 14px; border-radius: 20px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge-active { background: rgba(40, 167, 69, 0.1); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); }
    .badge-inactive { background: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3); }

    .btn-action-small { padding: 8px 14px; border-radius: 8px; font-size: 11px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: 0.3s; margin: 0 2px; }
    .btn-view { background: var(--bg-main); color: #4dabf7; border: 1px solid #4dabf7; }
    .btn-view:hover { background: #4dabf7; color: #000; }
    .btn-edit { background: var(--bg-main); color: var(--accent); border: 1px solid var(--accent); }
    .btn-edit:hover { background: var(--accent); color: #000; }
    .btn-toggle { background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: #dc3545; }
    .btn-toggle:hover { background: #dc3545; color: #fff; transform: scale(1.05); box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2); }
    .btn-activate { background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.3); color: #28a745; }
    .btn-activate:hover { background: #28a745 !important; color: #fff !important; transform: scale(1.05); box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2); }

    .client-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: 2px solid var(--border-color);
        object-fit: cover;
        box-shadow: var(--shadow-sm);
    }

    .tr-inativo td { background: rgba(220, 53, 69, 0.08) !important; }
    .tr-inativo td:first-child { border-left: 4px solid #dc3545 !important; }
</style>
</style>

<div class="page-wrapper">
    <h3 class="page-title"><i class="fas fa-handshake" style="color: #f2cc7d;"></i> Gerenciar Clientes</h3>

    <header class="header-breadcrumb">
        <h5><b><i class="fas fa-tachometer-alt"></i> Painel de Controle - Koketsu</b></h5>
    </header>

    <div class="dashboard-grid">
        <div class="stat-card" style="border-left: 4px solid #f2cc7d;">
            <div class="stat-info">
                <h3><?php echo $total_clientes; ?></h3>
                <p>Clientes Totais</p>
            </div>
            <div class="stat-icon"><i class="fas fa-users"></i></div>
        </div>
        
        <div class="stat-card" style="border-left: 4px solid #4caf50;">
            <div class="stat-info">
                <?php
                $ativos = 0;
                foreach($clientes as $c) if(empty($c['excluido_em'])) $ativos++;
                ?>
                <h3><?php echo $ativos; ?></h3>
                <p>Ativos na Página</p>
            </div>
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        </div>

        <div class="stat-card" style="border-left: 4px solid #e63946;">
            <div class="stat-info">
                <?php
                $inativos = 0;
                foreach($clientes as $c) if(!empty($c['excluido_em'])) $inativos++;
                ?>
                <h3><?php echo $inativos; ?></h3>
                <p>Inativos na Página</p>
            </div>
            <div class="stat-icon"><i class="fas fa-user-times"></i></div>
        </div>
    </div>

    <div class="actions-bar">
        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" id="clientInput" onkeyup="filterClients()" placeholder="Buscar cliente por nome ou email..." class="search-input">
        </div>
    </div>

    <main>
        <table class="client-table" id="clientTable">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th style="width: 60px;">Foto</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Cadastro</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($clientes) > 0): ?>
                    <?php foreach ($clientes as $cliente): ?>
                    <?php $is_inativo = !empty($cliente['excluido_em']);?>
                    
                    <tr class="<?= $is_inativo ? 'tr-inativo' : '' ?>"> 
                        <td class="id-column">#<?= $cliente['id_usuarios'] ?></td>
                        <td>
                            <?php
                            $foto = !empty($cliente['foto_usuarios']) ? $cliente['foto_usuarios'] : '/img/logoperf.jpg';
                            if (!filter_var($foto, FILTER_VALIDATE_URL) && strpos($foto, '/img/') === false) {
                                $foto = '/backend/upload/' . $foto;
                            }
                            ?>
                            <img src="<?= htmlspecialchars($foto) ?>" class="client-avatar" onerror="this.src='/img/logoperf.jpg'">
                        </td>
                        <td class="client-name" style="font-weight: 700; color: var(--text-main); font-size: 1.05em;">
                            <?= htmlspecialchars($cliente['nome_usuarios']) ?>
                        </td>
                        <td class="client-email"><?= htmlspecialchars($cliente['email_usuarios']) ?></td>
                        <td class="client-phone">
                            <?php if (!empty($cliente['telefone_perfil'])): ?>
                                <a href="https://wa.me/<?= preg_replace('/\D/', '', $cliente['telefone_perfil']) ?>" target="_blank" style="color: #25d366; text-decoration: none;">
                                    <?php 
                                        $tel = preg_replace('/\D/', '', $cliente['telefone_perfil']);
                                        if (strlen($tel) == 11) {
                                            $formatted = '(' . substr($tel, 0, 2) . ') ' . substr($tel, 2, 1) . ' ' . substr($tel, 3, 4) . '-' . substr($tel, 7);
                                        } elseif (strlen($tel) == 10) {
                                            $formatted = '(' . substr($tel, 0, 2) . ') ' . substr($tel, 2, 4) . '-' . substr($tel, 6);
                                        } else {
                                            $formatted = $cliente['telefone_perfil'];
                                        }
                                    ?>
                                    <i class="fab fa-whatsapp"></i> <?= htmlspecialchars($formatted) ?>
                                </a>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">Não inf.</span>
                            <?php endif; ?>
                        </td>
                        <td style="color: var(--text-muted); font-size: 13px;">
                            <?= date('d/m/Y', strtotime($cliente['criado_em'])) ?>
                        </td>
                        
                        <td style="text-align: center;">
                            <?php if ($is_inativo): ?>
                                <span class="badge-status badge-inactive">Inativo</span>
                            <?php else: ?>
                                <span class="badge-status badge-active">Ativo</span>
                            <?php endif; ?>
                        </td>
                        
                        <td style="text-align: center;">
                            <a href="/backend/usuario/editar/<?= $cliente['id_usuarios'] ?>" class="btn-action-small btn-edit">
                                <i class="fas fa-pencil-alt"></i> Editar
                            </a>
                            
                            <?php if ($is_inativo): ?>
                                <a href="/backend/usuario/ativar/<?= $cliente['id_usuarios'] ?>" class="btn-action-small btn-activate">
                                    <i class="fas fa-check"></i> Ativar
                                </a>
                            <?php else: ?>
                                <a href="/backend/usuario/excluir/<?= $cliente['id_usuarios'] ?>" class="btn-action-small btn-toggle">
                                    <i class="fas fa-power-off"></i> Inativar
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 50px; color: #666;">
                            <i class="fas fa-user-times" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                            Nenhum cliente encontrado.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>

<div style="height: 60px;"></div>

<script>
function filterClients() {
    var input, filter, table, tr, td_name, td_email, td_phone, i, nameValue, emailValue, phoneValue;
    input = document.getElementById("clientInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("clientTable");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {
        td_name = tr[i].getElementsByClassName("client-name")[0];
        td_email = tr[i].getElementsByClassName("client-email")[0];
        td_phone = tr[i].getElementsByClassName("client-phone")[0];
        
        if (td_name || td_email || td_phone) {
            nameValue = td_name ? (td_name.textContent || td_name.innerText) : "";
            emailValue = td_email ? (td_email.textContent || td_email.innerText) : "";
            phoneValue = td_phone ? (td_phone.textContent || td_phone.innerText) : "";
            
            if (nameValue.toUpperCase().indexOf(filter) > -1 || 
                emailValue.toUpperCase().indexOf(filter) > -1 ||
                phoneValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>
