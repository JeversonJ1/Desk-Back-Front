<div class="settings-page">
    <div class="settings-card glass-card">
        <header class="settings-header">
            <div class="header-content">
                <div>
                    <h2><i class="fa fa-cog k-icon-spin"></i> PREFERÊNCIAS & AJUSTES</h2>
                    <p class="text-muted">Personalize sua experiência exclusiva Koketsu</p>
                </div>
                <a href="/backend/<?= $usuarioTipo === 'admin' ? 'admin' : 'cliente' ?>/dashboard" class="btn-back-dash">
                    <i class="fa fa-chevron-left"></i> VOLTAR AO PAINEL
                </a>
            </div>
        </header>

        <div class="settings-grid">
            <!-- Coluna Esquerda -->
            <div class="left-column">
                <!-- Aparência -->
                <section class="settings-section">
                    <div class="section-title">
                        <i class="fa fa-paint-brush"></i> APARÊNCIA VISUAL
                    </div>
                    <div class="theme-options">
                        <div class="theme-option" onclick="setTheme('dark')" id="theme-dark">
                            <div class="theme-preview dark-preview"></div>
                            <span>Dark Luxury</span>
                        </div>
                        <div class="theme-option" onclick="setTheme('light')" id="theme-light">
                            <div class="theme-preview light-preview"></div>
                            <span>Light Mode</span>
                        </div>
                        <div class="theme-option" onclick="setTheme('system')" id="theme-system">
                            <div class="theme-preview system-preview"></div>
                            <span>Sistema</span>
                        </div>
                    </div>
                </section>

                <!-- Meu Manequim (Novo) -->
                <?php if ($usuarioTipo !== 'admin'): ?>
                <section class="settings-section">
                    <div class="section-title">
                        <i class="fa fa-ruler-combined"></i> MEU MANEQUIM
                        <span class="badge-new">NOVO</span>
                    </div>
                    <div class="setting-desc">
                        Defina seus tamanhos para recomendações personalizadas.
                    </div>
                    <div class="sizes-grid">
                        <div class="size-group">
                            <label><i class="fa fa-tshirt"></i> Camisetas</label>
                            <select class="k-select" name="tamanho_camiseta" onchange="salvarPreferencias()">
                                <option value="">Selecione</option>
                                <?php foreach(['PP','P','M','G','GG'] as $t): ?>
                                    <option value="<?= $t ?>" <?= ($preferencias['tamanho_camiseta'] ?? '') == $t ? 'selected' : '' ?>><?= $t ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="size-group">
                            <label><i class="fa fa-user-friends"></i> Calças</label>
                            <select class="k-select" name="tamanho_calca" onchange="salvarPreferencias()">
                                <option value="">Selecione</option>
                                <?php foreach(['36','38','40','42','44','46'] as $t): ?>
                                    <option value="<?= $t ?>" <?= ($preferencias['tamanho_calca'] ?? '') == $t ? 'selected' : '' ?>><?= $t ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="size-group">
                            <label><i class="fa fa-shoe-prints"></i> Calçados</label>
                            <select class="k-select" name="tamanho_calcado" onchange="salvarPreferencias()">
                                <option value="">Selecione</option>
                                <?php foreach(['38','39','40','41','42','43'] as $t): ?>
                                    <option value="<?= $t ?>" <?= ($preferencias['tamanho_calcado'] ?? '') == $t ? 'selected' : '' ?>><?= $t ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div id="save-status" class="save-indicator"></div>
                </section>
                <?php endif; ?>
            </div>

            <!-- Coluna Direita -->
            <div class="right-column">
                <!-- Notificações -->
                <section class="settings-section">
                    <div class="section-title">
                        <i class="fa fa-bell"></i> NOTIFICAÇÕES INTELIGENTES
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Status de Pedidos</h4>
                            <p class="text-muted">E-mail e alertas no painel.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="notif_pedidos" onchange="salvarPreferencias()" <?= !empty($preferencias['notif_pedidos']) ? 'checked' : '' ?>>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Ofertas Exclusivas</h4>
                            <p class="text-muted">Acesso antecipado a lançamentos.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="notif_ofertas" onchange="salvarPreferencias()" <?= !empty($preferencias['notif_ofertas']) ? 'checked' : '' ?>>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>WhatsApp Updates</h4>
                            <p class="text-muted">Receber rastreio pelo WhatsApp.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="notif_whatsapp" onchange="salvarPreferencias()" <?= !empty($preferencias['notif_whatsapp']) ? 'checked' : '' ?>>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </section>

                <hr class="settings-divider">

                <!-- Segurança e Privacidade -->
                <section class="settings-section">
                    <div class="section-title">
                        <i class="fa fa-shield-alt"></i> SEGURANÇA & PRIVACIDADE
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Autenticação em Dois Fatores (2FA)</h4>
                            <p class="text-muted">Camada extra de proteção.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="dois_fatores_ativo" onchange="salvarPreferencias()" <?= !empty($preferencias['dois_fatores_ativo']) ? 'checked' : '' ?>>
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div class="action-list">
                        <a href="/backend/cliente/meu-perfil/<?= $usuarioId ?? 0 ?>" class="action-link">
                            <i class="fa fa-key"></i> Alterar Senha de Acesso
                        </a>
                        <a href="/configuracoes/exportar" class="action-link" target="_blank">
                            <i class="fa fa-file-export"></i> Solicitar Meus Dados (LGPD)
                        </a>
                        <a href="/configuracoes/excluir" class="action-link danger" onclick="return confirm('ATENÇÃO: Essa ação excluirá permanentemente sua conta e histórico. Tem certeza?')">
                            <i class="fa fa-user-slash"></i> Excluir Minha Conta
                        </a>
                    </div>
                </section>
            </div>
        </div>

        <?php if ($usuarioTipo == 'admin'): ?>
        <hr class="settings-divider">
        <section class="settings-section glass-danger">
            <div class="section-title text-danger">
                <i class="fa fa-lock"></i> ÁREA ADMINISTRATIVA
            </div>
            <div class="setting-item">
                <div class="setting-info">
                    <h4>Modo Manutenção</h4>
                    <p class="text-muted">Bloquear acesso de clientes.</p>
                </div>
                <label class="switch">
                    <input type="checkbox" id="maintenance-toggle" <?= $manutencaoAtiva ? 'checked' : '' ?> onchange="toggleMaintenance(this.checked)">
                    <span class="slider round"></span>
                </label>
            </div>
        </section>
        <?php endif; ?>
    </div>
