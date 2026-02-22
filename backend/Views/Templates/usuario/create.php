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
        padding: 20px;
        width: 100%;
        box-sizing: border-box;
        background-color: #0c0c0c; /* Fundo padrão do dashboard */
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: #ffffff;
        text-transform: uppercase;
        margin-bottom: 5px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .header-breadcrumb {
        color: #888;
        margin-bottom: 25px;
        border-bottom: 1px solid #222;
        padding-bottom: 10px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .form-card {
        background: #111;
        padding: 35px;
        border-radius: 15px;
        width: 100%;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        border: 1px solid #333;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }

    .form-group label {
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 700;
        color: #f2cc7d; /* Dourado padrão */
        text-transform: uppercase;
    }

    .form-group input,
    .form-group select {
        background: #1a1a1a;
        border: 1px solid #444;
        padding: 15px;
        border-radius: 12px;
        color: #fff;
        font-size: 16px;
        transition: 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #f2cc7d;
        outline: none;
        box-shadow: 0 0 10px rgba(242, 204, 125, 0.1);
    }

    .actions-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 10px;
    }

    .btn-save {
        width: 100%;
        background: #f2cc7d;
        padding: 15px;
        color: #000;
        border: none;
        font-size: 14px;
        font-weight: 800;
        text-transform: uppercase;
        border-radius: 12px;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 4px 15px rgba(242, 204, 125, 0.2);
    }

    .btn-save:hover {
        background: #fff;
        transform: translateY(-2px);
    }

    .btn-cancelar {
        display: block;
        width: 100%;
        text-align: center;
        background: transparent;
        padding: 12px;
        color: #888;
        font-size: 14px;
        font-weight: 600;
        border-radius: 12px;
        transition: 0.3s;
        text-decoration: none;
        border: 1px solid #333;
        box-sizing: border-box;
    }

    .btn-cancelar:hover {
        background: #222;
        color: #fff;
        border-color: #444;
    }
</style>