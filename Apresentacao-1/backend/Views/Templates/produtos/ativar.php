<div class="page-wrapper">

    <h3 class="page-title title-green"><i class="fa fa-check-circle"></i> Confirmar Ativação</h3>

    <div class="form-card card-confirmacao card-ativacao">
        
        <p>Você tem certeza que deseja ativar este produto?</p>

        <h3 class="product-to-activate">Produto: <?= htmlspecialchars($produtos['nome_produtos']); ?></h3>

        <p class="info-message">O produto voltará a aparecer no site público e estará disponível para venda.</p>

        <hr> <form action="/backend/produtos/ativar" method="POST">
            <input type="hidden" name="id_produto" value="<?= $produtos['id_produto']; ?>">

            <div class="button-group">
                <button type="submit" class="btn-confirm-activate">
                    <i class="fa fa-check"></i> Sim, Ativar Produto
                </button>
                
                <a href="/backend/produtos/listar" class="btn-cancelar">
                    <i class="fa fa-times"></i> Cancelar
                </a>
            </div>
        </form>
        
    </div>
</div>

<style>
/* ------------------------------------------------------------------------- */
/* O SEU CSS EXISTENTE FOI MANTIDO. Adicionamos apenas o estilo de destaque do produto. */
/* ------------------------------------------------------------------------- */

.page-wrapper {
    /* Ajuste de padding-left para o menu, se necessário */
    padding-left: 10px; 
    padding-right: 220px;
    padding-top: 110px; /* Ajustei de 110px para 20px para subir o conteúdo */
    width: 100%;
    box-sizing: border-box;
    display: flex; 
    flex-direction: column;
}

.form-card {
    background: #111;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.page-title, .form-card {
    width: 500px;
    margin-left: auto;
    margin-right: auto;
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

/* NOVO: Classe para destacar o nome do produto */
.product-to-activate { 
    font-size: 18px;
    font-weight: 700;
    color: #f1faee;
    margin: 10px 0 15px 0;
}

/* Mantidas as classes existentes */
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