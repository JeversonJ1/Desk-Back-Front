<div class="page-wrapper">
    <h3 class="page-title"><i class="fa fa-shopping-cart"></i> Novo Pedido</h3>
    <p class="page-subtitle"><i class="fa fa-info-circle"></i> Preencha os dados do cliente e os itens iniciais do pedido.</p>

    <form action="/backend/pedido/salvar" method="post" class="form-card-modern">
        
        <div class="form-content">
            <div class="form-section">
                <h4 class="section-title">Informações Básicas</h4>
                
                <div class="form-group">
                    <label for="id_perfil">ID DO PERFIL (CLIENTE):</label>
                    <input type="number" id="id_perfil" name="id_perfil" placeholder="Ex: 5" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="data_pedido">DATA E HORA DO PEDIDO:</label>
                        <input type="datetime-local" id="data_pedido" name="data_pedido" value="<?= date('Y-m-d\TH:i'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="status_pedido">STATUS:</label>
                        <select id="status_pedido" name="status_pedido" required>
                            <option value="pendente" selected>Pendente</option>
                            <option value="pago">Pago</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="total_pedido">VALOR TOTAL DO PEDIDO (R$):</label>
                    <input type="number" step="0.01" id="total_pedido" name="total_pedido" placeholder="0.00" required>
                </div>
            </div>

            <div class="form-section item-highlight">
                <h4 class="section-title">Item (Produto Inicial)</h4>
                
                <div class="form-group">
                    <label for="id_produto">ID DO PRODUTO:</label>
                    <input type="number" id="id_produto" name="itens[0][id_produto]" placeholder="Ex: 101" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="quantidade">QUANTIDADE:</label>
                        <input type="number" id="quantidade" name="itens[0][quantidade]" value="1" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="preco_unitario">PREÇO UNITÁRIO (R$):</label>
                        <input type="number" step="0.01" id="preco_unitario" name="itens[0][preco_unitario]" placeholder="0.00" required>
                    </div>
                </div>
                
                <div class="item-info-box">
                    <i class="fa fa-lightbulb-o"></i>
                    <small>O estoque será atualizado automaticamente após a confirmação.</small>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save-modern">
                <i class="fa fa-check-circle"></i> FINALIZAR PEDIDO
            </button>
            <a href="/backend/pedido/listar" class="btn-back-link">
                <i class="fa fa-arrow-left"></i> Voltar para a lista
            </a>
        </div>
    </form>
</div>

<style>
/* Estrutura igual às suas outras páginas */
.page-wrapper {
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.page-title {
    color: #fff;
    font-size: 26px;
    margin-bottom: 5px;
    width: 100%;
    max-width: 950px;
}

.page-subtitle {
    color: #888;
    font-size: 14px;
    margin-bottom: 25px;
    width: 100%;
    max-width: 950px;
}

/* Card Principal */
.form-card-modern {
    background: #111; /* Cinza bem escuro igual ao Dashboard */
    border: 1px solid #222;
    padding: 35px;
    border-radius: 15px;
    width: 100%;
    max-width: 950px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.6);
}

.form-content {
    display: grid;
    grid-template-columns: 1.2fr 1fr; /* Duas colunas */
    gap: 40px;
}

.section-title {
    color: #fff;
    font-size: 18px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #333;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Estilização dos Inputs */
.form-group {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
}

.form-group label {
    color: #e2c93e; /* Dourado Koketsu */
    font-size: 11px;
    font-weight: 700;
    margin-bottom: 8px;
    letter-spacing: 0.5px;
}

.form-group input, 
.form-group select {
    background: #1a1a1a;
    border: 1px solid #333;
    color: #fff;
    padding: 12px 15px;
    border-radius: 8px;
    font-size: 14px;
    transition: 0.3s;
}

.form-group input:focus, 
.form-group select:focus {
    border-color: #e2c93e;
    outline: none;
    box-shadow: 0 0 8px rgba(226, 201, 62, 0.2);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* Destaque para a seção de itens */
.item-highlight {
    background: rgba(226, 201, 62, 0.03);
    padding: 20px;
    border-radius: 12px;
    border: 1px solid rgba(226, 201, 62, 0.1);
}

.item-info-box {
    margin-top: 15px;
    padding: 10px;
    background: #1a1500;
    border-left: 3px solid #e2c93e;
    color: #bbb;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Botões */
.form-actions {
    margin-top: 35px;
    padding-top: 25px;
    border-top: 1px solid #222;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-save-modern {
    background: #dfd155;
    color: #000;
    border: none;
    padding: 15px 45px;
    font-weight: 800;
    font-size: 15px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
    text-transform: uppercase;
}

.btn-save-modern:hover {
    background: #fff;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(223, 209, 85, 0.3);
}

.btn-back-link {
    color: #666;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: 0.3s;
}

.btn-back-link:hover {
    color: #fff;
}

/* Responsividade */
@media (max-width: 850px) {
    .form-content { grid-template-columns: 1fr; }
}
</style>