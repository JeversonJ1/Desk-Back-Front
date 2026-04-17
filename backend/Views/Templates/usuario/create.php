<div class="page-wrapper">
    <h3 class="page-title"><i class="fa fa-user-plus" style="color: #f2cc7d;"></i> Novo Usuário</h3>
    
    <header class="header-breadcrumb">
        <h5><b><i class="fa fa-plus-circle"></i> Cadastro de novo colaborador ou cliente - Koketsu</b></h5>
    </header>

    <form action="/backend/usuario/salvar" method="post" enctype="multipart/form-data" class="form-card">
        <div class="form-group">
            <label><i class="fa fa-user"></i> Nome:</label>
            <input type="text" name="nome_usuarios" placeholder="Digite o nome completo" required>
        </div>

        <div class="form-group">
            <label><i class="fa fa-envelope"></i> Email:</label>
            <input type="email" name="email_usuarios" placeholder="exemplo@email.com" required>
        </div>

        <div class="form-group">
            <label><i class="fa fa-lock"></i> Senha:</label>
            <input type="password" name="senha_usuarios" placeholder="Crie uma senha segura" required>
        </div>

        <div class="form-group">
            <label><i class="fa fa-shield"></i> Tipo de Acesso:</label>
            <select name="nivel_acesso" required>
                <option value="Vendedor">Vendedor</option>
                <option value="Admin">Admin</option>
                <option value="Cliente">Cliente</option>
            </select>
        </div>

        <div class="actions-container">
            <button type="submit" class="btn-save">
                <i class="fa fa-save"></i> Salvar Usuário
            </button>

            <a href="/backend/usuario/listar" class="btn-cancelar">
                <i class="fa fa-arrow-left"></i> Voltar para a lista
            </a>
        </div>
    </form>
</div>

<style>
    .page-wrapper {
        padding: 40px 20px;
        width: 100%;
        box-sizing: border-box;
        background-color: var(--bg-main);
        min-height: 100vh;
        font-family: Arial, sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center;
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

    .form-group input,
    .form-group select {
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

    .form-group input:focus,
    .form-group select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-glow);
    }

    .actions-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 16px;
    }

    .btn-save {
        width: 100%;
        background: linear-gradient(135deg, #F2C84B, #d4a800);
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