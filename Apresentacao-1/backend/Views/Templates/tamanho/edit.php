<div class="page-wrapper">
    <h3 class="page-title">
        <i class="fa fa-pencil" style="color: #f2cc7d;"></i> 
        Editando Tamanho: <span style="color: #f2cc7d;">#<?= htmlspecialchars($tamanho['id_tamanhos']); ?></span>
    </h3>

    <header class="header-breadcrumb">
        <h5><b><i class="fa fa-info-circle"></i> Altere as informações e clique em salvar para atualizar.</b></h5>
    </header>

    <form action="/backend/tamanho/atualizar" method="post" class="form-card">
        <input type="hidden" name="id_tamanhos" value="<?= $tamanho['id_tamanhos']; ?>">

        <div class="form-group">
            <label><i class="fa fa-box"></i> ID do Produto:</label>
            <input type="number" name="id_produto" value="<?= $tamanho['id_produto']; ?>" required>
        </div>

        <div class="form-group">
            <label><i class="fa fa-ruler"></i> Tamanho:</label>
            <input type="text" name="tamanho_tamanhos" value="<?= htmlspecialchars($tamanho['tamanho_tamanhos']); ?>" required maxlength="10">
        </div>

        <div class="form-group">
            <label><i class="fa fa-cubes"></i> Quantidade em Estoque:</label>
            <input type="number" name="quantidade_tamanhos" value="<?= $tamanho['quantidade_tamanhos']; ?>" required min="0">
        </div>

        <div class="actions-container">
            <button type="submit" class="btn-save">
                <i class="fa fa-save"></i> Salvar Alterações
            </button>
            <a href="/backend/tamanho/listar" class="btn-cancelar">
                <i class="fa fa-arrow-left"></i> Voltar para a lista
            </a>
        </div>
    </form>
</div>

<style>
    .page-wrapper { padding: 20px; width: 100%; box-sizing: border-box; min-height: 100vh; display: flex; flex-direction: column; align-items: center; }
    .page-title { font-size: 24px; font-weight: 800; color: var(--text-main); text-transform: uppercase; margin-bottom: 5px; max-width: 500px; width: 100%; text-align: center; }
    .header-breadcrumb { color: var(--text-muted); margin-bottom: 25px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; text-align: center; width: 100%; max-width: 500px; }
    .form-card { background: var(--bg-card); padding: 30px; border-radius: 15px; width: 100%; max-width: 500px; box-shadow: 0 10px 40px rgba(0,0,0,0.7); border: 1px solid var(--border-color); }
    .form-group { display: flex; flex-direction: column; margin-bottom: 18px; }
    .form-group label { margin-bottom: 8px; font-size: 12px; font-weight: 700; color: var(--accent); text-transform: uppercase; }
    .form-group input { background: var(--bg-main); border: 1px solid var(--border-color); padding: 12px; border-radius: 8px; color: var(--text-main); outline: none; }
    .form-group input:focus { border-color: var(--accent); }
    .actions-container { display: flex; flex-direction: column; gap: 10px; margin-top: 20px; }
    .btn-save { background: var(--accent); color: #000; padding: 15px; border: none; font-weight: 800; text-transform: uppercase; border-radius: 8px; cursor: pointer; transition: 0.3s; }
    .btn-save:hover { background: var(--text-main); transform: translateY(-2px); }
    .btn-cancelar { text-align: center; padding: 12px; color: var(--text-muted); text-decoration: none; font-size: 13px; }
</style>
