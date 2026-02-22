
<div class="luxury-profile-page">
    <div class="k-profile-card">
        <form action="/backend/cliente/perfil-atualizar/<?= $usuario['id_usuarios'] ?>" method="POST" enctype="multipart/form-data" class="profile-form">
            
            <div class="k-header-luxury">
                <div class="h-title">
                    <span class="gold-line"></span>
                    <h2>GERENCIAR PERFIL</h2>
                </div>
                <a class="k-back-link" href="/backend/cliente/dashboard">
                    <i class="fa fa-arrow-left"></i> VOLTAR
                </a>
            </div>

            <div class="k-profile-body">
                <div class="k-photo-section">
                    <div class="k-avatar-outer">
                        <div class="k-avatar-container">
                            <div class="avatar-overlay"></div>
                            <img id="preview-foto" 
                                 src="<?= !empty($usuario['foto_usuarios']) ? '/backend/upload/usuarios/' . htmlspecialchars($usuario['foto_usuarios']) : '/img/logoperf.jpg' ?>" 
                                 alt="Foto Perfil" class="k-avatar-main">
                            
                            <label class="k-upload-trigger" for="foto_usuarios">
                                <i class="fa fa-pencil"></i>
                            </label>
                            <input type="file" name="foto_usuarios" id="foto_usuarios" accept="image/*" onchange="previewFoto(event)" style="display: none;" />
                        </div>
                        <p class="photo-hint">Clique no ícone para alterar sua foto de perfil</p>
                    </div>
                </div>

                <div class="k-data-section">
                    <div class="form-section-title">Informações Pessoais</div>
                    
                    <div class="k-input-group full-width">
                        <label>NOME COMPLETO</label>
                        <div class="input-wrapper">
                            <i class="fa fa-user gold-icon"></i>
                            <input type="text" name="nome_usuarios" value="<?= htmlspecialchars($usuario['nome_usuarios'] ?? '') ?>" required placeholder="Seu nome completo" />
                        </div>
                    </div>

                    <div class="k-input-group full-width">
                        <label>EMAIL DE ACESSO</label>
                        <div class="input-wrapper">
                            <i class="fa fa-envelope gold-icon"></i>
                            <input type="email" name="email_usuarios" value="<?= htmlspecialchars($usuario['email_usuarios'] ?? '') ?>" required placeholder="seu@email.com" />
                        </div>
                    </div>

                    <div class="k-grid-inputs">
                        <div class="k-input-group">
                            <label>TELEFONE</label>
                            <div class="input-wrapper">
                                <i class="fa fa-phone gold-icon"></i>
                                <input type="text" name="telefone_perfil" value="<?= htmlspecialchars($perfil['telefone_perfil'] ?? '') ?>" placeholder="(00) 00000-0000" />
                            </div>
                        </div>
                        <div class="k-input-group">
                            <label>ENDEREÇO COMPLETO</label>
                            <div class="input-wrapper">
                                <i class="fa fa-map-marker-alt gold-icon"></i>
                                <input type="text" name="endereco_perfil" value="<?= htmlspecialchars($perfil['endereco_perfil'] ?? '') ?>" placeholder="Rua, Número, Bairro, Cidade - UF" />
                            </div>
                        </div>
                    </div>

                    <div class="form-section-title">Segurança da Conta</div>

                    <div class="k-grid-inputs">
                        <div class="k-input-group">
                            <label>NOVA SENHA</label>
                            <div class="input-wrapper">
                                <i class="fa fa-lock gold-icon"></i>
                                <input type="password" name="senha_usuarios" placeholder="••••••••" />
                            </div>
                        </div>
                        <div class="k-input-group">
                            <label>CONFIRMAÇÃO</label>
                            <div class="input-wrapper">
                                <i class="fa fa-shield-alt gold-icon"></i>
                                <input type="password" name="confirmar_senha" placeholder="••••••••" />
                            </div>
                        </div>
                    </div>
                    <p class="security-hint">* Deixe em branco se não desejar alterar sua senha atual.</p>

                    <div class="k-form-actions">
                        <button type="submit" class="k-btn-gold">
                            <i class="fa fa-save"></i> ATUALIZAR PERFIL
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function previewFoto(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('preview-foto');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap');

    :root {
        --k-gold: #f2cc7d;
        --k-gold-light: #ffebbc;
        --k-gold-dark: #b8860b;
        --bg-body-custom: var(--bg-main, #0a0a0a);
        --bg-card-custom: var(--bg-card, #141414);
        --text-color-custom: var(--text-main, #ffffff);
        --border-custom: var(--border-color, rgba(242, 204, 125, 0.2));
        --k-text-muted: var(--text-muted, #a0a0a0);
        --glass-bg: rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.05);
    }

    .luxury-profile-page {
        font-family: 'Outfit', sans-serif;
        color: var(--text-color-custom);
        padding: 0px 20px 40px; /* Topo zerado para máxima compacidade */
        display: flex;
        justify-content: center;
        background: var(--bg-body-custom);
        min-height: 100vh;
        box-sizing: border-box;
    }

    /* Ajuste para quando o fundo for claro */
    [data-theme="light"] .luxury-profile-page {
        background: #f4f4f4;
    }

    .k-profile-card {
        width: 100%;
        max-width: 900px;
        background: var(--bg-card-custom);
        border: 1px solid var(--border-custom);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
        overflow: hidden;
        position: relative;
        margin-top: 10px;
    }

    .k-header-luxury {
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--glass-border);
        background: rgba(125, 125, 125, 0.03);
    }

    .h-title { display: flex; align-items: center; gap: 15px; }
    .gold-line { width: 4px; height: 24px; background: var(--k-gold); border-radius: 2px; }
    .h-title h2 { 
        font-family: 'Playfair Display', serif; 
        font-size: 1.4rem; 
        letter-spacing: 1px; 
        margin: 0; 
        color: var(--text-color-custom);
        text-transform: uppercase;
    }

    .k-back-link { 
        color: var(--k-gold-dark); 
        text-decoration: none; 
        font-size: 0.85rem; 
        font-weight: 600; 
        display: flex; align-items: center; gap: 8px;
        transition: 0.3s;
    }
    .k-back-link:hover { color: var(--k-gold); }

    .k-profile-body { padding: 30px 40px; }

    .k-photo-section { 
        display: flex; 
        justify-content: center; 
        margin-bottom: 25px; 
    }

    .k-avatar-container { 
        position: relative; 
        width: 150px; 
        height: 150px; 
    }
    
    .k-avatar-main { 
        width: 100%; height: 100%; 
        border-radius: 50%;
        object-fit: cover; 
        border: 3px solid var(--k-gold);
        background: #111;
        box-shadow: 0 0 20px rgba(242, 204, 125, 0.2);
    }

    .k-upload-trigger {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: var(--k-gold);
        color: #000;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        border: 2px solid var(--bg-card-custom);
    }
    .k-upload-trigger:hover { transform: scale(1.1); background: #fff; }

    .photo-hint {
        text-align: center;
        font-size: 0.8rem;
        color: var(--k-text-muted);
        margin-top: 10px;
    }

    .k-data-section { max-width: 650px; margin: 0 auto; }

    .form-section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--k-gold-dark);
        margin: 20px 0 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, var(--glass-border), transparent);
    }

    .k-input-group { margin-bottom: 20px; }
    .k-input-group label { 
        display: block; 
        font-size: 0.8rem; 
        font-weight: 600; 
        color: var(--text-color-custom);
        margin-bottom: 8px; 
        opacity: 0.8;
    }

    .input-wrapper { position: relative; }

    .gold-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--k-gold);
        opacity: 0.7;
    }

    .k-input-group input {
        width: 100%;
        background: rgba(125, 125, 125, 0.05);
        border: 1px solid var(--border-custom);
        padding: 14px 15px 14px 50px;
        border-radius: 10px;
        color: var(--text-color-custom);
        font-family: inherit;
        font-size: 0.95rem;
        transition: 0.3s;
        box-sizing: border-box;
    }

    .k-input-group input:focus { 
        border-color: var(--k-gold); 
        outline: none; 
        background: rgba(242, 204, 125, 0.03);
    }

    .k-grid-inputs { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 20px; 
    }

    .security-hint { font-size: 0.75rem; color: var(--k-text-muted); margin-top: -10px; }

    .k-form-actions { margin-top: 35px; text-align: center; }

    .k-btn-gold {
        background: linear-gradient(135deg, var(--k-gold), var(--k-gold-dark));
        color: #000;
        border: none;
        padding: 15px 45px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
    .k-btn-gold:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(242, 204, 125, 0.15); }

    @media (max-width: 768px) {
        .k-grid-inputs { grid-template-columns: 1fr; }
        .k-profile-body { padding: 20px; }
        .k-header-luxury { flex-direction: column; gap: 10px; text-align: center; }
    }
</style>
