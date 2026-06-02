<div class="settings-page">
    <div class="settings-container">
        <!-- HEADER DA PÁGINA -->
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

        <!-- GRID DE CONFIGURAÇÕES -->
        <div class="settings-grid">
            
            <!-- CARD 1: APARÊNCIA VISUAL -->
            <div class="settings-card card-aparencia">
                <div class="card-bar"></div>
                <section class="settings-section">
                    <div class="section-title">
                        <i class="fa fa-paint-brush"></i> APARÊNCIA VISUAL
                    </div>
                    <p class="setting-desc">Personalize o tema de cores do seu painel administrativo.</p>
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
            </div>

            <!-- CARD 2: MEU MANEQUIM (Clientes apenas) -->
            <?php if ($usuarioTipo !== 'admin'): ?>
            <div class="settings-card card-manequim">
                <div class="card-bar"></div>
                <section class="settings-section">
                    <div class="section-title">
                        <i class="fa fa-ruler-combined"></i> MEU MANEQUIM
                        <span class="badge-new">NOVO</span>
                    </div>
                    <div class="setting-desc">
                        Defina seus tamanhos de roupas para obter recomendações sob medida.
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
            </div>
            <?php endif; ?>

            <!-- CARD 3: NOTIFICAÇÕES INTELIGENTES -->
            <div class="settings-card card-notificacoes">
                <div class="card-bar"></div>
                <section class="settings-section">
                    <div class="section-title">
                        <i class="fa fa-bell"></i> NOTIFICAÇÕES INTELIGENTES
                    </div>
                    <p class="setting-desc">Escolha quais atualizações e canais você deseja acompanhar.</p>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Status de Pedidos</h4>
                            <p class="text-muted">E-mail e alertas integrados no painel.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="notif_pedidos" onchange="salvarPreferencias()" <?= !empty($preferencias['notif_pedidos']) ? 'checked' : '' ?>>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Ofertas Exclusivas</h4>
                            <p class="text-muted">Acesso antecipado a lançamentos de drops.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="notif_ofertas" onchange="salvarPreferencias()" <?= !empty($preferencias['notif_ofertas']) ? 'checked' : '' ?>>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>WhatsApp Updates</h4>
                            <p class="text-muted">Receber código de rastreamento no WhatsApp.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="notif_whatsapp" onchange="salvarPreferencias()" <?= !empty($preferencias['notif_whatsapp']) ? 'checked' : '' ?>>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </section>
            </div>

            <!-- CARD 4: SEGURANÇA E PRIVACIDADE -->
            <div class="settings-card card-seguranca">
                <div class="card-bar"></div>
                <section class="settings-section">
                    <div class="section-title">
                        <i class="fa fa-shield-alt"></i> SEGURANÇA & PRIVACIDADE
                    </div>
                    <p class="setting-desc">Gerencie a segurança da sua conta e preferências da LGPD.</p>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Autenticação em Dois Fatores (2FA)</h4>
                            <p class="text-muted">Camada extra de proteção no seu login.</p>
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

            <!-- CARDS EXCLUSIVOS DO ADMINISTRADOR -->
            <?php if ($usuarioTipo == 'admin'): ?>
            
            <!-- CARD 5: MODO MANUTENÇÃO -->
            <div class="settings-card card-manutencao">
                <div class="card-bar"></div>
                <section class="settings-section">
                    <div class="section-title">
                        <i class="fa fa-lock"></i> ÁREA ADMINISTRATIVA
                    </div>
                    <p class="setting-desc">Controle o status de acesso público da loja.</p>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Modo Manutenção</h4>
                            <p class="text-muted">Bloquear temporariamente o acesso de clientes.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="maintenance-toggle" <?= $manutencaoAtiva ? 'checked' : '' ?> onchange="toggleMaintenance(this.checked)">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </section>
            </div>

            <!-- CARD 6: WHATSAPP DE ATENDIMENTO -->
            <div class="settings-card card-whatsapp">
                <div class="card-bar"></div>
                <section class="settings-section">
                    <div class="section-title">
                        <i class="fa fa-comment-dots"></i> WHATSAPP DE ATENDIMENTO
                        <span class="badge-new bg-success">VENDAS</span>
                    </div>
                    <p class="setting-desc">
                        Número oficial de vendas. Os pedidos gerados no carrinho serão direcionados para este WhatsApp.
                    </p>

                    <div class="whatsapp-config-grid">
                        <!-- Input do Número -->
                        <div class="whatsapp-input-group">
                            <label class="whatsapp-label">
                                <i class="fa fa-mobile-alt"></i> Número com DDI + DDD + Telefone
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
                            <p class="whatsapp-hint">Use apenas números. Exemplo: <strong>5511985477260</strong></p>
                        </div>

                        <!-- Toggle Ativo/Inativo -->
                        <div class="whatsapp-toggle-group">
                            <label class="whatsapp-label"><i class="fa fa-power-off"></i> Status do Botão</label>
                            <div class="setting-item" style="border:0; padding: 10px 0;">
                                <div class="setting-info">
                                    <h4 id="whatsapp-status-label"><?= ($whatsappAtivo ?? true) ? 'Ativo — clientes podem finalizar via WhatsApp' : 'Inativo — botão WhatsApp oculto' ?></h4>
                                    <p class="text-muted">Desative para ocultar o botão de checkout no carrinho.</p>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" id="whatsapp-ativo-toggle" <?= ($whatsappAtivo ?? true) ? 'checked' : '' ?> onchange="updateWhatsappStatusLabel(this.checked)">
                                    <span class="slider round"></span>
                                </label>
                              </div>
                          </div>
                      </div>

                      <!-- Preview Realista do Chat do WhatsApp -->
                      <div class="whatsapp-preview-wrapper">
                          <label class="whatsapp-label"><i class="fa fa-eye"></i> Simulação do Chat do Cliente</label>
                          <div class="wpp-mockup">
                              <div class="wpp-mockup-header">
                                  <div class="wpp-contact-info">
                                      <div class="wpp-avatar">
                                          <i class="fab fa-whatsapp"></i>
                                      </div>
                                      <div class="wpp-contact-details">
                                          <span class="wpp-contact-name">Koketsu Grife</span>
                                          <span class="wpp-contact-status">online</span>
                                      </div>
                                  </div>
                                  <div class="wpp-header-actions">
                                      <i class="fas fa-video"></i>
                                      <i class="fas fa-phone" style="margin-left: 15px;"></i>
                                      <i class="fas fa-ellipsis-v" style="margin-left: 15px;"></i>
                                  </div>
                              </div>
                              <div class="wpp-mockup-body">
                                  <div class="wpp-msg-bubble">
                                      <div class="wpp-msg-text">
                                          Olá! Gostaria de confirmar meu pedido na Koketsu Grife:<br><br>
                                          👕 <strong>Camiseta Oversized Preta</strong> - M x1 → <em>R$ 89,90</em><br><br>
                                          💰 <strong>Total: R$ 89,90</strong><br><br>
                                          🕒 Pedido gerado em <?= date('d/m/Y H:i') ?>
                                      </div>
                                      <div class="wpp-msg-time">
                                          <?= date('H:i') ?> <i class="fas fa-check-double" style="color: #53bdeb; margin-left: 3px;"></i>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- Botões de Ação do WhatsApp -->
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
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function salvarPreferencias() {
        const statusEl = document.getElementById('save-status');
        if (statusEl) {
            statusEl.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Salvando...';
            statusEl.style.opacity = 1;
        }

        const data = {
            tamanho_camiseta: document.getElementsByName('tamanho_camiseta')[0] ? document.getElementsByName('tamanho_camiseta')[0].value : '',
            tamanho_calca: document.getElementsByName('tamanho_calca')[0] ? document.getElementsByName('tamanho_calca')[0].value : '',
            tamanho_calcado: document.getElementsByName('tamanho_calcado')[0] ? document.getElementsByName('tamanho_calcado')[0].value : '',
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
                if (statusEl) {
                    statusEl.innerHTML = '<i class="fa fa-check"></i> Preferências salvas!';
                    statusEl.style.color = 'var(--accent)';
                    setTimeout(() => { statusEl.style.opacity = 0; }, 2000);
                }
            } else {
                if (statusEl) {
                    statusEl.innerHTML = '<i class="fa fa-times"></i> Erro ao salvar!';
                    statusEl.style.color = '#dc3545';
                }
            }
        })
        .catch(err => {
            console.error(err);
            if (statusEl) {
                statusEl.innerHTML = '<i class="fa fa-times"></i> Erro de conexão';
                statusEl.style.color = '#dc3545';
            }
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
            statusEl.style.color = '#dc3545';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Salvando...';
        statusEl.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Salvando número...';
        statusEl.style.opacity = 1;
        statusEl.style.color = '#25D366';

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
                statusEl.style.color = '#dc3545';
                btn.innerHTML = '<i class="fa fa-save"></i> SALVAR NÚMERO';
                btn.disabled = false;
            }
        } catch (err) {
            statusEl.innerHTML = '<i class="fa fa-times"></i> Erro de conexão.';
            statusEl.style.color = '#dc3545';
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
            const btnLight = document.getElementById('theme-light');
            if (btnLight) btnLight.classList.add('active');
        } else if (theme === 'dark') {
            body.classList.add('theme-dark');
            const btnDark = document.getElementById('theme-dark');
            if (btnDark) btnDark.classList.add('active');
        } else {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
                body.classList.add('theme-light');
            } else {
                body.classList.add('theme-dark');
            }
            const btnSys = document.getElementById('theme-system');
            if (btnSys) btnSys.classList.add('active');
        }
        window.dispatchEvent(new Event('themeChanged'));
    }

    document.addEventListener('DOMContentLoaded', applyTheme);
