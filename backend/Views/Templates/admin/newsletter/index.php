<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    body { background-color: var(--bg-main) !important; margin: 0; font-family: 'Inter', sans-serif; color: var(--text-main); }
    .page-wrapper { padding: 20px; width: 100%; box-sizing: border-box; min-height: 100vh; }
    .page-title { font-size: 26px; font-weight: 800; margin-bottom: 5px; color: var(--text-main); text-transform: uppercase; letter-spacing: -1px; }
    .header-breadcrumb { color: var(--text-muted); margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid var(--border-color); }
    
    .dashboard-grid { display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap; }
    .stat-card { flex: 1; min-width: 220px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 24px; display: flex; align-items: center; justify-content: space-between; transition: all 0.3s ease; box-shadow: var(--shadow-sm); }
    .stat-icon { font-size: 28px; color: var(--accent); background: rgba(242, 204, 125, 0.1); width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; border-radius: 12px; }
    .stat-info h3 { margin: 0; font-size: 28px; color: var(--text-main); font-weight: 800; }
    .stat-info p { margin: 0; color: var(--text-muted); text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: 700; }

    .actions-bar { display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 25px; flex-wrap: wrap; }
    .btn-main-action { display: inline-flex; align-items: center; gap: 10px; background-color: var(--accent) !important; color: #000 !important; padding: 12px 24px; border-radius: 10px; font-weight: 700; text-transform: uppercase; font-size: 13px; text-decoration: none; transition: 0.3s; border: none; }
    .btn-main-action:hover { background-color: var(--text-main) !important; color: var(--bg-main) !important; transform: scale(1.02); }

    .newsletter-table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
    .newsletter-table thead th { color: var(--text-muted) !important; text-transform: uppercase; font-size: 11px; padding: 10px 15px; letter-spacing: 1.5px; font-weight: 800; }
    .newsletter-table tbody tr { background: var(--bg-card); transition: all 0.2s ease; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); }
    .newsletter-table td { padding: 16px 15px !important; border: none; vertical-align: middle; color: var(--text-main); }
    .newsletter-table td:first-child { border-radius: 12px 0 0 12px; }
    .newsletter-table td:last-child { border-radius: 0 12px 12px 0; }

    .btn-danger-small { background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: #dc3545; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; }
    .btn-danger-small:hover { background: #dc3545; color: #fff; }

    /* Modal / Form Disparo */
    .promo-form-container { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 25px; margin-top: 40px; }
    .promo-form-container h4 { color: var(--accent); text-transform: uppercase; font-weight: 800; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; color: var(--text-muted); font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px; }
    .form-control-k { width: 100%; background: var(--bg-main); border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; color: var(--text-main); outline: none; }
    .form-control-k:focus { border-color: var(--accent); }
    textarea.form-control-k { min-height: 150px; resize: vertical; }
</style>

<div class="page-wrapper">
    <h3 class="page-title"><i class="fas fa-envelope-open-text" style="color: #f2cc7d;"></i> Gestão de Newsletter</h3>

    <header class="header-breadcrumb">
        <h5><b><i class="fas fa-tachometer-alt"></i> Painel de Controle - Koketsu</b></h5>
    </header>

    <div class="dashboard-grid">
        <div class="stat-card" style="border-left: 4px solid #f2cc7d;">
            <div class="stat-info"><h3><?= count($inscritos); ?></h3><p>Inscritos Ativos</p></div>
            <div class="stat-icon"><i class="fas fa-user-check"></i></div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #f2cc7d;">
            <div class="stat-info"><h3>CSV</h3><p>Pronto para Exportar</p></div>
            <div class="stat-icon"><i class="fas fa-file-export"></i></div>
        </div>
    </div>

    <div class="actions-bar">
        <div class="search-container" style="flex:1; max-width: 400px; position: relative;">
            <i class="fas fa-search" style="position: absolute; left: 15px; top: 15px; color: #f2cc7d;"></i>
            <input type="text" id="newsInput" onkeyup="filterNews()" placeholder="Filtrar e-mails..." class="form-control-k" style="padding-left: 45px;">
        </div>
        <a href="/backend/admin/newsletter/exportar" class="btn-main-action">
            <i class="fas fa-download"></i> Exportar Lista (CSV)
        </a>
    </div>

    <main>
        <div class="w3-responsive">
            <table class="newsletter-table" id="newsTable">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>E-mail do Cliente</th>
                        <th>Data da Inscrição</th>
                        <th style="text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscritos as $news): ?>
                    <tr class="news-row">
                        <td style="font-family: monospace; color: #888;">#<?= $news['id_newsletter'] ?></td>
                        <td class="email-text" style="font-weight: 700;"><?= htmlspecialchars($news['email_newsletter']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($news['data_inscricao'])) ?></td>
                        <td style="text-align: center;">
                            <a href="/backend/admin/newsletter/excluir/<?= $news['id_newsletter'] ?>" 
                               class="btn-danger-small" 
                               onclick="return confirm('Tem certeza que deseja remover este e-mail da lista?')">
                                <i class="fas fa-trash"></i> Remover
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($inscritos)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 40px !important;">Nenhum e-mail capturado ainda.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Formulário de Disparo -->
        <div class="promo-form-container">
            <h4><i class="fas fa-paper-plane"></i> Enviar Promoção / Novidade</h4>
            <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 25px;">
                Esta ferramenta enviará o e-mail abaixo para **todos os <?= count($inscritos) ?>** inscritos ativos da lista.
            </p>
            
            <form action="/backend/admin/newsletter/enviar" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Assunto do E-mail</label>
                    <input type="text" name="assunto" class="form-control-k" placeholder="Ex: Cupom de 20% OFF - Coleção 2026 🚀" required>
                </div>
                <div class="form-group">
                    <label>Banner da Promoção (Opcional)</label>
                    <input type="file" name="imagem_promo" class="form-control-k" accept="image/*">
                    <small style="color: #666; font-size: 11px;">Imagens sugeridas: 600x400px. Tamanho máx: 2MB.</small>
                </div>
                <div class="form-group">
                    <label>Conteúdo da Mensagem</label>
                    <textarea name="mensagem" class="form-control-k" placeholder="Escreva aqui a novidade para seus clientes..." required></textarea>
                </div>
                <div style="text-align: right;">
                    <button type="submit" class="btn-main-action" onclick="return confirm('Confirmar disparo para toda a base de inscritos?')">
                        <i class="fas fa-rocket"></i> Disparar para Todos
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
function filterNews() {
    const filter = document.getElementById("newsInput").value.toUpperCase();
    const rows = document.querySelectorAll(".news-row");

    rows.forEach(row => {
        const email = row.querySelector(".email-text").textContent.toUpperCase();
        row.style.display = email.includes(filter) ? "" : "none";
    });
}
</script>
