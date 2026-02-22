<div class="page-wrapper">

    <h3 class="page-title title-red"><i class="fa fa-exclamation-triangle"></i> Confirmar Inativação</h3>

    <div class="form-card card-confirmacao">
        
        <p>Você tem certeza que deseja inativar (excluir) este produto?</p>

        <h3 class="product-to-delete">Produto: <?= htmlspecialchars($produtos['nome_produtos']); ?></h3>

        <p class="warning-message">Esta ação não pode ser desfeita facilmente e o produto deixará de aparecer no site público.</p>

        <hr> <form action="/backend/produtos/deletar" method="POST">
            <input type="hidden" name="id_produto" value="<?= $produtos['id_produto']; ?>">

            <div class="button-group">
                <button type="submit" class="btn-confirm-delete">
                    <i class="fa fa-trash"></i> Sim, Inativar Produto
                </button>
                
                <a href="/backend/produtos/listar" class="btn-cancelar">
                    <i class="fa fa-times"></i> Cancelar
                </a>
            </div>
        </form>
        
    </div>
</div>

<style>
/* -------------------------------------- */
/* ESTILOS DE LAYOUT E ALERTAS (Reutilizando seu CSS existente) */
/* -------------------------------------- */

.page-wrapper {
    /* Mantendo seus ajustes de padding, mas idealmente centralizado pelo menu */
    padding-left: 10px; /* Ajuste para o padrão se o menu lateral tem 250px */
    padding-right: 200px;
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
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.page-title, .form-card {
    width: 500px; /* Mantido em 500px para alinhamento e caber a mensagem */
    margin-left: auto;
    margin-right: auto;
}

.page-title {
    font-size: 26px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #ffffff;
    text-align: left;
}

/* Estilos de Alerta e Confirmação */
.title-red {
    color: #e63946; 
}

.card-confirmacao {
    border-left: 5px solid #e63946; /* Borda vermelha de aviso */
}

/* Classe alterada de .user-to-delete para .product-to-delete */
.product-to-delete {
    font-size: 18px;
    font-weight: 700;
    color: #f1faee;
    margin: 10px 0 15px 0;
}

.warning-message {
    font-style: italic;
    color: #ffb703; /* Amarelo/Laranja para a mensagem de cuidado */
    margin-bottom: 15px;
}

.button-group {
    display: flex;
    gap: 15px; 
    margin-top: 15px;
}

.btn-confirm-delete {
    flex-grow: 1; 
    background: #e63946; /* Vermelho para a ação de exclusão */
    padding: 12px;
    color: #fff;
    border: none;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.2s;
}

.btn-confirm-delete:hover {
    background: #c91c2b;
    box-shadow: 0 0 10px rgba(230, 57, 70, 0.4);
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