</script>

<style>
    /* ===== SISTEMA DE DESIGN MODULAR KOKETSU — CONFIGURAÇÕES ===== */
    .settings-page {
        padding: 0;
        min-height: calc(100vh - 100px);
        background-color: var(--bg-main) !important;
        color: var(--text-main) !important;
        font-family: Arial, sans-serif;
    }

    .settings-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* HEADER DA PÁGINA */
    .settings-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--border-color);
    }
    
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .settings-header h2 {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: 1px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .settings-header p {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin: 5px 0 0 0;
    }

    .btn-back-dash {
        padding: 10px 18px;
        background: transparent;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        color: var(--text-main);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.8rem;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-back-dash:hover {
        background-color: var(--accent);
        color: #000;
        border-color: var(--accent);
        box-shadow: 0 4px 12px rgba(242, 200, 75, 0.3);
        transform: translateY(-2px);
    }

    /* GRID LAYOUT MODULAR */
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        align-items: start;
    }

    /* CARDS INDIVIDUAIS */
    .settings-card {
        background: var(--bg-card) !important;
        border: 2px solid var(--border-color) !important;
        border-radius: var(--radius-lg) !important;
        padding: 25px 30px;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), border-color 0.3s, box-shadow 0.3s;
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
    }

    /* BARRAS DE DESTAQUE SUPERIOR */
    .card-bar {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background-color: var(--card-accent);
        transition: height 0.3s ease;
    }

    .settings-card:hover {
        border-color: var(--card-accent) !important;
        transform: translateY(-4px);
        box-shadow: var(--shadow-md), 0 0 15px var(--card-glow);
    }

    .settings-card:hover .card-bar {
        height: 6px;
    }

    /* ATRIBUIÇÃO DE CORES DE DESTAQUE POR CARD */
    .card-aparencia {
        --card-accent: var(--accent);
        --card-glow: var(--accent-glow);
    }
    .card-manequim {
        --card-accent: var(--steel);
        --card-glow: var(--steel-glow);
    }
    .card-notificacoes {
        --card-accent: var(--amethyst);
        --card-glow: var(--amethyst-glow);
    }
    .card-seguranca {
        --card-accent: var(--copper);
        --card-glow: var(--copper-glow);
    }
    .card-manutencao {
        --card-accent: var(--color-danger);
        --card-glow: rgba(220, 53, 69, 0.2);
    }
    .card-whatsapp {
        --card-accent: #25D366;
        --card-glow: rgba(37, 211, 102, 0.15);
        grid-column: 1 / -1; /* WhatsApp ocupa a largura inteira para melhor disposição */
    }

    /* SEÇÕES DE CONFIGURAÇÃO */
    .section-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--card-accent);
        letter-spacing: 1px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .setting-desc {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-bottom: 25px;
        line-height: 1.5;
    }

    /* OPÇÕES DE TEMA */
    .theme-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-top: 10px;
    }

    .theme-option {
        background: rgba(255, 255, 255, 0.02);
        padding: 15px 10px;
        border-radius: var(--radius-md);
        text-align: center;
        cursor: pointer;
        border: 2px solid var(--border-color);
        transition: all 0.25s ease;
        color: var(--text-main);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-size: 0.8rem;
    }

    .theme-option:hover {
        border-color: var(--card-accent);
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.04);
    }

    .theme-option.active {
        background: var(--accent-dim);
        border-color: var(--accent);
        box-shadow: 0 0 10px var(--accent-glow);
    }

    .theme-preview {
        width: 100%;
        height: 38px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-color);
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.5);
    }

    .dark-preview { background-color: #0f0f0f; }
    .light-preview { background-color: #ffffff; }
    .system-preview { background: linear-gradient(135deg, #0f0f0f 50%, #ffffff 50%); }

    /* SELETORES DE TAMANHO */
    .sizes-grid {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .size-group {
        flex: 1;
        min-width: 100px;
    }

    .size-group label {
        display: block;
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: 8px;
        font-weight: 700;
    }

    .k-select, .k-input {
        width: 100%;
        padding: 12px;
        background: var(--input-bg) !important;
        border: 2px solid var(--border-color) !important;
        color: var(--text-main) !important;
        border-radius: var(--radius-md) !important;
        font-family: Arial, sans-serif;
        font-size: 0.85rem;
        cursor: pointer;
        transition: border-color 0.25s, box-shadow 0.25s;
        outline: none;
    }

    .k-select:focus, .k-input:focus {
        border-color: var(--card-accent) !important;
        box-shadow: 0 0 8px var(--card-glow) !important;
    }

    .badge-new {
        background-color: var(--card-accent);
        color: #000;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.62rem;
        font-weight: 800;
        margin-left: 8px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        vertical-align: middle;
    }

    .save-indicator {
        font-size: 0.8rem;
        color: var(--accent);
        margin-top: 15px;
        text-align: right;
        height: 20px;
        opacity: 0;
        transition: opacity 0.5s ease;
        font-weight: 700;
    }

    /* ITENS DE CONFIGURAÇÃO COM TOGGLE */
    .setting-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .setting-item:last-of-type {
        border-bottom: none;
    }

    .setting-info h4 {
        margin: 0;
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--text-main);
    }

    .setting-info p {
        margin: 5px 0 0 0;
        font-size: 0.76rem;
        color: var(--text-muted);
    }

    /* CUSTOM SWITCH / TOGGLE */
    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        flex-shrink: 0;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #222;
        transition: .3s ease;
        border-radius: 34px;
        border: 2px solid var(--border-color);
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: #888;
        transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: var(--card-accent) !important;
        border-color: var(--card-accent) !important;
        box-shadow: 0 0 10px var(--card-glow) !important;
    }

    input:checked + .slider:before {
        transform: translateX(20px);
        background-color: #fff;
    }

    /* LISTA DE LINKS DE AÇÕES (Segurança) */
    .action-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 20px;
    }

    .action-link {
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 700;
        transition: all 0.2s ease;
        padding: 12px 16px;
        border-radius: var(--radius-md);
        border: 2px solid transparent;
        background-color: rgba(255, 255, 255, 0.01);
    }

    .action-link i {
        color: var(--card-accent);
        font-size: 0.95rem;
        width: 18px;
        text-align: center;
    }

    .action-link:hover {
        background: rgba(255, 255, 255, 0.03);
        color: var(--text-main);
        border-color: var(--border-color);
        padding-left: 22px;
    }

    .action-link.danger {
        color: var(--color-danger);
    }
    
    .action-link.danger i {
        color: var(--color-danger);
    }

    .action-link.danger:hover {
        background: rgba(220, 53, 69, 0.08);
        border-color: rgba(220, 53, 69, 0.2);
    }

    .k-icon-spin {
        transition: transform 0.6s ease;
    }
    
    .settings-header:hover .k-icon-spin {
        transform: rotate(180deg);
    }

    /* WHATSAPP CONFIG GRID */
    .whatsapp-config-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 25px;
    }

    .whatsapp-label {
        display: block;
        font-size: 0.72rem;
        color: var(--text-muted);
        margin-bottom: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .whatsapp-input-wrapper {
        display: flex;
        align-items: center;
        background: rgba(37, 211, 102, 0.03);
        border: 2px solid rgba(37, 211, 102, 0.2);
        border-radius: var(--radius-md);
        overflow: hidden;
        transition: border-color 0.25s, box-shadow 0.25s;
    }

    .whatsapp-input-wrapper:focus-within {
        border-color: #25D366;
        box-shadow: 0 0 10px rgba(37, 211, 102, 0.2);
    }

    .whatsapp-flag {
        padding: 12px 15px;
        font-size: 0.9rem;
        background: rgba(37, 211, 102, 0.08);
        border-right: 2px solid rgba(37, 211, 102, 0.2);
        user-select: none;
        white-space: nowrap;
        font-weight: 700;
        color: #25D366;
    }

    .whatsapp-input {
        background: transparent !important;
        border: none !important;
        border-radius: 0 !important;
        padding: 12px 15px !important;
        font-size: 0.95rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.05em !important;
        color: #25D366 !important;
        flex: 1;
        outline: none;
        font-family: 'Courier New', monospace;
    }

    .whatsapp-hint {
        font-size: 0.72rem;
        color: var(--text-muted);
        margin-top: 8px;
    }
    
    .whatsapp-hint strong {
        color: #25D366;
    }

    /* MOCKUP REALISTA DO WHATSAPP CHAT */
    .whatsapp-preview-wrapper {
        margin-bottom: 25px;
    }

    .wpp-mockup {
        background-color: #0b141a;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
        max-width: 550px;
    }

    .wpp-mockup-header {
        background-color: #202c33;
        padding: 10px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .wpp-contact-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .wpp-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background-color: #25D366;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #000;
        font-size: 1.3rem;
    }

    .wpp-contact-details {
        display: flex;
        flex-direction: column;
    }

    .wpp-contact-name {
        font-size: 0.88rem;
        font-weight: 700;
        color: #e9edef;
    }

    .wpp-contact-status {
        font-size: 0.68rem;
        color: #8696a0;
    }

    .wpp-header-actions {
        color: #aebac1;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }

    .wpp-mockup-body {
        background-color: #0b141a;
        background-image: radial-gradient(rgba(37, 211, 102, 0.03) 15%, transparent 16%);
        background-size: 16px 16px;
        padding: 20px 24px;
        min-height: 180px;
        display: flex;
        align-items: flex-end;
    }

    .wpp-msg-bubble {
        background-color: #005c4b;
        color: #e9edef;
        padding: 10px 14px;
        border-radius: 8px 8px 0 8px;
        max-width: 85%;
        margin-left: auto;
        box-shadow: 0 1px 1px rgba(0,0,0,0.2);
        position: relative;
    }

    .wpp-msg-text {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        font-size: 0.82rem;
        line-height: 1.5;
        white-space: pre-wrap;
    }

    .wpp-msg-time {
        font-size: 0.62rem;
        color: rgba(255, 255, 255, 0.6);
        text-align: right;
        margin-top: 5px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    /* BOTÕES DE AÇÃO DO WHATSAPP */
    .whatsapp-actions {
        display: flex;
        gap: 15px;
    }

    .btn-test-whatsapp {
        padding: 12px 22px;
        background: transparent;
        border: 2px solid rgba(37, 211, 102, 0.4);
        border-radius: var(--radius-md);
        color: #25D366;
        font-weight: 700;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: Arial, sans-serif;
    }

    .btn-test-whatsapp:hover {
        background: rgba(37, 211, 102, 0.08);
        border-color: #25D366;
        transform: translateY(-2px);
    }

    .btn-save-whatsapp {
        padding: 12px 28px;
        background: #25D366;
        border: none;
        border-radius: var(--radius-md);
        color: #000;
        font-weight: 800;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: Arial, sans-serif;
        letter-spacing: 0.05em;
        flex: 1;
        justify-content: center;
    }

    .btn-save-whatsapp:hover {
        background: #1ebe5d;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
    }

    .btn-save-whatsapp:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

    /* RESPONSIVIDADE */
    @media (max-width: 900px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }
        .whatsapp-config-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        .whatsapp-actions {
            flex-direction: column;
        }
    }
</style>

