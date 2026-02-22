

<div class="page-wrapper">

    <h3 class="page-title title-red"><i class="fa fa-exclamation-triangle"></i> Confirmar Inativação</h3>

    <div class="form-card card-confirmacao">
        
        <p>Você tem certeza que deseja inativar (excluir) este pedido?</p>

        <h3 class="user-to-delete">Pedido: <?= htmlspecialchars($pedido['id_pedido']); ?></h3>

        <p class="warning-message">Esta ação não pode ser desfeita facilmente e o pedido deixará de aparecer no site público.</p>
        <hr> <form action="/backend/pedido/deletar" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido']; ?>">
            <div class="button-group">
                <button type="submit" class="btn-confirm-delete">
                    <i class="fa fa-trash"></i> Sim, Inativar Pedido
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
/* ESTILOS DE LAYOUT (Mantidos dos formulários) */
/* -------------------------------------- */

.page-wrapper {
    padding-left: 10px; 
    padding-right: 200px;
    padding-top: 110px; /* Mantido em 20px para espaçamento superior */
    
    width: 100%;
    box-sizing: border-box;

    display: flex; 
    flex-direction: column;
}

.form-card {
    background: #111;
    padding: 25px;
    border-radius: 12px;
    width: 420px; /* Largura definida para centralização */
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
    width: 420px; 
    margin-left: auto;
    margin-right: auto;
    text-align: left;
}

/* -------------------------------------- */
/* ESTILOS ESPECÍFICOS DE INATIVAÇÃO */
/* -------------------------------------- */

.title-red {
    color: #e63946; /* Cor vermelha para o título de aviso */
}

.card-confirmacao {
    /* Pequenos ajustes de cor ou borda para destacar que é um aviso */
    border-left: 5px solid #e63946; /* Borda vermelha de aviso */
    width: 500px; /* Aumentei a largura um pouco para a mensagem caber melhor */
}

/* Ajusta o alinhamento do título e do card de confirmação com a nova largura */
.page-title, .form-card {
    width: 500px; /* Ambos devem ter a mesma largura para alinhar */
}


.user-to-delete {
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
    gap: 15px; /* Espaço entre os botões */
    margin-top: 15px;
}

.btn-confirm-delete {
    flex-grow: 1; /* Faz o botão ocupar o espaço disponível */
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
    flex-grow: 1; /* Faz o botão ocupar o espaço disponível */
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

/* -------------------------------------- */
/* Outros estilos do seu tema (se existirem, devem ser mantidos) */
/* -------------------------------------- */
</style>