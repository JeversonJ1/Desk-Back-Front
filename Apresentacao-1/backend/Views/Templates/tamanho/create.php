<div class="page-wrapper">
    <h3 class="page-title"><i class="fa fa-plus-circle" style="color: #f2cc7d;"></i> Novo Tamanho</h3>
    
    <header class="header-breadcrumb">
        <h5><b><i class="fa fa-ruler-combined"></i> Cadastrar variação de tamanho para produto - Koketsu</b></h5>
    </header>

    <form action="/backend/tamanho/salvar" method="post" class="form-card">
        <div class="form-group">
            <label><i class="fa fa-box"></i> ID do Produto:</label>
            <input type="number" name="id_produto" placeholder="Ex: 5" required min="1">
        </div>

        <div class="form-group">
            <label><i class="fa fa-ruler"></i> Tamanho:</label>
            <input type="text" name="tamanho_tamanhos" placeholder="Ex: P, M, G, GG..." required maxlength="10">
        </div>

        <div class="form-group">
            <label><i class="fa fa-cubes"></i> Quantidade em Estoque:</label>
            <input type="number" name="quantidade_tamanho" placeholder="Ex: 50" required min="0">
        </div>

        <div class="actions-container">
            <button type="submit" class="btn-save">
                <i class="fa fa-save"></i> Salvar Tamanho
            </button>
            <a href="/backend/tamanho/listar" class="btn-cancelar">
                <i class="fa fa-arrow-left"></i> Voltar para a lista
            </a>
        </div>
    </form>
</div>

<style>
    .page-wrapper { padding: 20px; width: 100%; box-sizing: border-box; min-height: 100vh; display: flex; flex-direction: column; align-items: center; }
    .page-title { font-size: 28px; font-weight: 800; color: var(--text-main); text-transform: uppercase; margin-bottom: 5px; max-width: 500px; width: 100%; }
    .header-breadcrumb { color: var(--text-muted); margin-bottom: 25px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px; max-width: 500px; width: 100%; }
    .form-card { background: var(--bg-card); padding: 35px; border-radius: 15px; width: 100%; max-width: 500px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid var(--border-color); }
    .form-group { display: flex; flex-direction: column; margin-bottom: 20px; }
    .form-group label { margin-bottom: 8px; font-size: 14px; font-weight: 700; color: var(--accent); text-transform: uppercase; }
    .form-group input { background: var(--bg-main); border: 1px solid var(--border-color); padding: 15px; border-radius: 12px; color: var(--text-main); font-size: 16px; transition: 0.3s; }
    .form-group input:focus { border-color: var(--accent); outline: none; box-shadow: 0 0 10px rgba(242, 204, 125, 0.1); }
    .actions-container { display: flex; flex-direction: column; gap: 15px; margin-top: 10px; }
    .btn-save { width: 100%; background: var(--accent); padding: 15px; color: #000; border: none; font-size: 14px; font-weight: 800; text-transform: uppercase; border-radius: 12px; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 15px rgba(242, 204, 125, 0.2); }
    .btn-save:hover { background: var(--text-main); transform: translateY(-2px); }
    .btn-cancelar { display: block; width: 100%; text-align: center; background: transparent; padding: 12px; color: var(--text-muted); font-size: 14px; font-weight: 600; border-radius: 12px; transition: 0.3s; text-decoration: none; border: 1px solid var(--border-color); box-sizing: border-box; }
    .btn-cancelar:hover { background: var(--border-color); color: var(--text-main); }
</style>