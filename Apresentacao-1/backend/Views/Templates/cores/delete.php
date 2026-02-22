<div class="page-wrapper">
    <h3 class="page-title title-red"><i class="fa fa-exclamation-triangle"></i> Confirmar Exclusão</h3>

    <div class="form-card card-confirmacao">
        <p>Você tem certeza que deseja excluir esta cor?</p>
        <h3 class="item-to-delete">Cor: <?= htmlspecialchars($cor['cor_cores']); ?></h3>
        <p class="warning-message">Esta ação não pode ser desfeita. O estoque vinculado a esta cor será removido.</p>

        <hr>
        <form action="/backend/cor/deletar/<?= $cor['id_cores']; ?>" method="POST">
            <input type="hidden" name="id_cores" value="<?= $cor['id_cores']; ?>">
            <div class="button-group">
                <button type="submit" class="btn-confirm-delete">
                    <i class="fa fa-trash"></i> Sim, Excluir
                </button>
                <a href="/backend/cor/listar" class="btn-cancelar">
                    <i class="fa fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.page-wrapper { padding: 40px 20px; width: 100%; box-sizing: border-box; display: flex; flex-direction: column; align-items: center; min-height: 100vh; }
.form-card { background: var(--bg-card); padding: 25px; border-radius: 12px; width: 100%; max-width: 500px; box-shadow: 0 0 15px rgba(255, 255, 255, 0.05); border: 1px solid var(--border-color); }
.page-title { font-size: 26px; font-weight: 600; margin-bottom: 15px; color: var(--text-main); width: 100%; max-width: 500px; text-align: left; }
.title-red { color: #e63946; }
.card-confirmacao { border-left: 5px solid #e63946; }
.item-to-delete { font-size: 18px; font-weight: 700; color: var(--text-main); margin: 10px 0 15px 0; }
.warning-message { font-style: italic; color: #ffb703; margin-bottom: 15px; }
.button-group { display: flex; gap: 15px; margin-top: 15px; }
.btn-confirm-delete { flex-grow: 1; background: #e63946; padding: 12px; color: #fff; border: none; font-size: 16px; border-radius: 8px; cursor: pointer; transition: 0.2s; }
.btn-confirm-delete:hover { background: #c91c2b; box-shadow: 0 0 10px rgba(230, 57, 70, 0.4); }
.btn-cancelar { flex-grow: 1; text-align: center; background: #555; padding: 12px; color: #fff; border: none; font-size: 16px; border-radius: 8px; cursor: pointer; transition: 0.2s; text-decoration: none; }
.btn-cancelar:hover { background: #777; box-shadow: 0 0 10px rgba(255, 255, 255, 0.1); }
</style>