<div class="settings-page">
    <!-- MODAL EDITAR BANNER -->
    <div id="modal-editar-banner" class="modal-banner-overlay" onclick="if(event.target===this)fecharModalBanner()">
        <div class="modal-banner-box">
            <div class="modal-banner-header">
                <span><i class="fa fa-pencil-alt"></i> Editar Banner</span>
                <button type="button" onclick="fecharModalBanner()" class="modal-banner-close"><i class="fa fa-times"></i></button>
            </div>
            <form id="form-editar-banner" onsubmit="salvarEdicaoBanner(event)" enctype="multipart/form-data">
                <input type="hidden" id="edit-banner-id">

                <!-- Preview atual -->
                <div class="modal-banner-section">
                    <label class="k-label">Imagem Atual</label>
                    <img id="edit-banner-preview" src="" alt="Preview atual" class="edit-banner-current-img">
                </div>

                <!-- Trocar imagem (opcional) -->
                <div class="modal-banner-section">
                    <label class="k-label"><i class="fa fa-image"></i> Trocar Imagem <span style="color:var(--text-muted); font-weight:400;">(opcional)</span></label>
                    <label class="edit-banner-file-label">
                        <i class="fa fa-upload"></i> Selecionar nova imagem
                        <input type="file" id="edit-banner-file" name="banner_imagem" accept="image/*" style="display:none;" onchange="previewEditBannerFile(this)">
                    </label>
                    <img id="edit-banner-new-preview" src="" alt="Nova imagem" style="display:none; margin-top:10px; width:100%; max-height:130px; object-fit:cover; border-radius:8px; border:2px solid #00bcd4;">
                </div>

                <!-- Link de destino -->
                <div class="modal-banner-section">
                    <label class="k-label" for="edit-banner-link"><i class="fa fa-link"></i> Link de Destino <span style="color:var(--text-muted); font-weight:400;">(opcional)</span></label>
                    <input type="text" id="edit-banner-link" name="banner_link" class="k-input" placeholder="Ex: /pages/catalogo.html?categoria=camisetas">
                </div>

                <!-- Ordem -->
                <div class="modal-banner-section">
                    <label class="k-label" for="edit-banner-ordem"><i class="fa fa-sort-numeric-up"></i> Ordem de Exibição</label>
                    <input type="number" id="edit-banner-ordem" name="banner_ordem" class="k-input" min="0" placeholder="Ex: 1">
                </div>

                <p id="edit-banner-msg" style="font-size:0.8rem; font-weight:700; margin:10px 0 0 0; opacity:0; transition:opacity 0.3s;"></p>

                <div class="modal-banner-footer">
                    <button type="button" onclick="fecharModalBanner()" class="modal-btn-cancel"><i class="fa fa-times"></i> Cancelar</button>
                    <button type="submit" id="btn-salvar-edicao-banner" class="modal-btn-save"><i class="fa fa-save"></i> SALVAR ALTERAÇÕES</button>
                </div>
            </form>
        </div>
    </div>

    <div class="settings-container">
        <!-- HEADER DA PÁGINA -->
        <header class="settings-header">
            <div class="header-content">
                <div>
                    <h2><i class="fa fa-cog k-icon-spin"></i> PREFERÊNCIAS & AJUSTES</h2>
                    <p class="text-muted">Painel administrativo de configurações da Koketsu Grife</p>
                </div>
                <a href="/backend/admin/dashboard" class="btn-back-dash">
                    <i class="fa fa-chevron-left"></i> VOLTAR AO PAINEL
                </a>
            </div>
        </header>

        <!-- GRID DE CONFIGURAÇÕES -->
        <div class="settings-grid">
            
            <!-- CARD 1: MODO MANUTENÇÃO -->
            <div class="settings-card card-manutencao" id="card-manutencao">
                <div class="card-bar"></div>
                <section class="settings-section">
                    <div class="section-title">
                        <i class="fa fa-lock"></i> MODO MANUTENÇÃO
                    </div>
                    <p class="setting-desc">Controle o status de acesso público da loja virtual.</p>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Modo Manutenção</h4>
                            <p class="text-muted">Bloquear temporariamente o acesso de clientes ao site.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="maintenance-toggle" <?= ($config['manutencao'] ?? false) ? 'checked' : '' ?> onchange="toggleMaintenance(this.checked)">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </section>
            </div>

            <!-- CARD 2: BANNER PROMOCIONAL GLOBAL -->
            <div class="settings-card card-banner" id="card-banner">
                <div class="card-bar"></div>
                <section class="settings-section">
                    <div class="section-title">
                        <i class="fa fa-bullhorn"></i> BANNER PROMOCIONAL
                    </div>
                    <p class="setting-desc">Exiba uma barra de avisos ou descontos no topo do site.</p>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Banner Ativo</h4>
                            <p class="text-muted">Mostrar a barra de aviso no cabeçalho do e-commerce.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="banner_ativo" <?= ($config['banner_ativo'] ?? false) ? 'checked' : '' ?>>
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="k-label">Texto do Banner</label>
                        <input type="text" name="banner_texto" class="k-input" placeholder="Ex: Cupom KOKETSU10 garante 10% OFF!" value="<?= htmlspecialchars($config['banner_texto'] ?? '') ?>">
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <label class="k-label">Cor do Banner</label>
                        <select name="banner_cor" class="k-select">
                            <option value="dourado" <?= ($config['banner_cor'] ?? '') === 'dourado' ? 'selected' : '' ?>>Dourado Imperial (Padrão)</option>
                            <option value="vermelho" <?= ($config['banner_cor'] ?? '') === 'vermelho' ? 'selected' : '' ?>>Vermelho Alerta</option>
                            <option value="preto" <?= ($config['banner_cor'] ?? '') === 'preto' ? 'selected' : '' ?>>Preto Minimalista</option>
                        </select>
                    </div>

                    <div class="card-actions">
                        <button class="btn-save-gold btn-save" onclick="salvarCard('card-banner', ['banner_ativo', 'banner_texto', 'banner_cor'])">
                            <i class="fa fa-save"></i> SALVAR BANNER
                        </button>
                    </div>
                    <div class="save-indicator"></div>
                </section>
            </div>

            <!-- CARD 5: BANNERS DO CARROSSEL DA HOME -->
            <div class="settings-card card-banners-home" id="card-banners-home" style="grid-column: 1 / -1;">
                <div class="card-bar"></div>
                <section class="settings-section">
                    <div class="section-title">
                        <i class="fa fa-images"></i> BANNERS DO CARROSSEL DA HOME
                    </div>
                    <p class="setting-desc">Gerencie as imagens rotativas e links de destino do banner principal na tela inicial do site.</p>
                    
                    <div class="banners-home-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                        
                        <!-- Coluna 1: Adicionar Novo Banner -->
                        <div class="new-banner-panel" style="border-right: 1px solid var(--border-color); padding-right: 30px;">
                            <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 15px; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                                <i class="fa fa-plus-circle" style="color: var(--card-accent);"></i> Adicionar Novo Banner
                            </h4>
                            
                            <form id="form-novo-banner" onsubmit="adicionarBanner(event)">
                                <div class="form-group">
                                    <label class="k-label">Imagem do Banner (Recomendado: 1920x600px)</label>
                                    <div class="upload-dropzone" id="banner-dropzone" onclick="document.getElementById('banner-file-input').click()">
                                        <i class="fa fa-cloud-upload-alt" style="font-size: 2rem; margin-bottom: 10px; color: var(--card-accent);"></i>
                                        <p style="margin: 0; font-size: 0.8rem; font-weight: bold;">Clique ou Arraste a imagem aqui</p>
                                        <p style="margin: 5px 0 0 0; font-size: 0.7rem; color: var(--text-muted);">Formatos aceitos: JPG, PNG, WEBP. Máx 2MB</p>
                                        <input type="file" id="banner-file-input" name="banner_imagem" accept="image/*" style="display: none;" onchange="previewBannerFile(this)">
                                    </div>
                                    <!-- Imagem Preview -->
                                    <div id="banner-preview-container" style="display: none; margin-top: 15px; position: relative;">
                                        <img id="banner-img-preview" src="#" alt="Preview" style="width: 100%; height: auto; border-radius: var(--radius-md); border: 2px solid var(--border-color); object-fit: cover; max-height: 150px;">
                                        <button type="button" onclick="cancelarPreviewBanner()" style="position: absolute; top: 8px; right: 8px; background: rgba(0,0,0,0.7); border: none; border-radius: 50%; color: #fff; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s;"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                
                                <div class="form-group" style="margin-top: 15px;">
                                    <label class="k-label">Link de Destino (Opcional)</label>
                                    <input type="text" id="banner-link-input" name="banner_link" class="k-input" placeholder="Ex: /pages/catalogo.html?categoria=camisetas">
                                </div>
                                
                                <div class="form-group" style="margin-top: 15px;">
                                    <label class="k-label">Ordem de Exibição</label>
                                    <input type="number" id="banner-ordem-input" name="banner_ordem" class="k-input" placeholder="Ex: 1" value="1" min="0">
                                </div>
                                
                                <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                                    <button type="submit" id="btn-add-banner" class="btn-save-gold" style="background: linear-gradient(135deg, var(--card-accent), var(--card-accent));">
                                        <i class="fa fa-plus"></i> ADICIONAR BANNER
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Coluna 2: Banners Atuais -->
                        <div class="banners-list-panel">
                            <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 15px; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                                <i class="fa fa-list" style="color: var(--card-accent);"></i> Banners Cadastrados
                            </h4>
                            
                            <!-- Lista Dinâmica -->
                            <div id="banners-list-container" style="display: flex; flex-direction: column; gap: 15px; max-height: 400px; overflow-y: auto; padding-right: 5px;">
                                <div class="loading-banners" style="text-align: center; padding: 20px; color: var(--text-muted);">
                                    <i class="fa fa-spinner fa-spin"></i> Carregando banners...
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- CARD 3: INFORMAÇÕES INSTITUCIONAIS & SEO -->
            <div class="settings-card card-seo" id="card-seo">
                <div class="card-bar"></div>
                <section class="settings-section">
                    <div class="section-title">
                        <i class="fa fa-globe"></i> INFORMAÇÕES & SEO
                    </div>
                    <p class="setting-desc">Gerencie os metadados do site para o Google e dados do rodapé.</p>
                    
                    <div class="form-group">
                        <label class="k-label">Título da Loja (Meta Title)</label>
                        <input type="text" name="seo_titulo" class="k-input" placeholder="Ex: Koketsu | Roupas Premium & Grife" value="<?= htmlspecialchars($config['seo_titulo'] ?? '') ?>">
                    </div>

                    <div class="form-group" style="margin-top: 12px;">
                        <label class="k-label">Descrição SEO (Meta Description)</label>
                        <textarea name="seo_descricao" class="k-textarea" rows="2" placeholder="Descreva brevemente a loja para os buscadores..."><?= htmlspecialchars($config['seo_descricao'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group" style="margin-top: 12px;">
                        <label class="k-label">CNPJ Oficial</label>
                        <input type="text" name="cnpj" class="k-input" placeholder="00.000.000/0001-00" value="<?= htmlspecialchars($config['cnpj'] ?? '') ?>">
                    </div>

                    <div class="form-group" style="margin-top: 12px;">
                        <label class="k-label">E-mail de Atendimento Público</label>
                        <input type="email" name="email_contato" class="k-input" placeholder="contato@koketsugrife.com.br" value="<?= htmlspecialchars($config['email_contato'] ?? '') ?>">
                    </div>

                    <div class="form-group" style="margin-top: 12px;">
                        <label class="k-label">Endereço Físico</label>
                        <input type="text" name="endereco" class="k-input" placeholder="Av. Paulista, 1000 - São Paulo, SP" value="<?= htmlspecialchars($config['endereco'] ?? '') ?>">
                    </div>

                    <div class="card-actions">
                        <button class="btn-save-gold btn-save" onclick="salvarCard('card-seo', ['seo_titulo', 'seo_descricao', 'cnpj', 'email_contato', 'endereco'])">
                            <i class="fa fa-save"></i> SALVAR INFORMAÇÕES
                        </button>
                    </div>
                    <div class="save-indicator"></div>
                </section>
            </div>

            <!-- CARD 4: LINKS DE REDES SOCIAIS -->
            <div class="settings-card card-social" id="card-social">
                <div class="card-bar"></div>
                <section class="settings-section">
                    <div class="section-title">
                        <i class="fa fa-share-alt"></i> REDES SOCIAIS
                    </div>
                    <p class="setting-desc">Ajuste os links do rodapé e contatos públicos da marca.</p>
                    
                    <div class="form-group">
                        <label class="k-label"><i class="fab fa-instagram"></i> Instagram</label>
                        <input type="url" name="social_instagram" class="k-input" placeholder="https://instagram.com/koketsugrife" value="<?= htmlspecialchars($config['social_instagram'] ?? '') ?>">
                    </div>

                    <div class="form-group" style="margin-top: 12px;">
                        <label class="k-label"><i class="fab fa-tiktok"></i> TikTok</label>
                        <input type="url" name="social_tiktok" class="k-input" placeholder="https://tiktok.com/@koketsugrife" value="<?= htmlspecialchars($config['social_tiktok'] ?? '') ?>">
                    </div>

                    <div class="form-group" style="margin-top: 12px;">
                        <label class="k-label"><i class="fab fa-youtube"></i> Canal no YouTube</label>
                        <input type="url" name="social_youtube" class="k-input" placeholder="https://youtube.com/c/koketsugrife" value="<?= htmlspecialchars($config['social_youtube'] ?? '') ?>">
                    </div>

                    <div class="card-actions">
                        <button class="btn-save-gold btn-save" onclick="salvarCard('card-social', ['social_instagram', 'social_tiktok', 'social_youtube'])">
                            <i class="fa fa-save"></i> SALVAR LINKS
                        </button>
                    </div>
                    <div class="save-indicator"></div>
                </section>
            </div>

            <!-- CARD 6: WHATSAPP DE ATENDIMENTO -->
            <div class="settings-card card-whatsapp" id="card-whatsapp">
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
        </div>
    </div>
</div>

<script>
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

    // Função centralizada para salvar dados de outros cards via AJAX
    function salvarCard(cardId, keys) {
        const card = document.getElementById(cardId);
        const btn = card.querySelector('.btn-save');
        const statusEl = card.querySelector('.save-indicator');
        
        const data = {};
        keys.forEach(key => {
            const input = card.querySelector(`[name="${key}"]`);
            if (input) {
                if (input.type === 'checkbox') {
                    data[key] = input.checked;
                } else {
                    data[key] = input.value;
                }
            }
        });

        if (btn) {
            btn.disabled = true;
            btn.dataset.originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> SALVANDO...';
        }
        if (statusEl) {
            statusEl.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Gravando alterações...';
            statusEl.style.opacity = 1;
            statusEl.style.color = 'var(--accent)';
        }

        fetch('/backend/configuracoes/salvar-gerais', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(res => {
            if(res.success) {
                if (statusEl) {
                    statusEl.innerHTML = '<i class="fa fa-check"></i> Alterações salvas! ✅';
                    statusEl.style.color = '#25D366';
                }
                if (btn) {
                    btn.innerHTML = '<i class="fa fa-check"></i> SALVO!';
                }
                setTimeout(() => {
                    if (statusEl) statusEl.style.opacity = 0;
                    if (btn) {
                        btn.innerHTML = btn.dataset.originalHtml;
                        btn.disabled = false;
                    }
                }, 2000);
            } else {
                if (statusEl) {
                    statusEl.innerHTML = '<i class="fa fa-times"></i> Erro: ' + (res.message || 'Tente novamente.');
                    statusEl.style.color = '#dc3545';
                }
                if (btn) {
                    btn.innerHTML = 'TENTAR NOVAMENTE';
                    btn.disabled = false;
                }
            }
        })
        .catch(err => {
            console.error(err);
            if (statusEl) {
                statusEl.innerHTML = '<i class="fa fa-times"></i> Erro de conexão.';
                statusEl.style.color = '#dc3545';
            }
            if (btn) {
                btn.innerHTML = 'TENTAR NOVAMENTE';
                btn.disabled = false;
            }
        });
    }

    // ==== BANNER MANAGER FUNCTIONS ====
    async function carregarBanners() {
        const container = document.getElementById('banners-list-container');
        if (!container) return;

        try {
            const res = await fetch('/backend/configuracoes/banners');
            const data = await res.json();

            if (data.success && Array.isArray(data.data)) {
                if (data.data.length === 0) {
                    container.innerHTML = '<div style="text-align:center; padding:20px; color:var(--text-muted);">Nenhum banner cadastrado.</div>';
                    return;
                }

                let html = '';
                data.data.forEach(banner => {
                    const imgSrc = (banner.url_imagem_imagem_carrossel.startsWith('http') || banner.url_imagem_imagem_carrossel.startsWith('/'))
                        ? banner.url_imagem_imagem_carrossel
                        : '/backend/upload/' + banner.url_imagem_imagem_carrossel;

                    const checked = parseInt(banner.ativo_imagem_carrossel) ? 'checked' : '';
                    const linkText = banner.link_destino_imagem_carrossel || 'Nenhum link';

                    html += `
                        <div class="banner-item" style="display:flex; align-items:center; gap:15px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); padding:10px; border-radius:var(--radius-md); position:relative;">
                            <img src="${imgSrc}" style="width:100px; height:50px; object-fit:cover; border-radius:4px; border:1px solid var(--border-color); flex-shrink:0;">
                            <div style="flex:1; min-width:0;">
                                <div style="font-size:0.75rem; color:var(--text-muted); font-weight:bold; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="${linkText}">
                                    Link: <span style="color:var(--text-main); font-weight:normal;">${linkText}</span>
                                </div>
                                <div style="font-size:0.75rem; color:var(--text-muted); font-weight:bold; margin-top:4px;">
                                    Ordem: <span style="color:var(--text-main); font-weight:normal;">${banner.ordem_imagem_carrossel}</span>
                                </div>
                            </div>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <label class="switch">
                                    <input type="checkbox" ${checked} onchange="toggleBannerAtivo(${banner.id_carrossel})">
                                    <span class="slider round"></span>
                                </label>
                                <button type="button" title="Editar banner" onclick="abrirEditarBanner(${banner.id_carrossel}, '${(banner.link_destino_imagem_carrossel||'').replace(/'/g,\"\\\\'\")  }', ${banner.ordem_imagem_carrossel}, '${imgSrc}')" style="background:rgba(0,188,212,0.12); border:1px solid rgba(0,188,212,0.35); color:#00bcd4; cursor:pointer; font-size:0.78rem; padding:6px 10px; border-radius:6px; transition:all 0.2s; display:flex; align-items:center; gap:5px; font-weight:700;" onmouseover="this.style.background='rgba(0,188,212,0.22)'" onmouseout="this.style.background='rgba(0,188,212,0.12)'">
                                    <i class="fa fa-pencil-alt"></i> Editar
                                </button>
                                <button type="button" title="Excluir banner" onclick="deletarBanner(${banner.id_carrossel})" style="background:rgba(220,53,69,0.10); border:1px solid rgba(220,53,69,0.3); color:var(--color-danger); cursor:pointer; font-size:0.78rem; padding:6px 10px; border-radius:6px; transition:all 0.2s; display:flex; align-items:center; gap:5px; font-weight:700;" onmouseover="this.style.background='rgba(220,53,69,0.2)'" onmouseout="this.style.background='rgba(220,53,69,0.10)'">
                                    <i class="fa fa-trash"></i> Excluir
                                </button>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = '<div style="text-align:center; padding:20px; color:#dc3545;">Erro ao carregar banners.</div>';
            }
        } catch (err) {
            console.error(err);
            container.innerHTML = '<div style="text-align:center; padding:20px; color:#dc3545;">Erro de conexão.</div>';
        }
    }

    function previewBannerFile(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('banner-img-preview').src = e.target.result;
                document.getElementById('banner-preview-container').style.display = 'block';
                document.getElementById('banner-dropzone').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }

    function cancelarPreviewBanner() {
        document.getElementById('banner-file-input').value = '';
        document.getElementById('banner-preview-container').style.display = 'none';
        document.getElementById('banner-dropzone').style.display = 'block';
    }

    async function adicionarBanner(e) {
        e.preventDefault();
        const form = document.getElementById('form-novo-banner');
        const fileInput = document.getElementById('banner-file-input');
        
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Por favor, selecione uma imagem para o banner.');
            return;
        }

        const formData = new FormData(form);
        const btn = document.getElementById('btn-add-banner');
        const originalHtml = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> ADICIONANDO...';

        try {
            const res = await fetch('/backend/configuracoes/banners/salvar', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                form.reset();
                cancelarPreviewBanner();
                carregarBanners();
            } else {
                alert('Erro ao adicionar banner: ' + (data.message || 'Tente novamente.'));
            }
        } catch (err) {
            console.error(err);
            alert('Erro de conexão ao adicionar banner.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }

    async function toggleBannerAtivo(id) {
        try {
            const res = await fetch(`/backend/configuracoes/banners/toggle/${id}`, {
                method: 'POST'
            });
            const data = await res.json();
            if (!data.success) {
                alert('Erro ao alterar status do banner: ' + (data.message || 'Tente novamente.'));
                carregarBanners();
            }
        } catch (err) {
            console.error(err);
            alert('Erro de conexão.');
            carregarBanners();
        }
    }

    async function deletarBanner(id) {
        if (!confirm('Deseja realmente excluir este banner do carrossel?')) {
            return;
        }

        try {
            const res = await fetch(`/backend/configuracoes/banners/excluir/${id}`, {
                method: 'POST'
            });
            const data = await res.json();
            if (data.success) {
                carregarBanners();
            } else {
                alert('Erro ao excluir banner: ' + (data.message || 'Tente novamente.'));
            }
        } catch (err) {
            console.error(err);
            alert('Erro de conexão.');
        }
    }

    // ==== EDITAR BANNER ====
    function abrirEditarBanner(id, link, ordem, imgSrc) {
        document.getElementById('edit-banner-id').value    = id;
        document.getElementById('edit-banner-link').value  = link;
        document.getElementById('edit-banner-ordem').value = ordem;
        document.getElementById('edit-banner-preview').src = imgSrc;
        document.getElementById('edit-banner-file').value  = '';
        document.getElementById('edit-banner-new-preview').style.display = 'none';
        document.getElementById('modal-editar-banner').classList.add('active');
    }

    function fecharModalBanner() {
        document.getElementById('modal-editar-banner').classList.remove('active');
    }

    function previewEditBannerFile(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                const np = document.getElementById('edit-banner-new-preview');
                np.src = e.target.result;
                np.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    async function salvarEdicaoBanner(e) {
        e.preventDefault();
        const id    = document.getElementById('edit-banner-id').value;
        const btn   = document.getElementById('btn-salvar-edicao-banner');
        const msgEl = document.getElementById('edit-banner-msg');

        const formData = new FormData();
        formData.append('banner_link',  document.getElementById('edit-banner-link').value);
        formData.append('banner_ordem', document.getElementById('edit-banner-ordem').value);

        const fileInput = document.getElementById('edit-banner-file');
        if (fileInput.files && fileInput.files.length > 0) {
            formData.append('banner_imagem', fileInput.files[0]);
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> SALVANDO...';
        msgEl.style.opacity = 0;

        try {
            const res  = await fetch(`/backend/configuracoes/banners/editar/${id}`, {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                msgEl.textContent   = '✅ Banner atualizado com sucesso!';
                msgEl.style.color   = '#25D366';
                msgEl.style.opacity = 1;
                btn.innerHTML = '<i class="fa fa-check"></i> SALVO!';
                setTimeout(() => {
                    fecharModalBanner();
                    carregarBanners();
                    btn.disabled  = false;
                    btn.innerHTML = '<i class="fa fa-save"></i> SALVAR ALTERAÇÕES';
                }, 1200);
            } else {
                msgEl.textContent   = '❌ Erro: ' + (data.message || 'Tente novamente.');
                msgEl.style.color   = '#dc3545';
                msgEl.style.opacity = 1;
                btn.disabled  = false;
                btn.innerHTML = '<i class="fa fa-save"></i> SALVAR ALTERAÇÕES';
            }
        } catch (err) {
            console.error(err);
            msgEl.textContent   = '❌ Erro de conexão.';
            msgEl.style.color   = '#dc3545';
            msgEl.style.opacity = 1;
            btn.disabled  = false;
            btn.innerHTML = '<i class="fa fa-save"></i> SALVAR ALTERAÇÕES';
        }
    }

    // Inicializa a listagem de banners e drag & drop
    document.addEventListener("DOMContentLoaded", () => {
        carregarBanners();
        
        const dropzone = document.getElementById('banner-dropzone');
        if (dropzone) {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropzone.style.borderColor = 'var(--card-accent)';
                    dropzone.style.background = 'rgba(255, 255, 255, 0.04)';
                }, false);
            });
            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropzone.style.borderColor = 'var(--border-color)';
                    dropzone.style.background = 'rgba(255, 255, 255, 0.01)';
                }, false);
            });
            dropzone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length) {
                    const input = document.getElementById('banner-file-input');
                    input.files = files;
                    previewBannerFile(input);
                }
            });
        }
    });
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

    /* CORES DE DESTAQUE POR CARD */
    .card-manutencao {
        --card-accent: var(--color-danger);
        --card-glow: rgba(220, 53, 69, 0.2);
    }
    .card-banner {
        --card-accent: var(--accent);
        --card-glow: var(--accent-glow);
    }
    .card-seo {
        --card-accent: var(--steel);
        --card-glow: var(--steel-glow);
    }
    .card-social {
        --card-accent: var(--amethyst);
        --card-glow: var(--amethyst-glow);
    }
    .card-whatsapp {
        --card-accent: #25D366;
        --card-glow: rgba(37, 211, 102, 0.15);
        grid-column: 1 / -1; /* Ocupa largura total */
    }

    .card-banners-home {
        --card-accent: #00bcd4;
        --card-glow: rgba(0, 188, 212, 0.15);
        grid-column: 1 / -1;
    }

    .upload-dropzone {
        border: 2px dashed var(--border-color) !important;
        padding: 20px;
        text-align: center;
        border-radius: var(--radius-md);
        cursor: pointer;
        background: rgba(255, 255, 255, 0.01);
        transition: all 0.25s ease;
    }
    
    .upload-dropzone:hover {
        border-color: var(--card-accent) !important;
        background: rgba(255, 255, 255, 0.02);
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

    .k-label {
        display: block;
        font-size: 0.76rem;
        color: var(--text-muted);
        margin-bottom: 6px;
        font-weight: 700;
    }

    .k-select, .k-input, .k-textarea {
        width: 100%;
        padding: 12px;
        background: var(--input-bg) !important;
        border: 2px solid var(--border-color) !important;
        color: var(--text-main) !important;
        border-radius: var(--radius-md) !important;
        font-family: Arial, sans-serif;
        font-size: 0.85rem;
        transition: border-color 0.25s, box-shadow 0.25s;
        outline: none;
    }

    .k-textarea {
        resize: vertical;
    }

    .k-select:focus, .k-input:focus, .k-textarea:focus {
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
        font-size: 0.78rem;
        color: var(--accent);
        margin-top: 12px;
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
        margin-bottom: 15px;
    }
    
    .setting-item:last-of-type {
        border-bottom: none;
        margin-bottom: 0;
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

    .k-icon-spin {
        transition: transform 0.6s ease;
    }
    
    .settings-header:hover .k-icon-spin {
        transform: rotate(180deg);
    }

    /* BOTÕES DE SALVAMENTO DOS CARDS */
    .card-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .btn-save-gold {
        padding: 10px 20px;
        background: linear-gradient(135deg, var(--card-accent), var(--card-accent));
        filter: brightness(0.9);
        border: none;
        border-radius: var(--radius-md);
        color: #000;
        font-weight: 800;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: Arial, sans-serif;
    }

    .btn-save-gold:hover {
        filter: brightness(1.1);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px var(--card-glow);
    }

    .btn-save-gold:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
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

    /* ===== MODAL EDITAR BANNER ===== */
    .modal-banner-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.75);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
        animation: fadeInOverlay 0.2s ease;
    }
    .modal-banner-overlay.active {
        display: flex;
    }
    @keyframes fadeInOverlay {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    .modal-banner-box {
        background: var(--bg-card);
        border: 2px solid #00bcd4;
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 500px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.6), 0 0 30px rgba(0,188,212,0.15);
        overflow: hidden;
        animation: slideUpModal 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    @keyframes slideUpModal {
        from { transform: translateY(30px); opacity: 0; }
        to   { transform: translateY(0);    opacity: 1; }
    }

    .modal-banner-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 24px;
        border-bottom: 2px solid rgba(0,188,212,0.25);
        background: rgba(0,188,212,0.06);
        font-size: 1rem;
        font-weight: 800;
        color: #00bcd4;
        letter-spacing: 0.05em;
    }

    .modal-banner-close {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-size: 1.1rem;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .modal-banner-close:hover {
        background: rgba(220,53,69,0.15);
        color: #dc3545;
    }

    .modal-banner-section {
        padding: 0 24px;
        margin-top: 18px;
    }

    .edit-banner-current-img {
        width: 100%;
        max-height: 130px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid var(--border-color);
        margin-top: 8px;
    }

    .edit-banner-file-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        padding: 9px 16px;
        background: rgba(0,188,212,0.1);
        border: 1.5px dashed rgba(0,188,212,0.5);
        border-radius: 8px;
        color: #00bcd4;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }
    .edit-banner-file-label:hover {
        background: rgba(0,188,212,0.18);
        border-color: #00bcd4;
    }

    .modal-banner-footer {
        display: flex;
        gap: 12px;
        padding: 20px 24px;
        margin-top: 20px;
        border-top: 1px solid var(--border-color);
        background: rgba(255,255,255,0.01);
    }

    .modal-btn-cancel {
        flex: 1;
        padding: 11px;
        background: transparent;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        color: var(--text-muted);
        font-weight: 700;
        font-size: 0.82rem;
        cursor: pointer;
        transition: all 0.2s;
        font-family: Arial, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .modal-btn-cancel:hover {
        border-color: #dc3545;
        color: #dc3545;
        background: rgba(220,53,69,0.06);
    }

    .modal-btn-save {
        flex: 2;
        padding: 11px;
        background: linear-gradient(135deg, #00bcd4, #0097a7);
        border: none;
        border-radius: var(--radius-md);
        color: #000;
        font-weight: 800;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        font-family: Arial, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        letter-spacing: 0.04em;
    }
    .modal-btn-save:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(0,188,212,0.4);
    }
    .modal-btn-save:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }
</style>
