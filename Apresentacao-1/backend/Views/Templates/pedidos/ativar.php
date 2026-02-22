<div class="page-wrapper">

    <h3 class="page-title title-green"><i class="fa fa-check-circle"></i> Confirmar Ativação</h3>

    <div class="form-card card-confirmacao card-ativacao">
        
        <p>Você tem certeza que deseja ativar este pedido?</p>

        <h3 class="user-to-activate">Pedido: #<?= htmlspecialchars($pedido['id_pedido']); ?> (<?= htmlspecialchars($pedido['nome_cliente'] ?? 'N/A'); ?>)</h3>

        <p class="info-message">O pedido voltará a ser exibido como ativo no sistema.</p>

        <hr> <form action="/backend/pedido/ativar" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido']; ?>">

            <div class="button-group">
                <button type="submit" class="btn-confirm-activate">
                    <i class="fa fa-check"></i> Sim, Ativar Pedido
                </button>
                
                <a href="/backend/pedido/listar" class="btn-cancelar">
                    <i class="fa fa-times"></i> Cancelar
                </a>
            </div>
        </form>
        
    </div>
</div>

<style>
/* -------------------------------------- */
/* ESTILOS DE LAYOUT (Mantidos para centralização) */
/* -------------------------------------- */

.page-wrapper {
    padding-left: 10px; 
    padding-right: 220px;
    padding-top: 110px;
    width: 100%;
    box-sizing: border-box;
    display: flex; 
    flex-direction: column;
}

.form-card {
    background: #111;
    padding: 25px;
    border-radius: 12px;
    width: 500px; 
    margin-left: auto;
    margin-right: auto;
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.page-title {
    font-size: 26px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #ffffff;
    width: 500px;
    margin-left: auto;
    margin-right: auto;
    text-align: left;
}

/* -------------------------------------- */
/* ESTILOS ESPECÍFICOS DE ATIVAÇÃO */
/* -------------------------------------- */

.title-green {
    color: #4CAF50;
}

.card-ativacao {
    border-left: 5px solid #4CAF50;
}

.user-to-activate {
    font-size: 18px;
    font-weight: 700;
    color: #f1faee;
    margin: 10px 0 15px 0;
}

.info-message {
    font-style: italic;
    color: #a5d6a7; 
    margin-bottom: 15px;
}

.button-group {
    display: flex;
    gap: 15px;
    margin-top: 15px;
}

.btn-confirm-activate {
    flex-grow: 1; 
    background: #4CAF50; 
    padding: 12px;
    color: #fff;
    border: none;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s;
}

.btn-confirm-activate:hover {
    background: #388e3c;
    box-shadow: 0 0 10px rgba(76, 175, 80, 0.4);
}

/* -------------------------------------- */
/* Botão de Cancelar (Reutilizado da Inativação) */
/* -------------------------------------- */

.btn-cancelar {
    flex-grow: 1; 
    text-align: center;
    background: #555; 
    padding: 12px;
    color: #fff;
    border: none;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s;
    text-decoration: none;
}

.btn-cancelar:hover {
    background: #777;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
}

</style>