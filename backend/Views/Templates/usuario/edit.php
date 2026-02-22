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
                        // CAMINHO CORRIGIDO: pasta onde as fotos são salvas
                        $caminho_base = '/backend/upload/usuarios/';
                        echo (!empty($usuario['foto_usuarios'])) 
                             ? $caminho_base . htmlspecialchars($usuario['foto_usuarios']) 
                             : '/img/logoperf.jpg'; 
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
            <input type="password" id="senha_usuarios" name="senha_usuarios" placeholder="••••••••"> 
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
        background-color: #0c0c0c;
        font-family: 'Segoe UI', sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center; 
        justify-content: flex-start;
        box-sizing: border-box;
    }

    .page-title {
        font-size: 24px;
        font-weight: 800;
        color: #ffffff;
        text-transform: uppercase;
        margin-bottom: 5px;
        text-align: center;
    }

    .header-breadcrumb {
        color: #888;
        margin-bottom: 25px;
        border-bottom: 1px solid #222;
        padding-bottom: 15px;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .form-card {
        background: #111;
        padding: 30px;
        border-radius: 15px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.7);
        border: 1px solid #333;
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
        border: 1px dashed #444;
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
        border: 3px solid #f2cc7d;
        box-shadow: 0 0 15px rgba(242, 204, 125, 0.3);
    }

    .upload-label {
        background: #f2cc7d;
        color: #000;
        padding: 10px 20px;
        border-radius: 30px;
        cursor: pointer;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        transition: 0.3s ease;
    }

    .upload-label:hover { background: #fff; }
    .file-input { display: none; }
    .upload-hint { font-size: 11px; color: #555; margin-top: 5px; }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 18px;
    }

    .form-group label {
        margin-bottom: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #f2cc7d;
        text-transform: uppercase;
    }

    .form-group input, .form-group select {
        background: #1a1a1a;
        border: 1px solid #333;
        padding: 12px;
        border-radius: 8px;
        color: #fff;
        outline: none;
    }

    .form-group input:focus { border-color: #f2cc7d; }

    .actions-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-save {
        background: #f2cc7d;
        color: #000;
        padding: 15px;
        border: none;
        font-weight: 800;
        text-transform: uppercase;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-save:hover { background: #fff; transform: translateY(-2px); }

    .btn-cancelar {
        text-align: center;
        padding: 12px;
        color: #666;
        text-decoration: none;
        font-size: 13px;
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
</script>