</div>

<script>
    function salvarPreferencias() {
        const statusEl = document.getElementById('save-status');
        statusEl.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Salvando...';
        statusEl.style.opacity = 1;

        const data = {
            tamanho_camiseta: document.getElementsByName('tamanho_camiseta')[0].value,
            tamanho_calca: document.getElementsByName('tamanho_calca')[0].value,
            tamanho_calcado: document.getElementsByName('tamanho_calcado')[0].value,
            notif_pedidos: document.getElementsByName('notif_pedidos')[0].checked,
            notif_ofertas: document.getElementsByName('notif_ofertas')[0].checked,
            notif_whatsapp: document.getElementsByName('notif_whatsapp')[0].checked,
            dois_fatores_ativo: document.getElementsByName('dois_fatores_ativo')[0].checked
        };

        fetch('/backend/configuracoes/salvar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(res => {
            if(res.success) {
                statusEl.innerHTML = '<i class="fa fa-check"></i> Salvo!';
                setTimeout(() => { statusEl.style.opacity = 0; }, 2000);
            } else {
                statusEl.innerHTML = '<i class="fa fa-times"></i> Erro!';
            }
        })
        .catch(err => {
            console.error(err);
            statusEl.innerHTML = 'Erro de conexão';
        });
    }

    function toggleMaintenance(status) {
        fetch('/backend/configuracoes/manutencao', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Erro ao atualizar modo manutenção: ' + data.message);
                document.getElementById('maintenance-toggle').checked = !status;
            }
        });
    }

    function setTheme(theme) {
        localStorage.setItem('theme', theme);
        applyTheme();
    }

    function applyTheme() {
        const theme = localStorage.getItem('theme') || 'dark';
        const body = document.body;
        
        body.classList.remove('theme-light', 'theme-dark');
        document.querySelectorAll('.theme-option').forEach(opt => opt.classList.remove('active'));

        if (theme === 'light') {
            body.classList.add('theme-light');
            document.getElementById('theme-light').classList.add('active');
        } else if (theme === 'dark') {
            body.classList.add('theme-dark');
            document.getElementById('theme-dark').classList.add('active');
        } else {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
                body.classList.add('theme-light');
            } else {
                body.classList.add('theme-dark');
            }
            document.getElementById('theme-system').classList.add('active');
        }
        window.dispatchEvent(new Event('themeChanged'));
    }

    document.addEventListener('DOMContentLoaded', applyTheme);
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&family=Montserrat:wght@300;400;600&display=swap');

    :root {
        --k-gold: #f2cc7d;
        --k-gold-dark: #b8860b;
        /* Themes Linked */
        --bg-body-custom: var(--bg-main);
        --bg-card-custom: var(--bg-card);
        --text-color-custom: var(--text-main);
        --border-custom: var(--border-color);
        --muted-custom: var(--text-muted);
        --form-bg: rgba(255,255,255,0.05);
    }
    
    .theme-light .k-select {
        background-color: #fff !important;
        color: #000 !important;
        border-color: #ccc !important;
    }

    body { font-family: 'Montserrat', sans-serif; }

    .settings-page {
        padding: 40px;
        min-height: 100vh;
        background: var(--bg-body-custom);
        display: flex; justify-content: center;
        color: var(--text-color-custom);
    }

    .settings-card {
        width: 100%;
        max-width: 1000px;
        padding: 40px;
        border-radius: 20px;
        background: var(--bg-card-custom);
        border: 1px solid var(--border-custom);
        box-shadow: 0 30px 60px rgba(0,0,0,0.6);
        color: var(--text-color-custom);
    }

    .settings-header { margin-bottom: 40px; padding-bottom: 20px; border-bottom: 1px solid var(--border-custom); }
    .header-content { display: flex; justify-content: space-between; align-items: center; }
    
    .settings-header h2 { 
        font-family: 'Oswald', sans-serif; 
        font-size: 1.8rem; 
        color: var(--text-color-custom); 
        letter-spacing: 2px; 
        margin: 0; 
        display: flex; align-items: center; gap: 15px;
    }

    .settings-header p { color: var(--muted-custom); font-size: 0.9rem; margin-top: 5px; }

    .btn-back-dash {
        padding: 12px 20px;
        background: transparent;
        border: 1px solid var(--border-custom);
        border-radius: 10px;
        color: var(--text-color-custom);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.8rem;
        transition: 0.3s;
        display: flex; align-items: center; gap: 10px;
    }
    .btn-back-dash:hover { background: var(--k-gold); color: #000; border-color: var(--k-gold); }

    /* Grid Layout */
    .settings-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
    }

    .section-title {
        font-family: 'Oswald', sans-serif;
        font-size: 1rem;
        color: var(--k-gold);
        letter-spacing: 2px;
        margin-bottom: 25px;
        display: flex; align-items: center; gap: 10px;
    }

    /* Appearance */
    .theme-options { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px; }
    .theme-option { 
        background: var(--bg-card-custom); 
        padding: 15px; 
        border-radius: 12px; 
        text-align: center; 
        cursor: pointer; 
        border: 1px solid var(--border-custom); 
        transition: 0.3s;
        color: var(--text-color-custom);
    }
    .theme-option:hover { border-color: var(--k-gold); transform: translateY(-3px); }
    .theme-option.active { background: rgba(242, 204, 125, 0.1); border-color: var(--k-gold); }

    .theme-preview { height: 50px; border-radius: 8px; margin-bottom: 10px; border: 1px solid var(--border-custom); }
    .dark-preview { background: #111; }
    .light-preview { background: #f0f0f0; }
    .system-preview { background: linear-gradient(135deg, #111 50%, #f0f0f0 50%); }

    /* Sizes */
    .sizes-grid { display: flex; gap: 15px; flex-wrap: wrap; }
    .size-group { flex: 1; }
    .size-group label { display: block; font-size: 0.75rem; color: var(--muted-custom); margin-bottom: 8px; font-weight: 600; }
    .k-select { width: 100%; padding: 12px; background: var(--bg-main); border: 1px solid var(--border-custom); color: var(--text-color-custom); border-radius: 8px; font-family: 'Montserrat', sans-serif; cursor: pointer; }
    .k-select:focus { border-color: var(--k-gold); outline: none; }

    .badge-new { background: var(--k-gold); color: #000; padding: 2px 6px; border-radius: 4px; font-size: 0.6rem; font-weight: 800; margin-left: 10px; }
    
    .save-indicator { 
        font-size: 0.8rem; color: var(--k-gold); margin-top: 10px; text-align: right; height: 20px; opacity: 0; transition: opacity 0.5s; 
    }

    /* Settings Items */
    .setting-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid var(--border-custom); }
    .setting-info h4 { margin: 0; font-size: 0.95rem; color: var(--text-color-custom); }
    .setting-info p { margin: 4px 0 0; font-size: 0.75rem; color: var(--muted-custom); }
    .setting-desc { font-size: 0.8rem; color: var(--muted-custom); margin-bottom: 20px; }

    /* Switch */
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #333; transition: .4s; border-radius: 34px; border:1px solid var(--border-custom); }
    .slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: var(--k-gold); border-color: var(--k-gold); }
    input:focus + .slider { box-shadow: 0 0 1px var(--k-gold); }
    input:checked + .slider:before { transform: translateX(20px); }

    /* Action Links */
    .action-list { display: flex; flex-direction: column; gap: 15px; margin-top: 15px; }
    .action-link { 
        display: flex; align-items: center; gap: 12px; 
        color: var(--muted-custom); text-decoration: none; font-size: 0.9rem; font-weight: 500;
        transition: 0.2s; padding: 10px; border-radius: 8px;
    }
    .action-link:hover { background: rgba(255,255,255,0.03); color: var(--text-color-custom); }
    .action-link.danger { color: #e74c3c; }
    .action-link.danger:hover { background: rgba(231, 76, 60, 0.1); }

    .k-icon-spin { transition: transform 0.5s; }
    .settings-header:hover .k-icon-spin { transform: rotate(90deg); }

    .settings-divider { border: 0; border-top: 1px solid var(--border-custom); margin: 30px 0; }

    @media (max-width: 850px) {
        .settings-grid { grid-template-columns: 1fr; gap: 30px; }
    }
</style>
