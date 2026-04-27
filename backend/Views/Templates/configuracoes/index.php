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

        <hr class="settings-divider">

        <!-- ✅ SEÇÃO WHATSAPP -->
        <section class="settings-section whatsapp-section">
            <div class="section-title" style="color: #25D366;">
                <i class="fa fa-comment-dots"></i> WHATSAPP DE ATENDIMENTO
                <span class="badge-new" style="background:#25D366;">VENDAS</span>
            </div>
            <p class="setting-desc">
                Número para onde os pedidos do carrinho serão enviados. Use o formato internacional (sem espaços ou traços).
            </p>

            <div class="whatsapp-config-grid">
                <!-- Input do Número -->
                <div class="whatsapp-input-group">
                    <label class="whatsapp-label">
                        <i class="fa fa-mobile-alt"></i> Número com DDD e DDI (55 + DDD + número)
                    </label>
                    <div class="whatsapp-input-wrapper">
                        <span class="whatsapp-flag">🇧🇷 +</span>
                        <input
                            type="text"
                            id="whatsapp-numero-input"
                            class="k-input whatsapp-input"
                            placeholder="5511985477260"
                            value="<?= htmlspecialchars($whatsappNumero ?? '5511985477260') ?>"
                            maxlength="13"
                        >
                    </div>
                    <p class="whatsapp-hint">Exemplo: <strong>5511985477260</strong> → DDI (55) + DDD (11) + Número (985477260)</p>
                </div>

                <!-- Toggle Ativo/Inativo -->
                <div class="whatsapp-toggle-group">
                    <label class="whatsapp-label"><i class="fa fa-power-off"></i> Status do Botão</label>
                    <div class="setting-item" style="border:0; padding: 10px 0;">
                        <div class="setting-info">
                            <h4 id="whatsapp-status-label"><?= ($whatsappAtivo ?? true) ? 'Ativo — clientes podem finalizar via WhatsApp' : 'Inativo — botão WhatsApp oculto' ?></h4>
                            <p class="text-muted">Desative para esconder o botão no carrinho.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="whatsapp-ativo-toggle" <?= ($whatsappAtivo ?? true) ? 'checked' : '' ?> onchange="updateWhatsappStatusLabel(this.checked)">
                            <span class="slider round" style="background-color:<?= ($whatsappAtivo ?? true) ? '#25D366' : '#333' ?>; --wpp-color:#25D366;"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Preview da mensagem -->
            <div class="whatsapp-preview-box">
                <div class="whatsapp-preview-header">
                    <i class="fa fa-eye"></i> Prévia da mensagem que o cliente enviará
                </div>
                <div class="whatsapp-preview-msg">
                    Olá! Gostaria de confirmar meu pedido na Koketsu Grife:%0A%0A*Camiseta Oversized Preta - M x1 → R$ 89,90*%0A%0A*Total: R$ 89,90*%0A%0APedido gerado em <?= date('d/m/Y H:i') ?>
                </div>
            </div>

            <!-- Botões de ação -->
            <div class="whatsapp-actions">
                <button class="btn-test-whatsapp" onclick="testarWhatsapp()">
                    <i class="fa fa-paper-plane"></i> Testar número
                </button>
                <button class="btn-save-whatsapp" id="btn-save-whatsapp" onclick="salvarWhatsapp()">
                    <i class="fa fa-save"></i> SALVAR NÚMERO
                </button>
            </div>
            <div id="whatsapp-save-status" class="save-indicator"></div>
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

    // ─── Funções de WhatsApp ──────────────────────────────────────────────────
    function updateWhatsappStatusLabel(isAtivo) {
        const label = document.getElementById('whatsapp-status-label');
        const slider = document.querySelector('#whatsapp-ativo-toggle + .slider');
        if (label) {
            label.textContent = isAtivo
                ? 'Ativo — clientes podem finalizar via WhatsApp'
                : 'Inativo — botão WhatsApp oculto';
        }
        if (slider) {
            slider.style.backgroundColor = isAtivo ? '#25D366' : '#333';
        }
    }

    function testarWhatsapp() {
        const numero = document.getElementById('whatsapp-numero-input').value.replace(/\D/g, '');
        if (!numero || numero.length < 10) {
            alert('Informe um número válido antes de testar.');
            return;
        }
        const msg = encodeURIComponent(
            'Olá! Estou testando o número de atendimento da Koketsu Grife. ✅'
        );
        window.open(`https://wa.me/${numero}?text=${msg}`, '_blank');
    }

    async function salvarWhatsapp() {
        const statusEl = document.getElementById('whatsapp-save-status');
        const btn = document.getElementById('btn-save-whatsapp');
        const numero = document.getElementById('whatsapp-numero-input').value.replace(/\D/g, '');
        const ativo = document.getElementById('whatsapp-ativo-toggle').checked;

        if (!numero || numero.length < 10 || numero.length > 13) {
            statusEl.innerHTML = '<i class="fa fa-times"></i> Número inválido! Use DDI+DDD+número (ex: 5511985477260)';
            statusEl.style.opacity = 1;
            statusEl.style.color = '#e74c3c';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Salvando...';
        statusEl.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Salvando número...';
        statusEl.style.opacity = 1;
        statusEl.style.color = 'var(--k-gold)';

        try {
            const res = await fetch('/configuracoes/whatsapp', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ whatsapp_numero: numero, whatsapp_ativo: ativo })
            });
            const data = await res.json();

            if (data.success) {
                statusEl.innerHTML = '<i class="fa fa-check"></i> Número salvo com sucesso! ✅';
                statusEl.style.color = '#25D366';
                btn.innerHTML = '<i class="fa fa-check"></i> SALVO!';
                setTimeout(() => {
                    statusEl.style.opacity = 0;
                    btn.innerHTML = '<i class="fa fa-save"></i> SALVAR NÚMERO';
                    btn.disabled = false;
                }, 3000);
            } else {
                statusEl.innerHTML = '<i class="fa fa-times"></i> Erro: ' + (data.message || 'Tente novamente.');
                statusEl.style.color = '#e74c3c';
                btn.innerHTML = '<i class="fa fa-save"></i> SALVAR NÚMERO';
                btn.disabled = false;
            }
        } catch (err) {
            statusEl.innerHTML = '<i class="fa fa-times"></i> Erro de conexão.';
            statusEl.style.color = '#e74c3c';
            btn.innerHTML = '<i class="fa fa-save"></i> SALVAR NÚMERO';
            btn.disabled = false;
        }
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

    /* ─── WhatsApp Section ────────────────────────────────────────────── */
    .whatsapp-section {
        background: linear-gradient(135deg, rgba(37, 211, 102, 0.04) 0%, transparent 60%);
        border: 1px solid rgba(37, 211, 102, 0.15);
        border-radius: 16px;
        padding: 30px;
        margin-top: 0;
    }
    .whatsapp-config-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 25px;
    }
    .whatsapp-label {
        display: block;
        font-size: 0.75rem;
        color: var(--muted-custom);
        margin-bottom: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .whatsapp-input-wrapper {
        display: flex;
        align-items: center;
        background: rgba(37, 211, 102, 0.05);
        border: 1px solid rgba(37, 211, 102, 0.3);
        border-radius: 12px;
        overflow: hidden;
        transition: border-color 0.3s;
    }
    .whatsapp-input-wrapper:focus-within {
        border-color: #25D366;
        box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.1);
    }
    .whatsapp-flag {
        padding: 14px 16px;
        font-size: 1rem;
        background: rgba(37, 211, 102, 0.1);
        border-right: 1px solid rgba(37, 211, 102, 0.2);
        user-select: none;
        white-space: nowrap;
    }
    .whatsapp-input {
        background: transparent !important;
        border: none !important;
        border-radius: 0 !important;
        padding: 14px 16px !important;
        font-size: 1rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.05em !important;
        color: #25D366 !important;
        flex: 1;
        outline: none;
        font-family: 'Courier New', monospace;
    }
    .whatsapp-hint {
        font-size: 0.72rem;
        color: var(--muted-custom);
        margin-top: 8px;
        line-height: 1.5;
    }
    .whatsapp-hint strong { color: #25D366; }

    .whatsapp-preview-box {
        background: rgba(37, 211, 102, 0.04);
        border: 1px solid rgba(37, 211, 102, 0.12);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .whatsapp-preview-header {
        font-size: 0.75rem;
        color: #25D366;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .whatsapp-preview-msg {
        font-family: 'Courier New', monospace;
        font-size: 0.8rem;
        color: var(--muted-custom);
        line-height: 1.8;
        word-break: break-all;
    }

    .whatsapp-actions {
        display: flex;
        gap: 12px;
        margin-top: 5px;
    }
    .btn-test-whatsapp {
        padding: 12px 20px;
        background: transparent;
        border: 1px solid rgba(37, 211, 102, 0.4);
        border-radius: 10px;
        color: #25D366;
        font-weight: 700;
        font-size: 0.8rem;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: 'Montserrat', sans-serif;
    }
    .btn-test-whatsapp:hover {
        background: rgba(37, 211, 102, 0.1);
        border-color: #25D366;
    }
    .btn-save-whatsapp {
        padding: 12px 28px;
        background: #25D366;
        border: none;
        border-radius: 10px;
        color: #000;
        font-weight: 800;
        font-size: 0.85rem;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: 'Montserrat', sans-serif;
        letter-spacing: 0.05em;
        flex: 1;
        justify-content: center;
    }
    .btn-save-whatsapp:hover { background: #1ebe5d; transform: translateY(-1px); }
    .btn-save-whatsapp:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    .k-input {
        width: 100%;
        padding: 12px 16px;
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--border-custom);
        border-radius: 10px;
        color: var(--text-color-custom);
        font-family: 'Montserrat', sans-serif;
        font-size: 0.9rem;
        transition: border-color 0.3s;
    }
    .k-input:focus { border-color: var(--k-gold); outline: none; }

    @media (max-width: 700px) {
        .whatsapp-config-grid { grid-template-columns: 1fr; }
        .whatsapp-actions { flex-direction: column; }
    }
</style>
