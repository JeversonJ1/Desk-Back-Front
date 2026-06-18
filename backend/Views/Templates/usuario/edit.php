<?php
/** @var array $usuario */
?>
<div class="page-wrapper">
    <h3 class="page-title">
        <i class="fa fa-pencil" style="color: #f2cc7d;"></i> 
        Editando Usuário: <span style="color: #f2cc7d;"><?= htmlspecialchars($usuario['nome_usuarios']); ?></span>
    </h3>

    <header class="header-breadcrumb">
        <h5><b><i class="fa fa-info-circle"></i> Altere as informações abaixo e clique em salvar para atualizar o registro.</b></h5>
    </header>

    <form action="/backend/usuario/atualizar" method="post" enctype="multipart/form-data" class="form-card">
        <input type="text" id="id_usuarios" name="id_usuarios" value="<?php echo $usuario['id_usuarios']; ?>" hidden>

        <div class="profile-photo-section">
            <div class="photo-container">
                <img id="photoPreview" 
                     src="<?php 
                        $fotoRaw = $usuario['foto_usuarios'] ?? '';
                        if (!empty($fotoRaw)) {
                            if (filter_var($fotoRaw, FILTER_VALIDATE_URL) || str_starts_with($fotoRaw, '/img/')) {
                                echo htmlspecialchars($fotoRaw);
                            } else {
                                echo '/backend/upload/' . htmlspecialchars($fotoRaw);
                            }
                        } else {
                            echo '/img/logoperf.jpg';
                        }
                     ?>" 
                     alt="Foto de perfil"
                     class="profile-photo"
                     onerror="this.onerror=null;this.src='/img/logoperf.jpg';">
            </div>
            
            <div class="photo-upload">
                <label for="foto_usuarios" class="upload-label">
                    <i class="fa fa-camera"></i> Alterar Foto
                </label>
                <input type="file" 
                       id="foto_usuarios" 
                       name="foto_usuarios" 
                       accept="image/jpeg, image/png, image/gif, image/webp"
                       onchange="previewPhoto(event)"
                       class="file-input">
                <p class="upload-hint">JPG, PNG, GIF ou WebP (Máx. 2MB)</p>
            </div>
        </div>

        <div class="form-group">
            <label for="nome_usuarios">Nome Completo:</label>
            <input type="text" id="nome_usuarios" name="nome_usuarios" value="<?php echo htmlspecialchars($usuario['nome_usuarios']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email_usuarios">Email:</label>
            <input type="email" id="email_usuarios" name="email_usuarios" value="<?php echo htmlspecialchars($usuario['email_usuarios']); ?>" required>
        </div>

        <div class="form-group">
            <label for="senha_usuarios">Nova Senha (deixe vazio para manter):</label>
            <div style="position:relative;">
                <input type="password" id="senha_usuarios" name="senha_usuarios" placeholder="••••••••" style="padding-right:46px;width:100%;box-sizing:border-box;">
                 <button type="button" onclick="toggleEditPwd()" title="Mostrar/ocultar senha"
                    style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);font-size:16px;cursor:pointer;transition:color 0.2s;padding:4px;"
                    id="btnToggleEditPwd">
                    <i class="fa fa-eye" id="iconEditPwd"></i>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="nivel_acesso">Nível de Acesso:</label>
            <select id="nivel_acesso" name="nivel_acesso" required>
                <option value="cliente" <?php echo ($usuario['nivel_acesso'] === 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                <option value="vendedor" <?php echo ($usuario['nivel_acesso'] === 'vendedor') ? 'selected' : ''; ?>>Vendedor</option>
                <option value="admin" <?php echo ($usuario['nivel_acesso'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>

        <div class="actions-container">
            <button type="submit" class="btn-save">
                <i class="fa fa-save"></i> Salvar Alterações
            </button>

            <a href="/backend/usuario/listar" class="btn-cancelar">
                <i class="fa fa-arrow-left"></i> Voltar para a lista
            </a>
        </div>
    </form>
</div>

<style>
    /* Centralização Absoluta */
    .page-wrapper {
        padding: 40px 20px;
        width: 100%;
        min-height: 100vh;
        background-color: var(--bg-main);
        font-family: Arial, sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center; 
        justify-content: flex-start;
        box-sizing: border-box;
    }

    .page-title {
        font-size: 26px;
        font-weight: 800;
        color: var(--text-main);
        text-transform: uppercase;
        margin-bottom: 5px;
        text-align: center;
        width: 100%;
        max-width: 520px;
    }

    .header-breadcrumb {
        color: var(--text-muted);
        margin-bottom: 28px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 14px;
        text-align: center;
        width: 100%;
        max-width: 520px;
    }

    .form-card {
        background: linear-gradient(135deg, #1a1a1a, #0f0f0f);
        padding: 36px;
        border-radius: 16px;
        width: 100%;
        max-width: 520px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        border: 2px solid var(--border-color);
    }

    .profile-photo-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
        padding: 20px;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 12px;
        border: 1px dashed var(--border-hover);
    }

    .photo-container {
        width: 120px;
        height: 120px;
    }

    .profile-photo {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--accent);
        box-shadow: 0 0 15px rgba(242, 204, 125, 0.3);
    }

    .upload-label {
        background: linear-gradient(135deg, var(--accent), #d4a800);
        color: #000;
        padding: 10px 20px;
        border-radius: 30px;
        cursor: pointer;
        font-weight: 800;
        font-size: 12px;
        text-transform: uppercase;
        transition: 0.3s ease;
        box-shadow: 0 4px 12px rgba(242,200,75,.3);
    }

    .upload-label:hover { 
        background: #fff; 
        transform: translateY(-2px);
    }
    .file-input { display: none; }
    .upload-hint { font-size: 11px; color: var(--text-muted); margin-top: 5px; }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }

    .form-group label {
        margin-bottom: 8px;
        font-size: 11px;
        font-weight: 800;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .form-group input, .form-group select {
        background: var(--input-bg);
        border: 2px solid var(--border-color);
        padding: 14px 16px;
        border-radius: var(--radius-md);
        color: var(--text-main);
        font-size: 15px;
        font-family: Arial, sans-serif;
        transition: border-color .2s, box-shadow .2s;
        outline: none;
    }

    .form-group input:focus, .form-group select:focus { 
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-glow);
    }

    /* Autofill override to preserve dark theme */
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px var(--input-bg) inset !important;
        -webkit-text-fill-color: var(--text-main) !important;
    }

    .actions-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 16px;
    }

    .btn-save {
        width: 100%;
        background: linear-gradient(135deg, var(--accent), #d4a800);
        padding: 15px;
        color: #000;
        border: none;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .5px;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: transform .2s, box-shadow .2s;
        box-shadow: 0 4px 12px rgba(242,200,75,.3);
    }

    .btn-save:hover { 
        background: #fff; 
        transform: translateY(-3px); 
        box-shadow: 0 8px 24px rgba(242,200,75,.45); 
    }

    .btn-cancelar {
        display: block;
        width: 100%;
        text-align: center;
        background: transparent;
        padding: 12px;
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 600;
        border-radius: var(--radius-md);
        transition: .25s;
        text-decoration: none;
        border: 2px solid var(--border-color);
        box-sizing: border-box;
    }

    .btn-cancelar:hover {
        background: var(--border-color);
        color: var(--text-main);
        border-color: var(--border-hover);
    }
</style>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            alert('Máximo 2MB permitido.');
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function toggleEditPwd() {
    const input = document.getElementById('senha_usuarios');
    const icon  = document.getElementById('iconEditPwd');
    const btn   = document.getElementById('btnToggleEditPwd');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
        btn.style.color = '#f2cc7d';
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
        btn.style.color = '';
    }
}
</script>