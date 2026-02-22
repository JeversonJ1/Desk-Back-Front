<div class="page-wrapper">
    <h3 class="page-title">
        <i class="fa fa-pencil" style="color: #f2cc7d;"></i> 
        Editando Categoria: <span style="color: #f2cc7d;"><?= htmlspecialchars($categoria['nome_categorias']); ?></span>
    </h3>

    <header class="header-breadcrumb">
        <h5><b><i class="fa fa-info-circle"></i> Altere as informações abaixo e clique em salvar para atualizar.</b></h5>
    </header>

    <form action="/backend/categoria/atualizar/<?= $categoria['id_categorias']; ?>" method="post" class="form-card">
        <input type="hidden" name="id_categorias" value="<?= $categoria['id_categorias']; ?>">

        <div class="form-group">
            <label><i class="fa fa-tag"></i> Nome da Categoria:</label>
            <input type="text" name="nome_categorias" value="<?= htmlspecialchars($categoria['nome_categorias']); ?>" required>
        </div>

        <div class="form-group">
            <label><i class="fa fa-align-left"></i> Descrição:</label>
            <textarea name="descricao_categorias" rows="4" required><?= htmlspecialchars($categoria['descricao_categorias']); ?></textarea>
        </div>

        <div class="actions-container">
            <button type="submit" class="btn-save">
                <i class="fa fa-save"></i> Salvar Alterações
            </button>

            <a href="/backend/categoria/listar" class="btn-cancelar">
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
        background-color: var(--bg-main);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .page-title {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-main);
        text-transform: uppercase;
        margin-bottom: 5px;
        max-width: 500px;
        width: 100%;
        text-align: center;
    }

    .header-breadcrumb {
        color: var(--text-muted);
        margin-bottom: 25px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 15px;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .form-card {
        background: var(--bg-card);
        padding: 30px;
        border-radius: 15px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.7);
        border: 1px solid var(--border-color);
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 18px;
    }

    .form-group label {
        margin-bottom: 8px;
        font-size: 12px;
        font-weight: 700;
        color: var(--accent);
        text-transform: uppercase;
    }

    .form-group input, .form-group textarea {
        background: var(--bg-main);
        border: 1px solid var(--border-color);
        padding: 12px;
        border-radius: 8px;
        color: var(--text-main);
        outline: none;
        font-family: inherit;
        resize: vertical;
    }

    .form-group input:focus, .form-group textarea:focus { border-color: var(--accent); }

    .actions-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-save {
        background: var(--accent);
        color: #000;
        padding: 15px;
        border: none;
        font-weight: 800;
        text-transform: uppercase;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-save:hover { background: var(--text-main); transform: translateY(-2px); }

    .btn-cancelar {
        text-align: center;
        padding: 12px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 13px;
    }
</style>